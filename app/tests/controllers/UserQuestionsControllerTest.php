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
		// fake count
		$count = uniqid();

		// create user question
		$question = new Question();
		$question->question = uniqid();
		$question->answer = uniqid();
		$userQuestion = new UserQuestion();
		$userQuestion->percent_of_good_answers = 0;
		$userQuestion->setRelation('question', $question);

		// create user question repository mock
		$repository = $this->createRepositoryMock([
			'collection',
			'count'
		]);

		// mock collection() method
		$repository->
			expects($this->once())->
			method('collection')->
			with(1, 0, 'id', 'ASC')->
			willReturn([$userQuestion]);

		// mock count() method
		$repository->
			expects($this->once())->
			method('count')->
			willReturn($count);

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $repository);

		// call route
		$responseContent = $this->route('POST', 'list_questions', [
				'jtSorting' => 'id ASC',
				'jtStartIndex' => '0',
				'jtPageSize' => 1
			])->getContent();

		// check http response
		$data = json_decode($responseContent);
		$this->assertEquals('OK', $data->Result);
		$this->assertEquals($userQuestion->id, $data->Records[0]->id);
		$this->assertEquals($question->question, $data->Records[0]->question);
		$this->assertEquals($question->answer, $data->Records[0]->answer);
		$this->assertEquals(0, $data->Records[0]->percent_of_good_answers);
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

}
