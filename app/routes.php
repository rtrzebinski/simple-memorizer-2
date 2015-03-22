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

Route::group(array('before' => 'guest'), function() {
	Route::get('/signup', ['as' => 'signup', 'uses' => 'AccountController@getSignup']);
	Route::post('/signup', ['as' => 'signup', 'uses' => 'AccountController@postSignup']);
	Route::get('/login', ['as' => 'login', 'uses' => 'AccountController@getLogin']);
	Route::post('/login', ['as' => 'login', 'uses' => 'AccountController@postLogin']);
});

Route::group(array('before' => 'auth'), function() {
	Route::get('/', ['as' => 'overview', 'uses' => 'OverviewController@getOverview']);
	Route::get('/logout', ['as' => 'logout', 'uses' => 'AccountController@getLogout']);
});
