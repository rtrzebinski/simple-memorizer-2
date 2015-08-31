<?php

class LearningPageControllerTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldDisplayRandomQuestion()
	{
		// create question
		$question = new Question();
		$question->question = uniqid();
		$question->answer = uniqid();

		// create user question
		$userQuestion = new UserQuestion();
		$userQuestion->setRelation('question', $question);
		$userQuestion->id = uniqid();

		// mock repository
		$repositoryMock = $this->
			getMockBuilder('UserQuestionRepository')->
			setMethods(['randomUserQuestion'])->
			disableOriginalConstructor()->
			getMock();
		$repositoryMock->
			expects($this->once())->
			method('randomUserQuestion')->
			willReturn($userQuestion);
		$this->app->instance('UserQuestionRepository', $repositoryMock);

		// call route and check view data
		$this->route('GET', 'learning_page');
		$this->assertViewHas('user_question_id', $userQuestion->id);
		$this->assertViewHas('display_answer', false);
		$this->assertViewHas('question', $question->question);
		$this->assertViewHas('answer', $question->answer);
	}

	/**
	 * @test
	 */
	public function shouldDisplayInfoIfUserHasNoQuestions()
	{
		// mock UserQuestionRepository::randomUserQuestion() to return null
		$repositoryMock = $this->
			getMockBuilder('UserQuestionRepository')->
			setMethods(['randomUserQuestion'])->
			disableOriginalConstructor()->
			getMock();
		$repositoryMock->
			expects($this->once())->
			method('randomUserQuestion')->
			willReturn(null);
		$this->app->instance('UserQuestionRepository', $repositoryMock);

		// expect 'info_page' view, with 'info' variable, to be displayed
		View::shouldReceive('make')->with('info_page', [
			'info' => Lang::get('messages.no_questions', ['url' => route('questions')])
		])->once();

		// call route and check view data
		$this->route('GET', 'learning_page');
	}

	public function shouldUpdateNumberOfAnswersProvider()
	{
		return [
			[true, "I know"],
			[false, "I don't know"]
		];
	}

	/**
	 * @test
	 * @dataProvider shouldUpdateNumberOfAnswersProvider
	 */
	public function shouldUpdateNumberOfAnswers($updateAnswersParameter, $answerCorrectness)
	{
		// create question
		$question = new Question();
		$question->question = uniqid();
		$question->answer = uniqid();

		// mock user question
		$userQuestionMock = $this->
			getMockBuilder('UserQuestion')->
			setMethods(['updateAnswers'])->
			getMock();
		$userQuestionMock->expects($this->once())->method('updateAnswers')->with($updateAnswersParameter);
		$userQuestionMock->id = uniqid();
		$userQuestionMock->setRelation('question', $question);
		$this->app->instance('UserQuestion', $userQuestionMock);

		// mock repository
		$repositoryMock = $this->
			getMockBuilder('UserQuestionRepository')->
			setMethods(['find'])->
			disableOriginalConstructor()->
			getMock();
		$repositoryMock->
			expects($this->once())->
			method('find')->
			with($userQuestionMock->id)->
			willReturn($userQuestionMock);
		$this->app->instance('UserQuestionRepository', $repositoryMock);

		// call route
		$this->route('POST', 'learning_page', [
			'user_question_id' => $userQuestionMock->id,
			'answer_correctness' => $answerCorrectness
		]);

		$this->assertRedirectedToRoute('learning_page');
	}

	/**
	 * @test
	 */
	public function shouldUpdateQuestionAndAnswer()
	{
		$newQuestion = uniqid();
		$newAnswer = uniqid();

		// mock question
		$question = $this->getMock('Question', [
			'setAttribute',
			'save'
		]);
		call_user_func_array([$question->expects($this->exactly(2))->method('setAttribute'), 'withConsecutive'], [
			['question', $newQuestion],
			['answer', $newAnswer]
		]);
		$question->expects($this->once())->method('save');
		App::instance('Question', $question);

		// create user question and set question relation
		$userQuestion = new UserQuestion();
		$userQuestion->id = uniqid();
		$userQuestion->setRelation('question', $question);

		// mock user question repositury
		$repositoryMock = $this->
			getMockBuilder('UserQuestionRepository')->
			setMethods(['find'])->
			disableOriginalConstructor()->
			getMock();
		$repositoryMock->
			expects($this->once())->
			method('find')->
			with($userQuestion->id)->
			willReturn($userQuestion);
		$this->app->instance('UserQuestionRepository', $repositoryMock);

		// call route
		$this->route('POST', 'learning_page', [
			'user_question_id' => $userQuestion->id,
			'update' => 'Update question and answer',
			'question' => $newQuestion,
			'answer' => $newAnswer,
			'display_answer' => true
		]);

		// check view data
		$this->assertViewHas('user_question_id', $userQuestion->id);
		$this->assertViewHas('display_answer', true);
	}

}
