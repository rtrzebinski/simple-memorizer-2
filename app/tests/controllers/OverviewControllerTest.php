<?php

class OverviewControllerTest extends TestCase {

	public function testOverview()
	{
		$user = $this->createUser();
		$this->be($user);

		View::shouldReceive('make')->with('overview', ['user' => $user])->once();
		$this->route('GET', 'overview');
	}

}
