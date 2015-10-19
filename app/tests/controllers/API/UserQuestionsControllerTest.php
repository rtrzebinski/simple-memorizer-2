<?php

class API_UserQuestionsControllerTest extends TestCase {

	use ApiTestHelper;

	/**
	 * Test collection()
	 * @test
	 */
	public function shouldReturnUserQuestionsCollection()
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
		$userQuestionRepository = $this->createUserQuestionRepositoryMock([
			'collection',
			'count'
		]);

		// mock collection() method
		$userQuestionRepository->
			expects($this->once())->
			method('collection')->
			with($take, $skip, $orderByField, $orderBySort)->
			willReturn([$row]);

		// mock count() method
		$userQuestionRepository->
			expects($this->once())->
			method('count')->
			willReturn($count);

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $userQuestionRepository);

		// call route
		$this->route('POST', 'api_user_questions_collection', [
			'take' => $take,
			'skip' => $skip,
			'order_by_field' => $orderByField,
			'order_by_sort' => $orderBySort,
			'auth_token' => $this->getAuthToken()
		]);

		// check api response
		$this->assertSuccessApiResponse([
			'records' => [[
				'id' => $row->id,
				'question' => $row->question,
				'answer' => $row->answer,
				'percent_of_good_answers' => $row->percent_of_good_answers
				]],
			'count' => $count
		]);
	}

	/**
	 * Test delete()
	 * @test
	 */
	public function shouldDeleteUserQuestion()
	{
		$id = uniqid();

		// mock question, expect delete() to be called on it
		$question = $this->getMock('Question', ['delete']);
		$question->expects($this->once())->method('delete');

		$userQuestion = new UserQuestion();
		$userQuestion->setRelation('question', $question);

		// create user question repository mock
		$userQuestionRepository = $this->createUserQuestionRepositoryMock(['find']);

		// mock find() method
		$userQuestionRepository->
			expects($this->once())->
			method('find')->
			with($id)->
			willReturn($userQuestion);

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $userQuestionRepository);

		// call route
		$this->route('POST', 'api_delete_user_question', [
			'id' => $id,
			'auth_token' => $this->getAuthToken()
		]);

		// checkj api response
		$this->assertSuccessApiResponse();
	}

	/**
	 * Test delete()
	 * @test
	 */
	public function shouldNotDeleteNotExistingUserQuestion()
	{
		// create user question repository mock
		$userQuestionRepository = $this->createUserQuestionRepositoryMock(['find']);

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $userQuestionRepository);

		// call route
		$this->route('POST', 'api_delete_user_question', [
			'id' => uniqid(),
			'auth_token' => $this->getAuthToken()
		]);

		$this->assertErrorApiResponse('user_question_does_not_exist');
	}

	/**
	 * Test update()
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
			'answer' => $newAnswer,
			'auth_token' => $this->getAuthToken()
		];

		// create user question repository mock
		$userQuestionRepository = $this->createUserQuestionRepositoryMock(['find']);

		// mock find() method
		$userQuestionRepository->
			expects($this->once())->
			method('find')->
			with($userQuestion->id)->
			willReturn($userQuestion);

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $userQuestionRepository);

		// call route
		$this->route('POST', 'api_update_user_question', $input);

		// check api response
		$this->assertSuccessApiResponse();
	}

	/**
	 * Test update()
	 * @test
	 */
	public function shouldNotUpdateNotExistingUserQuestion()
	{
		// create user question repository mock
		$userQuestionRepository = $this->createUserQuestionRepositoryMock(['find']);

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $userQuestionRepository);

		// call route
		$this->route('POST', 'api_update_user_question', [
			'id' => uniqid(),
			'auth_token' => $this->getAuthToken()
		]);

		$this->assertErrorApiResponse('user_question_does_not_exist');
	}

	/**
	 * Test create()
	 * @test
	 */
	public function shouldCreateUserQuestion()
	{
		$question = uniqid();
		$answer = uniqid();

		// create user question repository mock
		$repository = $this->createUserQuestionRepositoryMock(['create']);

		// create user question
		$userQuestion = new UserQuestion();
		$userQuestion->id = 1;
		$userQuestion->percent_of_good_answers = 0;

		// mock create() method
		$repository->
			expects($this->once())->
			method('create')->
			with($question, $answer)->
			willReturn($userQuestion);

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $repository);

		// call route
		$this->route('POST', 'api_create_user_question', [
			'question' => $question,
			'answer' => $answer,
			'auth_token' => $this->getAuthToken()
		]);

		// check api response
		$this->assertSuccessApiResponse([
			'user_question_id' => $userQuestion->id
		]);
	}

	/**
	 * Test random()
	 * @test
	 */
	public function shouldReturnRandomUserQuestion()
	{
		$row = new stdClass();
		$row->id = uniqid();
		$row->question = new stdClass();
		$row->question->question = uniqid();
		$row->question->answer = uniqid();
		$row->percent_of_good_answers = uniqid();
		$row->number_of_good_answers = uniqid();
		$row->number_of_bad_answers = uniqid();

		// create repository mock
		$userQuestionRepository = $this->createUserQuestionRepositoryMock([
			'randomUserQuestion',
		]);

		// mock randomUserQuestion() method
		$userQuestionRepository->
			expects($this->once())->
			method('randomUserQuestion')->
			willReturn($row);

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $userQuestionRepository);

		// call route
		$this->route('POST', 'api_random_user_question', [
			'auth_token' => $this->getAuthToken()
		]);

		// check api response
		$this->assertSuccessApiResponse([
			'id' => $row->id,
			'question' => $row->question->question,
			'answer' => $row->question->answer,
			'percent_of_good_answers' => $row->percent_of_good_answers,
			'number_of_good_answers' => $row->number_of_good_answers,
			'number_of_bad_answers' => $row->number_of_bad_answers
		]);
	}

	/**
	 * Test random()
	 * @test
	 */
	public function shouldNotReturnRandomUserQuestionIfUserHasNoQuestion()
	{
		// create repository mock
		$userQuestionRepository = $this->createUserQuestionRepositoryMock([
			'randomUserQuestion',
		]);

		// mock randomUserQuestion() method
		$userQuestionRepository->
			expects($this->once())->
			method('randomUserQuestion')->
			willReturn(null);

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $userQuestionRepository);

		// call route
		$this->route('POST', 'api_random_user_question', [
			'auth_token' => $this->getAuthToken()
		]);

		$this->assertErrorApiResponse('user_has_not_created_any_questions_yet');
	}

	/**
	 * Test find()
	 * @test
	 */
	public function shouldFindUserQuestion()
	{
		$row = new stdClass();
		$row->id = uniqid();
		$row->question = new stdClass();
		$row->question->question = uniqid();
		$row->question->answer = uniqid();
		$row->percent_of_good_answers = uniqid();
		$row->number_of_good_answers = uniqid();
		$row->number_of_bad_answers = uniqid();

		// create repository mock
		$userQuestionRepository = $this->createUserQuestionRepositoryMock([
			'find',
		]);

		// mock find() method
		$userQuestionRepository->
			expects($this->once())->
			method('find')->
			willReturn($row);

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $userQuestionRepository);

		// call route
		$this->route('POST', 'api_find_user_question', [
			'auth_token' => $this->getAuthToken()
		]);

		// check api response
		$this->assertSuccessApiResponse([
			'id' => $row->id,
			'question' => $row->question->question,
			'answer' => $row->question->answer,
			'percent_of_good_answers' => $row->percent_of_good_answers,
			'number_of_good_answers' => $row->number_of_good_answers,
			'number_of_bad_answers' => $row->number_of_bad_answers
		]);
	}

	/**
	 * Test find()
	 * @test
	 */
	public function shouldNotFindNotExistingUserQuestion()
	{
		// create repository mock
		$userQuestionRepository = $this->createUserQuestionRepositoryMock([
			'find',
		]);

		// mock find() method
		$userQuestionRepository->
			expects($this->once())->
			method('find')->
			willReturn(null);

		// bind mock object to UserQuestionRepository
		App::instance('UserQuestionRepository', $userQuestionRepository);

		// call route
		$this->route('POST', 'api_find_user_question', [
			'auth_token' => $this->getAuthToken()
		]);

		$this->assertErrorApiResponse('user_question_does_not_exist');
	}

	/**
	 * Test addGoodAnswer()
	 * @test
	 */
	public function shouldAddGoodAnswer()
	{
		$this->checkAddAnswer('api_add_good_answer', true);
	}

	/**
	 * Test addBadAnswer()
	 * @test
	 */
	public function shouldAddBadAnswer()
	{
		$this->checkAddAnswer('api_add_bad_answer', false);
	}

	/**
	 * All routes which belongs to this controller
	 * @return type
	 */
	public function routesDataProvider()
	{
		return[
			['api_user_questions_collection'],
			['api_random_user_question'],
			['api_create_user_question'],
			['api_update_user_question'],
			['api_delete_user_question'],
			['api_add_good_answer'],
			['api_add_bad_answer'],
		];
	}

	/**
	 * Ensure that auth token is checked by API
	 * @test
	 * @dataProvider routesDataProvider
	 */
	public function shouldReturnErrorIfAuthTokenIsNotCorrect($route)
	{
		$this->route('POST', $route);
		$this->assertErrorApiResponse('bad_auth_token');
	}

	/**
	 * @return UserQuestionRepository
	 */
	private function createUserQuestionRepositoryMock($method = [])
	{
		return $this->getMockBuilder('UserQuestionRepository')->
				setMethods($method)->
				disableOriginalConstructor()->
				getMock();
	}

	/**
	 * Check add answer
	 * @param string $route
	 * @param bool $isAnswerCorrect
	 */
	private function checkAddAnswer($route, $isAnswerCorrect)
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
		$userQuestionMock->expects($this->once())->method('updateAnswers')->with($isAnswerCorrect);
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
		$this->route('POST', $route, [
			'id' => $userQuestionMock->id,
			'auth_token' => $this->getAuthToken()
		]);

		// check api response
		$this->assertSuccessApiResponse();
	}

}
