<?php

/**
 * Creators of various models instances
 * 
 * Each method creates real database data, so db state is also tested
 * 
 * Should be used in models tests, should not be used in controllers tests
 * where models interaction should be mocked
 */
trait DatabaseTestHelper {

	/**
	 * Create User instance
	 * @return User
	 */
	protected function createUser()
	{
		$user = App::make('User');
		$user->email = $this->randomEmailAddress();
		$user->password = Hash::make($user->email);
		$user->name = 'test';
		$user->save();
		return $user;
	}

	/**
	 * Create Question instance
	 * @return Question
	 */
	protected function createQuestion()
	{
		$question = App::make('Question');
		$question->question = uniqid();
		$question->answer = uniqid();
		$question->save();
		return $question;
	}

	/**
	 * Create UserQuestion instance
	 * @param int $userId
	 * @param int $questionId
	 * @return UserQuestion
	 */
	protected function createUserQuestion($userId = null, $questionId = null)
	{
		$userQuestion = App::make('UserQuestion');
		$userQuestion->user_id = $userId ? : $this->createUser()->id;
		$userQuestion->question_id = $questionId ? : $this->createQuestion()->id;
		$userQuestion->percent_of_good_answers = 0;
		$userQuestion->save();
		return $userQuestion;
	}

	/**
	 * Create ApiSession instance
	 * @param int $userId
	 * @return \ApiSession
	 */
	protected function createApiSession($userId = null)
	{
		$apiSession = App::make('ApiSession');
		$apiSession->user_id = $userId ? : $this->createUser()->id;
		$apiSession->auth_token = uniqid();
		$apiSession->client_ip = uniqid();
		$apiSession->client_name = uniqid();
		$apiSession->save();
		return $apiSession;
	}

}
