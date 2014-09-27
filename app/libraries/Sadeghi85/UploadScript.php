<?php namespace Libraries\Sadeghi85;

use \Illuminate\Support\Facades\Config as Config;

class UploadScript {

	public static function setReadonly($dir)
	{
		//shell_exec("find $dir/ -type f | sed 's/^/\"/g' | sed 's/$/\"/g' | sed 's/^.*\\/\\..*$/\\n/g' | xargs -I{} sudo chattr +i \"{}\"");
		
		shell_exec("sudo chattr -R +i \"$dir\"");
		shell_exec("find \"$dir/\" -type d | sed 's/^/\"/g' | sed 's/$/\"/g' | sed 's/^.*\\/\\..*$/\\n/g' | xargs -I{} sudo chattr -i \"{}\"");
	}
	
	public static function unsetReadonly($dir)
	{
		shell_exec("sudo chattr -R -i \"$dir\"");
		
		//shell_exec("find $dir/ -type f | sed 's/^/\"/g' | sed 's/$/\"/g' | sed 's/^.*\\/\\..*$/\\n/g' | xargs -I{} sudo chattr -i \"{}\"");
		
		
	}
	
	
	public static function createHome($dir)
	{
		shell_exec("sudo mkdir -p \"$dir\"");
		shell_exec("sudo chmod 777 -R \"$dir\"");
	}
	
	
	public static function getTopDir($file)
	{
		$ftpHome = Config::get('ftppanel.ftpHome');
		
		$relativeFile = str_replace($ftpHome, '', $file);
		$topDir = explode('/', trim($relativeFile, '/'));
		$topDir = $ftpHome.'/'.$topDir[0];
		
		return array('relativeFile' => $relativeFile, 'topDir' => $topDir);
	}

}
