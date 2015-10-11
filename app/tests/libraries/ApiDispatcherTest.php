<?php

class ApiDispatcherTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldCallApiRoute()
	{
		// mock ApiResponse
		$apiResponseMock = $this->getMock('ApiResponse');
		$apiResponseMock->
			expects($this->once())->
			method('createFromJsonResponse')->
			willReturn($apiResponseMock);
		$this->app->instance('ApiResponse', $apiResponseMock);

		// call api via api dispatcher
		$apiDispatcher = new ApiDispatcher();
		$response = $apiDispatcher->callApiRoute('api_logout', []);

		// check response
		$this->assertEquals($apiResponseMock, $response);
	}

	/**
	 * @test
	 */
	public function shouldBackupAndRestoreOriginalRequestInput()
	{
		// add key - value pair to the request
		Input::merge(['foo' => 'bar']);

		// call api via api dispatcher
		$apiDispatcher = new ApiDispatcher();
		$apiDispatcher->callApiRoute('api_logout');

		// check if request data is the same after API call
		$this->assertEquals('bar', Input::get('foo'));
		$this->assertEquals(['foo' => 'bar'], Input::all());
	}

}
