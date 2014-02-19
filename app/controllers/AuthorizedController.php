<?php

class AuthorizedController extends BaseController {

	/**
	 * Whitelisted auth routes.
	 *
	 * @var array
	 */
	protected $whitelist = array();

	/**
	 * Initializer.
	 */
	public function __construct()
	{
		// Apply the auth filter
		$this->beforeFilter('auth.sentry', array('except' => $this->whitelist));

		// Call parent
		parent::__construct();
	}

}
