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
	 * API output
	 * 
	 * Closure returning valid API response is required.
	 * 
	 * @param Closure $f
	 * @param bool $requireAuth 
	 * Pass true to check user authentication, and return error if it's not correct
	 * Pass false to skip checking user authentication, and execute closure without passing user
	 * @return Illuminate\Http\JsonResponse
	 */
	public function apiOutput(Closure $f, $requireAuth)
	{
		// Obtain authenticated user
		$user = $this->apiSessionRepository->user(Input::get('auth_token'));

		// Response 'bad auth' API error response if user not found, and authentication check is required
		if (!$user && $requireAuth)
		{
			return Response::apiError();
		}

		// Return closure result
		return $f($user);
	}

}
