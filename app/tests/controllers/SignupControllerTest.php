<?php

class SignupControllerTest extends TestCase {

	use ControllerTestHelper;

	/**
	 * @test
	 */
	public function shouldDisplaySignupForm()
	{
		View::shouldReceive('make')->with('user.signup')->once();

		$this->route('GET', 'signup');

		$this->assertResponseOk();
	}

	/**
	 * @test
	 */
	public function shouldSignupUser()
	{
		$user = new User();
		$email = uniqid();
		$password = uniqid();
		$authToken = uniqid();
		$webRequestData = [
			'email' => $email,
			'password' => $password,
		];
		$apiRequestData = [
			'email' => $email,
			'password' => $password,
			'client_name' => 'Web',
		];

		// mock validator (so it doesn't access database to check if email was already used)
		$validatorMock = $this->
			getMockBuilder('\Illuminate\Validation\Validator')->
			setMethods(['fails'])->
			disableOriginalConstructor()->
			getMock();
		// fails() should return false
		$validatorMock->method('fails')->willReturn(false);
		Validator::shouldReceive('make')->once()->andReturn($validatorMock);

		// mock API dispatcher
		$this->mockApiDispatcher('api_signup', $this->createSuccessApiResponse(['auth_token' => $authToken]), $apiRequestData);

		// mock ApiSessionRepository
		$apiSessionRepositoryMock = $this->getMock('ApiSessionRepository');
		$apiSessionRepositoryMock->
			expects($this->once())->
			method('user')->
			with($authToken)->
			willReturn($user);
		$this->app->instance('ApiSessionRepository', $apiSessionRepositoryMock);

		// mock auth facade
		Auth::shouldReceive('login')->once()->with($user, true)->andReturn(true);

		// call route
		$this->route('POST', 'signup', $webRequestData);

		// check session
		$this->assertSessionHas('api_auth_token', $authToken);

		// check redirection to overview
		$this->assertRedirectedToRoute('overview');
	}

	/**
	 * @test
	 */
	public function shouldNotSignupUserWithInvalidCredentials()
	{
		// mock validator
		$validatorMock = $this->
			getMockBuilder('\Illuminate\Validation\Validator')->
			setMethods(['fails', 'getMessageBag'])->
			disableOriginalConstructor()->
			getMock();
		// fails() should return true
		$validatorMock->method('fails')->willReturn(true);
		// mock error messages
		$validatorMock->method('getMessageBag')->willReturnCallback(function () {
			return new Illuminate\Support\MessageBag(['foo']);
		});
		Validator::shouldReceive('make')->once()->andReturn($validatorMock);

		$this->route('POST', 'signup');

		$this->assertViewHas('errors');
		$this->assertFalse(Auth::check());
	}

	/**
	 * @test
	 */
	public function shouldThrowAnExceptionOnUnexpectedApiResponse()
	{
		// expect exception to be thrown
		$this->setExpectedException('Exception', 'Unexpected API response');

		// mock validator (so it doesn't access database to check if email was already used)
		$validatorMock = $this->
			getMockBuilder('\Illuminate\Validation\Validator')->
			setMethods(['fails'])->
			disableOriginalConstructor()->
			getMock();
		// fails() should return false
		$validatorMock->method('fails')->willReturn(false);
		Validator::shouldReceive('make')->once()->andReturn($validatorMock);

		// mock API call
		$this->mockApiDispatcher('api_signup', $this->createUnexpectedApiResponse(), [
			'email' => null,
			'password' => null,
			'client_name' => 'Web',
		]);

		// call route
		$this->route('POST', 'signup');
	}

}
