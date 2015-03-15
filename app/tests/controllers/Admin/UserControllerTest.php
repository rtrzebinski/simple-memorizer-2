<?php

class Tests_Controllers_Admin_UserControllerTest extends TestCase {

	public function testGetOverview_user_logged_in()
	{
		$user = $this->createUser();
		$this->be($user);

		$this->route('GET', 'admin_overview');

		$this->assertViewHas('user');
	}

	public function testGetOverview_user_not_logged_in()
	{
		$this->route('GET', 'admin_overview');

		$this->assertRedirectedToRoute('admin_user_login');
	}

	public function testGetLogin()
	{
		$this->route('GET', 'admin_user_login');

		$this->assertResponseOk();
	}

	public function testPostLogin_ok()
	{
		$user = $this->createUser();
		$data = [
			'email' => $user->email,
			'password' => $user->email
		];

		$this->route('POST', 'admin_user_login', $data);

		$this->assertRedirectedToRoute('admin_overview');
		$this->assertTrue(Auth::check());
	}

	public function testPostLogin_fail()
	{
		$user = $this->createUser();
		$data = [
			'email' => $user->email,
			'password' => uniqid()
		];

		$this->route('POST', 'admin_user_login', $data);

		$this->assertViewHas('errors');
		$this->assertFalse(Auth::check());
	}

	public function testGetLogout()
	{
		$user = $this->createUser();
		$this->be($user);

		$this->route('GET', 'admin_user_logout');
		
		$this->assertRedirectedToRoute('admin_user_login');
		$this->assertFalse(Auth::check());
	}

}
