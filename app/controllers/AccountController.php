<?php

class AccountController extends BaseController {

	/**
	 * Show signup form.
	 */
	public function signup()
	{
		return View::make('user.signup');
	}

	/**
	 * Create new user.
	 */
	public function doSignup()
	{
		$email = Input::get('email');
		$password = Input::get('password');

		$validator = Validator::make(
				array(
				'email' => $email,
				'password' => $password
				), array(
				'email' => 'required|email|unique:users,email',
				'password' => 'required'
				)
		);

		if ($validator->fails())
		{
			return View::make('user.signup')->withErrors($validator);
		}

		$user = App::make('User');
		$user->email = $email;
		$user->password = Hash::make($password);
		$user->save();

		Auth::login($user);
		return Redirect::route('overview');
	}

	/**
	 * Show login form.
	 */
	public function login()
	{
		return View::make('user.login');
	}

	/**
	 * Login user.
	 */
	public function doLogin()
	{
		// try to log in
		if (Auth::attempt([
				'email' => Input::get('email'),
				'password' => Input::get('password')
				], Input::get('remember_me')))
		{
			return Redirect::route('overview');
		}
		else
		{
			$this->viewData['email'] = Input::get('email');
			$errors = $this->createErrors([
				Lang::get('messages.bad_login')
			]);
			return View::make('user.login', $this->viewData)->withErrors($errors);
		}
	}

	/**
	 * Logout user.
	 */
	public function logout()
	{
		Auth::logout();
		return Redirect::route('landing');
	}

}
