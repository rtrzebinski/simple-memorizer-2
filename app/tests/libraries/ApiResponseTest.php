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

}
