<?php

class API_LogoutControllerTest extends TestCase {

	use ApiTestHelper;

	/**
	 * @test
	 */
	public function shouldLogoutUser()
	{
		$authToken = uniqid();

		// mock ApiSessionRepository::delete()
		$apiSessionRepositoryMock = $this->getMock('ApiSessionRepository', ['delete']);
		$apiSessionRepositoryMock->expects($this->once())->method('delete')->with($authToken);
		$this->app->instance('ApiSessionRepository', $apiSessionRepositoryMock);

		// call route
		$this->route('POST', 'api_logout', ['auth_token' => $authToken]);

		// verify response
		$this->assertSuccessApiResponse();
	}

}
