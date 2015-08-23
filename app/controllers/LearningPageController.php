<?php

/**
 * Learning page web interface
 */
class LearningPageController extends BaseController {

	/**
	 * @var UserQuestionRepository
	 */
	private $repository;

	public function __construct()
	{
		// create user question repository
		$this->repository = App::make('UserQuestionRepository', [Auth::user()->id]);
	}

	/**
	 * Display learning interface with random question
	 */
	public function index()
	{
		$userQuestion = $this->repository->randomUserQuestion();
		$this->viewData['question'] = $userQuestion->question->question;
		$this->viewData['answer'] = $userQuestion->question->answer;
		return View::make('learning_page', $this->viewData);
	}

	/**
	 * Increase number of good or bad answers, and display next question
	 */
	public function answer()
	{
		$userQuestion = $this->repository->find(Input::get('user_question_id'));
		$userQuestion->increaseNumberOfAnswers(Input::get('is_answer_correct'));
		return $this->index();
	}

}
