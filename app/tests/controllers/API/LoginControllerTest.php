<?php

class API_LoginControllerTest extends TestCase {

	use ApiTestHelper;

	/**
	 * @test
	 */
	public function shouldLoginUser()
	{
		$data = [
			'email' => $this->randomEmailAddress(),
			'password' => uniqid(),
			'client_name' => uniqid()
		];
		$userId = uniqid();

		// api session object
		$apiSession = new ApiSession();
		$apiSession->auth_token = uniqid();

		// mock ApiSessionRepository
		$apiSessionRepositoryMock = $this->getMock('ApiSessionRepository', ['create']);
		$apiSessionRepositoryMock->expects($this->once())->method('create')->with($userId, $data['client_name'], '127.0.0.1')->willReturn($apiSession);
		$this->app->instance('ApiSessionRepository', $apiSessionRepositoryMock);

		// mock auth facade
		Auth::shouldReceive('attempt')->once()->with([
			'email' => $data['email'],
			'password' => $data['password']
		])->andReturn(true);
		Auth::shouldReceive('id')->once()->andReturn($userId);

		// call route
		$this->route('POST', 'api_login', $data);

		// verify response
		$this->assertSuccessApiResponse(['auth_token' => $apiSession->auth_token]);
	}

	/**
	 * @test
	 */
	public function shouldNotLoginUserWithBadCredentials()
	{
		$data = [
			'email' => $this->randomEmailAddress(),
			'password' => uniqid()
		];

		// mock auth facade
		Auth::shouldReceive('attempt')->once()->with([
			'email' => $data['email'],
			'password' => $data['password']
		])->andReturn(false);

		// call route
		$this->route('POST', 'api_login', $data);

		$this->assertErrorApiResponse();
	}

}
