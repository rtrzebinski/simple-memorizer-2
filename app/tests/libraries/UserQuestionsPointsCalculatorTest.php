<?php

class UserQuestionsPointsCalculatorTest extends TestCase {

	public function answersProvider()
	{
		return [
			[0, 10],
			[5, 10],
			[15, 9],
			[20, 9],
			[25, 8],
			[30, 8],
			[35, 7],
			[40, 7],
			[45, 6],
			[50, 6],
			[55, 5],
			[60, 5],
			[65, 4],
			[70, 4],
			[75, 3],
			[80, 3],
			[85, 2],
			[90, 2],
			[95, 1],
			[100, 1]
		];
	}

	/**
	 * @test
	 * Test calculation of knowledge points
	 * @dataProvider answersProvider
	 */
	public function shouldConvertPercentOfGoodAnswersToPoints($percentOfGoodAnswers, $points)
	{
		// create user question
		$userQuestion = new UserQuestion();
		$userQuestion->percent_of_good_answers = $percentOfGoodAnswers;

		// instantiate calculator
		$calculator = App::make('UserQuestionsPointsCalculator');

		// check number of points
		$this->assertEquals($points, $calculator->calculateNumberOfPoints($userQuestion));
	}

}
