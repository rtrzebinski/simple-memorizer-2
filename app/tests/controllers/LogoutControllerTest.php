<?php

class LogoutControllerTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldLogoutUser()
	{
		// mock auth facade
		Auth::shouldReceive('logout')->once();

		// call route
		$this->route('GET', 'logout');

		// check redirection
		$this->assertRedirectedToRoute('landing_page');
	}

}
