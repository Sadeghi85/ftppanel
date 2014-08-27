<?php namespace Libraries\Sadeghi85;

use \Illuminate\Support\Facades\DB as DB;
use \Illuminate\Support\Facades\Config as Config;
// use AdminController;
// use View;

class Overview {

	private static $_memInfo = '';
	private static $_diskInfo = '';
	

	private static function _memInfo()
	{
		if ( ! self::$_memInfo)
		{
			self::$_memInfo = `sudo cat /proc/meminfo`;
		}
	}
	
	private static function _diskInfo()
	{
		if ( ! self::$_diskInfo)
		{
			self::$_diskInfo = `sudo df --total`;
		}
	}
	
	public static function getFtpStatus()
	{
		return `sudo pure-ftpwho`;
	}
	
	public static function getHostname()
	{
		return `sudo hostname -f`;
	}
	
	public static function getOperatingSystem()
	{
		return `sudo cat /etc/redhat-release`;
	}

	public static function getTime()
	{
		return `sudo date`;
	}
	
	public static function getUptime()
	{
		return `sudo uptime | sed 's/.*up \\([^,]*\\), \\([^:]*\\):\\([^,]*\\), .*/\\1, \\2 hours, \\3 minutes/'`;
	}
	
	public static function getLoadAverages()
	{
		$load = `sudo uptime | sed 's/.*average: \\(.*\\)/\\1/'`;
		$loads = explode(',', $load);
		$loads = array_map('trim', $loads);
		
		return sprintf('%s (1 min), %s (5 mins), %s (15 mins)', $loads[0], $loads[1], $loads[2]);
	}
	
	public static function getTotalSpace()
	{
		self::_diskInfo();

		$totalSpace = preg_replace('#.*?total\s*(\d+).*#is', '$1', self::$_diskInfo);
		$totalSpace = sprintf('%01.2f', ($totalSpace / (1024 * 1024)));
		
		return $totalSpace;
	}
	
	public static function getUsedSpace()
	{
		self::_diskInfo();

		$usedSpace = preg_replace('#.*?total\s*\d+\s*(\d+).*#is', '$1', self::$_diskInfo);
		$usedSpace = sprintf('%01.2f', ($usedSpace / (1024 * 1024)));

		return $usedSpace;
	}
	
	public static function getDirSpace($dir)
	{
		$dirSpace = shell_exec('sudo du -ck --max-depth=1 ' . $dir);
		$dirSpace = preg_replace('#.*?(\d+)\s*total.*#is', '$1', $dirSpace);
		
		return sprintf('%01.2f', $dirSpace / 1024);
	}

	public static function getPanelTotalSpace()
	{
		self::_diskInfo();
		
		$usedSpace = preg_replace('#.*?total\s*\d+\s*(\d+).*#is', '$1', self::$_diskInfo);
		$totalSpace = preg_replace('#.*?total\s*(\d+).*#is', '$1', self::$_diskInfo);
		
		$cdnSpace = shell_exec('sudo du -ck --max-depth=1 ' . Config::get('ftppanel.ftpHome'));
		$cdnSpace = preg_replace('#.*?(\d+)\s*total.*#is', '$1', $cdnSpace);
		
		return sprintf('%01.2f', (($totalSpace - ($usedSpace - $cdnSpace)) / (1024 * 1024)));
	}

	public static function getPanelAssignedSpace()
	{
		// Prevent SQL injection - variable binding
		$sum = DB::select(DB::raw("SELECT SUM(quotasize) sum FROM (SELECT * FROM (SELECT * FROM accounts WHERE activated = 1 ORDER BY quotasize DESC) a GROUP BY a.home) b"), array());

		return sprintf('%01.2f', ($sum[0]->sum / 1024));
	}
	
	public static function getTotalMemory()
	{
		self::_memInfo();

		$totalMem = preg_replace('#.*?MemTotal:\s*(\d+).*#is', '$1', self::$_memInfo);
		$totalMem = sprintf('%01.2f', ($totalMem / 1024));
		
		return $totalMem;
	}

	public static function getUsedMemory()
	{
		self::_memInfo();

		$totalMem = preg_replace('#.*?MemTotal:\s*(\d+).*#is', '$1', self::$_memInfo);
		//$totalMem = sprintf('%01.2f', ($totalMem / 1024));
		
		$freeMem = preg_replace('#.*?MemFree:\s*(\d+).*#is', '$1', self::$_memInfo);
		//$freeMem = sprintf('%01.2f', ($freeMem / 1024));
		
		$cachedMem = preg_replace('#.*?Cached:\s*(\d+).*#is', '$1', self::$_memInfo);
		//$cachedMem = sprintf('%01.2f', ($cachedMem / 1024));
		
		
		return sprintf('%01.2f', ($totalMem - $freeMem - $cachedMem) / 1024);
	}
	
	public static function getTotalSwap()
	{
		self::_memInfo();

		$totalMem = preg_replace('#.*?SwapTotal:\s*(\d+).*#is', '$1', self::$_memInfo);
		$totalMem = sprintf('%01.2f', ($totalMem / 1024));
		
		return $totalMem;
	}

	public static function getUsedSwap()
	{
		self::_memInfo();

		$totalMem = preg_replace('#.*?SwapTotal:\s*(\d+).*#is', '$1', self::$_memInfo);
		//$totalMem = sprintf('%01.2f', ($totalMem / 1024));
		
		$freeMem = preg_replace('#.*?SwapFree:\s*(\d+).*#is', '$1', self::$_memInfo);
		//$freeMem = sprintf('%01.2f', ($freeMem / 1024));
		
		return sprintf('%01.2f', ($totalMem - $freeMem) / 1024);
	}

}
