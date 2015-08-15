<?php

class UserQuestionsController extends JtableController {

	private $repository;

	/**
	 * Inject user question repository
	 * @param UserQuestionRepository $repository
	 */
	public function __construct(UserQuestionRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * Web interface root
	 */
	public function index()
	{
		$this->viewData['user'] = Auth::user();
		return View::make('questions', $this->viewData);
	}

	/**
	 * jTable listAction ajax handler 
	 */
	public function listAction()
	{
		/*
		 * Fetch data
		 */
		list($orderByField, $orderBySort) = explode(' ', Input::get('jtSorting'));
		$userId = Auth::user()->id;
		$skip = Input::get('jtStartIndex');
		$take = Input::get('jtPageSize');
		$collection = $this->repository->collection($userId, $take, $skip, $orderByField, $orderBySort);

		/*
		 * Convert to format expected by jTable
		 */
		$records = [];
		foreach ($collection as $row)
		{
			$records[] = [
				'id' => $row->id,
				'question' => $row->question->question,
				'answer' => $row->question->answer,
				'percent_of_good_answers' => $row->percent_of_good_answers
			];
		}

		return $this->successReponse(['Records' => $records]);
	}

	/**
	 * jTable deleteAction ajax handler
	 * Deletes Questions, and User_Question with foreign key
	 */
	public function deleteAction()
	{
		$userQuestion = $this->repository->find(Input::get('id'));

		if (!$userQuestion || $userQuestion->user_id != Auth::user()->id)
		{
			return $this->errorResponse("Not found");
		}

		// foreing key deletes all user_questions when question is deleted
		$userQuestion->question->delete();

		return $this->successReponse();
	}

	/**
	 * jTable updateAction ajax handler 
	 */
	public function updateAction()
	{
		$userQuestion = $this->repository->find(Input::get('id'));

		if (!$userQuestion || $userQuestion->user_id != Auth::user()->id)
		{
			return $this->errorResponse("Not found");
		}

		$userQuestion->question->question = Input::get('question');
		$userQuestion->question->answer = Input::get('answer');
		$userQuestion->question->save();

		return $this->successReponse();
	}

	/**
	 * jTable createAction ajax handler 
	 */
	public function createAction()
	{
		$userQuestion = $this->repository->create(Input::get('question'), Input::get('answer'), Auth::user()->id);

		return $this->successReponse(['Record' => [
					'id' => $userQuestion->id,
					'percent_of_good_answers' => $userQuestion->percent_of_good_answers,
					'question' => Input::get('question'),
					'answer' => Input::get('answer')
		]]);
	}

}
