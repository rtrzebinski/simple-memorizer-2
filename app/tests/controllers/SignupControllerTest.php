<?php

class SignupControllerTest extends TestCase {

	/**
	 * @test
	 */
	public function shouldDisplaySignupForm()
	{
		View::shouldReceive('make')->with('user.signup')->once();

		$this->route('GET', 'signup');

		$this->assertResponseOk();
	}

	/**
	 * @test
	 */
	public function shouldSignupUser()
	{
		$data = [
			'email' => $this->createRandomEmailAddress(),
			'password' => uniqid()
		];

		$this->route('POST', 'signup', $data);

		$this->assertRedirectedToRoute('overview');
		$this->assertTrue(Auth::check());
	}

	public function invalidCredentialsProvider()
	{
		return [
			['', ''],
			['foo@bar.com', ''],
			['foo', '']
		];
	}

	/**
	 * @test
	 * @dataProvider invalidCredentialsProvider
	 */
	public function shouldNotSignupUserWithInvalidCredentials($email, $password)
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
