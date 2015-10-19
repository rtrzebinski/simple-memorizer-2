<?php

class LogoutControllerTest extends TestCase {

	use ControllerTestHelper;

	/**
	 * @test
	 */
	public function shouldLogoutUser()
	{
		$authToken = uniqid();

		// mock API dispatcher
		$this->mockApiDispatcher('api_logout', $this->createSuccessApiResponse(), [
			'auth_token' => $authToken
		]);

		// call route
		$this->route('GET', 'logout', [
			'auth_token' => $authToken
		]);

		// check redirection
		$this->assertRedirectedToRoute('landing_page');
	}

	/**
	 * @test
	 */
	public function shouldThrowAnExceptionOnUnexpectedApiResponse()
	{
		// expect exception to be thrown
		$this->setExpectedException('Exception', 'Unexpected API response');

		// mock API call
		$this->mockApiDispatcher('api_logout', $this->createUnexpectedApiResponse(), [
			'auth_token' => null
		]);

		// call route
		$this->route('GET', 'logout');
	}

}
