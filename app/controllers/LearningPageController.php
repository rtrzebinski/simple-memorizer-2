<?php

/**
 * Learning page web interface
 */
class LearningPageController extends BaseController {

	/**
	 * HTTP GET request handler
	 * @param User_Question $userQuestionId
	 * @param bool $displayAnswer
	 */
	public function index($userQuestionId = null, $displayAnswer = false)
	{
		/**
		 * Display conrete question
		 */
		if ($userQuestionId)
		{
			$apiFindUserQuestionResponse = $this->apiDispatcher->callApiRoute('api_find_user_question', [
				'auth_token' => Session::get('auth_token'),
				'id' => $userQuestionId
			]);

			if ($apiFindUserQuestionResponse->getSuccess())
			{
				// display learning interface
				$this->viewData['display_answer'] = $displayAnswer;
				$this->viewData['user_question_id'] = $apiFindUserQuestionResponse->id;
				$this->viewData['question'] = $apiFindUserQuestionResponse->question;
				$this->viewData['answer'] = $apiFindUserQuestionResponse->answer;
				return View::make('learning_page', $this->viewData);
			}

			// error API response
			if ($apiFindUserQuestionResponse->getErrorCode() == Config::get('api.user_question_does_not_exist.error_code'))
			{
				// display info if user has no questions
				$this->viewData['info'] = Lang::get('messages.user_question_does_not_exist', ['url' => route('questions')]);
				return View::make('info_page', $this->viewData);
			}
		}

		/*
		 * Display random question
		 */
		if (!$userQuestionId)
		{
			// obtain random user question if not passed as argument
			$apiRandomUserQuestionResponse = $this->apiDispatcher->callApiRoute('api_random_user_question', [
				'auth_token' => Session::get('auth_token')
			]);

			if ($apiRandomUserQuestionResponse->getSuccess())
			{
				// display learning interface
				$this->viewData['display_answer'] = $displayAnswer;
				$this->viewData['user_question_id'] = $apiRandomUserQuestionResponse->id;
				$this->viewData['question'] = $apiRandomUserQuestionResponse->question;
				$this->viewData['answer'] = $apiRandomUserQuestionResponse->answer;
				return View::make('learning_page', $this->viewData);
			}

			// error API response
			if ($apiRandomUserQuestionResponse->getErrorCode() == Config::get('api.user_has_not_created_any_questions_yet.error_code'))
			{
				// display info if user has no questions
				$this->viewData['info'] = Lang::get('messages.no_questions', ['url' => route('questions')]);
				return View::make('info_page', $this->viewData);
			}
		}
	}

	/**
	 * HTTP POST request handler
	 */
	public function update()
	{
		// increase number of good or bad answers
		if (Input::has('answer_correctness'))
		{
			// increase number of good answers
			if (Input::get('answer_correctness') == 'I know')
			{
				$this->apiDispatcher->callApiRoute('api_add_good_answer', [
					'auth_token' => Session::get('auth_token'),
					'id' => Input::get('user_question_id'),
				]);
			}

			// increase number of bad answers
			if (Input::get('answer_correctness') == "I don't know")
			{
				$this->apiDispatcher->callApiRoute('api_add_bad_answer', [
					'auth_token' => Session::get('auth_token'),
					'id' => Input::get('user_question_id'),
				]);
			}

			// display next user question
			return Redirect::route('learning_page');
		}

		// update question and/or answer
		if (Input::has('update'))
		{
			$apiUpdateUserQuestionResponse = $this->apiDispatcher->callApiRoute('api_update_user_question', [
				'auth_token' => Session::get('auth_token'),
				'id' => Input::get('user_question_id'),
				'question' => Input::get('question'),
				'answer' => Input::get('answer')
			]);

			if ($apiUpdateUserQuestionResponse->getSuccess())
			{
				// display updated fields
				return $this->index(Input::get('user_question_id'), Input::get('display_answer'));
			}
		}
	}

}
