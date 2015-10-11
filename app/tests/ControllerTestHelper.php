<?php

/**
 * Controller test helper
 * 
 * Common helper methods for controllers unit tests
 */
trait ControllerTestHelper {

	/**
	 * Mock API dispatcher
	 * @param string $routeName
	 * @param array $requestData
	 * @param array $responseData
	 */
	protected function mockApiDispatcher($routeName, array $requestData = [], array $responseData = [])
	{
		// create json response
		$jsonResponse = new \Illuminate\Http\JsonResponse($responseData);

		// create api response
		$apiResponse = App::make('ApiResponse')->createFromJsonResponse($jsonResponse);

		// mock ApiDispatcher
		$apiDispatcherMock = $this->getMock('ApiDispatcher');
		$apiDispatcherMock->
			expects($this->once())->
			method('callApiRoute')->
			with($routeName, $requestData)->
			willReturn($apiResponse);
		$this->app->instance('ApiDispatcher', $apiDispatcherMock);
	}

}
