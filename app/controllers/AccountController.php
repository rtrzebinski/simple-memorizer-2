<?php

class AccountController extends BaseController {

	/**
	 * Show login page.
	 */
	public function getLogin()
	{
		return View::make('user.login');
	}

	/**
	 * Check auth credentials, redirect back to login, or to overview.
	 */
	public function postLogin()
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
	 * Logout user, and redirect to login page.
	 */
	public function getLogout()
	{
		Auth::logout();
		return Redirect::route('login');
	}

	/**
	 * Show signup form
	 */
	public function getSignup()
	{
		return View::make('user.signup');
	}

	/**
	 * Create new user
	 */
	public function postSignup()
	{
		$email = Input::get('email');
		$password = Input::get('password');

		$validator = Validator::make(
				array(
				'email' => $email,
				'password' => $password
				), array(
				'email' => 'required|email',
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

}
