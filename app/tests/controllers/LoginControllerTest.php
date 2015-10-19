<?php

class LoginControllerTest extends TestCase {

	use ControllerTestHelper;

	/**
	 * @test
	 */
	public function shouldDisplayLoginForm()
	{
		View::shouldReceive('make')->with('user.login');

		$this->route('GET', 'login');

		$this->assertResponseOk();
	}

	/**
	 * @test
	 * @dataProvider trueFalseProvider
	 * @param bool $rememberMe
	 */
	public function shouldLoginUser($rememberMe)
	{
		$user = new User();
		$email = uniqid();
		$password = uniqid();
		$authToken = uniqid();
		$webRequestData = [
			'email' => $email,
			'password' => $password,
			'remember_me' => $rememberMe,
		];
		$apiRequestData = [
			'email' => $email,
			'password' => $password,
			'client_name' => 'Web',
		];

		// mock API dispatcher
		$this->mockApiDispatcher('api_login', $this->createSuccessApiResponse(['auth_token' => $authToken]), $apiRequestData);

		// mock ApiSessionRepository
		$apiSessionRepositoryMock = $this->getMock('ApiSessionRepository');
		$apiSessionRepositoryMock->
			expects($this->once())->
			method('user')->
			with($authToken)->
			willReturn($user);
		$this->app->instance('ApiSessionRepository', $apiSessionRepositoryMock);

		// mock auth facade
		Auth::shouldReceive('login')->once()->with($user, $rememberMe)->andReturn(true);

		// call route (include $rememberMe)
		$this->route('POST', 'login', $webRequestData);

		// check session
		$this->assertSessionHas('api_auth_token', $authToken);

		// check redirection
		$this->assertRedirectedToRoute('overview');
	}

	/**
	 * @test
	 */
	public function shouldNotLoginUserWithBadCredentials()
	{
		$email = uniqid();
		$password = uniqid();
		$webRequestData = [
			'email' => $email,
			'password' => $password,
		];
		$apiRequestData = [
			'email' => $email,
			'password' => $password,
			'client_name' => 'Web',
		];

		// mock API call
		$this->mockApiDispatcher('api_login', $this->createErrorApiResponse('unable_to_login'), $apiRequestData);

		// call route
		$this->route('POST', 'login', $webRequestData);

		// check if view has errors
		$this->assertViewHas('errors');
	}

	/**
	 * @test
	 */
	public function shouldThrowAnExceptionOnUnexpectedApiResponse()
	{
		// expect exception to be thrown
		$this->setExpectedException('Exception', 'Unexpected API response');

		// mock API call
		$this->mockApiDispatcher('api_login', $this->createUnexpectedApiResponse(), [
			'email' => null,
			'password' => null,
			'client_name' => 'Web',
		]);

		// call route
		$this->route('POST', 'login');
	}

	public function trueFalseProvider()
	{
		return [
			[true],
			[false]
		];
	}

}
