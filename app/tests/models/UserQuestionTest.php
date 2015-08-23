<?php

class UserQuestionTest extends TestCase {

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
	 * @test
	 * @dataProvider answersProvider
	 */
	public function shouldCalculatePercentOfGoodAnswers($goodAnswers, $badAnswers, $percentOfGoodAnswers)
	{
		$userQuestion = new UserQuestion();
		$userQuestion->number_of_good_answers = $goodAnswers;
		$userQuestion->number_of_bad_answers = $badAnswers;

		// call private percentOfGoodAnswers() on $userQuestion object
		$class = new ReflectionClass('UserQuestion');
		$reflectionMethod = $class->getMethod('calculatePercentOfGoodAnswers');
		$reflectionMethod->setAccessible(true);

		$this->assertEquals($percentOfGoodAnswers, $reflectionMethod->invoke($userQuestion));
	}

	/**
	 * @test
	 */
	public function shouldDefineUserRelation()
	{
		$userQuestion = $this->createUserQuestion();
		$this->assertInstanceOf('User', $userQuestion->user);
	}

	/**
	 * @test
	 */
	public function shouldDefineQuestionRelation()
	{
		$userQuestion = $this->createUserQuestion();
		$this->assertInstanceOf('Question', $userQuestion->question);
	}

	public function increaseNumberOfAnswersProvider()
	{
		return [
			['number_of_good_answers', true, 100],
			['number_of_bad_answers', false, 0],
		];
	}

	/**
	 * @test
	 * @dataProvider increaseNumberOfAnswersProvider
	 * @param string $field Fields to be increased - number of good or bad points
	 * @param bool $parameter Parameter passed to UserQuestion::updateAnswers()
	 * @param int $percentOfGoodAnswers Percent of good answers expected after UserQuestion::updateAnswers() called
	 */
	public function shouldIncreaseNumberOfAnswers($field, $parameter, $percentOfGoodAnswers)
	{
		// create user question
		$userQuestion = $this->createUserQuestion();

		// ensure number of answers is 0 in both object state and db
		$this->assertEquals(0, $userQuestion->{$field});
		$this->assertEquals(0, DB::table('user_questions')->where('id', $userQuestion->id)->first()->{$field});

		// ensure percent of good answers is 0 in both object state and db
		$this->assertEquals(0, $userQuestion->percent_of_good_answers);
		$this->assertEquals(0, DB::table('user_questions')->where('id', $userQuestion->id)->first()->percent_of_good_answers);

		// increase number of answers
		$userQuestion->updateAnswers($parameter);

		// ensure number of answers was updated in both objects and db
		$this->assertEquals(1, $userQuestion->{$field});
		$this->assertEquals(1, DB::table('user_questions')->where('id', $userQuestion->id)->first()->{$field});

		// check percent of good answers was updated in both objects and db
		$this->assertEquals($percentOfGoodAnswers, $userQuestion->percent_of_good_answers);
		$this->assertEquals($percentOfGoodAnswers, DB::table('user_questions')->where('id', $userQuestion->id)->first()->percent_of_good_answers);
	}

}
