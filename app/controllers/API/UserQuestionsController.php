<?php

/**
 * REST API user questions controller
 * 
 * Allows user to manage question/answer pairs
 */
class API_UserQuestionsController extends API_BaseController {

	/**
	 * @var UserQuestionRepository 
	 */
	private $userQuestionRepository;

	/**
	 * @param ApiSessionRepository $apiSessionRepository
	 * @param UserQuestionRepository $userQuestionRepository
	 */
	public function __construct(ApiSessionRepository $apiSessionRepository, UserQuestionRepository $userQuestionRepository)
	{
		parent::__construct($apiSessionRepository);
		$this->userQuestionRepository = $userQuestionRepository;
	}

	/**
	 * Collection of user questions
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function collection()
	{
		$take = Input::get('take');
		$skip = Input::get('skip');
		$orderByField = Input::get('order_by_field');
		$orderBySort = Input::get('order_by_sort');

		$collection = $this->userQuestionRepository->collection($take, $skip, $orderByField, $orderBySort);

		return Response::apiSuccess([
				'records' => $collection,
				'count' => $this->userQuestionRepository->count()
		]);
	}

	/**
	 * Create new user question
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function create()
	{
		$question = Input::get('question');
		$answer = Input::get('answer');

		$userQuestion = $this->userQuestionRepository->create($question, $answer);

		return Response::apiSuccess([
				'user_question_id' => $userQuestion->id
		]);
	}

	/**
	 * Update existing user question
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function update()
	{
		$userQuestion = $this->userQuestionRepository->find(Input::get('id'));

		if (!$userQuestion)
		{
			return Response::apiError();
		}

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
	}

	/**
	 * Delete existing user question
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function delete()
	{
		$userQuestion = $this->userQuestionRepository->find(Input::get('id'));

		if (!$userQuestion)
		{
			return Response::apiError();
		}

		// foreing key deletes all UserQuestions when question is deleted
		$userQuestion->question->delete();

		return Response::apiSuccess();
	}

	/**
	 * Return random user question
	 * 
	 * Return less known questions more often that better known
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function random()
	{
		$userQuestion = $this->userQuestionRepository->randomUserQuestion();

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
	}

	/**
	 * Add good answer to user question
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function addGoodAnswer()
	{
		return $this->addAnswer(true);
	}

	/**
	 * Add bad answer to user question
	 * 
	 * @return Illuminate\Http\JsonResponse
	 */
	public function addBadAnswer()
	{
		return $this->addAnswer(false);
	}

	private function addAnswer($isAnswerCorrect)
	{
		$userQuestion = $this->userQuestionRepository->find(Input::get('id'));

		$userQuestion->updateAnswers($isAnswerCorrect);

		return Response::apiSuccess();
	}

}
