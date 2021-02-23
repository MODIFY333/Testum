<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Answer;
use App\User;

class UserController extends Controller
{
  
  public function profile_update(Request $request)
  {
    $user = Auth::user();

    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email'
    ]);

    $input = $request->all();

    if (Auth::user()->role == 'A') {
      $status =  $user->update([
        'name' => $input['name'],
        'email' => $input['email'],
        'password' => bcrypt($input['password']),
        'mobile' => $input['mobile'],
        'address' => $input['address'],
        'city' => $input['city'],
        'role' => $input['role'],
      ]);
    } else if (Auth::user()->role == 'S') {
      $status = $user->update([
        'name' => $input['name'],
        'email' => $input['email'],
        'password' => bcrypt($input['password']),
        'mobile' => $input['mobile'],
        'address' => $input['address'],
        'city' => $input['city'],
      ]);
    }

    if($status){
      $user = Auth::user();
      return response()->json(array('user'=>$user), 200);
    }
    else{
      return response()->json('unsucessful', 400);
    }
  }
  
  public function show_report($id){
    $user_id = Auth::user()->id;
    $topic = Topic::findOrFail($id);
    $topic_que = Question::where('topic_id', $id)->get();
    $answers = Answer::where('user_id', $user_id)
                      ->where('topic_id', $id)->get();          
    return response()->json(array('topic'=>$topic, 'topic_que'=>$topic_que, 'answers' => $answers), 200);
  }

}
