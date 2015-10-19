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
		// call api_login to login existing user and receive new auth_token
		$apiLoginResponse = $this->apiDispatcher->callApiRoute('api_login', [
			'email' => Input::get('email'),
			'password' => Input::get('password'),
			'client_name' => 'Web',
		]);

		// success API response
		if ($apiLoginResponse->getSuccess())
		{
			// use api session repository to obtain user related to auth_token
			$authToken = $apiLoginResponse->auth_token;
			$apiSessionRepository = App::make('ApiSessionRepository');
			$user = $apiSessionRepository->user($authToken);

			// login user
			Auth::login($user, Input::get('remember_me'));

			/*
			 * store api_auth_token in session
			 * this will be used for future API calls authentication
			 */
			Session::set('api_auth_token', $authToken);

			// redirect to overview page
			return Redirect::route('overview');
		}

		// error API response
		if ($apiLoginResponse->getErrorCode() == Config::get('api.unable_to_login.error_code'))
		{
			// display errors
			$this->viewData['email'] = Input::get('email');
			$errors = new Illuminate\Support\MessageBag([Lang::get('messages.bad_login')]);
			return View::make('user.login', $this->viewData)->withErrors($errors);
		}

		// unexpected API resppnse
		throw new Exception('Unexpected API response');
	}

}
