<?php

class BaseController extends Controller {

	/**
	 * Message bag.
	 *
	 * @var Illuminate\Support\MessageBag
	 */
	protected $messageBag = null;

	/**
	 * Initializer.
	 */
	public function __construct()
	{
		// CSRF Protection
		$this->beforeFilter('csrf', array('on' => 'post'));
		
		$this->messageBag = new Illuminate\Support\MessageBag;
		
		$this->trimInputBeforeValidation();
	}
	
	/**
	 * Trim Input Before Validation.
	 */
	public function trimInputBeforeValidation()
	{
		$inputs = array();
		
		foreach(Input::get() as $name => $input)
		{
			if (is_array($input))
			{
				array_walk_recursive($input, 'trim');
				
				$inputs[$name] = $input;
			}
			else
			{
				$inputs[$name] = trim($input);
			}
		}
		
		Input::merge($inputs);
	}
	
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