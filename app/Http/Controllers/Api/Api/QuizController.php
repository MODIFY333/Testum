<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Topic;
use App\Question;

class QuizController extends Controller
{
  public function index(){
  	$users=Auth::user()->name()->toArray();

  	$topics = Topic::all()->toArray();
  	$questions = Question::all()->toArray();

		return response()->json(array('users'=>$user,'topics'=>$topics,'questions'=>$questions), 200);

  	// return response()->json(['data' => $posts], 200, [], JSON_NUMERIC_CHECK);

  }
}
