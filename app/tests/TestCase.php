<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__ . '/../../bootstrap/start.php';
	}

	/**
	 * @return User
	 */
	protected function createUser()
	{
		$user = App::make('User');
		$user->email = uniqid() . '@blackhole.io';
		$user->password = Hash::make($user->email);
		$user->name = 'test';
		$user->save();
		return $user;
	}

	protected function dumpResponseContent()
	{
		dd($this->client->getResponse()->getContent());
	}

}
