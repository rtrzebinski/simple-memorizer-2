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
		$data = [
			'email' => $this->randomEmailAddress(),
			'password' => uniqid(),
			'remember_me' => $rememberMe
		];

		// mock auth facade
		Auth::shouldReceive('attempt')->once()->with([
			'email' => $data['email'],
			'password' => $data['password']
			], $rememberMe)->andReturn(true);

		// call route
		$this->route('POST', 'login', $data);

		// check redirection
		$this->assertRedirectedToRoute('overview');
	}

	/**
	 * @dataProvider trueFalseProvider
	 * @test
	 */
	public function shouldNotLoginUserWithBadCredentials($rememberMe)
	{
		$data = [
			'email' => $this->randomEmailAddress(),
			'password' => uniqid(),
			'remember_me' => $rememberMe
		];

		// mock auth facade
		Auth::shouldReceive('attempt')->once()->with([
			'email' => $data['email'],
			'password' => $data['password']
			], $rememberMe)->andReturn(false);

		// call route
		$this->route('POST', 'login', $data);

		// check if view has errors
		$this->assertViewHas('errors');
	}

	public function trueFalseProvider()
	{
		return [
			[true],
			[false]
		];
	}

}
