<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'Api\Auth\RegisterController@register');
Route::post('login', 'Api\Auth\LoginController@Login');
Route::post('refresh', 'Api\Auth\LoginController@refresh');

Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'Api\Auth\LoginController@logout');
    Route::get('quiz', 'Api\QuizController@index');
	Route::get('question/{id}', 'Api\QuizController@show_question');
	Route::post('submit', 'Api\QuizController@store_answer');
	Route::get('result/{id}', 'Api\QuizController@show_result');
	Route::post('profile', 'Api\UserController@profile_update');
	Route::get('report/{id}', 'Api\UserController@show_report');
});