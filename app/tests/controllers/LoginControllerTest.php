<?php

class LoginControllerTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldDisplayLoginForm()
	{
		View::shouldReceive('make')->with('user.login');

		$this->route('GET', 'login');

		$this->assertResponseOk();
	}

	/**
	 * @test
	 * @dataProvider trueFalseProvider
	 * @param bool $rememberMe
	 */
	public function shouldLoginUser($rememberMe)
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

	/**
	 * @test
	 */
	public function shouldNotLoginUserWithBadCredentials()
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

}
