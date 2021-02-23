<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Topic;
use App\Question;
use App\Answer;
use App\User;

class QuizController extends Controller
{
  public function index(){

    $user = Auth::user();
  	$topics = Topic::all()->toArray();
  	$questions = Question::all()->toArray();

		return response()->json(array('users' =>$user,'topics'=>$topics,'questions'=>$questions), 200);

  	// return response()->json(['data' => $posts], 200, [], JSON_NUMERIC_CHECK);
	}
	
    public function show_question($id){
        $topic = Topic::findOrFail($id);
        $user_id = Auth::user()->id;
        $existing = Answer::where('user_id',$user_id)->where('topic_id',$id)->first();
        if($existing == ""){
            $topic_ques = Question::where('topic_id', $id)->get()->shuffle(); 
            $questions = $topic_ques->all();
            return response()->json(array('topic'=>$topic,'questions' => $questions), 200);
        }
        else{
          return response()->json("test already given", 300);  
        }
    }


	public function store_answer(Request $request)
  {
    $input = $request->all();
    $user_id = Auth::user()->id;
    if($user_id){
        $topic_id = Question::findorfail($input['question_id'])->topic->id;
        $success = Answer::create([
        	'user_id' => $user_id,
        	'answer' => $input['answer'],
        	'topic_id' => $topic_id,
        	'question_id' => $input['question_id'],
        	'user_answer' => $input['user_answer']
        ]);
        if($success){
        	return response()->json('success', 200);
        }
    }
  }

  public function show_result($id){
    $user_id = Auth::user()->id;
	  $topic = Topic::findOrFail($id);
	  $count_que = Question::where('topic_id', $id)->count();
	  $answers = Answer::where('user_id', $user_id)
	  									->where('topic_id', $id)->get();
	  $right = 0;
	  foreach($answers as $answer){
	  	if($answer->user_answer == $answer->answer){
	  		$right++;
	  	}
	  }						
		return response()->json(array('topic'=>$topic, 'count'=>$count_que, 'right' => $right), 200);
	}
}
