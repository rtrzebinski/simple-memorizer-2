<?php

class OverviewController extends BaseController {

	/**
	 * Show overview page
	 */
	public function getOverview()
	{
		if (Auth::check())
		{
			$this->viewData['user'] = Auth::user();
			return View::make('overview', $this->viewData);
		}
		else
		{
			return Redirect::route('login');
		}
	}

}
