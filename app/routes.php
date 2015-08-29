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
	Route::get('/', ['as' => 'landing_page', 'uses' => 'LandingPageController@index']);
	Route::get('/signup', ['as' => 'signup', 'uses' => 'SignupController@index']);
	Route::post('/signup', ['as' => 'signup', 'uses' => 'SignupController@signup', 'before' => 'csrf']);
	Route::get('/login', ['as' => 'login', 'uses' => 'LoginController@index']);
	Route::post('/login', ['as' => 'login', 'uses' => 'LoginController@login', 'before' => 'csrf']);
});

Route::group(array('before' => 'auth'), function() {
	Route::get('/overview', ['as' => 'overview', 'uses' => 'OverviewController@index']);
	Route::get('/logout', ['as' => 'logout', 'uses' => 'LogoutController@logout']);

	Route::get('/questions', ['as' => 'questions', 'uses' => 'UserQuestionsController@index']);
	Route::post('/questions/list', ['as' => 'list_questions', 'uses' => 'UserQuestionsController@listAction']);
	Route::post('/questions/delete', ['as' => 'delete_questions', 'uses' => 'UserQuestionsController@deleteAction']);
	Route::post('/questions/update', ['as' => 'update_questions', 'uses' => 'UserQuestionsController@updateAction']);
	Route::post('/questions/create', ['as' => 'create_questions', 'uses' => 'UserQuestionsController@createAction']);

	Route::get('/learn', ['as' => 'learning_page', 'uses' => 'LearningPageController@index']);
	Route::post('/learn', ['as' => 'learning_page', 'uses' => 'LearningPageController@update', 'before' => 'csrf']);
});
