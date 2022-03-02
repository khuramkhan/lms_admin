<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TopicDetail;
use App\Models\TopicQuestion;
use App\Models\UserQuiz;
use App\Models\UserQuizDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function saveQuizTest(Request $request)
    {
        $data = $request->all();
        $data['questions'] = json_decode($request->questions);

        $validate = Validator::make($data,[
            'quiz_id' => 'required|integer|exists:topic_details,id',
            'questions' => 'required|array',
        ]);


        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->first()
            ]);
        }

        $quizId = TopicDetail::find($request->quiz_id);
        if($quizId->type != 'quiz'){
            return response()->json([
                'success' => false,
                'message' => 'Invalid Quiz Id'
            ]);
        }


        foreach($data['questions'] as $question)
        {
            $validateQuestion = $quizId->quizQuestions()->find($question->question_id);
            if(!$validateQuestion){
                $message = 'Invalid Question with (' .$question->question_id . ') id';
                return response()->json([
                    'success' => false,
                    'message' => $message
                ]);
            }
        }

        $validSelectedOptions = ['opt_1' , 'opt_2', 'opt_3', 'opt_4',''];

        foreach($data['questions'] as $question)
        {
            if(!in_array($question->selected,$validSelectedOptions)){
                $message = 'invalid selected option (' . $question->selected . ')';
                return response()->json([
                    'success' => false,
                    'message' => $message
                ]);
            }
        }

        $totalMarks = $quizId->quizQuestions()->count();
        $obtMarks = 0;
        foreach($data['questions'] as $question)
        {
            $quizQuestion = $quizId->quizQuestions()->find($question->question_id);
            if($quizQuestion->c_opt == $question->selected){
                $question->result = true;
                $obtMarks++;
            }else{
                $question->result = false;
            }
        }


        $userQuiz = UserQuiz::create([
            'user_id' => auth()->user()->id,
            'quiz_id' => $quizId->id,
            'total_marks' => $totalMarks,
            'obt_marks' => $obtMarks,
            'date' => date('Y-m-d')
        ]);

        foreach($data['questions'] as $question)
        {
            UserQuizDetail::create([
                'user_quiz_id' => $userQuiz->id,
                'question_id' => $question->question_id,
                'selected' => $question->selected,
                'result' => $question->result
            ]);
        }

        $userQuiz->userQuizDetail;
        return response()->json([
            'success' => true,
            'message' => 'quiz attempted successfully',
            'userQuiz' => $userQuiz
        ]);



    }
}
