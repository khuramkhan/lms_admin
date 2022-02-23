<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseTopic;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PurchaseCourse;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserWishlistCourse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function courses(){
        $success = false;
        $message = '';

        $userPurchaseCourses = [];
        $orders = auth()->user()->userPurchaseCourses;
        if(count($orders) > 0){
            foreach($orders as $order){
                $orderDetails = $order->orderDetail;
                foreach($orderDetails as $od){
                    $course = Course::find($od->course_id);
                    $course->topics->each(function($topic){
                        $topic->topicDetail;
                    });
                    $userPurchaseCourses [] = $course;
                }
            }
        }
        

        $courses = Course::with('topics.topicDetail')->get()->each(function($course) use($userPurchaseCourses){
            $course->is_purchased = false;
            if(count($userPurchaseCourses) > 0)
            {
                foreach($userPurchaseCourses as $pCourse){
                    if($pCourse->id == $course->id){
                        $course->is_purchased = true;
                    }
                }
            }
        });

        if(count($courses) > 0){
            $success = true;
            $message = 'Courses Found Successfully!';
        }else{
            $success = false;
            $message = 'Courses Not Found';
            $courses = [];
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'courses' => $courses
        ]);
    }

    public function purchaseCourse(Request $request){

        $courses = json_decode($request->courses);

        if(!is_array($courses)){
            return [
                'success' => false,
                'errors' => 'Invalid Courses'
            ];
        }

        $validateCourse = true;
        $invalidCourseId = null;

        foreach($courses as $course){
            $c = Course::find($course->course_id);
            if(!$c){
                $validateCourse = false;
                $invalidCourseId = $course->course_id;
                break;
            }
        }

        if($validateCourse == false){
            $error = 'Invalid course_id('. $invalidCourseId . ')' ;
            return [
                'success' => false,
                'errors' => $error
            ];
        }




        $validate = Validator::make($request->all(),[
            'user_id' => 'integer|required|exists:users,id',
            'payment_method' => 'required|string',
        ]);


        $data = $request->all();

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->first()
            ]);
        }

        $data['current_date'] = Carbon::now();
        $cSetting = Setting::where('key','validity')->first();

        if(!$cSetting){
            return response()->json([
                'success' => false,
                'message' => 'Course Setting Not Found'
            ]);
        }


        $amount = 0;
        foreach ($courses as $course)
        {
            $amount += $course->price;
        }

        $order = Order::create([
            'user_id' => $request->user_id,
            'amount' => $amount,
            'date' => Carbon::now(),
            'valid_till' =>  Carbon::now()->addDays(intval($cSetting->value)),
            'payment_method' => $request->payment_method
        ]);

        foreach($courses as $course){
            OrderDetail::create([
                'course_id' => $course->course_id,
                'price' => $course->price,
                'order_id' => $order->id
            ]);
        }



        return response()->json([
            'success' => true,
            'message' => 'Course Purchase Successfully!',
            'order' => $order->where('id',$order->id)->with('orderDetail')->get()
        ]);


    }

    public function userWishListCourses(Request $request){

        $validate = Validator::make($request->all(),[
            'user_id' => 'integer|required|exists:users,id',
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->first()
            ]);
        }

        $success = false;
        $message = '';

        $user = User::find($request->user_id);

        $courses = $user->userWishListCourses->each(function($course){
            return $course->course;
        });

        if(count($courses) > 0){
            $success = true;
            $message = 'WishList Courses Found Successfully!';
        }else{
            $success = false;
            $message = 'WishList Courses Not Found';
            $courses = [];
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'courses' => $courses
        ]);
    }

    public function addUserWishListCourses(Request $request){

        $validate = Validator::make($request->all(),[
            'course_id' => 'integer|required|exists:courses,id',
            'user_id' => 'integer|required|exists:users,id',
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->first()
            ]);
        }

        $isExistCourse = UserWishlistCourse::where('course_id',$request->course_id)->where('user_id',$request->user_id)->first();
        if($isExistCourse){
            return response()->json([
                'success' => false,
                'message' => 'Course is already in wishList'
            ]);
        }

        $course = UserWishlistCourse::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Course added to wishlist successfully',
            'course' => $course
        ]);
    }

    public function purchaseHistory(Request $request){

        $validate = Validator::make($request->all(),[
            'user_id' => 'integer|required|exists:users,id',
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->first()
            ]);
        }

        $user = User::find($request->user_id);
        $orders = $user->userPurchaseHistory;


        if(count($orders) <= 0){
            return response()->json([
                'success' => false,
                'message' => "User's Orders Not Found"
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "User's Orders Found Successfully",
            'orders' => $orders
        ]);
    }

    public function subscriptions(Request $request){

        $validate = Validator::make($request->all(),[
            'user_id' => 'integer|required|exists:users,id',
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->first()
            ]);
        }

        $user = User::find($request->user_id);

        $courses = [];

        $orders = $user->userPurchaseCourses;

        if(count($orders) > 0){
            foreach($orders as $order){
                $orderDetails = $order->orderDetail;
                foreach($orderDetails as $od){
                    $course = Course::find($od->course_id);
                    $course->topics->each(function($topic){
                        $topic->topicDetail;
                    });
                    $courses [] = $course;
                }
            }
        }



        if(count($courses) <= 0){
            return response()->json([
                'success' => false,
                'message' => "User's Subscriptions Not Found"
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "User's Subscriptions Found Successfully",
            'courses' => $courses
        ]);
    }
}
