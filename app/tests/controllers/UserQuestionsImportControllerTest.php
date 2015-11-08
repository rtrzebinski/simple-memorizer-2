<?php

class UserQuestionsImportControllerTest extends TestCase {

	use ControllerTestHelper;

	/**
	 * @test
	 */
	public function shouldImportUserQuestionsWithNumberOfAnswers()
	{
		$userQuestionId = uniqid();
		$question = uniqid();
		$answer = uniqid();
		$numberOfGoodAnswers = uniqid();
		$numberOfBadAnswers = uniqid();
		$percentOfGoodAnswers = uniqid();
		$authToken = uniqid();
		$this->session(['api_auth_token' => $authToken]);

		// data to build csv file
		$row = new stdClass();
		$row->question = $question;
		$row->answer = $answer;
		$row->number_of_good_answers = $numberOfGoodAnswers;
		$row->number_of_bad_answers = $numberOfBadAnswers;
		$row->percent_of_good_answers = $percentOfGoodAnswers;
		$data = [];
		$data[] = $row;

		// create file to be uploaded
		$file = new Symfony\Component\HttpFoundation\File\UploadedFile($this->getUploadedCsvFilePath($data), 'csv_file', 'text/csv');

		// mock API dispatcher
		$apiRequestParameters = [
			'auth_token' => $authToken,
			'question' => $question,
			'answer' => $answer,
			'number_of_good_answers' => $numberOfGoodAnswers,
			'number_of_bad_answers' => $numberOfBadAnswers,
			'percent_of_good_answers' => $percentOfGoodAnswers,
		];
		$apiResponse = $this->createSuccessApiResponse([
			'user_question_id' => $userQuestionId
		]);
		$this->mockApiDispatcher('api_create_user_question', $apiResponse, $apiRequestParameters);

		// call route
		$this->route('POST', 'import_user_questions_from_csv', [], [], ['csv_file' => $file]);

		// check redirection to questions interface
		$this->assertRedirectedToRoute('display_user_questions');
	}

	/**
	 * @test
	 */
	public function shouldImportUserQuestionsWithoutNumberOfAnswers()
	{
		$userQuestionId = uniqid();
		$question = uniqid();
		$answer = uniqid();
		$authToken = uniqid();
		$this->session(['api_auth_token' => $authToken]);

		// data to build csv file
		$row = new stdClass();
		$row->question = $question;
		$row->answer = $answer;
		$row->number_of_good_answers = uniqid();
		$row->number_of_bad_answers = uniqid();
		$row->percent_of_good_answers = uniqid();
		$data[] = $row;

		// create file to be uploaded
		$file = new Symfony\Component\HttpFoundation\File\UploadedFile($this->getUploadedCsvFilePath($data), 'csv_file', 'text/csv');

		// mock API dispatcher
		$apiRequestParameters = [
			'auth_token' => $authToken,
			'question' => $question,
			'answer' => $answer,
		];
		$apiResponse = $this->createSuccessApiResponse([
			'user_question_id' => $userQuestionId
		]);
		$this->mockApiDispatcher('api_create_user_question', $apiResponse, $apiRequestParameters);

		// call route
		$this->route('POST', 'import_user_questions_from_csv', ['reset_number_of_answers' => 1], [], ['csv_file' => $file]);

		// check redirection to questions interface
		$this->assertRedirectedToRoute('display_user_questions');
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
		$this->route('POST', 'import_user_questions_from_csv', [], [], ['csv_file' => $file]);
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
