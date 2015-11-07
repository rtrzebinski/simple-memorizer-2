<?php

class LearningPageControllerTest extends TestCase {

	use ControllerTestHelper;

	/**
	 * @test
	 */
	public function shouldDisplayRandomQuestion()
	{
		$authToken = uniqid();
		$this->session(['auth_token' => $authToken]);
		$apiResponseData = [
			'id' => uniqid(),
			'question' => uniqid(),
			'answer' => uniqid()
		];

		$this->mockApiDispatcher('api_random_user_question', $this->createSuccessApiResponse($apiResponseData), [
			'auth_token' => $authToken
		]);

		// call route and check view data
		$this->route('GET', 'learning_page');
		$this->assertViewHas('display_answer', false);
		$this->assertViewHas('user_question_id', $apiResponseData['id']);
		$this->assertViewHas('question', $apiResponseData['question']);
		$this->assertViewHas('answer', $apiResponseData['answer']);
	}

	/**
	 * @test
	 */
	public function shouldDisplayInfoIfUserHasNoQuestions()
	{
		$authToken = uniqid();
		$this->session(['auth_token' => $authToken]);

		$this->mockApiDispatcher('api_random_user_question', $this->createErrorApiResponse('user_has_not_created_any_questions_yet'), [
			'auth_token' => $authToken
		]);

		// user_has_not_created_any_questions_yet
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
		$authToken = uniqid();
		$this->session(['auth_token' => $authToken]);
		$userQuestionId = uniqid();

		if ($updateAnswersParameter)
		{
			$this->mockApiDispatcher('api_add_good_answer', $this->createSuccessApiResponse(), [
				'auth_token' => $authToken,
				'id' => $userQuestionId
			]);
		}
		else
		{
			$this->mockApiDispatcher('api_add_bad_answer', $this->createSuccessApiResponse(), [
				'auth_token' => $authToken,
				'id' => $userQuestionId
			]);
		}

		// call route
		$this->route('POST', 'learning_page', [
			'user_question_id' => $userQuestionId,
			'answer_correctness' => $answerCorrectness
		]);

		$this->assertRedirectedToRoute('learning_page');
	}

	/**
	 * @test
	 */
	public function shouldUpdateQuestionAndAnswer()
	{
		$authToken = uniqid();
		$this->session(['auth_token' => $authToken]);
		$userQuestionId = uniqid();
		$newQuestion = uniqid();
		$newAnswer = uniqid();

		// Mock 2 calls of ApiDispatcher::callApiRoute()
		$apiDispatcherMock = $this->getMock('ApiDispatcher');
		$callApiRouteMethodMock = call_user_func_array([$apiDispatcherMock->expects($this->exactly(2))->method('callApiRoute'), 'withConsecutive'], [
			['api_update_user_question', [
					'auth_token' => $authToken,
					'id' => $userQuestionId,
					'question' => $newQuestion,
					'answer' => $newAnswer
				]],
			['api_find_user_question', [
					'auth_token' => $authToken,
					'id' => $userQuestionId,
				]]
		]);
		$callApiRouteMethodMock->will($this->onConsecutiveCalls($this->createSuccessApiResponse(), $this->createSuccessApiResponse([
					'id' => $userQuestionId,
					'question' => $newQuestion,
					'answer' => $newAnswer
		])));
		$this->app->instance('ApiDispatcher', $apiDispatcherMock);

		// call route
		$this->route('POST', 'learning_page', [
			'user_question_id' => $userQuestionId,
			'update' => 'Update question and answer',
			'question' => $newQuestion,
			'answer' => $newAnswer,
			'display_answer' => true
		]);

		// check view data
		$this->assertViewHas('user_question_id', $userQuestionId);
		$this->assertViewHas('display_answer', true);
	}

}
