<?php

/**
 * ApiSession repository
 */
class ApiSessionRepository {

	/**
	 * Create new ApiSession
	 * @param int $userId
	 * @param string $clientName
	 * @param string $clientIp
	 * @return ApiSession
	 */
	public function create($userId, $clientName, $clientIp)
	{
		$apiSession = App::make('ApiSession');
		$apiSession->user_id = $userId;
		$apiSession->auth_token = md5(uniqid() . $userId);
		$apiSession->client_name = $clientName;
		$apiSession->client_ip = $clientIp;
		$apiSession->save();

		return $apiSession;
	}

	/**
	 * User related to provided auth token
	 * 
	 * Null will be returned if provided auth token does not match any user
	 * 
	 * @param string $authToken
	 * @return User
	 */
	public function user($authToken)
	{
		$apiSession = App::make('ApiSession')->where('auth_token', $authToken)->first();

		if ($apiSession)
		{
			return $apiSession->user;
		}
	}

}
