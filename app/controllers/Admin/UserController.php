<?php

use Illuminate\Support\MessageBag;

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
			return Redirect::action('Admin_UserController@getLogin');
		}
	}

	/**
	 * Show login page.
	 */
	public function getLogin()
	{
		if (Auth::check())
		{
			return Redirect::action('Admin_UserController@getOverview');
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
			return Redirect::action('Admin_UserController@getOverview');
		}
		else
		{
			$messageBag = new MessageBag();
			$messageBag->add(null, Lang::get('messages.admin.bad_login'));
			$this->viewData['email'] = $email;
			return View::make('admin.user.login', $this->viewData)->withErrors($messageBag);
		}
	}

	/**
	 * Logout user, and redirect to login page.
	 */
	public function getLogout()
	{
		Auth::logout();
		return Redirect::action('Admin_UserController@getLogin');
	}

}
