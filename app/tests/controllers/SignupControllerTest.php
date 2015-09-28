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
			'email' => $this->randomEmailAddress(),
			'password' => uniqid()
		];

		// empty user
		$user = new User();

		// mock UserRepository
		$userRepositoryMock = $this->getMock('UserRepository', ['create']);
		$userRepositoryMock->expects($this->once())->method('create')->with($data['email'], $data['password'])->willReturn($user);
		App::instance('UserRepository', $userRepositoryMock);

		// mock auth facade
		Auth::shouldReceive('login')->once()->with($user);

		// mock validator (so it doesn't access database to check if email was already used)
		$validatorMock = $this->
			getMockBuilder('\Illuminate\Validation\Validator')->
			setMethods(['fails'])->
			disableOriginalConstructor()->
			getMock();
		// fails() should return false
		$validatorMock->method('fails')->willReturn(false);
		Validator::shouldReceive('make')->once()->andReturn($validatorMock);

		// call route
		$this->route('POST', 'signup', $data);

		// check redirection to overview
		$this->assertRedirectedToRoute('overview');
	}

	/**
	 * @test
	 */
	public function shouldNotSignupUserWithInvalidCredentials()
	{
		// mock validator
		$validatorMock = $this->
			getMockBuilder('\Illuminate\Validation\Validator')->
			setMethods(['fails', 'getMessageBag'])->
			disableOriginalConstructor()->
			getMock();
		// fails() should return true
		$validatorMock->method('fails')->willReturn(true);
		// mock error messages
		$validatorMock->method('getMessageBag')->willReturnCallback(function () {
			return new Illuminate\Support\MessageBag(['foo']);
		});
		Validator::shouldReceive('make')->once()->andReturn($validatorMock);

		$this->route('POST', 'signup');

		$this->assertViewHas('errors');
		$this->assertFalse(Auth::check());
	}

}
