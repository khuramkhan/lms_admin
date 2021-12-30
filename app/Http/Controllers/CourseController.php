<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('admin.courses.index',compact('courses'));
    }
    public function addCourse(Request $request)
    {
        if(count($request->all()) > 0){
            $validate = Validator::make($request->all(),[
                'coverImage' => 'required|mimes:png,jpg,svg,jpeg'
            ]);
            if($validate->fails()){
               return back()->with('error', $validate->errors()->messages()['coverImage'][0]);
            }
            // dd($request->all());
            $path = $request->file('coverImage')->store('public/Images');
            $path = str_replace('public/','',$path);
            $data = $request->all();
            $data['coverImage'] = $path;
            $course = Course::create($data);
            $courseID = $course->id;
            return view('admin.courses.topics.add',compact('courseID'));
        }
        return view('admin.courses.add');
    }

    public function addTopic(Request $request)
    {
        for($i=0; $i<count($request->topic); $i++){
            CourseTopic::create([
                'topic' => $request->topic[$i],
                'videoLink' => $request->videoLink[$i],
                'courseId' => $request->courseID
            ]);
        }
        return redirect()->to('/courses')->with('success','Course Added Successfully');
    }
}
