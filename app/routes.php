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

Route::get('/admin/overview', array('uses' => 'Admin_UserController@getOverview'));
Route::get('/admin/user/login', array('uses' => 'Admin_UserController@getLogin'));
Route::post('/admin/user/login', array('uses' => 'Admin_UserController@postLogin'));
Route::get('/admin/user/logout', array('uses' => 'Admin_UserController@getLogout'));
