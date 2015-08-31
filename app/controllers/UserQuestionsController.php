<?php

/**
 * jTable base user questions web interface
 * 
 * Allows user to manage question/answer pairs
 */
class UserQuestionsController extends JtableController {

	/**
	 * @var UserQuestionRepository 
	 */
	private $repository;

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
		$skip = Input::get('jtStartIndex');
		$take = Input::get('jtPageSize');
		$collection = $this->repository->collection($take, $skip, $orderByField, $orderBySort);

		/*
		 * Convert to format expected by jTable
		 */
		$records = [];
		foreach ($collection as $row)
		{
			$records[] = [
				'id' => $row->id,
				'question' => $row->question,
				'answer' => $row->answer,
				'percent_of_good_answers' => $row->percent_of_good_answers
			];
		}

		return $this->successReponse([
				'Records' => $records,
				// count total number of rows
				'TotalRecordCount' => $this->repository->count()
		]);
	}

	/**
	 * jTable deleteAction ajax handler
	 * Deletes Questions, and UserQuestion with foreign key
	 */
	public function deleteAction()
	{
		// will return object only if belongs to currently logged in user
		$userQuestion = $this->repository->find(Input::get('id'));

		if (!$userQuestion)
		{
			return $this->errorResponse("Not found");
		}

		// foreing key deletes all UserQuestions when question is deleted
		$userQuestion->question->delete();

		return $this->successReponse();
	}

	/**
	 * jTable updateAction ajax handler 
	 */
	public function updateAction()
	{
		// will return object only if belongs to currently logged in user
		$userQuestion = $this->repository->find(Input::get('id'));

		if (!$userQuestion)
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
		$userQuestion = $this->repository->create(Input::get('question'), Input::get('answer'));

		return $this->successReponse(['Record' => [
					'id' => $userQuestion->id,
					'percent_of_good_answers' => $userQuestion->percent_of_good_answers,
					'question' => Input::get('question'),
					'answer' => Input::get('answer')
		]]);
	}

	/**
	 * Export user questions as a CSV file
	 */
	public function export()
	{
		// check total number of user questions to export
		$count = $this->repository->count();

		if (!$count)
		{
			// display info if nothing to export
			$this->viewData['info'] = Lang::get('messages.nothing_to_export');
			return View::make('info_page', $this->viewData);
		}

		// build csv file
		$csvBuilder = App::make('CsvBuilder');
		$csvBuilder->setData($this->repository->collection($count));
		$csvBuilder->setHeaderField('question', 'question');
		$csvBuilder->setHeaderField('answer', 'answer');
		$csvBuilder->setHeaderField('number_of_good_answers', 'number_of_good_answers');
		$csvBuilder->setHeaderField('number_of_bad_answers', 'number_of_bad_answers');
		$csvBuilder->setHeaderField('percent_of_good_answers', 'percent_of_good_answers');
		$csvBuilder->build();

		// download file
		return Response::download($csvBuilder->getPath(), 'export.csv', [
				'Content-Type' => 'text/csv'
		]);
	}

}
