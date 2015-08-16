<?php

/**
 * Randomizer of user questions
 * 
 * Randomizes questions using ratio of good and bad answers,
 * so questions with more bad answers are returned more often,
 * than questions with more good answers
 */
class UserQuestionsRandomizer {

	private $userQuestions;

	/**
	 * Add user question
	 * @param UserQuestion $userQuestion
	 */
	public function addUserQuestion(UserQuestion $userQuestion)
	{
		$this->userQuestions[] = $userQuestion;
	}

	/**
	 * Get a random question, from one of attached userQuestions.
	 * Questions that user knows less have more chance to be returned.
	 * Questions that user knows more have less chance to be returned.
	 * @return Question
	 */
	public function getRandomQuestion()
	{
		$questions = $this->getQuestionsArray();
		if (count($questions) > 0)
		{
			shuffle($questions);
			return $questions[array_rand($questions)];
		}
	}

	/**
	 * Returned array contains questions multiplied by userQuestions points.
	 * @return array
	 */
	private function getQuestionsArray()
	{
		$questions = [];
		foreach ($this->userQuestions as $userQuestion)
		{
			for ($i = $this->getPoints($userQuestion); $i > 0; $i--)
			{
				$questions[] = $userQuestion->question;
			}
		}
		return $questions;
	}

	/**
	 * Point is an integer value between 1 and 10, it determines if user is familiar with the answer.
	 * 1 means highest familiarity with the answer.
	 * 10 means lowest familiarity with the answer.
	 * @return int
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
