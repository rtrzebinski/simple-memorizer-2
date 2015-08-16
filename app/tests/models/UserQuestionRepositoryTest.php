<?php

/**
 * This class tests real DB interactions of UserQuestionRepository
 */
class UserQuestionRepositoryTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldFindUserQuestion()
	{
		$user = $this->createUser();
		$userQuestion = $this->createUserQuestion($user->id);

		$repository = new UserQuestionRepository($user->id);
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

		$repository = new UserQuestionRepository($user->id);
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
		$question->question = uniqid();
		$question->answer = uniqid();
		$question->save();
		$userQuestion = $this->createUserQuestion($user->id, $question->id);

		$repository = new UserQuestionRepository($user->id);
		$data = $repository->collection(1);

		$this->assertEquals($userQuestion->id, $data[0]->id);
		$this->assertEquals($question->question, $data[0]->question->question);
		$this->assertEquals($question->answer, $data[0]->question->answer);
		$this->assertEquals(0, $data[0]->percent_of_good_answers);
	}

}
