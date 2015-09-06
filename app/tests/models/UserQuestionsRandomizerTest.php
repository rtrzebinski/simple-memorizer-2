<?php

/**
 * Tested class public interface returns random object, so it's quite hard to test it works correctly
 * Because of that, each of class private methods is tested separately (not only public methods as normally should be done)
 */
class UserQuestionsRandomizerTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldMultiplUserQuestionsByNumberOfPoints()
	{
		// this user question will have 10 points (0% good answers)
		$userQuestionWithNoAnswers = $this->getMock('UserQuestion', ['getPercentOfGoodAnswersAttribute']);
		$userQuestionWithNoAnswers->method('getPercentOfGoodAnswersAttribute')->willReturn(0);
		$userQuestionWithNoAnswers->id = 1;

		// this user question will have 1 point (100% good answers)
		$userQuestionWithGoodAnswersOnly = $this->getMock('UserQuestion', ['getPercentOfGoodAnswersAttribute']);
		$userQuestionWithGoodAnswersOnly->method('getPercentOfGoodAnswersAttribute')->willReturn(100);
		$userQuestionWithGoodAnswersOnly->id = 2;

		// create user, and relate created user questions
		$user = new User();
		$userQuestionsCollection = new \Illuminate\Database\Eloquent\Collection([
			$userQuestionWithNoAnswers,
			$userQuestionWithGoodAnswersOnly
		]);
		$user->setRelation('userQuestions', $userQuestionsCollection);

		$randomizer = App::make('UserQuestionsRandomizer');

		// call private getUserQuestionsArray() method on randomizer
		$class = new ReflectionClass($randomizer);
		$pointsReflectionMethod = $class->getMethod('getUserQuestionsArray');
		$pointsReflectionMethod->setAccessible(true);
		$result = $pointsReflectionMethod->invokeArgs($randomizer, [$user]);
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

		// instantiate randomizer
		$randomizer = App::make('UserQuestionsRandomizer');

		$this->assertEquals($userQuestion, $randomizer->randomUserQuestion($user));
	}

	/**
	 * @test
	 */
	public function shouldReturnNullIsUserHasNoQuestions()
	{
		// create user without questions
		$user = new User();

		// instantiate randomizer
		$randomizer = App::make('UserQuestionsRandomizer');

		// assert null is returned by randmizer
		$this->assertNull($randomizer->randomUserQuestion($user));
	}

}
