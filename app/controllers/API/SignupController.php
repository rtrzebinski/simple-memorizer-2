<?php

/**
 * REST API signup controller
 */
class API_SignupController extends API_BaseController {

	/**
	 * @var UserRepository 
	 */
	private $userRepository;

	/**
	 * @param ApiSessionRepository $apiSessionRepository
	 * @param UserRepository $userRepository
	 */
	public function __construct(ApiSessionRepository $apiSessionRepository, UserRepository $userRepository)
	{
		parent::__construct($apiSessionRepository);
		$this->userRepository = $userRepository;
	}

	/**
	 * Create new user and related API auth token
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
	public function signup()
	{
		$email = Input::get('email');
		$password = Input::get('password');

		// validate credentials
		$validator = Validator::make(
				array(
				'email' => $email,
				'password' => $password
				), array(
				'email' => 'required|email|unique:users,email',
				'password' => 'required'
				)
		);

		if ($validator->fails())
		{
			// validation error
			throw new ApiException();
		}

		// create new user
		$user = $this->userRepository->create($email, $password);

		// create new api session
		$apiSession = $this->apiSessionRepository->create($user->id, Input::get('client_name'), Request::getClientIp());

		// return api session auth token
		return $this->response(['auth_token' => $apiSession->auth_token]);
	}

}
