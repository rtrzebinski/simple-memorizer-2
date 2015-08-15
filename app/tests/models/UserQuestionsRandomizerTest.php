<?php

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
	 * Tests percent of good answers to points convertion
	 * @dataProvider answersProvider
	 */
	public function testGetPoints($goodAnswers, $badAnswers, $points)
	{
		$userQuestion = new UserQuestion();
		$userQuestion->number_of_good_answers = $goodAnswers;
		$userQuestion->number_of_bad_answers = $badAnswers;
		$randomizer = new UserQuestionsRandomizer();

		$class = new ReflectionClass($randomizer);
		$pointsReflectionMethod = $class->getMethod('getPoints');
		$pointsReflectionMethod->setAccessible(true);
		$this->assertEquals($points, $pointsReflectionMethod->invokeArgs($randomizer, [$userQuestion]));
	}

	/**
	 * Check if method returns array with correct number of elements
	 */
	public function testGetQuestionsArray()
	{
		$randomizer = new UserQuestionsRandomizer();
		// this user question will have 10 points (no answers)
		$userQuestion = new UserQuestion();
		$userQuestion->number_of_good_answers = 0;
		$userQuestion->number_of_bad_answers = 0;
		$randomizer->addUserQuestion($userQuestion);
		// this user question will have 10 points (100% bad answers)
		$userQuestion = new UserQuestion();
		$userQuestion->number_of_good_answers = 0;
		$userQuestion->number_of_bad_answers = 1;
		$randomizer->addUserQuestion($userQuestion);
		// this user question will have 1 point (100% good answers)
		$userQuestion = new UserQuestion();
		$userQuestion->number_of_good_answers = 1;
		$userQuestion->number_of_bad_answers = 0;
		$randomizer->addUserQuestion($userQuestion);

		$class = new ReflectionClass($randomizer);
		$pointsReflectionMethod = $class->getMethod('getQuestionsArray');
		$pointsReflectionMethod->setAccessible(true);
		$this->assertEquals(21, count($pointsReflectionMethod->invokeArgs($randomizer, [$userQuestion])));
	}

	/**
	 * Can't test really much here, so just add one userQuestion and check if it's question is being returned
	 */
	public function testGetRandomQuestion()
	{
		$userQuestion = $this->createUserQuestion();
		$randomizer = new UserQuestionsRandomizer();
		$randomizer->addUserQuestion($userQuestion);
		$this->assertEquals($userQuestion->question, $randomizer->getRandomQuestion());
	}

}
