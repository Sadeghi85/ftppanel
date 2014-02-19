<?php namespace Libraries\Sadeghi85\Sites;

use \Illuminate\Support\Facades\Config as Config;

class Create {
	
	private static $webpanelRoot;
	private static $sitesHome;
	private static $webDir;
	private static $userShell;

    private static $initialized = false;

    private static function initialize()
    {
    	if (self::$initialized)
    		return;

		self::$webpanelRoot = Config::get('webpanel.webpanelRoot');
        self::$sitesHome = Config::get('webpanel.sitesHome');
		self::$webDir = Config::get('webpanel.webDir');
		self::$userShell = Config::get('webpanel.userShell');
		
    	self::$initialized = true;
    }
	
	public static function formatOutput($output)
	{
		return trim(preg_replace(sprintf('#^\s*(?:%s)?\s*(.*?)(?:%s)?\s*$#is', preg_quote(self::$utilitiesBeginSignature), preg_quote(self::$utilitiesEndSignature)), '$1', implode("\n", $output)));
	}
	
	public static function create(&$errorMessage, $params)
	{
		self::initialize();
		
		$siteServerName = isset($params['siteServerName']) ? $params['siteServerName'] : 'example.com:80';
		$sitePort = isset($params['sitePort']) ? $params['sitePort'] : '80';
		$siteAliases = isset($params['siteAliases']) ? $params['siteAliases'] : array('example.com');
		$siteTag = isset($params['siteTag']) ? $params['siteTag'] : 'web001';
		$siteActivate = isset($params['siteActivate']) ? $params['siteActivate'] : 0;
		
		
		
////////// Step 1: Create the user
		if ( ! \Libraries\Sadeghi85\Sites\Shell::createUser($errorMessage, $siteTag, $siteAliases))
		{
			return false;
		}
////////// \Step 1

////////// Step 2: Create the site and web directory and index.php
		if ( ! \Libraries\Sadeghi85\Sites\Shell::createSiteDir($errorMessage, $siteTag, $siteServerName))
		{
			return false;
		}
////////// \Step 2

////////// Step 3: Creating PHP pool definition
		if ( ! \Libraries\Sadeghi85\Sites\Shell::CreatePHPPool($errorMessage, $siteTag, $siteServerName))
		{
			return false;
		}
////////// \Step 3

////////// Step 4: Creating Apache virtualhost definition
		if ( ! \Libraries\Sadeghi85\Sites\Shell::CreateApacheVhost($errorMessage, $siteTag, $siteServerName, $siteAliases, $sitePort))
		{
			return false;
		}
////////// \Step 4

////////// Step 5: Creating Nginx virtualhost definition
		if ( ! \Libraries\Sadeghi85\Sites\Shell::CreateNginxVhost($errorMessage, $siteTag, $siteServerName, $siteAliases, $sitePort))
		{
			return false;
		}
////////// \Step 5

////////// Step 6: Creating Webalizer definition
		if ( ! \Libraries\Sadeghi85\Sites\Shell::CreateWebalizerConfig($errorMessage, $siteTag, $siteServerName))
		{
			return false;
		}
////////// \Step 6

////////// Step 7: Activate site or not?
		if ($siteActivate)
		{
			\Libraries\Sadeghi85\Sites\Shell::ActivateSite($errorMessage, $siteTag, $siteServerName);
		}
////////// \Step 7		
		
		dd($siteTag);

		
		
		return true;
	}
}
