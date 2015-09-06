<?php

/**
 * Randomizer of user questions
 * 
 * Randomizes questions using ratio of good and bad answers,
 * so questions with more bad answers are returned more often,
 * than questions with more good answers
 */
class UserQuestionsRandomizer {

	/**
	 * @var UserQuestionsPointsCalculator 
	 */
	private $pointsCalculator;

	/**
	 * @param UserQuestionsPointsCalculator $pointsCalculator
	 */
	public function __construct(UserQuestionsPointsCalculator $pointsCalculator)
	{
		$this->pointsCalculator = $pointsCalculator;
	}

	/**
	 * Random user question
	 * 
	 * Questions that user knows less have more chance to be returned.
	 * Questions that user knows more have less chance to be returned.
	 * 
	 * @param User $user
	 * @return UserQuestion|NULL
	 * NULL will be returned if user has no questions
	 */
	public function randomUserQuestion(User $user)
	{
		$questions = $this->getUserQuestionsArray($user);
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
	 * @param User $user
	 * @return array
	 */
	private function getUserQuestionsArray(User $user)
	{
		// return an empty array if user has no questions attached
		if (!$user->userQuestions)
		{
			return [];
		}

		$return = [];
		foreach ($user->userQuestions as $userQuestion)
		{
			for ($i = $this->pointsCalculator->calculateNumberOfPoints($userQuestion); $i > 0; $i--)
			{
				$return[] = $userQuestion;
			}
		}
		return $return;
	}

}
