<?php

class LearningPageControllerTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldDisplayRandomQuestion()
	{
		$this->be(new User());

		// create question
		$question = new Question();
		$question->question = uniqid();
		$question->answer = uniqid();

		// create user question
		$userQuestion = new UserQuestion();
		$userQuestion->setRelation('question', $question);

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
		$this->assertViewHas('question', $question->question);
		$this->assertViewHas('answer', $question->answer);
	}

	/**
	 * @test
	 */
	public function shouldIncreaseNumberOfAnswers()
	{
		$this->be(new User());

		// create question
		$question = new Question();
		$question->question = uniqid();
		$question->answer = uniqid();

		// mock user question
		$userQuestionMock = $this->
			getMockBuilder('UserQuestion')->
			setMethods(['increaseNumberOfAnswers'])->
			getMock();
		$userQuestionMock->expects($this->once())->method('increaseNumberOfAnswers')->with(true);
		$userQuestionMock->id = uniqid();
		$userQuestionMock->setRelation('question', $question);
		$this->app->instance('UserQuestion', $userQuestionMock);

		// mock repository
		$repositoryMock = $this->
			getMockBuilder('UserQuestionRepository')->
			setMethods(['find', 'randomUserQuestion'])->
			disableOriginalConstructor()->
			getMock();
		$repositoryMock->
			expects($this->once())->
			method('find')->
			with($userQuestionMock->id)->
			willReturn($userQuestionMock);
		$repositoryMock->
			expects($this->once())->
			method('randomUserQuestion')->
			willReturn($userQuestionMock);
		$this->app->instance('UserQuestionRepository', $repositoryMock);

		// call route
		$this->route('POST', 'learning_page', [
			'user_question_id' => $userQuestionMock->id,
			'is_answer_correct' => true
		]);
	}

}
