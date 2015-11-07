<?php

/*
 * Web interface routes
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
	Route::get('/questions-export', ['as' => 'questions_export', 'uses' => 'UserQuestionsController@export']);
	Route::post('/questions-list', ['as' => 'list_questions', 'uses' => 'UserQuestionsController@listAction']);
	Route::post('/questions-delete', ['as' => 'delete_questions', 'uses' => 'UserQuestionsController@deleteAction']);
	Route::post('/questions-update', ['as' => 'update_questions', 'uses' => 'UserQuestionsController@updateAction']);
	Route::post('/questions-create', ['as' => 'create_questions', 'uses' => 'UserQuestionsController@createAction']);

	Route::get('/questions-import', ['as' => 'questions_import', 'uses' => 'UserQuestionsImportController@index']);
	Route::post('/questions-import', ['as' => 'questions_import', 'uses' => 'UserQuestionsImportController@import']);

	Route::get('/learn', ['as' => 'learning_page_display_user_question', 'uses' => 'LearningPageController@displayUserQuestion']);
	Route::post('/learn', ['as' => 'learning_page_update_user_question', 'uses' => 'LearningPageController@updateUserQuestion', 'before' => 'csrf']);
});

/*
 * REST API routes
 */

// user methods
Route::post('/api/login', ['as' => 'api_login', 'uses' => 'API_LoginController@login']);
Route::post('/api/logout', ['as' => 'api_logout', 'uses' => 'API_LogoutController@logout']);
Route::post('/api/signup', ['as' => 'api_signup', 'uses' => 'API_SignupController@signup']);

// user question methods
Route::post('/api/questions-collection', ['as' => 'api_user_questions_collection', 'uses' => 'API_UserQuestionsController@collection']);
Route::post('/api/find-question', ['as' => 'api_find_user_question', 'uses' => 'API_UserQuestionsController@find']);
Route::post('/api/random-question', ['as' => 'api_random_user_question', 'uses' => 'API_UserQuestionsController@random']);
Route::post('/api/create-question', ['as' => 'api_create_user_question', 'uses' => 'API_UserQuestionsController@create']);
Route::post('/api/update-question', ['as' => 'api_update_user_question', 'uses' => 'API_UserQuestionsController@update']);
Route::post('/api/delete-question', ['as' => 'api_delete_user_question', 'uses' => 'API_UserQuestionsController@delete']);
Route::post('/api/add-good-answer', ['as' => 'api_add_good_answer', 'uses' => 'API_UserQuestionsController@addGoodAnswer']);
Route::post('/api/add-bad-answer', ['as' => 'api_add_bad_answer', 'uses' => 'API_UserQuestionsController@addBadAnswer']);
