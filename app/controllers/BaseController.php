<?php

use Illuminate\Support\MessageBag;

class BaseController extends Controller {

	/**
	 * Data to be passed to the view.
	 * @var array 
	 */
	protected $viewData = array();

	/**
	 * Create errors object from provided array.
	 * @param array $data
	 * @return \Illuminate\Support\MessageBag
	 */
	protected function createErrors(array $data)
	{
		$messageBag = new MessageBag();
		foreach ($data as $key => $value)
		{
			$messageBag->add($key, $value);
		}
		return $messageBag;
	}

}
