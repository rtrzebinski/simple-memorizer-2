<?php

/**
 * Internal API requests dispatcher
 * 
 * Use to call API routes
 */
class ApiDispatcher {

	/**
	 * Call an internal API route
	 * 
	 * @param string $routeName
	 * @param array $httpRequestPatameters
	 * @param string $httpMethod
	 * @return ApiResponse
	 */
	public function callApiRoute($routeName, array $httpRequestPatameters = [], $httpMethod = 'POST')
	{
		// backup original input
		$originalInput = Request::input();
		Request::replace($httpRequestPatameters);

		// create and dispatch the request
		$request = Request::create(route($routeName), $httpMethod);
		$jsonResponse = Route::dispatch($request);

		// create api response from json response
		$apiResponse = App::make('ApiResponse')->createFromJsonResponse($jsonResponse);

		// restore original input
		Request::replace($originalInput);

		// return api response
		return $apiResponse;
	}

}
