<?php

class UserQuestionRepository {

	/**
	 * Find user question
	 * @param int $id
	 * @return User_Question
	 */
	public function find($id)
	{
		return App::make('User_Question')->
				where('id', $id)->
				first();
	}

	/**
	 * Create user question
	 * @param string $question
	 * @param string $answer
	 * @param int $userId
	 * @return User_Question
	 */
	public function create($question, $answer, $userId)
	{
		$oQuestion = App::make('Question');
		$oQuestion->question = $question;
		$oQuestion->answer = $answer;
		$oQuestion->save();
		$userQuestion = App::make('User_Question');
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
		return User_Question::with('question')->
				where('user_id', '=', $userId)->
				skip($skip)->
				take($take)->
				orderBy($orderByField, $orderBySort)->
				get();
	}

}
