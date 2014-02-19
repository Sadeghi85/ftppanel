<?php

class MyLog extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'logs';
	
	/**
     * One to many relationship.
     *
     * @return Model
     */
    public function user()
    {
		return $this->belongsTo('User');
    }
	
	/**
     * One to many relationship.
     *
     * @return Model
     */
    public function account()
    {
		return $this->belongsTo('Account');
    }
	
	public function scopeNewest($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }
	
	public static function hasAccessToLogs()
	{
		if (Sentry::check() and (Group::isRoot() or Sentry::getUser()->hasAccess('log.self') or Sentry::getUser()->hasAccess('log.all') or Sentry::getUser()->hasAccess('log.nonroot')))
		{
			return true;
		}
		
		return false;
	}
	
	public static function insertLog(array $params)
    {
		$myLog = new MyLog;
		
		$myLog->description = isset($params['description']) ? $params['description'] : '';
		$myLog->user_id = isset($params['user_id']) ? $params['user_id'] : null;
		$myLog->site_id = isset($params['account_id']) ? $params['account_id'] : null;
		$myLog->event = isset($params['event']) ? $params['event'] : null;
		$myLog->type = isset($params['type']) ? $params['type'] : null;
		
		try
		{
			$myLog->save();
		}
		catch (\Exception $e)
		{
			return false;
		}
		
		return true;
    }
	
	public static function danger(array $params)
	{
		$params['type'] = 'danger';
		
		self::insertLog($params);
	}
	
	public static function warning(array $params)
	{
		$params['type'] = 'warning';
		
		self::insertLog($params);
	}
	
	public static function info(array $params)
	{
		$params['type'] = 'info';
		
		self::insertLog($params);
	}
	
	public static function success(array $params)
	{
		$params['type'] = 'success';
		
		self::insertLog($params);
	}
}
