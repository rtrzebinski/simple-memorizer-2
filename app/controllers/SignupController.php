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

		// validate input
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

		// call api_signup to create new user and receive new auth_token
		$apiSignupResponse = $this->apiDispatcher->callApiRoute('api_signup', [
			'email' => $email,
			'password' => $password,
			'client_name' => 'Web',
		]);

		// success API response
		if ($apiSignupResponse->getSuccess())
		{
			// use api session repository to obrain user related to auth_token
			$authToken = $apiSignupResponse->auth_token;
			$apiSessionRepository = App::make('ApiSessionRepository');
			$user = $apiSessionRepository->getUserByAuthToken($authToken);

			// login user (with 'remember me')
			Auth::login($user, true);

			/*
			 * store api_auth_token in session
			 * this will be used for future API calls authentication
			 */
			Session::set('api_auth_token', $authToken);

			// redirect to overview page
			return Redirect::route('overview');
		}

		// unexpected API resppnse
		throw new Exception('Unexpected API response');
	}

}
