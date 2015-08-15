<?php

class JtableController extends Controller {

	/**
	 * Build success JSON response
	 * @param array $records
	 */
	protected function successReponse($records = [])
	{
		return Response::JSON(array_merge(['Result' => "OK"], $records));
	}

	/**
	 * Build error JSON response
	 * @param string $message
	 */
	protected function errorResponse($message)
	{
		return Response::JSON([
				'Result' => "ERROR",
				'Message' => $message
		]);
	}

}
