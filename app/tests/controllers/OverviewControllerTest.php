<?php

class OverviewControllerTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldDisplayOverviewPage()
	{
		$user = $this->createUser();
		$this->be($user);

		View::shouldReceive('make')->with('overview', ['user' => $user])->once();
		$this->route('GET', 'overview');

		$this->assertResponseOk();
	}

}
