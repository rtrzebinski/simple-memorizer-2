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
				// check credentials and log user in
				if (Auth::attempt([
						'email' => Input::get('email'),
						'password' => Input::get('password')
					]))
				{
					// create new api session, using logged user id
					$apiSession = $this->apiSessionRepository->create(Auth::id(), Input::get('client_name'), Request::getClientIp());

					// log user out, we don't want to keep user logged in (with cookies/session)
					Auth::logout();

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
