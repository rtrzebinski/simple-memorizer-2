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
		// try to log in
		if (Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password'))))
		{
			return Redirect::route('admin_overview');
		}
		else
		{
			$this->viewData['email'] = Input::get('email');
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
