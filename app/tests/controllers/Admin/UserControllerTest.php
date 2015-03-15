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

	/**
	 * @dataProvider trueFalseProvider
	 * @param bool $rememberMe
	 */
	public function testPostLogin_ok($rememberMe)
	{
		$user = $this->createUser();
		$data = [
			'email' => $user->email,
			'password' => $user->email,
			'remember_me' => $rememberMe
		];

		$this->route('POST', 'admin_user_login', $data);

		// check auth
		$this->assertRedirectedToRoute('admin_overview');
		$this->assertTrue(Auth::check());

		// check remember token
		$rememberToken = User::where('id', $user->id)->first()->remember_token;
		$this->assertEquals($rememberMe, (bool) $rememberToken);
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
