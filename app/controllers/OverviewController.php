<?php

/**
 * Overview page web interface
 */
class OverviewController extends BaseController {

	/**
	 * Overview page
	 */
	public function index()
	{
		$this->viewData['user'] = Auth::user();
		return View::make('overview', $this->viewData);
	}

}
