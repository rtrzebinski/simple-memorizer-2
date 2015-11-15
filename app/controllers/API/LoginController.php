<?php

/**
 * REST API login controller
 * 
 * Log in existing user with given credentials
 * Return auth_token which can be used to authenticate other API calls
 */
class API_LoginController extends API_BaseController {

	/**
	 * Create API auth token for existing user
	 * 
	 * Parameteres:
	 * - string email User email
	 * - string password User password
	 * - string client_name Name of client app
	 * 
	 * @return Illuminate\Http\JsonResponse
	 * Auth token
	 */
	public function login()
	{
		return $this->apiOutput(function() {
				$credentials = [
					'email' => Input::get('email'),
					'password' => Input::get('password')
				];

				/*
				 * check credentials using Auth::once()
				 * this will log user in just for current request
				 */
				if (Auth::once($credentials))
				{
					// create new api session, using logged user id
					$apiSession = $this->apiSessionRepository->create(Auth::id(), Input::get('client_name'), Request::getClientIp());

					// return api session auth token
					return $this->successResponse(['auth_token' => $apiSession->auth_token]);
				}
				else
				{
					// bad user login
					return $this->errorResponse('unable_to_login');
				}
			}, false);
	}

}
