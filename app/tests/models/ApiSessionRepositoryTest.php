<?php

class ApiSessionRepositoryTest extends TestCase {

	use DatabaseTestHelper;

	/**
	 * @test
	 */
	public function shouldCreateApiSession()
	{
		$user = $this->createUser();

		$clientName = uniqid();
		$clientIp = uniqid();

		$repository = new ApiSessionRepository();
		$apiSession = $repository->create($user->id, $clientName, $clientIp);

		// reload to ensure data is stored in db
		$this->refresh($apiSession);

		$this->assertEquals($user->id, $apiSession->user_id);
		$this->assertTrue(isset($apiSession->auth_token));
		$this->assertEquals($clientName, $apiSession->client_name);
		$this->assertEquals($clientIp, $apiSession->client_ip);
	}

	/**
	 * @test
	 */
	public function shouldDeleteApiSession()
	{
		$user = $this->createUser();
		$clientName = uniqid();
		$clientIp = uniqid();

		$repository = new ApiSessionRepository();
		$apiSession = $repository->create($user->id, $clientName, $clientIp);

		$repository->delete($apiSession->auth_token);

		$this->assertNull($repository->user($apiSession->auth_token));
	}

	/**
	 * @test
	 */
	public function shouldReturnUserMatchingAuthToken()
	{
		$user = $this->createUser();
		$repository = new ApiSessionRepository();
		$apiSession = $repository->create($user->id, uniqid(), uniqid());
		$this->assertEquals($user->id, $repository->user($apiSession->auth_token)->id);
	}

}
