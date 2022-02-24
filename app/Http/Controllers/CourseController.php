<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTopic;
use App\Models\TopicDetail;
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

            $data = $request->all();
            $imageName = time().'.'.$request->file('coverImage')->extension();
            $request->file('coverImage')->move(public_path('images/CoverImages'), $imageName);
            $imageName = 'images/CoverImages/' . $imageName;
            $data['coverImage'] = $imageName;

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

            // dd($request->all());
            if(!empty($request->pdf)){
                $validate = Validator::make($request->all(),[
                    'pdf.*' => 'mimes:pdf',
                ]);
                if($validate->fails()){
                    return back()->with('error','You can upload only PDF');
                }
            }

            $topic = CourseTopic::create([
                'topic' => $request->topic,
                'courseId' => $request->courseID,
            ]);

            if(isset($request->quizName)){
                for($index=0; $index < count($request->quizName); $index++)
                {
                    $topicDetail = TopicDetail::create([
                        'name' => $request->quizName[$index],
                        'topic_id' => $topic->id,
                        'type' => 'quiz'
                    ]);

                    $headings = array_filter($request->heading[$index], fn($value) => !is_null($value) && $value !== '');
                    $opt_1 = array_filter($request->opt_1[$index], fn($value) => !is_null($value) && $value !== '');
                    $opt_2 = array_filter($request->opt_2[$index], fn($value) => !is_null($value) && $value !== '');
                    $opt_3 = array_filter($request->opt_3[$index], fn($value) => !is_null($value) && $value !== '');
                    $opt_4 = array_filter($request->opt_4[$index], fn($value) => !is_null($value) && $value !== '');
                    $c_opt = array_filter($request->c_opt[$index], fn($value) => !is_null($value) && $value !== '');

                    foreach($headings as $i => $heading){
                        TopicQuestion::create([
                            'topic_detail_id' => $topicDetail->id,
                            'heading' => $headings[$i],
                            'opt_1' => $opt_1[$i],
                            'opt_2' => $opt_2[$i],
                            'opt_3' => $opt_3[$i],
                            'opt_4' => $opt_4[$i],
                            'c_opt' => $c_opt[$i]
                        ]);
                    }

                }
            }

            if(!empty($request->videoLink)){
                $videoLinks = array_filter($request->videoLink, fn($value) => !is_null($value) && $value !== '');
                $count = 0;
                foreach($videoLinks as $videoLink){
                    TopicDetail::create([
                        'name' => $request->videoLinkName[$count],
                        'topic_id' => $topic->id,
                        'videoLink' => $videoLink,
                        'type' => 'videoLink'
                    ]);
                    $count++;
                }
            }

            if(!empty($request->addressUrl)){
                $addressUrls = array_filter($request->addressUrl, fn($value) => !is_null($value) && $value !== '');
                $count = 0;
                foreach($addressUrls as $addressUrl){
                    TopicDetail::create([
                        'name' => $request->addressUrlName[$count],
                        'topic_id' => $topic->id,
                        'addressUrl' => $addressUrl,
                        'type' => 'addressUrl'
                    ]);
                    $count++;
                }
            }

            if(!empty($request->text)){
                $texts = array_filter($request->text, fn($value) => !is_null($value) && $value !== '');
                $count = 0;
                foreach($texts as $text){
                    TopicDetail::create([
                        'name' => $request->textName[$count],
                        'topic_id' => $topic->id,
                        'text' => $text,
                        'type' => 'text'
                    ]);
                    $count++;
                }
            }

            if(!empty($request->pdf))
            {
                $pdfs = array_filter($request->pdf, fn($value) => !is_null($value) && $value !== '');
                $count = 0;
                foreach($pdfs as $pdf)
                {
                    $imageName = time().'.'.$pdf->extension();
                    $pdf->move(public_path('images/Topic_PDF'), $imageName);
                    $imageName = 'images/Topic_PDF/' . $imageName;

                    TopicDetail::create([
                        'name' => $request->pdfName[$count],
                        'topic_id' => $topic->id,
                        'pdf' =>  $imageName,
                        'type' => 'pdf'
                    ]);
                    $count++;
                }
            }
            if($request->next == 'next'){
                return back()->with('success','Topic has been added successfully');
            }
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
                $imageName = time().'.'.$request->file('coverImage')->extension();
                $request->file('coverImage')->move(public_path('images/CoverImages'), $imageName);
                $imageName = 'images/CoverImages/' . $imageName;
                $data['coverImage'] = $imageName;
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

            if(!empty($request->pdf)){
                $validate = Validator::make($request->all(),[
                    'pdf.*' => 'mimes:pdf',
                ]);
                if($validate->fails()){
                    return back()->with('error','You can upload only PDF');
                }
            }


            $prePdfFiles = isset($request->prePdf) ? array_filter($request->prePdf) : [];
            $preDetail = $topic->topicDetail()->whereNotIn('id',$prePdfFiles)->delete();

            $topic->update([
                'topic' => $request->topic,
                'courseId' => $request->courseID,
            ]);



            if(!empty($request->videoLink)){
                $videoLinks = array_filter($request->videoLink, fn($value) => !is_null($value) && $value !== '');
                $count = 0;
                foreach($videoLinks as $videoLink){
                    TopicDetail::create([
                        'name' => $request->videoLinkName[$count],
                        'topic_id' => $topic->id,
                        'videoLink' => $videoLink
                    ]);
                }
                $count++;
            }

            if(!empty($request->addressUrl)){
                $addressUrls = array_filter($request->addressUrl, fn($value) => !is_null($value) && $value !== '');
                $count = 0;
                foreach($addressUrls as $addressUrl){
                    TopicDetail::create([
                        'name' => $request->addressUrlName[$count],
                        'topic_id' => $topic->id,
                        'addressUrl' => $addressUrl
                    ]);
                    $count++;
                }
            }

            if(!empty($request->text)){
                $texts = array_filter($request->text, fn($value) => !is_null($value) && $value !== '');
                $count = 0;
                foreach($texts as $text){
                    TopicDetail::create([
                        'name' => $request->textName[$count],
                        'topic_id' => $topic->id,
                        'text' => $text
                    ]);
                    $count++;
                }
            }

            if(!empty($request->pdf))
            {
                $pdfs = array_filter($request->pdf, fn($value) => !is_null($value) && $value !== '');
                $count = 0;
                foreach($pdfs as $pdf)
                {
                    $imageName = time().'.'.$pdf->extension();
                    $pdf->move(public_path('images/Topic_PDF'), $imageName);
                    $imageName = 'images/Topic_PDF/' . $imageName;

                    TopicDetail::create([
                        'name' => $request->pdfName[$count],
                        'topic_id' => $topic->id,
                        'pdf' => $imageName,
                    ]);
                    $count++;
                }
            }
            if($request->next == 'next'){
                return back()->with('success','Topic has been added successfully');
            }

            return back()->with('success','Topic Updated Successfully!');
        }
        return view('admin.courses.topics.edit',compact('topic'));
    }
}
