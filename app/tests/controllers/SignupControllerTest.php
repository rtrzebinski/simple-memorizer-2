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

		// mock User model
		$userMock = $this->createMock('User', [
			'setAttribute',
			'save'
		]);
		call_user_func_array([$userMock->expects($this->exactly(2))->method('setAttribute'), 'withConsecutive'], [
			['email', $data['email']],
			['password'] // don't check password (hash) value, as it's different on every hashing
		]);
		$userMock->expects($this->once())->method('save');
		App::instance('User', $userMock);

		// mock auth facade
		Auth::shouldReceive('login')->once()->with($userMock);

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
