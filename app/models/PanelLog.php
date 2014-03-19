<?php

class PanelLog extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'panel_logs';
	
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
    public function group()
    {
		return $this->belongsTo('Group');
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
        return $query->orderBy('created_at', 'desc');
    }
	
	public function storeLog(array $params)
    {
		$this->description = isset($params['description']) ? $params['description'] : '';
		$this->user_id = isset($params['user_id']) ? $params['user_id'] : null;
		$this->account_id = isset($params['account_id']) ? $params['account_id'] : null;
		$this->group_id = isset($params['group_id']) ? $params['group_id'] : null;
		$this->user_object = isset($params['user_object']) ? $params['user_object'] : null;
		$this->account_object = isset($params['account_object']) ? $params['account_object'] : null;
		$this->group_object = isset($params['group_object']) ? $params['group_object'] : null;
		$this->event = isset($params['event']) ? $params['event'] : null;
		$this->type = isset($params['type']) ? $params['type'] : null;
		
		try
		{
			$this->save();
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
		
		$log = new PanelLog;
		
		$log->storeLog($params);
	}
	
	public static function warning(array $params)
	{
		$params['type'] = 'warning';
		
		$log = new PanelLog;
		
		$log->storeLog($params);
	}
	
	public static function info(array $params)
	{
		$params['type'] = 'info';
		
		$log = new PanelLog;
		
		$log->storeLog($params);
	}
	
	public static function success(array $params)
	{
		$params['type'] = 'success';
		
		$log = new PanelLog;
		
		$log->storeLog($params);
	}
}
