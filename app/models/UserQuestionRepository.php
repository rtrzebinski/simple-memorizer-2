<?php

/**
 * UserQuestion repository
 */
class UserQuestionRepository {

	/**
	 * @var User
	 */
	private $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Find user question
	 * @param int $id Id of user question
	 * @return UserQuestion
	 */
	public function find($id)
	{
		return App::make('UserQuestion')->
				where('id', $id)->
				where('user_id', $this->user->id)->
				first();
	}

	/**
	 * Create user question
	 * @param string $question
	 * @param string $answer
	 * @param int $numberOfGoodAnswers
	 * @param int $numberOfBadAnswers
	 * @param int $percentOfGoodAnswers
	 * @return UserQuestion
	 */
	public function create($question, $answer, $numberOfGoodAnswers = 0, $numberOfBadAnswers = 0, $percentOfGoodAnswers = 0)
	{
		$userQuestion = App::make('UserQuestion')->
			select('user_questions.*')->
			join('questions', 'questions.id', '=', 'user_questions.question_id')->
			where('questions.question', '=', $question)->
			where('questions.answer', '=', $answer)->
			where('user_id', '=', $this->user->id)->
			first();

		if ($userQuestion)
		{
			// this user question already exists, update number of answers
			$userQuestion->number_of_good_answers = $numberOfGoodAnswers;
			$userQuestion->number_of_bad_answers = $numberOfBadAnswers;
			$userQuestion->percent_of_good_answers = $percentOfGoodAnswers;
			$userQuestion->save();
		}
		else
		{
			// create new user question
			$oQuestion = App::make('Question');
			$oQuestion->question = $question;
			$oQuestion->answer = $answer;
			$oQuestion->save();
			$userQuestion = App::make('UserQuestion');
			$userQuestion->user_id = $this->user->id;
			$userQuestion->question_id = $oQuestion->id;
			$userQuestion->number_of_good_answers = $numberOfGoodAnswers;
			$userQuestion->number_of_bad_answers = $numberOfBadAnswers;
			$userQuestion->percent_of_good_answers = $percentOfGoodAnswers;
			$userQuestion->save();
		}

		return $userQuestion;
	}

	/**
	 * Collection of user questions
	 * @param int $take Number of taken elements
	 * @param int $skip Number of skipped elements
	 * @param string $orderByField Field by which elements are sorted
	 * @param string $orderBySort Sort order (ASC|DESC)
	 * @return array Array of stdClass objects
	 */
	public function collection($take, $skip = 0, $orderByField = 'id', $orderBySort = 'ASC')
	{
		return DB::table('user_questions')->
				select([
					'user_questions.id as id',
					'user_questions.percent_of_good_answers as percent_of_good_answers',
					'user_questions.number_of_good_answers as number_of_good_answers',
					'user_questions.number_of_bad_answers as number_of_bad_answers',
					'questions.question as question',
					'questions.answer as answer'
				])->
				join('questions', 'questions.id', '=', 'user_questions.question_id')->
				where('user_questions.user_id', '=', $this->user->id)->
				skip($skip)->
				take($take)->
				orderBy($orderByField, $orderBySort)->
				get();
	}

	/**
	 * Return random user question
	 * 
	 * UserQuestionsRandomizer is used to return less known questions
	 * more often that better known
	 * 
	 * @return UserQuestion
	 */
	public function randomUserQuestion()
	{
		// instantiate randomizer
		$randomizer = new UserQuestionsRandomizer($this->user);

		// return random user question
		return $randomizer->randomUserQuestion();
	}

	/**
	 * Count user questions
	 * @return int
	 */
	public function count()
	{
		return $this->user->userQuestions()->count();
	}

}
