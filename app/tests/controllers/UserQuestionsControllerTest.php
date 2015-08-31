<?php

class UserQuestionsControllerTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldDisplayIndexPage()
	{
		$this->be(new User());
		App::instance('UserQuestionRepository', $this->createRepositoryMock());
		$this->route('GET', 'questions');
		$this->assertResponseOk();
	}

	/**
	 * @test
	 */
	public function shouldReturnUserQuestionsList()
	{
		// fake data
		$count = uniqid();
		$row = new stdClass();
		$row->id = uniqid();
		$row->question = uniqid();
		$row->answer = uniqid();
		$row->percent_of_good_answers = uniqid();

		// repository parameters
		$take = uniqid();
		$skip = uniqid();
		$orderByField = uniqid();
		$orderBySort = uniqid();

		// create repository mock
		$repository = $this->createRepositoryMock([
			'collection',
			'count'
		]);

		// mock collection() method
		$repository->
			expects($this->once())->
			method('collection')->
			with($take, $skip, $orderByField, $orderBySort)->
			willReturn([$row]);

		// mock count() method
		$repository->
			expects($this->once())->
			method('count')->
			willReturn($count);

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $repository);

		// call route
		$responseContent = $this->route('POST', 'list_questions', [
				'jtSorting' => $orderByField . ' ' . $orderBySort,
				'jtStartIndex' => $skip,
				'jtPageSize' => $take
			])->getContent();

		// check http response
		$data = json_decode($responseContent);
		$this->assertEquals('OK', $data->Result);
		$this->assertEquals($row->id, $data->Records[0]->id);
		$this->assertEquals($row->question, $data->Records[0]->question);
		$this->assertEquals($row->answer, $data->Records[0]->answer);
		$this->assertEquals($row->percent_of_good_answers, $data->Records[0]->percent_of_good_answers);
		$this->assertEquals($count, $data->TotalRecordCount);
	}

	/**
	 * @test
	 */
	public function shouldDeleteUserQuestion()
	{
		// mock question, expect delete() to be called on it
		$question = $this->getMock('Question', ['delete']);
		$question->expects($this->once())->method('delete');

		$userQuestion = new UserQuestion();
		$userQuestion->setRelation('question', $question);

		// create user question repository mock
		$repository = $this->createRepositoryMock(['find']);

		// mock find() method
		$repository->
			expects($this->once())->
			method('find')->
			with(1)->
			willReturn($userQuestion);

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $repository);

		// call route
		$responseContent = $this->route('POST', 'delete_questions', [
				'id' => 1
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
		// new question is anser - to be updated
		$newQuestion = uniqid();
		$newAnswer = uniqid();

		// mock question (question object is updated by controller)
		$questionMock = $this->getMock('Question', [
			'setAttribute',
			'save'
		]);
		call_user_func_array([$questionMock->expects($this->exactly(2))->method('setAttribute'), 'withConsecutive'], [
			['question', $newQuestion],
			['answer', $newAnswer]
		]);
		$questionMock->expects($this->once())->method('save');

		// create user question and set question mock as related question
		$userQuestion = new UserQuestion();
		$userQuestion->setRelation('question', $questionMock);

		// prepare route input
		$input = [
			'id' => $userQuestion->id,
			'question' => $newQuestion,
			'answer' => $newAnswer
		];

		// create user question repository mock
		$repository = $this->createRepositoryMock(['find']);

		// mock find() method
		$repository->
			expects($this->once())->
			method('find')->
			with($userQuestion->id)->
			willReturn($userQuestion);

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $repository);

		// call route
		$responseContent = $this->route('POST', 'update_questions', $input)->getContent();

		// check http response
		$data = json_decode($responseContent);
		$this->assertEquals('OK', $data->Result);
	}

	/**
	 * @test
	 */
	public function shouldCreateUserQuestion()
	{
		$question = uniqid();
		$answer = uniqid();

		// create user question repository mock
		$repository = $this->createRepositoryMock(['create']);

		// mock create() method
		$repository->
			expects($this->once())->
			method('create')->
			with($question, $answer)->
			willReturnCallback(function() {
				$userQuestion = new UserQuestion();
				$userQuestion->id = 1;
				$userQuestion->percent_of_good_answers = 0;
				return $userQuestion;
			});

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $repository);

		// call route
		$responseContent = $this->route('POST', 'create_questions', [
				'question' => $question,
				'answer' => $answer
			])->getContent();

		// check http response
		$data = json_decode($responseContent);
		$this->assertEquals('OK', $data->Result);
		$this->assertEquals(1, $data->Record->id);
		$this->assertEquals(0, $data->Record->percent_of_good_answers);
		$this->assertEquals($question, $data->Record->question);
		$this->assertEquals($answer, $data->Record->answer);
	}

	private function createRepositoryMock($method = [])
	{
		return $this->getMockBuilder('UserQuestionRepository')->
				setMethods($method)->
				disableOriginalConstructor()->
				getMock();
	}

	/**
	 * @test
	 */
	public function shouldExportUserQuestionsAsCsvFile()
	{
		$collection = uniqid();

		// mock repository to return fake $collection
		$repository = $this->getMockBuilder('UserQuestionRepository')->
			setMethods(['count', 'collection'])->
			disableOriginalConstructor()->
			getMock();
		$repository->method('count')->willReturn(1);
		$repository->method('collection')->willReturn($collection);
		App::instance('UserQuestionRepository', $repository);

		// mock CsvBuilder
		$builder = $this->
			getMockBuilder('CsvBuilder')->
			setMethods([
				'setData',
				'setHeaderField',
				'build'
			])->
			getMock();
		$builder->expects($this->once())->method('setData')->with($collection);
		call_user_func_array([$builder->expects($this->exactly(5))->method('setHeaderField'), 'withConsecutive'], [
			['question', 'question'],
			['answer', 'answer'],
			['number_of_good_answers', 'number_of_good_answers'],
			['number_of_bad_answers', 'number_of_bad_answers'],
			['percent_of_good_answers', 'percent_of_good_answers']
		]);
		$builder->expects($this->once())->method('build');
		$this->app->instance('CsvBuilder', $builder);

		// call route
		$this->route('GET', 'questions_export');
		$this->assertResponseOk();
	}

	/**
	 * @test
	 */
	public function shouldNotExportUserQuestionsIfUserHasNoQuestions()
	{
		$collection = uniqid();

		// mock repository to return fake $collection
		$repository = $this->getMockBuilder('UserQuestionRepository')->
			setMethods(['count'])->
			disableOriginalConstructor()->
			getMock();
		$repository->method('count')->willReturn(0);
		App::instance('UserQuestionRepository', $repository);

		// expect view to display info
		View::shouldReceive('make')->with('info_page', [
			'info' => Lang::get('messages.nothing_to_export')
		]);

		// call route
		$this->route('GET', 'questions_export');
		$this->assertResponseOk();
	}

}
