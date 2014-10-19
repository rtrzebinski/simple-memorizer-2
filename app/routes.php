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

Route::group(array('namespace' => 'Admin'), function() {
	Route::get('/admin/overview', array('uses' => 'UserController@getOverview'));
	Route::get('/admin/user/login', array('uses' => 'UserController@getLogin'));
	Route::post('/admin/user/login', array('uses' => 'UserController@postLogin'));
	Route::get('/admin/user/logout', array('uses' => 'UserController@getLogout'));
});
