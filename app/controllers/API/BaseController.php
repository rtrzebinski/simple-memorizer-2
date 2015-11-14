<?php

/**
 * REST API base controller
 */
class API_BaseController extends Controller {

	/**
	 * @var ApiSessionRepository 
	 */
	protected $apiSessionRepository;

	/**
	 * @var ApiResponse 
	 */
	protected $apiResponse;

	/**
	 * @param ApiSessionRepository $apiSessionRepository
	 * @param ApiResponse $apiResponse
	 */
	public function __construct(ApiSessionRepository $apiSessionRepository, ApiResponse $apiResponse)
	{
		$this->apiSessionRepository = $apiSessionRepository;
		$this->apiResponse = $apiResponse;
	}

	/**
	 * Success API response
	 * @param array $data
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function successResponse(array $data = [])
	{
		$this->apiResponse->createSuccessResponse($data);
		return $this->apiResponse->toJsonResponse();
	}

	/**
	 * Error API response
	 * @param string $error
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function errorResponse($error)
	{
		$this->apiResponse->createErrorResponse($error);
		return $this->apiResponse->toJsonResponse();
	}

	/**
	 * API output
	 * 
	 * @param Closure $f Closure returning valid API response
	 * @param bool $requireAuth 
	 * Pass true to check user authentication, and return error if it's not correct
	 * Pass false to skip checking user authentication, and execute closure without passing user
	 * @return Illuminate\Http\JsonResponse
	 */
	public function apiOutput(Closure $f, $requireAuth)
	{
		// Obtain authenticated user
		$user = $this->apiSessionRepository->getUserByAuthToken(Input::get('auth_token'));

		// Response 'bad auth' API error response if user not found, and authentication check is required
		if (!$user && $requireAuth)
		{
			return $this->errorResponse('bad_auth_token');
		}

		// Return closure result
		return $f($user);
	}

}
