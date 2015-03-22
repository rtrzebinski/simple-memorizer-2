<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

Route::get('/', function() 
{
	return View::make('hello');
});

Route::get('/overview', ['as' => 'overview', 'uses' => 'OverviewController@getOverview']);
Route::get('/user/signup', ['as' => 'user_signup', 'uses' => 'AccountController@getSignup']);
Route::post('/user/signup', ['as' => 'user_signup', 'uses' => 'AccountController@postSignup']);
Route::get('/user/login', ['as' => 'user_login', 'uses' => 'AccountController@getLogin']);
Route::post('/user/login', ['as' => 'user_login', 'uses' => 'AccountController@postLogin']);
Route::get('/user/logout', ['as' => 'user_logout', 'uses' => 'AccountController@getLogout']);
