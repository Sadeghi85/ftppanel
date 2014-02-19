<?php

class Ip extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ip';

	protected $fillable = array('ip_start', 'ip_end', 'ip_start_for_humans', 'ip_end_for_humans');

	/**
     * One to many relationship.
     *
     * @return Model
     */
    public function account()
    {
		return $this->belongsTo('Account');
    }

	public static function formatForHumans($ipCollection)
	{
		$formattedIp = "\r\n";

		foreach($ipCollection as $ip)
		{
			if ($ip->ip_start == $ip->ip_end)
			{
				$formattedIp .= long2ip($ip->ip_start)."\r\n";
			}
			else
			{
				$formattedIp .= long2ip($ip->ip_start).'-'.long2ip($ip->ip_end)."\r\n";
			}
		}

		$formattedIp = trim($formattedIp);

		if ($formattedIp == '0.0.0.0-255.255.255.255')
			$formattedIp = Lang::get('general.unlimited');

		return $formattedIp;
	}
	
}
