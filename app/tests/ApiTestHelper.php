<?php

trait ApiTestHelper {

	/**
	 * Assert success API response
	 * @param array $data
	 */
	protected function assertSuccessApiResponse(array $data = [])
	{
		$actual = $this->client->getResponse()->getContent();
		$expected = Response::JSON([
				'success' => true,
				'data' => $data
			])->getContent();
		$this->assertEquals($expected, $actual);
	}

	/**
	 * Assert error API response
	 */
	protected function assertErrorApiResponse($error)
	{
		$actual = $this->client->getResponse()->getContent();
		$expected = Response::JSON([
				'success' => false,
				'error_message' => Config::get("api.$error.error_message"),
				'error_code' => Config::get("api.$error.error_code"),
			])->getContent();
		$this->assertEquals($expected, $actual);
	}

	/**
	 * Return auth token that should be used with api call
	 * @return string
	 */
	protected function getAuthToken()
	{
		$authToken = uniqid();
		$user = new User();

		$apiSessionRepository = $this->getMock('ApiSessionRepository');
		$apiSessionRepository->
			expects($this->once())->
			method('user')->
			with($authToken)->
			willReturn($user);
		$this->app->instance('ApiSessionRepository', $apiSessionRepository);

		return $authToken;
	}

}
