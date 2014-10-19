<?php

class BaseController extends Controller {

	/**
	 * Data to be passed to the view.
	 * @var array 
	 */
	protected $viewData = array();

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}
