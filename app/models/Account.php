<?php

class Account extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'accounts';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	protected $guarded = array('id', 'password');
	
	private $validationRules = array(
		'username'     => 'required|between:3,127|alpha_dash|unique:accounts,username',
		'password'     => 'required|between:3,32|confirmed',
		'password_confirmation'  => 'required|between:3,32|same:password',

		'home'         => array('required', 'between:1,127', 'regex:/^[\/a-zA-z0-9_-]+$/'),
		'aliases'           => 'custom.domain',
		'ip'           => 'custom.ip_range',
		'ulbandwidth'  => 'integer',
		'dlbandwidth'  => 'integer',
		'quotasize'    => 'required|integer',
		'quotafiles'   => 'integer',
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

	/**
	 * Many to many relationship.
	 *
	 * @return Model
	 */
	public function users()
	{
		// Second argument is the name of pivot table.
		// Third & forth arguments are the names of foreign keys.
		return $this->belongsToMany('User', 'account_user', 'account_id', 'user_id')->withTimestamps();

	}

	/**
	 * One to many relationship.
	 *
	 * @return Model
	 */
	public function logs()
    {
        return $this->hasMany('PanelLog', 'account_id');
    }
	
	/**
	 * One to many relationship.
	 *
	 * @return Model
	 */
	public function ip()
    {
        return $this->hasMany('Ip', 'account_id');
    }

	public function aliases()
    {
        return $this->hasMany('Alias', 'account_id');
    }

	public function scopeActivated($query)
	{
		return $query->where('activated', '=', 1);
	}

	public function store()
	{
		$inputs = array_filter(Input::only('username', 'ulbandwidth', 'dlbandwidth', 'quotasize', 'quotafiles'));
		$inputs['home'] = Config::get('ftppanel.ftpHome').'/'.Input::get('home');
		$inputs['readonly'] = (int) Input::get('readonly', 1);
		$inputs['activated'] = (int) Input::get('activated', 0);
		$inputs['comment'] = Input::get('comment', '');
		
		try
		{
			foreach ($inputs as $column => $value)
			{
				$this->$column = $value;
			}

			$password = Input::get('password', '');
			
			if ($password)
			{
				$this->password = sha1($password);
			}

			$this->save();
		}
		catch (\Exception $e)
		{
			return false;
		}
		
		$topDir = Libraries\Sadeghi85\UploadScript::getTopDir($inputs['home'])['topDir'];
			
		if ($inputs['readonly'])
		{
			Event::fire('account.readonly_upload', array($topDir));
		}
		else
		{
			
			$sharedHome = Account::where('home', 'LIKE', $topDir.'%')->where('readonly', '=', 1)->lists('username');

			if (empty($sharedHome))
			{
				Event::fire('account.normal_upload', array($topDir));
			}
		}

		return true;
	}
	
	public function storeUser()
	{
		$users = Input::get('users', array());
		
		if ( ! empty($users))
		{
			$this->users()->sync($users);
		}
		
		
		if ( ! Sentry::getUser()->isSuperUser() && ! $this->users()->find(Sentry::getId()))
		{
			$this->users()->attach(Sentry::getId());
		}
	}

	public function storeAliases()
	{
		$this->aliases()->delete();
		
		$aliasCollection = array_filter(explode("\r\n", Input::get('aliases', '')));

		if (empty($aliasCollection))
		{
			$alias = new Alias();

			$this->aliases()->save($alias);
		}
		else
		{
			foreach($aliasCollection as $_alias)
			{
				$alias = new Alias(array(
					'domain' => $_alias,
				));

				$this->aliases()->save($alias);
			}
		}
	}

	public function storeIp()
	{
		$this->ip()->delete();
		
		$ipCollection = array_filter(explode("\r\n", Input::get('ip', '')));

		if (empty($ipCollection))
		{
			$ip = new Ip();

			$this->ip()->save($ip);
		}
		else
		{
			foreach($ipCollection as $ipRange)
			{
				$ipSegments = explode('-', $ipRange);

				if (count($ipSegments) == 2)
				{
					$ip_start = sprintf('%u', ip2long($ipSegments[0]));
					$ip_end = sprintf('%u', ip2long($ipSegments[1]));

					if ($ip_start > $ip_end)
					{
						list($ip_start, $ip_end) = array($ip_end, $ip_start);
					}

					$ip_start_for_humans = long2ip($ip_start);
					$ip_end_for_humans = long2ip($ip_end);
				}
				else
				{
					$ip_start_for_humans = $ipSegments[0];
					$ip_end_for_humans = $ip_start_for_humans;

					$ip_start = sprintf('%u', ip2long($ip_start_for_humans));
					$ip_end = $ip_start;
				}

				$ip = new Ip(array(
					'ip_start' => $ip_start,
					'ip_end' => $ip_end,
					'ip_start_for_humans' => $ip_start_for_humans,
					'ip_end_for_humans' => $ip_end_for_humans,
				));

				$this->ip()->save($ip);
			}
		}
	}

}
