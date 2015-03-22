<?php

class OverviewController extends BaseController {

	/**
	 * Show overview page
	 */
	public function overview()
	{
		$this->viewData['user'] = Auth::user();
		return View::make('overview', $this->viewData);
	}

}
