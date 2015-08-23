<?php

/**
 * Tested class public interface returns random object, so it's quite hard to test it works correctly
 * Because of that, each of class private methods is tested separately (not only public methods as normally should be done)
 */
class UserQuestionsRandomizerTest extends TestCase {

	public function answersProvider()
	{
		return [
			[1, 19, 10],
			[1, 9, 10],
			[3, 17, 9],
			[2, 8, 9],
			[5, 15, 8],
			[3, 7, 8],
			[7, 13, 7],
			[4, 6, 7],
			[9, 11, 6],
			[5, 5, 6],
			[11, 9, 5],
			[6, 4, 5],
			[13, 7, 4],
			[7, 3, 4],
			[15, 5, 3],
			[8, 2, 3],
			[17, 3, 2],
			[9, 1, 2],
			[19, 1, 1],
			[10, 0, 1],
		];
	}

	/**
	 * Test calculation of knowledge points
	 * @test
	 * @dataProvider answersProvider
	 */
	public function shouldConvertPercentOfGoodAnswersToPoints($goodAnswers, $badAnswers, $points)
	{
		// create user question
		$userQuestion = new UserQuestion();
		$userQuestion->number_of_good_answers = $goodAnswers;
		$userQuestion->number_of_bad_answers = $badAnswers;

		// instantiate randomizer
		$randomizer = App::make('UserQuestionsRandomizer');

		// call private getPoints() on $randomizer object
		$class = new ReflectionClass($randomizer);
		$pointsReflectionMethod = $class->getMethod('getPoints');
		$pointsReflectionMethod->setAccessible(true);
		$this->assertEquals($points, $pointsReflectionMethod->invokeArgs($randomizer, [$userQuestion]));
	}

	/**
	 * @test
	 */
	public function shouldMultiplUserQuestionsByNumberOfPoints()
	{
		// this user question will have 10 points (0% good answers)
		$userQuestionWithNoAnswers = $this->getMock('UserQuestion', ['calculatePercentOfGoodAnswers']);
		$userQuestionWithNoAnswers->method('calculatePercentOfGoodAnswers')->willReturn(0);
		$userQuestionWithNoAnswers->id = 1;

		// this user question will have 1 point (100% good answers)
		$userQuestionWithGoodAnswersOnly = $this->getMock('UserQuestion', ['calculatePercentOfGoodAnswers']);
		$userQuestionWithGoodAnswersOnly->method('calculatePercentOfGoodAnswers')->willReturn(100);
		$userQuestionWithGoodAnswersOnly->id = 2;

		// create user, and relate created user questions
		$user = new User();
		$userQuestionsCollection = new \Illuminate\Database\Eloquent\Collection([
			$userQuestionWithNoAnswers,
			$userQuestionWithGoodAnswersOnly
		]);
		$user->setRelation('userQuestions', $userQuestionsCollection);

		$randomizer = new UserQuestionsRandomizer($user);

		// call private getUserQuestionsArray() method on randomizer
		$class = new ReflectionClass($randomizer);
		$pointsReflectionMethod = $class->getMethod('getUserQuestionsArray');
		$pointsReflectionMethod->setAccessible(true);
		$result = $pointsReflectionMethod->invoke($randomizer);
		$this->assertEquals(11, count($result));

		/*
		 * replace each response value object, with object id
		 * as array_count_values() doesn't work with objects
		 */
		array_walk($result, function(&$value) {
			$value = $value->id;
		});

		// count array values
		$values = array_count_values($result);

		// check if each object was multiplied correct number of times
		$this->assertEquals(10, $values[1]);
		$this->assertEquals(1, $values[2]);
	}

	/**
	 * @test
	 * Can't test really much here, so just add one userQuestion and check if it's question is being returned
	 */
	public function shouldReturnRandomObject()
	{
		// create one user question
		$userQuestion = $this->createUserQuestion();

		// create user, and relate created user question
		$user = new User();
		$userQuestionsCollection = new \Illuminate\Database\Eloquent\Collection([$userQuestion]);
		$user->setRelation('userQuestions', $userQuestionsCollection);

		// instantiate randomizer
		$randomizer = new UserQuestionsRandomizer($user);


		$this->assertEquals($userQuestion, $randomizer->randomUserQuestion());
	}

	/**
	 * @test
	 */
	public function shouldReturnNullIsUserHasNoQuestions()
	{
		// create user without questions
		$user = new User();

		// instantiate randomizer
		$randomizer = new UserQuestionsRandomizer($user);

		// assert null is returned by randmizer
		$this->assertNull($randomizer->randomUserQuestion());
	}

}
