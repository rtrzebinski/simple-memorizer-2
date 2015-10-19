<?php

/**
 * Base web interface controller
 * Provides code common for all web controllers
 */
class BaseController extends Controller {

	/**
	 * @var ApiDispatcher 
	 */
	protected $apiDispatcher;

	/**
	 * @param ApiDispatcher $apiDispatcher
	 */
	public function __construct(ApiDispatcher $apiDispatcher)
	{
		$this->apiDispatcher = $apiDispatcher;
	}

	/**
	 * Data to be passed to the view.
	 * @var array 
	 */
	protected $viewData = [];

}
