<?php namespace Libraries\Sadeghi85;

// use AdminController;
// use View;

class Overview {

	private static $memInfo = '';
    private static $initialized = false;

    private static function initialize()
    {
    	if (self::$initialized)
    		return;

//        self::$memInfo = `sudo cat /proc/meminfo`;
    	self::$initialized = true;
    }

	public static function getFtpStatus()
	{
		self::initialize();

		return `sudo pure-ftpwho`;
	}

//	public static function getTotalMemory()
//	{
//		self::initialize();
//
//		return preg_replace('#.*?MemTotal:\s*(\d+[^\r\n]*).*#is', '$1', self::$memInfo);
//	}
//
//	public static function getFreeMemory()
//	{
//		self::initialize();
//
//		return preg_replace('#.*?MemFree:\s*(\d+[^\r\n]*).*#is', '$1', self::$memInfo);
//	}

}
