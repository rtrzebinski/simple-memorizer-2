<?php

/**
 * Learning page web interface
 */
class LearningPageController extends BaseController {

	/**
	 * @var UserQuestionRepository
	 */
	private $repository;

	public function __construct(UserQuestionRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * HTTP GET request handler
	 * @param User_Question $userQuestion
	 * @param bool $displayAnswer
	 */
	public function index($userQuestion = null, $displayAnswer = false)
	{
		if (!$userQuestion)
		{
			// obtain random user question if not passed as argument
			$userQuestion = $this->repository->randomUserQuestion();

			if (!$userQuestion)
			{
				// display info if user has no questions
				$this->viewData['info'] = Lang::get('messages.no_questions', ['url' => route('questions')]);
				return View::make('info_page', $this->viewData);
			}
		}
		// display learning interface
		$this->viewData['user_question_id'] = $userQuestion->id;
		$this->viewData['display_answer'] = $displayAnswer;
		$this->viewData['question'] = $userQuestion->question->question;
		$this->viewData['answer'] = $userQuestion->question->answer;
		return View::make('learning_page', $this->viewData);
	}

	/**
	 * HTTP POST request handler
	 */
	public function update()
	{
		// instantiate user question
		$userQuestion = $this->repository->find(Input::get('user_question_id'));

		// increase number of good or bad answers, and 
		if (Input::has('answer_correctness'))
		{
			$userQuestion->updateAnswers(Input::get('answer_correctness') == 'I know');
		}

		// update question and/or answer
		if (Input::has('update'))
		{
			$userQuestion->question->question = Input::get('question');
			$userQuestion->question->answer = Input::get('answer');
			$userQuestion->question->save();
			// display updated fields
			return $this->index($userQuestion, Input::get('display_answer'));
		}

		// display next user question
		return Redirect::route('learning_page');
	}

}
