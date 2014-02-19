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
	public function ip()
    {
        return $this->hasMany('Ip', 'account_id');
    }

	public function scopeActivated($query)
	{
		return $query->where('activated', '=', 1);
	}

	public function isActivated()
	{
		return ($this->activated == 1) ? true : false;
	}

	public function insertUser($users)
	{
		if (Group::isRoot())
		{
			foreach ($users as $userId)
			{
				try
				{
					$this->users()->attach($userId);
				}
				catch (\Exception $e)
				{

				}
			}
		}
		else
		{
			$this->users()->attach(Sentry::getUser()->id);
		}
	}

	public function insertIp(array $ipCollection)
	{
		$ipCollection = array_filter($ipCollection);

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

		if (empty($ipCollection))
		{
			$ip = new Ip();

			$this->ip()->save($ip);
		}
	}

}
