<?php

/**
 * User logout web interface
 */
class LogoutController extends BaseController {

	/**
	 * Logout user, and redirect to landing page
	 */
	public function logout()
	{
		$apiLogoutResponse = $this->apiDispatcher->callApiRoute('api_logout', [
			'auth_token' => Input::get('auth_token')
		]);

		// success API response
		if ($apiLogoutResponse->getSuccess())
		{
			// delete all user data from session
			Session::flush();

			// redirect to landing page
			return Redirect::route('landing_page');
		}

		// unexpected API response
		throw new Exception('Unexpected API response');
	}

}
