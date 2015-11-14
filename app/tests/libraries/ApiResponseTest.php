<?php

class ApiResponseTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldCreateApiResponseFromJsonResponse()
	{
		$data = [
			'success' => true,
			'data' => [
				'foo' => 'bar'
			]
		];
		$jsonResponse = new \Illuminate\Http\JsonResponse($data);

		// create api response from json response
		$apiResponse = new ApiResponse();
		$apiResponse->createFromJsonResponse($jsonResponse);

		// ensure api response returns correct data
		$this->assertTrue($apiResponse->getSuccess());
		$this->assertEquals('bar', $apiResponse->getData()['foo']);
	}

	/**
	 * @test
	 */
	public function shouldConvertSuccessResponseToJsonResponse()
	{
		$data = ['foo' => 'bar'];
		$response = new ApiResponse();
		$response->setSuccess(true);
		$response->setData($data);

		$jsonResponse = $response->toJsonResponse();
		$this->assertInstanceOf('Illuminate\Http\JsonResponse', $jsonResponse);
		$this->assertTrue($jsonResponse->getData(true)['success']);
		$this->assertEquals('bar', $jsonResponse->getData(true)['data']['foo']);
	}

	/**
	 * @test
	 */
	public function shouldConvertErrorResponseToJsonResponse()
	{
		$errorMessage = uniqid();
		$errorCode = uniqid();
		$response = new ApiResponse();
		$response->setSuccess(false);
		$response->setErrorCode($errorCode);
		$response->setErrorMessage($errorMessage);

		$jsonResponse = $response->toJsonResponse();
		$this->assertInstanceOf('Illuminate\Http\JsonResponse', $jsonResponse);
		$this->assertFalse($jsonResponse->getData(true)['success']);
		$this->assertEquals($errorMessage, $jsonResponse->getData(true)['error_message']);
		$this->assertEquals($errorCode, $jsonResponse->getData(true)['error_code']);
	}

	/**
	 * @test
	 */
	public function shouldCreateSuccessResponse()
	{
		$data = ['foo' => 'bar'];
		$response = new ApiResponse();
		$response->createSuccessResponse($data);

		$this->assertTrue($response->getSuccess());
		$this->assertEquals('bar', $response->foo);
	}

	/**
	 * @test
	 */
	public function shouldCreateErrorResponse()
	{
		$error = 'unable_to_login';
		$response = new ApiResponse();
		$response->createErrorResponse($error);

		$this->assertFalse($response->getSuccess());
		$this->assertEquals(Config::get("api.$error.error_code"), $response->getErrorCode());
		$this->assertEquals(Config::get("api.$error.error_message"), $response->getErrorMessage());
	}

	/**
	 * @test
	 */
	public function shouldStoreSuccessFlag()
	{
		$response = new ApiResponse();
		$response->setSuccess(true);
		$this->assertTrue($response->getSuccess());
	}

	/**
	 * @test
	 */
	public function shouldStoreData()
	{
		$data = ['foo' => 'bar'];
		$response = new ApiResponse();
		$response->setData($data);
		$this->assertEquals($data, $response->getData());
		// check that data fields are accessible with __get()
		$this->assertEquals('bar', $response->foo);
	}

	/**
	 * @test
	 */
	public function shouldStoreErrorMessage()
	{
		$errorMessage = uniqid();
		$response = new ApiResponse();
		$response->setErrorMessage($errorMessage);
		$this->assertEquals($errorMessage, $response->getErrorMessage());
	}

	/**
	 * @test
	 */
	public function shouldStoreErrorCode()
	{
		$errorCode = uniqid();
		$response = new ApiResponse();
		$response->setErrorCode($errorCode);
		$this->assertEquals($errorCode, $response->getErrorCode());
	}

}
