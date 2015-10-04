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

}
