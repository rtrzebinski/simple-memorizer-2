<?php

/**
 * REST API signup controller
 * 
 * Create new user from given credentials
 * Return auth_token which can be used to authenticate other API calls
 */
class API_SignupController extends API_BaseController {

	/**
	 * Create new user and related API auth token
	 * 
	 * Parameteres:
	 * - string email User email
	 * - string password User password
	 * - string client_name Name of client app
	 * 
	 * @return Illuminate\Http\JsonResponse
	 * Auth token
	 */
	public function signup()
	{
		return $this->apiOutput(function() {
				$email = Input::get('email');
				$password = Input::get('password');

				// validate credentials
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
					// validation error
					return $this->errorResponse('unable_to_signup');
				}

				// create new user
				$userRepository = App::make('UserRepository');
				$user = $userRepository->create($email, $password);

				// create new api session
				$apiSession = $this->apiSessionRepository->create($user->id, Input::get('client_name'), Request::getClientIp());

				// return api session auth token
				return $this->successResponse(['auth_token' => $apiSession->auth_token]);
			}, false);
	}

}
