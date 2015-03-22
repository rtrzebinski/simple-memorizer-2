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

	public function testGetSignup_user_logged_in()
	{
		$user = $this->createUser();
		$this->be($user);

		$this->route('GET', 'admin_user_signup');

		$this->assertRedirectedToRoute('admin_overview');
	}

	public function testGetSignup_user_not_logged_in()
	{
		View::shouldReceive('make')->with('admin.user.signup')->once();

		$this->route('GET', 'admin_user_signup');

		$this->assertResponseOk();
	}

	public function testPostSignup_validation_error_provider()
	{
		return [
			['', ''],
			['foo@bar.com', ''],
			['foo', '']
		];
	}

	/**
	 * @dataProvider testPostSignup_validation_error_provider
	 */
	public function testPostSignup_validation_error($email, $password)
	{
		$data = [
			'email' => $email,
			'password' => $password
		];

		$this->route('POST', 'admin_user_signup', $data);

		$this->assertViewHas('errors');
		$this->assertFalse(Auth::check());
	}

	public function testPostSignup_ok()
	{
		$data = [
			'email' => $this->createRandomEmailAddress(),
			'password' => uniqid()
		];

		$this->route('POST', 'admin_user_signup', $data);

		$this->assertRedirectedToRoute('admin_overview');
		$this->assertTrue(Auth::check());
	}

}
