<?php

class UserQuestionsControllerTest extends TestCase {

	use ControllerTestHelper;

	/**
	 * @test
	 */
	public function shouldDisplayIndexPage()
	{
		$this->be(new User());
		$this->route('GET', 'display_user_questions');
		$this->assertResponseOk();
	}

	/**
	 * @test
	 */
	public function shouldReturnUserQuestionsList()
	{
		$count = uniqid();
		$row = [
			'id' => uniqid(),
			'question' => uniqid(),
			'answer' => uniqid(),
			'percent_of_good_answers' => uniqid(),
		];
		$take = uniqid();
		$skip = uniqid();
		$orderByField = uniqid();
		$orderBySort = uniqid();
		$authToken = uniqid();
		$this->session(['api_auth_token' => $authToken]);

		// mock API dispatcher
		$apiRequestParameters = [
			'auth_token' => $authToken,
			'take' => $take,
			'skip' => $skip,
			'order_by_field' => $orderByField,
			'order_by_sort' => $orderBySort,
		];
		$apiResponse = $this->createSuccessApiResponse([
			'records' => [$row],
			'count' => $count
		]);
		$this->mockApiDispatcher('api_user_questions_collection', $apiResponse, $apiRequestParameters);

		// call route
		$responseContent = $this->route('POST', 'list_user_questions', [
				'jtSorting' => $orderByField . ' ' . $orderBySort,
				'jtStartIndex' => $skip,
				'jtPageSize' => $take
			])->getContent();

		// check http response
		$data = json_decode($responseContent);
		$this->assertEquals('OK', $data->Result);
		$this->assertEquals($row['id'], $data->Records[0]->id);
		$this->assertEquals($row['question'], $data->Records[0]->question);
		$this->assertEquals($row['answer'], $data->Records[0]->answer);
		$this->assertEquals($row['percent_of_good_answers'], $data->Records[0]->percent_of_good_answers);
		$this->assertEquals($count, $data->TotalRecordCount);
	}

	/**
	 * @test
	 */
	public function shouldDeleteUserQuestion()
	{
		$authToken = uniqid();
		$this->session(['api_auth_token' => $authToken]);
		$userQuestionId = uniqid();

		// mock API dispatcher
		$apiRequestParameters = [
			'auth_token' => $authToken,
			'id' => $userQuestionId,
		];
		$apiResponse = $this->createSuccessApiResponse([]);
		$this->mockApiDispatcher('api_delete_user_question', $apiResponse, $apiRequestParameters);

		// call route
		$responseContent = $this->route('POST', 'delete_user_question', [
				'id' => $userQuestionId
			])->getContent();

		// checkj http response
		$data = json_decode($responseContent);
		$this->assertEquals('OK', $data->Result);
	}

	/**
	 * @test
	 */
	public function shouldUpdateUserQuestion()
	{
		$authToken = uniqid();
		$this->session(['api_auth_token' => $authToken]);
		$userQuestionId = uniqid();
		$question = uniqid();
		$answer = uniqid();

		// mock API dispatcher
		$apiRequestParameters = [
			'auth_token' => $authToken,
			'id' => $userQuestionId,
			'question' => $question,
			'answer' => $answer,
		];
		$apiResponse = $this->createSuccessApiResponse([]);
		$this->mockApiDispatcher('api_update_user_question', $apiResponse, $apiRequestParameters);

		// call route
		$responseContent = $this->route('POST', 'update_user_question', [
				'id' => $userQuestionId,
				'question' => $question,
				'answer' => $answer,
			])->getContent();

		// check http response
		$data = json_decode($responseContent);
		$this->assertEquals('OK', $data->Result);
	}

	/**
	 * @test
	 */
	public function shouldCreateUserQuestion()
	{
		$authToken = uniqid();
		$this->session(['api_auth_token' => $authToken]);
		$userQuestionId = uniqid();
		$question = uniqid();
		$answer = uniqid();

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
		$responseContent = $this->route('POST', 'create_user_question', [
				'question' => $question,
				'answer' => $answer
			])->getContent();

		// check http response
		$data = json_decode($responseContent);
		$this->assertEquals('OK', $data->Result);
		$this->assertEquals($userQuestionId, $data->Record->id);
		$this->assertEquals(0, $data->Record->percent_of_good_answers);
		$this->assertEquals($question, $data->Record->question);
		$this->assertEquals($answer, $data->Record->answer);
	}

	/**
	 * @test
	 */
	public function shouldExportUserQuestionsAsCsvFile()
	{
		$count = uniqid();
		$row = [
			'id' => uniqid(),
			'question' => uniqid(),
			'answer' => uniqid(),
			'percent_of_good_answers' => uniqid(),
			'number_of_good_answers' => uniqid(),
			'number_of_bad_answers' => uniqid(),
		];
		$authToken = uniqid();
		$this->session(['api_auth_token' => $authToken]);

		// mock CsvBuilder
		$builder = $this->
			getMockBuilder('CsvBuilder')->
			setMethods([
				'setData',
				'setHeaderField',
				'build'
			])->
			getMock();
		$builder->expects($this->once())->method('setData')->with([$row]);
		call_user_func_array([$builder->expects($this->exactly(5))->method('setHeaderField'), 'withConsecutive'], [
			['question', 'question'],
			['answer', 'answer'],
			['number_of_good_answers', 'number_of_good_answers'],
			['number_of_bad_answers', 'number_of_bad_answers'],
			['percent_of_good_answers', 'percent_of_good_answers']
		]);
		$builder->expects($this->once())->method('build');
		$this->app->instance('CsvBuilder', $builder);

		// mock API dispatcher
		$apiRequestParameters = [
			'auth_token' => $authToken,
		];
		$apiResponse = $this->createSuccessApiResponse([
			'records' => [$row],
			'count' => $count
		]);
		$this->mockApiDispatcher('api_user_questions_collection', $apiResponse, $apiRequestParameters);

		// call route
		$this->route('GET', 'export_user_questions_to_csv');

		// check response
		$this->assertResponseOk();
	}

	/**
	 * @test
	 */
	public function shouldNotExportUserQuestionsIfUserHasNoQuestions()
	{
		$count = 0;
		$authToken = uniqid();
		$this->session(['api_auth_token' => $authToken]);

		// mock API dispatcher
		$apiRequestParameters = [
			'auth_token' => $authToken,
		];
		$apiResponse = $this->createSuccessApiResponse([
			'records' => [],
			'count' => $count
		]);
		$this->mockApiDispatcher('api_user_questions_collection', $apiResponse, $apiRequestParameters);

		// set expected view
		View::shouldReceive('make')->with('info_page', [
			'info' => Lang::get('messages.nothing_to_export')
		]);

		// call route
		$this->route('GET', 'export_user_questions_to_csv');

		// check response
		$this->assertResponseOk();
	}

}
