<?php

use Cartalyst\Sentry\Groups\Eloquent\Group as SentryGroupModel;

class Group extends SentryGroupModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'groups';
	
	public static function isRoot()
	{
		if (Sentry::check() and Sentry::getUser()->inGroup(Sentry::findGroupByName('Root')))
		{
			return true;
		}
		
		return false;
	}
}
