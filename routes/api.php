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

});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});