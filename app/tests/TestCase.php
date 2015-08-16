<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__ . '/../../bootstrap/start.php';
	}

	/**
	 * @return string
	 */
	protected function createRandomEmailAddress()
	{
		return uniqid() . '@blackhole.io';
	}

	/**
	 * @return User
	 */
	protected function createUser()
	{
		$user = App::make('User');
		$user->email = $this->createRandomEmailAddress();
		$user->password = Hash::make($user->email);
		$user->name = 'test';
		$user->save();
		return $user;
	}

	/**
	 * @return Question
	 */
	protected function createQuestion()
	{
		$question = App::make('Question');
		$question->save();
		return $question;
	}

	/**
	 * @param int $userId
	 * @param int $questionId
	 * @return UserQuestion
	 */
	protected function createUserQuestion($userId = null, $questionId = null)
	{
		$userQuestion = App::make('UserQuestion');
		$userQuestion->user_id = $userId ? : $this->createUser()->id;
		$userQuestion->question_id = $questionId ? : $this->createQuestion()->id;
		$userQuestion->save();
		return $userQuestion;
	}

	protected function dumpResponseContent()
	{
		dd($this->client->getResponse()->getContent());
	}

	public function trueFalseProvider()
	{
		return [
			[true],
			[false]
		];
	}

	public function refresh(\Illuminate\Database\Eloquent\Model &$object)
	{
		$object = App::make(get_class($object))->find($object->id);
	}

	/**
	 * Create mock object
	 * @param string $class
	 * @param array $methods
	 * @return object
	 */
	protected function createMock($class, array $methods = [], array $constructorArgs = [])
	{
		return $this->
				getMockBuilder($class)->
				setMethods($methods)->
				setConstructorArgs($constructorArgs)->
				getMock();
	}

}
