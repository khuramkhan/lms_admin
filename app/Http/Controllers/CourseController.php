<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTopic;
use App\Models\TopicQuestion;
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
            return redirect()->route('course.addTopic',['courseID' => $courseID]);
        }
        return view('admin.courses.add');
    }

    public function addTopic(Request $request)
    {
        $course = Course::find($request->courseID);
        if(count($request->all()) > 0){
            if($request->hasFile('pdf')){
                $validate = Validator::make($request->all(),[
                    'pdf.*' => 'mimes:pdf',
                ]);
                if($validate->fails()){
                    return back()->with('error','You can upload only PDF');
                }
            }

            for($i=0; $i<count($request->topic); $i++){
                $path = null;
               if(isset($request->file()['pdf'][$i])){
                $path = $request->file()['pdf'][$i]->store('/public/Topic_PDF');
                $path = removePublicFromPath($path);
               }
                $topic = CourseTopic::create([
                    'topic' => $request->topic[$i],
                    'videoLink' => $request->videoLink[$i],
                    'courseId' => $request->courseID,
                    'pdf' => $path,
                ]);
            }
           return redirect()->route('course.addQuiz',['courseID' => $request->courseID]);
        }
        return view('admin.courses.topics.add',['courseID' => $request->courseID ]);
    }

    public function addQuiz($courseID,Request $request){
        $course = Course::find($courseID);

        if(count($request->all()) > 0){
            // dd($request->all());
            for($index=0; $index < count($request->heading); $index++){
                TopicQuestion::create([
                    'topic_id' => $request->topicId,
                    'heading' => $request->heading[$index],
                    'opt_1' => $request->opt_1[$index],
                    'opt_2' => $request->opt_2[$index],
                    'opt_3' => $request->opt_3[$index],
                    'opt_4' => $request->opt_4[$index],
                    'c_opt' => $request->c_opt[$index]
                ]);
            }
            return back()->with('success','Question Created Successfully');
        }

        return view('admin.courses.topics.quizes.add',compact('courseID','course'));
    }

    public function editCourse(Request $request,$id){
        $course = Course::find($id);
        if(count($request->all()) > 0){
            $validate = Validator::make($request->all(),[
                'coverImage' => 'nullable|mimes:png,jpg,svg,jpeg'
            ]);
            if($validate->fails()){
               return back()->with('error', $validate->errors()->messages()['coverImage'][0]);
            }

            $data = $request->all();
            if($request->hasFile('coverImage')){
                $path = $request->file('coverImage')->store('public/Images');
                $path = str_replace('public/','',$path);
                $data['coverImage'] = $path;
            }
             $course->update($data);
        }
        return view('admin.courses.edit',compact('course'));
    }

    public function viewCourse(Request $request,$id=null){
        $course = Course::find($id);
        return view('admin.courses.view',compact('course'));
    }


    public function editTopic(Request $request,$id){
        $topic = CourseTopic::find($id);
        if(count($request->all()) > 0){
            $data = $request->all();
            if($request->hasFile('pdf')){
                $path = $request->file('pdf')->store('/public/Topic_PDF');
                $path = removePublicFromPath($path);
                $data['pdf'] = $path;
            }
            $topic->update($data);

            $topic->questions->each(function($ques){
                $ques->delete();
            });

            if(count($request->heading) > 0 ){
                for($index=0; $index < count($request->heading); $index++){
                    TopicQuestion::create([
                        'topic_id' => $topic->id,
                        'heading' => $request->heading[$index],
                        'opt_1' => $request->opt_1[$index],
                        'opt_2' => $request->opt_2[$index],
                        'opt_3' => $request->opt_3[$index],
                        'opt_4' => $request->opt_4[$index],
                        'c_opt' => $request->c_opt[$index]
                    ]);
                }
            }
            return back()->with('success','Topic Updated Successfully!');
        }
        return view('admin.courses.topics.edit',compact('topic'));
    }
}
