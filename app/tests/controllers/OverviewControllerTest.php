<?php

class OverviewControllerTest extends TestCase {

	public function testGetOverview_user_logged_in()
	{
		$user = $this->createUser();
		$this->be($user);

		$this->route('GET', 'overview');

		$this->assertViewHas('user');
	}

	public function testGetOverview_user_not_logged_in()
	{
		$this->route('GET', 'overview');

		$this->assertRedirectedToRoute('user_login');
	}

}
