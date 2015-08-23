<?php

/**
 * Randomizer of user questions
 * 
 * Randomizes questions using ratio of good and bad answers,
 * so questions with more bad answers are returned more often,
 * than questions with more good answers
 */
class UserQuestionsRandomizer {

	private $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Random user question
	 * 
	 * Questions that user knows less have more chance to be returned.
	 * Questions that user knows more have less chance to be returned.
	 * 
	 * @return UserQuestion
	 */
	public function randomUserQuestion()
	{
		$questions = $this->getUserQuestionsArray();
		if (count($questions) > 0)
		{
			// do randomization
			shuffle($questions);
			return $questions[array_rand($questions)];
		}
	}

	/**
	 * Multiply user questions by points
	 * 
	 * @return array
	 */
	private function getUserQuestionsArray()
	{
		// extract user questions from injected user
		$userQuestions = $this->user->userQuestions;

		// return an empty array if user has no questions attached
		if (!$userQuestions)
		{
			return [];
		}

		$return = [];
		foreach ($this->user->userQuestions as $userQuestion)
		{
			for ($i = $this->getPoints($userQuestion); $i > 0; $i--)
			{
				$return[] = $userQuestion;
			}
		}
		return $return;
	}

	/**
	 * Convert percent of good answers to knowledge points
	 * 
	 * Point is an integer value between 1 and 10, it determines if user is familiar with the answer.
	 * 
	 * 1 means highest familiarity with the answer.
	 * 10 means lowest familiarity with the answer.
	 * 
	 * @return int Number of points
	 * @throws Exception
	 */
	private function getPoints(UserQuestion $userQuestion)
	{
		$percentOfGoodAnswers = $userQuestion->calculatePercentOfGoodAnswers();

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
