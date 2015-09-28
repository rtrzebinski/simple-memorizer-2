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
	 * 
	 * @throws ApiException
	 */
	public function login()
	{
		// check credentials and log user in
		if (Auth::attempt([
				'email' => Input::get('email'),
				'password' => Input::get('password')
			]))
		{
			// create new api session, using logged user id
			$apiSession = $this->apiSessionRepository->create(Auth::id(), Input::get('client_name'), Request::getClientIp());

			// remove all items from session, we don't want to keep user logged in
			Session::flush();

			// return api session auth token
			return $this->response(['auth_token' => $apiSession->auth_token]);
		}
		else
		{
			throw new ApiException('Authentication error');
		}
	}

}
