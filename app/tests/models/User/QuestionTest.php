<?php

class QuestionTest extends TestCase {

	public function answersProvider()
	{
		return [
			[0, 0, 0],
			[1, 19, 5],
			[1, 9, 10],
			[3, 17, 15],
			[2, 8, 20],
			[5, 15, 25],
			[3, 7, 30],
			[7, 13, 35],
			[4, 6, 40],
			[9, 11, 45],
			[5, 5, 50],
			[11, 9, 55],
			[6, 4, 60],
			[13, 7, 65],
			[7, 3, 70],
			[15, 5, 75],
			[8, 2, 80],
			[17, 3, 85],
			[9, 1, 90],
			[19, 1, 95],
			[10, 0, 100],
		];
	}

	/**
	 * @dataProvider answersProvider
	 */
	public function testPercentOfGoodAnswers($goodAnswers, $badAnswers, $percentOfGoodAnswers)
	{
		$userQuestion = new User_Question();
		$userQuestion->number_of_good_answers = $goodAnswers;
		$userQuestion->number_of_bad_answers = $badAnswers;
		$this->assertEquals($percentOfGoodAnswers, $userQuestion->calculatePercentOfGoodAnswers());
	}

	public function testUserRelation()
	{
		$userQuestion = $this->createUserQuestion();
		$this->assertInstanceOf('User', $userQuestion->user);
	}

	public function testQuestionRelation()
	{
		$userQuestion = $this->createUserQuestion();
		$this->assertInstanceOf('Question', $userQuestion->question);
	}

	public function testIncreaseNumberOfGoodAnswers()
	{
		$userQuestion = $this->createUserQuestion();
		$userQuestion->increaseNumberOfGoodAnswers();
		$this->assertEquals(1, $userQuestion->number_of_good_answers);
		$this->assertEquals(100, $userQuestion->percent_of_good_answers);
		$this->refresh($userQuestion);
		$this->assertEquals(1, $userQuestion->number_of_good_answers);
		$this->assertEquals(100, $userQuestion->percent_of_good_answers);
	}

	public function testIncreaseNumberOfBadAnswers()
	{
		$userQuestion = $this->createUserQuestion();
		$userQuestion->number_of_good_answers = 1;
		$userQuestion->save();
		$userQuestion->increaseNumberOfBadAnswers();
		$this->assertEquals(1, $userQuestion->number_of_bad_answers);
		$this->assertEquals(50, $userQuestion->percent_of_good_answers);
		$this->refresh($userQuestion);
		$this->assertEquals(1, $userQuestion->number_of_bad_answers);
		$this->assertEquals(50, $userQuestion->percent_of_good_answers);
	}

}
