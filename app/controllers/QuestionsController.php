<?php

class QuestionsController extends BaseController {

	public function index()
	{
		$this->viewData['user'] = Auth::user();
		return View::make('questions', $this->viewData);
	}

	public function listAction()
	{
		list($orderByField, $orderBySort) = explode(' ', Input::get('jtSorting'));
		$data = DB::table('user_questions')->
			select([
				'user_questions.id as id',
				'questions.question as question',
				'questions.answer as answer',
				'percent_of_good_answers'
			])->
			join('questions', 'questions.id', '=', 'user_questions.question_id')->
			where('user_questions.user_id', '=', Auth::user()->id)->
			skip(Input::get('jtStartIndex'))->
			take(Input::get('jtPageSize'))->
			orderBy($orderByField, $orderBySort)->
			get();

		$records = [];
		foreach ($data as $datum)
		{
			$records[] = [
				'id' => $datum->id,
				'question' => $datum->question,
				'answer' => $datum->answer,
				'percent_of_good_answers' => $datum->percent_of_good_answers
			];
		}

		return json_encode([
			'Result' => "OK",
			'Records' => $records
		]);
	}

	public function deleteAction()
	{
		$userQuestion = App::make('User_question')->where('user_id', Auth::user()->id)->where('question_id', Input::get('id'))->first();

		if (!$userQuestion)
		{
			return json_encode([
				'Result' => "ERROR",
				'Message' => "Not found"
			]);
		}

		// foreing key deletes all user_questions when question is deleted
		$userQuestion->question->delete();

		return json_encode([
			'Result' => "OK"
		]);
	}

	public function updateAction()
	{
		$userQuestion = App::make('User_question')->where('user_id', Auth::user()->id)->where('question_id', Input::get('id'))->first();

		if (!$userQuestion)
		{
			return json_encode([
				'Result' => "ERROR",
				'Message' => "Not found"
			]);
		}

		$userQuestion->question->question = Input::get('question');
		$userQuestion->question->answer = Input::get('answer');
		$userQuestion->question->save();

		return json_encode([
			'Result' => "OK"
		]);
	}

	public function createAction()
	{
		$question = App::make('Question');
		$question->question = Input::get('question');
		$question->answer = Input::get('answer');
		$question->save();
		$userQuestion = App::make('User_Question');
		$userQuestion->user_id = Auth::user()->id;
		$userQuestion->question_id = $question->id;
		$userQuestion->save();

		return json_encode([
			'Result' => "OK",
			'Record' => [
				'id' => $userQuestion->id,
				'question' => $question->question,
				'answer' => $question->answer,
				'percent_of_good_answers' => $userQuestion->percent_of_good_answers
			]
		]);
	}

}
