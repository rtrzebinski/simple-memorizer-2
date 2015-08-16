<?php

class LogoutControllerTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldLogoutUser()
	{
		$user = $this->createUser();
		$this->be($user);

		$this->route('GET', 'logout');

		$this->assertRedirectedToRoute('landing');
		$this->assertFalse(Auth::check());
	}

}
