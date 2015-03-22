<?php

class OverviewControllerTest extends TestCase {

	public function testGetOverview_user_logged_in()
	{
		$user = $this->createUser();
		$this->be($user);

		View::shouldReceive('make')->with('overview', ['user' => $user])->once();
		$this->route('GET', 'overview');
	}

	public function testGetOverview_user_not_logged_in()
	{
		$this->route('GET', 'overview');

		$this->assertRedirectedToRoute('login');
	}

}
