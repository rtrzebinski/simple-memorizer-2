<?php

/**
 * REST API logout controller
 * 
 * Delete session auth_token
 */
class API_LogoutController extends API_BaseController {

	/**
	 * Logout user
	 * 
	 * Delete API session
	 */
	public function logout()
	{
		return $this->apiOutput(function() {

				// delete api session related to auth_token
				$this->apiSessionRepository->delete(Input::get('auth_token'));

				return $this->successResponse();
			}, false);
	}

}
