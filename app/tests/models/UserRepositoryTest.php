<?php

class UserRepositoryTest extends TestCase {

	use DatabaseTestHelper;

	/**
	 * @test
	 */
	public function shouldCreateNewUser()
	{
		$email = uniqid();
		$repository = new UserRepository();

		$user = $repository->create($email, uniqid());

		// reload to ensure data is stored in db
		$this->refresh($user);

		$this->assertEquals($email, $user->email);
	}

}
