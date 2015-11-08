<?php

/**
 * Learning page web interface
 */
class LearningPageController extends BaseController {

	/**
	 * Display user question
	 * 
	 * Displays either random on concrete user question, depending on session
	 * variable 'user_question_id' being set or not
	 * 
	 * Answer div can be displayed or not depending on 'display_answer' session variable
	 */
	public function displayUserQuestion()
	{
		/*
		 * Read flash data from session, this may be set for this request only
		 * by LearningPageController::updateUserQuestion() before redirecting here
		 * If route is called directly both will be empty
		 */
		$userQuestionId = Session::get('user_question_id');
		$displayAnswer = Session::get('display_answer');

		/*
		 * Obtain user question to be displayed
		 */
		if ($userQuestionId)
		{
			/**
			 * Obtain conrete question
			 */
			$apiResponse = $this->apiDispatcher->callApiRoute('api_find_user_question', [
				'auth_token' => $this->apiAuthToken,
				'id' => $userQuestionId
			]);
		}
		else
		{
			/*
			 * Obtain random question
			 */
			$apiResponse = $this->apiDispatcher->callApiRoute('api_random_user_question', [
				'auth_token' => $this->apiAuthToken
			]);
		}

		/*
		 * Success API response - display learning page with obtained user question
		 */
		if ($apiResponse->getSuccess())
		{
			// display learning interface
			$this->viewData['display_answer'] = $displayAnswer;
			$this->viewData['user_question_id'] = $apiResponse->id;
			$this->viewData['question'] = $apiResponse->question;
			$this->viewData['answer'] = $apiResponse->answer;
			return View::make('learning_page', $this->viewData);
		}

		// error API response
		if ($apiResponse->getErrorCode() == Config::get('api.user_has_not_created_any_questions_yet.error_code'))
		{
			$this->viewData['info'] = Lang::get('messages.no_questions', ['url' => route('display_user_questions')]);
			return View::make('info_page', $this->viewData);
		}

		// unexpected API resppnse
		throw new Exception('Unexpected API response');
	}

	/**
	 * Update user question, redirect to learning page
	 */
	public function updateUserQuestion()
	{
		if (Input::has('add_good_answer'))
		{
			// increase number of good answers
			$apiResponse = $this->apiDispatcher->callApiRoute('api_add_good_answer', [
				'auth_token' => $this->apiAuthToken,
				'id' => Input::get('user_question_id'),
			]);
		}

		if (Input::has('add_bad_answer'))
		{
			// increase number of bad answers
			$apiResponse = $this->apiDispatcher->callApiRoute('api_add_bad_answer', [
				'auth_token' => $this->apiAuthToken,
				'id' => Input::get('user_question_id'),
			]);
		}

		if (Input::has('update'))
		{
			// update question and/or answer
			$apiResponse = $this->apiDispatcher->callApiRoute('api_update_user_question', [
				'auth_token' => $this->apiAuthToken,
				'id' => Input::get('user_question_id'),
				'question' => Input::get('question'),
				'answer' => Input::get('answer')
			]);

			/*
			 * Store in session for next request only
			 * This will force learning page to display concrete user question instead of random one,
			 * and the answer div to be displayed so user can see updated fields
			 */
			Session::flash('user_question_id', Input::get('user_question_id'));
			Session::flash('display_answer', true);
		}

		/*
		 * Success API response
		 */
		if (isset($apiResponse) && $apiResponse->getSuccess())
		{
			// redirect to learning page display user question
			return Redirect::route('learning_page_display_user_question');
		}

		// unexpected API resppnse
		throw new Exception('Unexpected API response');
	}

}
