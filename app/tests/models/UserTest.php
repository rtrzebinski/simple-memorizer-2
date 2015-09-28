<?php

class UserTest extends TestCase {

	use DatabaseTestHelper;

	/**
	 * @test
	 */
	public function shouldDefineUserQuestionsRelation()
	{
		$userQuestion = $this->createUserQuestion();

		$res = $userQuestion->user->userQuestions;

		$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $res);
		$this->assertEquals(1, $res->count());
		$this->assertEquals($userQuestion->id, $res[0]->id);
	}

	/**
	 * @test
	 */
	public function shouldDefineApiSessionsRelation()
	{
		$apiSession = $this->createApiSession();

		$res = $apiSession->user->apiSessions;

		$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $res);
		$this->assertEquals(1, $res->count());
		$this->assertEquals($apiSession->id, $res[0]->id);
	}

	/**
	 * @test
	 * Can't test really much here, so just create one userQuestion and check if it's returned
	 */
	public function shouldReturnRandomUserQuestion()
	{
		// create one user question
		$userQuestion = new UserQuestion();

		// create user, and relate created user question
		$user = new User();
		$userQuestionsCollection = new \Illuminate\Database\Eloquent\Collection([$userQuestion]);
		$user->setRelation('userQuestions', $userQuestionsCollection);

		$this->assertEquals($userQuestion, $user->randomUserQuestion());
	}

}
