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
	 * Generate random email address
	 * 
	 * @return string
	 */
	protected function randomEmailAddress()
	{
		return uniqid() . '@blackhole.io';
	}

	/**
	 * Refresh model instance with data from database
	 * 
	 * No value returned, operates on object reference
	 * 
	 * @param \Illuminate\Database\Eloquent\Model $object
	 */
	protected function refresh(\Illuminate\Database\Eloquent\Model &$object)
	{
		$object = App::make(get_class($object))->find($object->id);
	}

}
