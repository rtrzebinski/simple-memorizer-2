<?php

/**
 * REST API base controller
 * 
 * Common functions used by most API methods
 */
class API_BaseController extends Controller {

	/**
	 * @var ApiSessionRepository 
	 */
	protected $apiSessionRepository;

	/**
	 * @param ApiSessionRepository $apiSessionRepository
	 */
	public function __construct(ApiSessionRepository $apiSessionRepository)
	{
		$this->apiSessionRepository = $apiSessionRepository;
	}

	/**
	 * User whose token signed API call
	 * 
	 * Null will be returned if 'auth_token' does not match any user
	 * 
	 * @return User
	 */
	protected function user()
	{
		return $this->apiSessionRepository->user(Input::get('auth_token'));
	}

	/**
	 * Success API response
	 * @param mixed $data Data returned with API response
	 * @return Illuminate\Http\JsonResponse
	 */
	protected function response($data = null)
	{
		return Response::JSON([
				'success' => true,
				'data' => $data
		]);
	}

}
