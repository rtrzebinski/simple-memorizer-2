<?php

/**
 * Landing page web interface
 */
class LandingPageController extends BaseController {

	/**
	 * Landing page
	 */
	public function index()
	{
		return View::make('landing_page');
	}

}
