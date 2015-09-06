<?php

/**
 * Convert percent of good answers to knowledge points
 * 
 * Point is an integer value between 1 and 10, it determines if user is familiar with the answer
 */
class UserQuestionsPointsCalculator {

	/**
	 * Calculate number of points
	 * 
	 * 1 means highest familiarity with the answer.
	 * 10 means lowest familiarity with the answer.
	 * 
	 * @return int Number of points
	 * @throws Exception
	 */
	public function calculateNumberOfPoints(UserQuestion $userQuestion)
	{
		$percentOfGoodAnswers = $userQuestion->percent_of_good_answers;

		if ($percentOfGoodAnswers <= 100 && $percentOfGoodAnswers > 90)
		{
			return 1;
		}
		else if ($percentOfGoodAnswers <= 90 && $percentOfGoodAnswers > 80)
		{
			return 2;
		}
		else if ($percentOfGoodAnswers <= 80 && $percentOfGoodAnswers > 70)
		{
			return 3;
		}
		else if ($percentOfGoodAnswers <= 70 && $percentOfGoodAnswers > 60)
		{
			return 4;
		}
		else if ($percentOfGoodAnswers <= 60 && $percentOfGoodAnswers > 50)
		{
			return 5;
		}
		else if ($percentOfGoodAnswers <= 50 && $percentOfGoodAnswers > 40)
		{
			return 6;
		}
		else if ($percentOfGoodAnswers <= 40 && $percentOfGoodAnswers > 30)
		{
			return 7;
		}
		else if ($percentOfGoodAnswers <= 30 && $percentOfGoodAnswers > 20)
		{
			return 8;
		}
		else if ($percentOfGoodAnswers <= 20 && $percentOfGoodAnswers > 10)
		{
			return 9;
		}
		else if ($percentOfGoodAnswers <= 10)
		{
			return 10;
		}

		throw new Exception('Percent of good answers must be a value between 0 and 100');
	}

}
