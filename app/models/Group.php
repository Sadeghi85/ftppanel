<?php

use Cartalyst\Sentry\Groups\Eloquent\Group as SentryGroupModel;

class Group extends SentryGroupModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'groups';
	
	private $validationRules = array(
        'name' => 'required|between:3,127|alpha_dash|unique:groups,name',
    );
	
	private $validator;
	
	public function validationPasses()
    {
        // make a new validator object
        $v = Validator::make(Input::all(), $this->validationRules);

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
	
	public function validationFails()
	{
		return ( ! $this->validationPasses());
	}
	
	public function getValidator()
    {
        return $this->validator;
    }
	
	public function setValidationRules(array $newRules)
    {
        $this->validationRules = array_replace($this->validationRules, $newRules);
    }
}
