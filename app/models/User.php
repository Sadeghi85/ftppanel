<?php

use Cartalyst\Sentry\Users\Eloquent\User as SentryUserModel;

class User extends SentryUserModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');
	
	/**
	 * Many to many relationship.
	 *
	 * @return Model
	 */
	public function accounts()
    {
		// Second argument is the name of pivot table.
		// Third & forth arguments are the names of foreign keys.
        return $this->belongsToMany('Account', 'account_user', 'user_id', 'account_id')->withTimestamps();
    }

	/**
	 * One to many relationship.
	 *
	 * @return Model
	 */
	public function logs()
    {
        return $this->hasMany('MyLog', 'user_id');
    }
	
	
	/**
	 * Returns the user full name, it simply concatenates
	 * the user first and last name.
	 *
	 * @return string
	 */
	public function fullName()
	{
		return trim("{$this->first_name} {$this->last_name}");
	}
	
	public function usernameWithFullName()
	{
		$fullName = $this->fullName();
		
		return  $this->username . ($fullName ? ' ('.$fullName.')' : '');
	}

}
