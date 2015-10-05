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

				return Response::apiSuccess([
						'records' => $collection,
						'count' => $userQuestionRepository->count()
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
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function create()
	{
		return $this->apiOutput(function(User $user) {

				$question = Input::get('question');
				$answer = Input::get('answer');

				// Create user question via repository
				$userQuestionRepository = App::make('UserQuestionRepository', [$user]);
				$userQuestion = $userQuestionRepository->create($question, $answer);

				return Response::apiSuccess([
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
					return Response::apiError();
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

				return Response::apiSuccess();
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
					return Response::apiError();
				}

				/*
				 * Delete user question
				 * Foreing key deletes all UserQuestions when question is deleted
				 */
				$userQuestion->question->delete();

				return Response::apiSuccess();
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
					return Response::apiError();
				}

				return Response::apiSuccess([
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

		return Response::apiSuccess();
	}

}
