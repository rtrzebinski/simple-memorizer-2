<?php

trait ApiTestHelper {

	/**
	 * Assert success API response
	 * @param array $data
	 */
	protected function assertSuccessApiResponse(array $data)
	{
		$actual = $this->client->getResponse()->getContent();
		$expected = Response::JSON([
				'success' => true,
				'data' => $data
			])->getContent();
		$this->assertEquals($expected, $actual);
	}

}
