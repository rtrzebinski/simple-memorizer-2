<?php

/**
 * REST API login controller
 */
class API_LoginController extends API_BaseController {

	/**
	 * Create API auth token for existing user
	 * 
	 * Parameteres:
	 * - string email
	 * - string password
	 * - string client_name
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
					return Response::apiSuccess(['auth_token' => $apiSession->auth_token]);
				}
				else
				{
					return Response::apiError();
				}
			}, false);
	}

}
