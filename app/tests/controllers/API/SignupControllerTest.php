<?php

class API_SignupControllerTest extends TestCase {

	use ApiTestHelper;

	/**
	 * @test
	 */
	public function shouldSignupUser()
	{
		$data = [
			'email' => $this->randomEmailAddress(),
			'password' => uniqid(),
			'client_name' => uniqid()
		];

		//user object
		$user = new User();
		$user->id = uniqid();

		// api session object
		$apiSession = new ApiSession();
		$apiSession->auth_token = uniqid();

		// mock ApiSessionRepository
		$apiSessionRepositoryMock = $this->getMock('ApiSessionRepository', ['create']);
		$apiSessionRepositoryMock->expects($this->once())->method('create')->with($user->id, $data['client_name'], '127.0.0.1')->willReturn($apiSession);
		$this->app->instance('ApiSessionRepository', $apiSessionRepositoryMock);

		// mock UserRepository
		$userRepositoryMock = $this->getMock('UserRepository', ['create']);
		$userRepositoryMock->expects($this->once())->method('create')->with($data['email'], $data['password'])->willReturn($user);
		App::instance('UserRepository', $userRepositoryMock);

		// mock validator (so it doesn't access database to check if email was already used)
		$validatorMock = $this->
			getMockBuilder('\Illuminate\Validation\Validator')->
			setMethods(['fails'])->
			disableOriginalConstructor()->
			getMock();
		// fails() should return false
		$validatorMock->method('fails')->willReturn(false);
		Validator::shouldReceive('make')->once()->andReturn($validatorMock);

		// call route
		$this->route('POST', 'api_signup', $data);

		// verify response
		$this->assertSuccessApiResponse(['auth_token' => $apiSession->auth_token]);
	}

	/**
	 * @test
	 */
	public function shouldNotSignupUserWithInvalidCredentials()
	{
		$this->setExpectedException('ApiException');

		// mock validator
		$validatorMock = $this->
			getMockBuilder('\Illuminate\Validation\Validator')->
			setMethods(['fails', 'getMessageBag'])->
			disableOriginalConstructor()->
			getMock();
		// fails() should return true
		$validatorMock->method('fails')->willReturn(true);
		Validator::shouldReceive('make')->once()->andReturn($validatorMock);

		// call route
		$this->route('POST', 'api_signup');
	}

}
