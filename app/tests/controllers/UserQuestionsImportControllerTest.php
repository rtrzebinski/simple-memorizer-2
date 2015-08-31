<?php

class UserQuestionsImportControllerTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldImportUserQuestionsWithNumberOfAnswers()
	{
		// data to build csv file
		$data = [];
		$row = new stdClass();
		$row->question = uniqid();
		$row->answer = uniqid();
		$row->number_of_good_answers = uniqid();
		$row->number_of_bad_answers = uniqid();
		$row->percent_of_good_answers = uniqid();
		$data[] = $row;

		// create file to be uploaded
		$file = new Symfony\Component\HttpFoundation\File\UploadedFile($this->getUploadedCsvFilePath($data), 'csv_file', 'text/csv');

		// mock user questions repository
		$repository = $this->getMockBuilder('UserQuestionRepository')->
			disableOriginalConstructor()->
			setMethods(['create'])->
			getMock();
		$repository->expects($this->once())->method('create')->with(
			$row->question, $row->answer, $row->number_of_good_answers, $row->number_of_bad_answers, $row->percent_of_good_answers
		);
		App::instance('UserQuestionRepository', $repository);

		// call route
		$this->route('POST', 'questions_import', [], [], ['csv_file' => $file]);

		// check redirection to questions interface
		$this->assertRedirectedToRoute('questions');
	}

	/**
	 * @test
	 */
	public function shouldImportUserQuestionsWithoutNumberOfAnswers()
	{
		// data to build csv file
		$data = [];
		$row = new stdClass();
		$row->question = uniqid();
		$row->answer = uniqid();
		$row->number_of_good_answers = uniqid();
		$row->number_of_bad_answers = uniqid();
		$row->percent_of_good_answers = uniqid();
		$data[] = $row;

		// create file to be uploaded
		$file = new Symfony\Component\HttpFoundation\File\UploadedFile($this->getUploadedCsvFilePath($data), 'csv_file', 'text/csv');

		// mock user questions repository
		$repository = $this->getMockBuilder('UserQuestionRepository')->
			disableOriginalConstructor()->
			setMethods(['create'])->
			getMock();
		$repository->expects($this->once())->method('create')->with($row->question, $row->answer);
		App::instance('UserQuestionRepository', $repository);

		// call route
		$this->route('POST', 'questions_import', ['reset_number_of_answers' => 1], [], ['csv_file' => $file]);

		// check redirection to questions interface
		$this->assertRedirectedToRoute('questions');
	}

	/**
	 * @test
	 */
	public function shouldNotImportUserQuestionsIfUploadedFileIsInvalid()
	{
		// data to build csv file
		$data = [];
		$row = new stdClass();
		$row->question = uniqid();
		$row->answer = uniqid();
		$row->number_of_good_answers = uniqid();
		$row->number_of_bad_answers = uniqid();
		$row->percent_of_good_answers = uniqid();
		$data[] = $row;

		// create file to be uploaded
		$file = new Symfony\Component\HttpFoundation\File\UploadedFile($this->getUploadedCsvFilePath($data), 'csv_file');

		// mock user questions repository
		$repository = $this->getMockBuilder('UserQuestionRepository')->
			disableOriginalConstructor()->
			getMock();
		App::instance('UserQuestionRepository', $repository);

		$messageBag = new Illuminate\Support\MessageBag([Lang::get('messages.import_file_not_valid')]);
		View::shouldReceive('make')->once()->with('user_questions_import', [
			'errors' => $messageBag
		]);

		// call route
		$this->route('POST', 'questions_import', [], [], ['csv_file' => $file]);
	}

	private function getUploadedCsvFilePath($data)
	{
		// build csv file
		$csvBuilder = new CsvBuilder();
		$csvBuilder->setData($data);
		$csvBuilder->setHeaderField('question', 'question');
		$csvBuilder->setHeaderField('answer', 'answer');
		$csvBuilder->setHeaderField('number_of_good_answers', 'number_of_good_answers');
		$csvBuilder->setHeaderField('number_of_bad_answers', 'number_of_bad_answers');
		$csvBuilder->setHeaderField('percent_of_good_answers', 'percent_of_good_answers');
		$csvBuilder->build();

		return $csvBuilder->getPath();
	}

}
