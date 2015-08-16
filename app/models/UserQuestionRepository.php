<?php

/**
 * UserQuestion repository
 */
class UserQuestionRepository {

	/**
	 * Id of user
	 * @var int
	 */
	private $userId;

	public function __construct($userId)
	{
		$this->userId = $userId;
	}

	/**
	 * Find user question
	 * @param int $id Id of user question
	 * @return UserQuestion
	 */
	public function find($id)
	{
		return App::make('UserQuestion')->
				where('id', $id)->
				where('user_id', $this->userId)->
				first();
	}

	/**
	 * Create user question
	 * @param string $question
	 * @param string $answer
	 * @return UserQuestion
	 */
	public function create($question, $answer)
	{
		$oQuestion = App::make('Question');
		$oQuestion->question = $question;
		$oQuestion->answer = $answer;
		$oQuestion->save();
		$userQuestion = App::make('UserQuestion');
		$userQuestion->user_id = $this->userId;
		$userQuestion->question_id = $oQuestion->id;
		$userQuestion->save();
		return $userQuestion;
	}

	/**
	 * List user questions
	 * @param int $take Number of taken elements
	 * @param int $skip Number of skipped elements
	 * @param string $orderByField Field by which elements are sorted
	 * @param string $orderBySort Sort order (ASC|DESC)
	 * @return array
	 */
	public function collection($take, $skip = 0, $orderByField = 'id', $orderBySort = 'ASC')
	{
		return UserQuestion::with('question')->
				where('user_id', '=', $this->userId)->
				skip($skip)->
				take($take)->
				orderBy($orderByField, $orderBySort)->
				get();
	}

}