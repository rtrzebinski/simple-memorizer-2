<?php

/**
 * Landing page web interface
 */
class LandingController extends BaseController {

	/**
	 * Landing page
	 */
	public function index()
	{
		return View::make('landing');
	}

}
