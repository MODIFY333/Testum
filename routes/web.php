<?php

use App\User;
use App\Topic;
use App\Answer;
use App\Question;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', 'home');

Auth::routes();

Route::get('/home', function(){
  $topics = Topic::all();
  $questions = Question::all();
  return view('home', compact('topics', 'questions'));
});

Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => '1',
        'redirect_uri' => 'http://example.com/callback',
        'response_type' => 'token', 
        'scope' => '',
    ]);

    return redirect('http://your-app.com/oauth/authorize?'.$query);
});

Route::group(['middleware'=> 'isadmin'], function(){

  Route::get('/admin', function(){

    $user = User::where('role', '!=', 'A')->count();
    $question = Question::count();
    $answers = Answer::count();
    $quiz = Topic::count();
    $user_latest = User::where('id', '!=', Auth::id())->orderBy('created_at', 'desc')->get();

    return view('admin.dashboard', ['user'=> $user, 'question' => $question, 'answer' => $answers, 'quiz'=> $quiz, 'user_latest' => $user_latest]);

  });

  Route::resource('/admin/all_reports', 'AllReportController');
  Route::resource('/admin/top_report', 'TopReportController');
  Route::resource('/admin/topics', 'TopicController');
  Route::resource('/admin/questions', 'QuestionsController');
  Route::post('/admin/questions/import_questions', 'QuestionsController@importExcelToDB')->name('import_questions');
  Route::resource('/admin/answers', 'AnswersController');
  Route::resource('/admin/settings', 'SettingController');


  Route::post('/admin/users/destroy', 'DestroyAllController@AllUsersDestroy');
  Route::post('/admin/answers/destroy', 'DestroyAllController@AllAnswersDestroy');

});

Route::resource('/admin/users', 'UsersController');
Route::get('/admin/profile', function(){
  if (Auth::check()) {
    return view('admin.users.profile');
  } else {
    return redirect('/');
  }
});
Route::get('/admin/my_reports', 'MyReportsController@index')->name('my_report');
Route::get('/admin/my_reports/{my_reports}', 'MyReportsController@show')->name('my_report_show');

Route::get('start_quiz/{id}', function($id){
  $topic = Topic::findOrFail($id);
  return view('main_quiz', compact('topic'));
})->name('start_quiz');

Route::resource('start_quiz/{id}/quiz', 'MainQuizController');

Route::get('start_quiz/{id}/finish', function($id){
  $topic = Topic::findOrFail($id);
  $count_questions = Question::where('topic_id', $id)->count();
  $answers = Answer::where('topic_id', $id)->get();
  return view('finish', compact('topic', 'answers', 'count_questions'));
});
