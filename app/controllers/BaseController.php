<?php

/**
 * Base web interface controller
 * Provides code common for all web controllers
 */
class BaseController extends Controller {

	/**
	 * API dispatcher to be used to make API calls
	 * @var ApiDispatcher 
	 */
	protected $apiDispatcher;

	/**
	 * API auth token used for authenticating user when making API calls
	 * @var string
	 */
	protected $apiAuthToken;

	/**
	 * @param ApiDispatcher $apiDispatcher
	 */
	public function __construct(ApiDispatcher $apiDispatcher)
	{
		$this->apiDispatcher = $apiDispatcher;
		$this->apiAuthToken = Session::get('api_auth_token');
	}

	/**
	 * Data to be passed to the view.
	 * @var array 
	 */
	protected $viewData = [];

}
