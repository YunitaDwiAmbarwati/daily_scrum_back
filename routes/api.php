<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', 'UserController@login'); 
Route::post('register', 'UserController@register');

Route::group(['middleware' => ['jwt.verify']], function () {

	Route::get('login/check', "UserController@LoginCheck");
	Route::post('logout', "UserController@logout"); 

	Route::get('user', "UserController@index");
	Route::get('user/{limit}/{offset}', "UserController@getAll");

	//daily
	Route::get('daily', "DailyController@index"); //read daily
	Route::get('daily/{limit}/{offset}', "DailyController@getAll"); //read daily
	Route::post('daily', 'DailyController@store'); //create daily
	Route::put('daily/{id}', "DailyController@update"); //update daily
	Route::delete('daily/{id}', "DailyController@delete"); //delete daily

	

});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

