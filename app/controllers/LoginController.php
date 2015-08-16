<?php

/**
 * User login web interface
 */
class LoginController extends BaseController {

	/**
	 * Show login form
	 */
	public function index()
	{
		return View::make('user.login');
	}

	/**
	 * Login user, and redirect to overview page
	 */
	public function login()
	{
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

}
