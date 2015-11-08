<?php

/**
 * User questions web interface
 * 
 * Allows user to manage question/answer pairs, and export user questions as CSV file
 */
class UserQuestionsController extends BaseController {

	use JtableTrait;

	/**
	 * Display user questions web interface
	 */
	public function displayUserQuestions()
	{
		$this->viewData['user'] = Auth::user();
		return View::make('questions', $this->viewData);
	}

	/**
	 * List user questions
	 * 
	 * jTable listAction ajax handler 
	 */
	public function listUserQuestions()
	{
		list($orderByField, $orderBySort) = explode(' ', Input::get('jtSorting'));
		$skip = Input::get('jtStartIndex');
		$take = Input::get('jtPageSize');

		// call API
		$apiUserQuestionsCollectionResponse = $this->apiDispatcher->callApiRoute('api_user_questions_collection', [
			'auth_token' => $this->apiAuthToken,
			'take' => $take,
			'skip' => $skip,
			'order_by_field' => $orderByField,
			'order_by_sort' => $orderBySort,
		]);

		// success API response
		if ($apiUserQuestionsCollectionResponse->getSuccess())
		{
			/*
			 * Convert to format expected by jTable
			 */
			$records = [];
			foreach ($apiUserQuestionsCollectionResponse->getData()['records'] as $row)
			{
				$records[] = [
					'id' => $row['id'],
					'question' => $row['question'],
					'answer' => $row['answer'],
					'percent_of_good_answers' => $row['percent_of_good_answers'],
				];
			}

			return $this->jtableSuccessReponse([
					'Records' => $records,
					// count total number of rows
					'TotalRecordCount' => $apiUserQuestionsCollectionResponse->getData()['count']
			]);
		}

		// unexpected API resppnse
		throw new Exception('Unexpected API response');
	}

	/**
	 * Delete user question
	 * 
	 * jTable deleteAction ajax handler
	 */
	public function deleteUserQuestion()
	{
		// call API
		$apiDeleteUserQuestionResponse = $this->apiDispatcher->callApiRoute('api_delete_user_question', [
			'auth_token' => $this->apiAuthToken,
			'id' => Input::get('id')
		]);

		// error API response
		if ($apiDeleteUserQuestionResponse->getErrorCode() == Config::get('api.user_question_does_not_exist.error_code'))
		{
			return $this->jtableErrorResponse("Not found");
		}

		// success API response
		if ($apiDeleteUserQuestionResponse->getSuccess())
		{
			return $this->jtableSuccessReponse();
		}

		// unexpected API resppnse
		throw new Exception('Unexpected API response');
	}

	/**
	 * Update user question
	 * 
	 * jTable updateAction ajax handler 
	 */
	public function updateUserQuestion()
	{
		// call API
		$apiUpdateUserQuestionResponse = $this->apiDispatcher->callApiRoute('api_update_user_question', [
			'auth_token' => $this->apiAuthToken,
			'id' => Input::get('id'),
			'question' => Input::get('question'),
			'answer' => Input::get('answer'),
		]);

		// error API response
		if ($apiUpdateUserQuestionResponse->getErrorCode() == Config::get('api.user_question_does_not_exist.error_code'))
		{
			return $this->jtableErrorResponse("Not found");
		}

		// success API response
		if ($apiUpdateUserQuestionResponse->getSuccess())
		{
			return $this->jtableSuccessReponse();
		}

		// unexpected API resppnse
		throw new Exception('Unexpected API response');
	}

	/**
	 * Create user question
	 * 
	 * jTable createAction ajax handler 
	 */
	public function createUserQuestion()
	{
		// call API
		$apiCreateUserQuestionResponse = $this->apiDispatcher->callApiRoute('api_create_user_question', [
			'auth_token' => $this->apiAuthToken,
			'question' => Input::get('question'),
			'answer' => Input::get('answer'),
		]);

		// success API response
		if ($apiCreateUserQuestionResponse->getSuccess())
		{
			return $this->jtableSuccessReponse(['Record' => [
						'id' => $apiCreateUserQuestionResponse->getData()['user_question_id'],
						'percent_of_good_answers' => 0,
						'question' => Input::get('question'),
						'answer' => Input::get('answer'),
			]]);
		}

		// unexpected API resppnse
		throw new Exception('Unexpected API response');
	}

	/**
	 * Export user questions as a CSV file
	 */
	public function exportUserQuestionsToCsv()
	{
		// call API
		$apiUserQuestionsCollectionResponse = $this->apiDispatcher->callApiRoute('api_user_questions_collection', [
			'auth_token' => $this->apiAuthToken,
		]);

		// success API response
		if ($apiUserQuestionsCollectionResponse->getSuccess())
		{
			// check total number of user questions to export
			if (!$apiUserQuestionsCollectionResponse->getData()['count'])
			{
				// display info if nothing to export
				$this->viewData['info'] = Lang::get('messages.nothing_to_export');
				return View::make('info_page', $this->viewData);
			}

			// build csv file
			$csvBuilder = App::make('CsvBuilder');
			$csvBuilder->setData($apiUserQuestionsCollectionResponse->getData()['records']);
			$csvBuilder->setHeaderField('question', 'question');
			$csvBuilder->setHeaderField('answer', 'answer');
			$csvBuilder->setHeaderField('number_of_good_answers', 'number_of_good_answers');
			$csvBuilder->setHeaderField('number_of_bad_answers', 'number_of_bad_answers');
			$csvBuilder->setHeaderField('percent_of_good_answers', 'percent_of_good_answers');
			$csvBuilder->build();

			// download file
			return Response::download($csvBuilder->getPath(), 'export.csv', [
					'Content-Type' => 'text/csv'
			]);
		}

		// unexpected API resppnse
		throw new Exception('Unexpected API response');
	}

}
