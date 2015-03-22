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

Route::get('/', ['as' => 'landing', 'uses' => 'MainController@landing']);

Route::group(array('before' => 'guest'), function() {
	Route::get('/signup', ['as' => 'signup', 'uses' => 'AccountController@signup']);
	Route::post('/signup', ['as' => 'signup', 'uses' => 'AccountController@doSignup', 'before' => 'csrf']);
	Route::get('/login', ['as' => 'login', 'uses' => 'AccountController@login']);
	Route::post('/login', ['as' => 'login', 'uses' => 'AccountController@doLogin', 'before' => 'csrf']);
});

Route::group(array('before' => 'auth'), function() {
	Route::get('/overview', ['as' => 'overview', 'uses' => 'MainController@overview']);
	Route::get('/logout', ['as' => 'logout', 'uses' => 'AccountController@logout']);
});
