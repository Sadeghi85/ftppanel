<?php

class Alias extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'aliases';

	protected $fillable = array('domain');

	/**
     * One to many relationship.
     *
     * @return Model
     */
    public function account()
    {
		return $this->belongsTo('Account');
    }

	public static function formatForHumans($aliasCollection)
	{
		$formattedAlias = "\r\n";
		
		foreach($aliasCollection as $alias)
		{
			$formattedAlias .= ($alias->domain)."\r\n";
		}
		
		$formattedAlias = trim($formattedAlias);

		return $formattedAlias;
	}
}
