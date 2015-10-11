<?php

/**
 * Internal API response 
 */
class ApiResponse {

	/**
	 * @var bool
	 */
	private $success;

	/**
	 * @var stdClass
	 */
	private $data;

	/**
	 * Create from JSON response
	 * @param \Illuminate\Http\JsonResponse $jsonResponse
	 * @return \ApiResponse
	 */
	public function createFromJsonResponse(\Illuminate\Http\JsonResponse $jsonResponse)
	{
		$response = json_decode($jsonResponse->getContent());
		$this->success = $response->success;
		if (isset($response->data))
		{
			$this->data = $response->data;
		}
		return $this;
	}

	/**
	 * Get success
	 * @return bool
	 */
	public function getSuccess()
	{
		return $this->success;
	}

	/**
	 * Get data
	 * @return stdClass
	 */
	public function getData()
	{
		return $this->data;
	}

}
