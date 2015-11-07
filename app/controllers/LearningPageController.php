<?php

/**
 * Learning page web interface
 */
class LearningPageController extends BaseController {

	/**
	 * Display user question
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
				'auth_token' => Session::get('api_auth_token'),
				'id' => $userQuestionId
			]);
		}
		else
		{
			/*
			 * Obtain random question
			 */
			$apiResponse = $this->apiDispatcher->callApiRoute('api_random_user_question', [
				'auth_token' => Session::get('api_auth_token')
			]);
		}

		/*
		 * Success API response
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
			$this->viewData['info'] = Lang::get('messages.no_questions', ['url' => route('questions')]);
			return View::make('info_page', $this->viewData);
		}

		// unexpected API resppnse
		throw new Exception('Unexpected API response');
	}

	/**
	 * Update user question
	 */
	public function updateUserQuestion()
	{
		// increase number of good or bad answers
		if (Input::has('answer_correctness'))
		{
			// increase number of good answers
			if (Input::get('answer_correctness') == 'I know')
			{
				$apiResponse = $this->apiDispatcher->callApiRoute('api_add_good_answer', [
					'auth_token' => Session::get('api_auth_token'),
					'id' => Input::get('user_question_id'),
				]);
			}

			// increase number of bad answers
			if (Input::get('answer_correctness') == "I don't know")
			{
				$apiResponse = $this->apiDispatcher->callApiRoute('api_add_bad_answer', [
					'auth_token' => Session::get('api_auth_token'),
					'id' => Input::get('user_question_id'),
				]);
			}

			// display updated answer after page reload
			$displayAnswer = false;
		}

		// update question and/or answer
		if (Input::has('update'))
		{
			$apiResponse = $this->apiDispatcher->callApiRoute('api_update_user_question', [
				'auth_token' => Session::get('api_auth_token'),
				'id' => Input::get('user_question_id'),
				'question' => Input::get('question'),
				'answer' => Input::get('answer')
			]);

			// don't display question answer after page reload
			$displayAnswer = true;
		}

		/*
		 * Success API response
		 */
		if (isset($apiResponse) && $apiResponse->getSuccess())
		{
			/*
			 * Store in session for next request only
			 * This will force learning page to display concrete user question
			 * instead of random one, answer will not be visible
			 */
			Session::flash('user_question_id', Input::get('user_question_id'));
			Session::flash('display_answer', $displayAnswer);

			// redirect to learning page
			return Redirect::route('learning_page_display_user_question');
		}

		// unexpected API resppnse
		throw new Exception('Unexpected API response');
	}

}
