<?php

/**
 * Common methods used by jTable based web interfaces
 */
trait JtableTrait {

	/**
	 * Build success JSON response
	 * @param array $records
	 */
	protected function jtableSuccessReponse($records = [])
	{
		return Response::JSON(array_merge(['Result' => "OK"], $records));
	}

	/**
	 * Build error JSON response
	 * @param string $message
	 */
	protected function jtableErrorResponse($message)
	{
		return Response::JSON([
				'Result' => "ERROR",
				'Message' => $message
		]);
	}

}
