<?php

class MainController extends BaseController {

	/**
	 * Show overview page
	 */
	public function overview()
	{
		$this->viewData['user'] = Auth::user();
		return View::make('overview', $this->viewData);
	}

	/**
	 * Show landing page
	 */
	public function landing()
	{
		return View::make('landing');
	}

}
