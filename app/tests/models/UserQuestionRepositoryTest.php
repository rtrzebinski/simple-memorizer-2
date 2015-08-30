<?php

/**
 * This class tests real DB interactions of UserQuestionRepository
 */
class UserQuestionRepositoryTest extends TestCase {

	use DatabaseTestHelper;

	/**
	 * @test
	 */
	public function shouldFindUserQuestion()
	{
		$user = $this->createUser();
		$userQuestion = $this->createUserQuestion($user->id);

		$repository = new UserQuestionRepository($user);
		$this->assertEquals($userQuestion->id, $repository->find($userQuestion->id)->id);
	}

	/**
	 * @test
	 */
	public function shouldCreateUserQuestion()
	{
		$user = $this->createUser();
		$question = uniqid();
		$answer = uniqid();

		$repository = new UserQuestionRepository($user);
		$userQuestion = $repository->create($question, $answer);

		$this->assertEquals($user->id, $userQuestion->user_id);
		$this->assertEquals($question, $userQuestion->question->question);
		$this->assertEquals($answer, $userQuestion->question->answer);
	}

	/**
	 * @test
	 */
	public function shouldReturnCollection()
	{
		$user = $this->createUser();
		$question = $this->createQuestion();
		$question->save();
		$userQuestion = $this->createUserQuestion($user->id, $question->id);

		$repository = new UserQuestionRepository($user);
		$data = $repository->collection(1);

		$this->assertEquals($userQuestion->id, $data[0]->id);
		$this->assertEquals($question->question, $data[0]->question);
		$this->assertEquals($question->answer, $data[0]->answer);
		$this->assertEquals(0, $data[0]->percent_of_good_answers);
	}

	/**
	 * @test
	 */
	public function shouldReturnRandomQuestion()
	{
		$userQuestion = $this->createUserQuestion();
		$repository = new UserQuestionRepository($userQuestion->user);

		/*
		 * ensure that the only existing question is returned
		 * randomizer is tested separatly, so no need to test randomization here
		 */
		$randomUserQuestion = $repository->randomUserQuestion();
		$this->assertInstanceOf('UserQuestion', $randomUserQuestion);
		$this->assertEquals($userQuestion->id, $randomUserQuestion->id);
	}

	/**
	 * @test
	 */
	public function shouldCountUserQuestions()
	{
		$count = uniqid();

		// mock relation to return predefined count
		$relation = $this->
			getMockBuilder('\Illuminate\Database\Eloquent\Relations\hasMany')->
			setMethods(['count'])->
			disableOriginalConstructor()->
			getMock();
		$relation->method('count')->willReturn($count);

		// mock user to return userQuestions relation
		$user = $this->getMock('User', ['userQuestions']);
		$user->method('userQuestions')->willReturn($relation);

		// assert predefined count is returned by repository
		$repository = new UserQuestionRepository($user);
		$this->assertEquals($count, $repository->count());
	}

}
