<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
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

        $courses = Course::all();
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

        $validate = Validator::make($request->all(),[
            'course_id' => 'integer|required|exists:courses,id',
            'user_id' => 'integer|required|exists:users,id',
            'payment_method' => 'required|string',
        ]);

        $data = $request->all();

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
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

        $isExistSubscription = PurchaseCourse::where('user_id',$request->user_id)->where('course_id',$request->course_id)->first();
        if($isExistSubscription){
            $isExistSubscription->current_date = $data['current_date'];
            $isExistSubscription->valid_till = Carbon::parse($isExistSubscription->valid_till)->addDays($cSetting->value);
            $isExistSubscription->save();
            return response()->json([
                'success' => true,
                'message' => 'Subscription Updated Successfully',
                'purchaseCourse' => $isExistSubscription
            ]);
        }
        $data['valid_till'] = Carbon::now()->addDays(intval($cSetting->value));

        $course = Course::find($request->course_id);
        $data['price'] = $course->price;

        $purchaseCourse = PurchaseCourse::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Course Purchase Successfully!',
            'purchaseCourse' => $purchaseCourse
        ]);
    }

    public function userWishListCourses(Request $request){

        $validate = Validator::make($request->all(),[
            'user_id' => 'integer|required|exists:users,id',
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
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
                'errors' => $validate->errors()
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
                'errors' => $validate->errors()
            ]);
        }

        $user = User::find($request->user_id);
        $courses = $user->userPurchaseCourses;

        if(count($courses) < 0){
            return response()->json([
                'success' => false,
                'message' => "User's Purchased Courses Not Found"
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "User's Purchased Courses Found Successfully",
            'courses' => $courses
        ]);
    }

    public function subscriptions(Request $request){

        $validate = Validator::make($request->all(),[
            'user_id' => 'integer|required|exists:users,id',
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ]);
        }

        $user = User::find($request->user_id);
        $courses = $user->userPurchaseCourses;

        if(count($courses) < 0){
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
