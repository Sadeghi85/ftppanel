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
	
	private $validationRules = array(
        'username'       => 'required|between:3,127|alpha_dash|unique:users,username',
		'first_name'       => 'between:3,127|alpha_dash',
		'last_name'       => 'between:3,127|alpha_dash',
		'password'         => 'required|between:3,32|confirmed',
		'password_confirmation'  => 'required|between:3,32|same:password',
    );
	
	private $validator;
	
	public function validationPasses($inputs)
    {
        // make a new validator object
        $v = Validator::make($inputs, $this->validationRules);

        // check for failure
        if ($v->fails())
        {
            // set errors and return false
            $this->validator = $v;
            return false;
        }

        // validation pass
        return true;
    }
	
	public function validationFails($inputs)
	{
		return ( ! $this->validationPasses($inputs));
	}
	
	public function getValidator()
    {
        return $this->validator;
    }
	
	public function setValidationRules(array $newRules)
    {
        $this->validationRules = array_merge($this->validationRules, $newRules);
    }
	
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
