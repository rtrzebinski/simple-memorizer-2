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
	 * @param \ApiResponse $apiResponse
	 * @param array $requestData
	 */
	protected function mockApiDispatcher($routeName, ApiResponse $apiResponse, array $requestData = [])
	{
		// mock ApiDispatcher
		$apiDispatcherMock = $this->getMock('ApiDispatcher');
		$apiDispatcherMock->
			expects($this->once())->
			method('callApiRoute')->
			with($routeName, $requestData)->
			willReturn($apiResponse);
		$this->app->instance('ApiDispatcher', $apiDispatcherMock);
	}

	/**
	 * Create success API response
	 * @param array $data
	 * @return \ApiResponse
	 */
	protected function createSuccessApiResponse(array $data = [])
	{
		$apiResponse = new ApiResponse();
		$apiResponse->createSuccessResponse($data);
		return $apiResponse;
	}

	/**
	 * Create error API response
	 * @param string $error
	 * @return \ApiResponse
	 */
	protected function createErrorApiResponse($error)
	{
		$apiResponse = new ApiResponse();
		$apiResponse->createErrorResponse($error);
		return $apiResponse;
	}

	/**
	 * Create unexpected API response
	 * @return \ApiResponse
	 */
	protected function createUnexpectedApiResponse()
	{
		$apiResponse = new ApiResponse();
		$apiResponse->setSuccess(false);
		$apiResponse->setErrorCode(uniqid());
		$apiResponse->setErrorMessage(uniqid());
		return $apiResponse;
	}

}
