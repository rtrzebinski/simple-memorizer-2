<?php

class ApiSessionTest extends TestCase {

	use DatabaseTestHelper;

	/**
	 * @test
	 */
	public function shouldDefineUserRelation()
	{
		$apiSession = $this->createApiSession();
		$this->assertInstanceOf('User', $apiSession->user);
	}

}
