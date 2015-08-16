<?php

/**
 * User signup web interface
 */
class SignupController extends BaseController {

	/**
	 * Show signup form
	 */
	public function index()
	{
		return View::make('user.signup');
	}

	/**
	 * Signup user, and redirect to overview page
	 */
	public function signup()
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

}
