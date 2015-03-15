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

Route::get('/admin/overview', ['as' => 'admin_overview', 'uses' => 'Admin_UserController@getOverview']);
Route::get('/admin/user/login', ['as' => 'admin_user_login', 'uses' => 'Admin_UserController@getLogin']);
Route::post('/admin/user/login', ['as' => 'admin_user_login', 'uses' => 'Admin_UserController@postLogin']);
Route::get('/admin/user/logout', ['as' => 'admin_user_logout', 'uses' => 'Admin_UserController@getLogout']);
