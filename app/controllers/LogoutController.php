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
		Auth::logout();
		return Redirect::route('landing');
	}

}
