<?php

/**
 * REST API user questions controller
 * 
 * Manage user questions - question/answer pairs
 */
class API_UserQuestionsController extends API_BaseController {

	/**
	 * Collection of user questions
	 * 
	 * Parameters:
	 * - string auth_token
	 * - int take Number of rows to take
	 * - int skip Number of rows to skip
	 * - string order_by_field Column to sort by
	 * - string order_by_sort ASC|DESC
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function collection()
	{
		return $this->apiOutput(function(User $user) {
				$take = Input::get('take');
				$skip = Input::get('skip');
				$orderByField = Input::get('order_by_field');
				$orderBySort = Input::get('order_by_sort');

				// Obtain user questions collection from repository
				$userQuestionRepository = App::make('UserQuestionRepository', [$user]);
				$collection = $userQuestionRepository->collection($take, $skip, $orderByField, $orderBySort);

				return $this->successResponse([
						'records' => $collection,
						// count of all items in the entire collection (not of returned collection part)
						'count' => $userQuestionRepository->count(),
				]);
			}, true);
	}

	/**
	 * Create new user question
	 * 
	 * Parameters:
	 * - string auth_token
	 * - string question
	 * - string answer
	 * - int number_of_good_answers (default 0)
	 * - int number_of_bad_answers (default 0)
	 * - int percent_of_good_answers (default 0)
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function create()
	{
		return $this->apiOutput(function(User $user) {

				$question = Input::get('question');
				$answer = Input::get('answer');
				$numberOfGoodAnswers = Input::get('number_of_good_answers', 0);
				$numberOfBadAnswers = Input::get('number_of_bad_answers', 0);
				$percentOfGoodAnswers = Input::get('percent_of_good_answers', 0);

				// Create user question via repository
				$userQuestionRepository = App::make('UserQuestionRepository', [$user]);
				$userQuestion = $userQuestionRepository->create($question, $answer, $numberOfGoodAnswers, $numberOfBadAnswers, $percentOfGoodAnswers);

				return $this->successResponse([
						'user_question_id' => $userQuestion->id
				]);
			}, true);
	}

	/**
	 * Update existing user question
	 * 
	 * Parameters:
	 * - string auth_token
	 * - int id User question id
	 * - string question
	 * - string answer
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function update()
	{
		return $this->apiOutput(function(User $user) {
				// Find user question using repository
				$userQuestionRepository = App::make('UserQuestionRepository', [$user]);
				$userQuestion = $userQuestionRepository->find(Input::get('id'));

				// Retur error if user question does not exist
				if (!$userQuestion)
				{
					return $this->errorResponse('user_question_does_not_exist');
				}

				// Update fields
				$fields = [
					'question',
					'answer'
				];

				foreach ($fields as $row)
				{
					if (Input::has($row))
					{
						$userQuestion->question->{$row} = Input::get($row);
					}
				}

				$userQuestion->question->save();

				return $this->successResponse();
			}, true);
	}

	/**
	 * Delete existing user question
	 * 
	 * Parameters:
	 * - string auth_token
	 * - int id User question id
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function delete()
	{
		return $this->apiOutput(function(User $user) {
				// Find user question using repository
				$userQuestionRepository = App::make('UserQuestionRepository', [$user]);
				$userQuestion = $userQuestionRepository->find(Input::get('id'));

				// Retur error if user question does not exist
				if (!$userQuestion)
				{
					return $this->errorResponse('user_question_does_not_exist');
				}

				/*
				 * Delete user question
				 * Foreing key deletes all UserQuestions when question is deleted
				 */
				$userQuestion->question->delete();

				return $this->successResponse();
			}, true);
	}

	/**
	 * Return random user question
	 * 
	 * Return less known questions more often that better known
	 * 
	 * Parameters:
	 * - string auth_token
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function random()
	{
		return $this->apiOutput(function(User $user) {
				// Obtain random user question using repository
				$userQuestionRepository = App::make('UserQuestionRepository', [$user]);
				$userQuestion = $userQuestionRepository->randomUserQuestion();

				// Retur error if user question does not exist
				if (!$userQuestion)
				{
					return $this->errorResponse('user_has_not_created_any_questions_yet');
				}

				return $this->successResponse([
						'id' => $userQuestion->id,
						'question' => $userQuestion->question->question,
						'answer' => $userQuestion->question->answer,
						'percent_of_good_answers' => $userQuestion->percent_of_good_answers,
						'number_of_good_answers' => $userQuestion->number_of_good_answers,
						'number_of_bad_answers' => $userQuestion->number_of_bad_answers
				]);
			}, true);
	}

	/**
	 * Find user question
	 * 
	 * Parameters:
	 * - string auth_token
	 * - int id
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function find()
	{
		return $this->apiOutput(function(User $user) {
				// Obtain user question using repository
				$userQuestionRepository = App::make('UserQuestionRepository', [$user]);
				$userQuestion = $userQuestionRepository->find(Input::get('id'));

				// Retur error if user question does not exist
				if (!$userQuestion)
				{
					return $this->errorResponse('user_question_does_not_exist');
				}

				return $this->successResponse([
						'id' => $userQuestion->id,
						'question' => $userQuestion->question->question,
						'answer' => $userQuestion->question->answer,
						'percent_of_good_answers' => $userQuestion->percent_of_good_answers,
						'number_of_good_answers' => $userQuestion->number_of_good_answers,
						'number_of_bad_answers' => $userQuestion->number_of_bad_answers
				]);
			}, true);
	}

	/**
	 * Add good answer to user question
	 * 
	 * Parameters:
	 * - string auth_token
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function addGoodAnswer()
	{
		return $this->apiOutput(function(User $user) {
				return $this->addAnswer(true, $user);
			}, true);
	}

	/**
	 * Add bad answer to user question
	 * 
	 * Parameters:
	 * - string auth_token
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function addBadAnswer()
	{
		return $this->apiOutput(function(User $user) {
				return $this->addAnswer(false, $user);
			}, true);
	}

	/**
	 * @param bool $isAnswerCorrect
	 * @param User $user
	 * @return Illuminate\Http\Response
	 */
	private function addAnswer($isAnswerCorrect, User $user)
	{
		// Find user question using repository
		$userQuestionRepository = App::make('UserQuestionRepository', [$user]);
		$userQuestion = $userQuestionRepository->find(Input::get('id'));

		// Update number or good od bad answer depending on passed parameter
		$userQuestion->updateAnswers($isAnswerCorrect);

		return $this->successResponse();
	}

}
