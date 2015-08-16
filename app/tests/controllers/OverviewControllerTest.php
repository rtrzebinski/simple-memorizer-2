<?php

class OverviewControllerTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldDisplayOverviewPage()
	{
		// create unsaved user
		$user = new User();

		//mock facades
		Auth::shouldReceive('user')->once()->andReturn($user);
		View::shouldReceive('make')->once()->with('overview', ['user' => $user]);

		// call route
		$this->route('GET', 'overview');

		// assert response ok
		$this->assertResponseOk();
	}

}
