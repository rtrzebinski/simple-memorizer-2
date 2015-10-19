<?php

/**
 * API response 
 */
class ApiResponse {

	/**
	 * @var bool
	 */
	private $success;

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @var string 
	 */
	private $errorMessage;

	/**
	 * @var int 
	 */
	private $errorCode;

	/**
	 * Get response data field
	 * @param string $name
	 * @return mixed
	 * @throws Exception
	 */
	public function __get($name)
	{
		if (isset($this->data[$name]))
		{
			return $this->data[$name];
		}

		throw new Exception('Trying to access not existing property: ' . $name);
	}

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
	 * Create success API response
	 * @param array $data
	 * @return ApiResponse
	 */
	public function createSuccessResponse(array $data = [])
	{
		$this->success = true;
		$this->data = $data;
		return $this;
	}

	/**
	 * Create error API response
	 * @param string $error
	 * @return ApiResponse
	 */
	public function createErrorResponse($error)
	{
		$this->success = false;
		$this->errorMessage = Config::get("api.$error.error_message");
		$this->errorCode = Config::get("api.$error.error_code");
		return $this;
	}

	/**
	 * JSON response to be returned to client
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function toJsonResponse()
	{
		$response = [];
		$response['success'] = $this->success;

		if ($this->success)
		{
			$response['data'] = $this->data;
		}
		else
		{
			$response['error_message'] = $this->errorMessage;
			$response['error_code'] = $this->errorCode;
		}

		return Response::JSON($response);
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
	 * Set success
	 * @param bool $success
	 */
	public function setSuccess($success)
	{
		$this->success = $success;
	}

	/**
	 * Get data
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Set data
	 * @param array $data
	 */
	public function setData(array $data)
	{
		$this->data = $data;
	}

	/**
	 * Get error message
	 * @return string
	 */
	public function getErrorMessage()
	{
		return $this->errorMessage;
	}

	/**
	 * Set error message
	 * @param string $errorMessage
	 */
	public function setErrorMessage($errorMessage)
	{
		$this->errorMessage = $errorMessage;
	}

	/**
	 * Get error code
	 * @return int
	 */
	public function getErrorCode()
	{
		return $this->errorCode;
	}

	/**
	 * Set error code
	 * @param int $errorCode
	 */
	public function setErrorCode($errorCode)
	{
		$this->errorCode = $errorCode;
	}

}
