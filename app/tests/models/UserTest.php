<?php

class UserTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldReturnUserQuestionsCollection()
	{
		$userQuestion = $this->createUserQuestion();

		$res = $userQuestion->user->userQuestions;

		$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $res);
		$this->assertEquals(1, $res->count());
		$this->assertEquals($userQuestion->id, $res[0]->id);
	}

}
