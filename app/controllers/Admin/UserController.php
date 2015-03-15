<?php

class Admin_UserController extends BaseController {

	/**
	 * Show overview page.
	 */
	public function getOverview()
	{
		if (Auth::check())
		{
			$this->viewData['user'] = Auth::user();
			return View::make('admin.user.overview', $this->viewData);
		}
		else
		{
			return Redirect::route('admin_user_login');
		}
	}

	/**
	 * Show login page.
	 */
	public function getLogin()
	{
		if (Auth::check())
		{
			return Redirect::route('admin_overview');
		}
		else
		{
			return View::make('admin.user.login');
		}
	}

	/**
	 * Check auth credentials, redirect back to login, or to overview.
	 */
	public function postLogin()
	{
		$email = Input::get('email');
		$password = Input::get('password');

		// input validation
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
			return View::make('admin.user.login')->withErrors($validator);
		}

		// try to log in
		if (Auth::attempt(array('email' => $email, 'password' => $password)))
		{
			return Redirect::route('admin_overview');
		}
		else
		{
			$this->viewData['email'] = $email;
			$errors = $this->createErrors([
				Lang::get('messages.admin.bad_login')
			]);
			return View::make('admin.user.login', $this->viewData)->withErrors($errors);
		}
	}

	/**
	 * Logout user, and redirect to login page.
	 */
	public function getLogout()
	{
		Auth::logout();
		return Redirect::route('admin_user_login');
	}

}
