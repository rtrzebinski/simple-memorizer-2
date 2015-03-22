<?php

class Controllers_AccountControllerTest extends TestCase {

	public function testGetLogin()
	{
		$this->route('GET', 'user_login');

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

		$this->route('POST', 'user_login', $data);

		// check auth
		$this->assertRedirectedToRoute('overview');
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

		$this->route('POST', 'user_login', $data);

		$this->assertViewHas('errors');
		$this->assertFalse(Auth::check());
	}

	public function testGetLogout()
	{
		$user = $this->createUser();
		$this->be($user);

		$this->route('GET', 'user_logout');

		$this->assertRedirectedToRoute('user_login');
		$this->assertFalse(Auth::check());
	}

	public function testGetSignup_user_logged_in()
	{
		$user = $this->createUser();
		$this->be($user);

		$this->route('GET', 'user_signup');

		$this->assertRedirectedToRoute('overview');
	}

	public function testGetSignup_user_not_logged_in()
	{
		View::shouldReceive('make')->with('user.signup')->once();

		$this->route('GET', 'user_signup');

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

		$this->route('POST', 'user_signup', $data);

		$this->assertViewHas('errors');
		$this->assertFalse(Auth::check());
	}

	public function testPostSignup_ok()
	{
		$data = [
			'email' => $this->createRandomEmailAddress(),
			'password' => uniqid()
		];

		$this->route('POST', 'user_signup', $data);

		$this->assertRedirectedToRoute('overview');
		$this->assertTrue(Auth::check());
	}

}
