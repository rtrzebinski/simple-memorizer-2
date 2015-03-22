<?php

class Controllers_AccountControllerTest extends TestCase {

	public function testLogin()
	{
		View::shouldReceive('make')->with('user.login');

		$this->route('GET', 'login');

		$this->assertResponseOk();
	}

	/**
	 * @dataProvider trueFalseProvider
	 * @param bool $rememberMe
	 */
	public function testDoLogin($rememberMe)
	{
		$user = $this->createUser();
		$data = [
			'email' => $user->email,
			'password' => $user->email,
			'remember_me' => $rememberMe
		];

		$this->route('POST', 'login', $data);

		// check auth
		$this->assertRedirectedToRoute('overview');
		$this->assertTrue(Auth::check());

		// check remember token
		$rememberToken = User::where('id', $user->id)->first()->remember_token;
		$this->assertEquals($rememberMe, (bool) $rememberToken);
	}

	public function testDoLogin_bad_credentials()
	{
		$user = $this->createUser();
		$data = [
			'email' => $user->email,
			'password' => uniqid()
		];

		$this->route('POST', 'login', $data);

		$this->assertViewHas('errors');
		$this->assertFalse(Auth::check());
	}

	public function testLogout()
	{
		$user = $this->createUser();
		$this->be($user);

		$this->route('GET', 'logout');

		$this->assertRedirectedToRoute('login');
		$this->assertFalse(Auth::check());
	}

	public function testSignup()
	{
		View::shouldReceive('make')->with('user.signup')->once();

		$this->route('GET', 'signup');

		$this->assertResponseOk();
	}

	public function testDoSignup()
	{
		$data = [
			'email' => $this->createRandomEmailAddress(),
			'password' => uniqid()
		];

		$this->route('POST', 'signup', $data);

		$this->assertRedirectedToRoute('overview');
		$this->assertTrue(Auth::check());
	}

	public function testDoSignup_validation_error_provider()
	{
		return [
			['', ''],
			['foo@bar.com', ''],
			['foo', '']
		];
	}

	/**
	 * @dataProvider testDoSignup_validation_error_provider
	 */
	public function testDoSignup_validation_error($email, $password)
	{
		$data = [
			'email' => $email,
			'password' => $password
		];

		$this->route('POST', 'signup', $data);

		$this->assertViewHas('errors');
		$this->assertFalse(Auth::check());
	}

}
