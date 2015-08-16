<?php

/**
 * UserQuestion repository
 */
class UserQuestionRepository {

	/**
	 * Find user question
	 * @param int $id
	 * @return UserQuestion
	 */
	public function find($id)
	{
		return App::make('UserQuestion')->
				where('id', $id)->
				first();
	}

	/**
	 * Create user question
	 * @param string $question
	 * @param string $answer
	 * @param int $userId
	 * @return UserQuestion
	 */
	public function create($question, $answer, $userId)
	{
		$oQuestion = App::make('Question');
		$oQuestion->question = $question;
		$oQuestion->answer = $answer;
		$oQuestion->save();
		$userQuestion = App::make('UserQuestion');
		$userQuestion->user_id = $userId;
		$userQuestion->question_id = $oQuestion->id;
		$userQuestion->save();
		return $userQuestion;
	}

	/**
	 * List user questions
	 * @param int $userId
	 * @param int $take
	 * @param int $skip
	 * @param string $orderByField
	 * @param string $orderBySort
	 * @return array
	 */
	public function collection($userId, $take, $skip = 0, $orderByField = 'id', $orderBySort = 'ASC')
	{
		return UserQuestion::with('question')->
				where('user_id', '=', $userId)->
				skip($skip)->
				take($take)->
				orderBy($orderByField, $orderBySort)->
				get();
	}

}
