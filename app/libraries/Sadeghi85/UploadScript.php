<?php namespace Libraries\Sadeghi85;

use \Illuminate\Support\Facades\Config as Config;
use \Illuminate\Support\Facades\Log as Log;

class UploadScript {

	public static function disableHttp($dir)
	{
		$nginxConfig = shell_exec("sudo cat /etc/nginx/nginx.conf 2>/dev/null");
		
		$nginxConfig = preg_replace('#[\r\n]+\s*location\s*'.($dir).'/\s*{\s*return\s*444;\s*}#i', '', $nginxConfig);
		$nginxConfig = preg_replace('#[\r\n]+\s*server\s*{#i', "\n    server {\n        location $dir/ { return 444; }", $nginxConfig);
		
		shell_exec("sudo \cp /etc/nginx/nginx.conf /etc/nginx/nginx.conf.bak 2>/dev/null");
		
		
		$descriptorspec = array(
		   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
		);

		$process = proc_open('sudo tee /etc/nginx/nginx.conf', $descriptorspec, $pipes);

		if (is_resource($process)) {
			// $pipes now looks like this:
			// 0 => writeable handle connected to child stdin
			// 1 => readable handle connected to child stdout

			fwrite($pipes[0], $nginxConfig);
			fclose($pipes[0]);
		}

		
		#shell_exec(sprintf('echo "%s" | sudo tee "%s"', $nginxConfig, '/etc/nginx/nginx.conf'));
		
		shell_exec('sudo /sbin/service nginx reload');
	}
	
	public static function enableHttp($dir)
	{
		$nginxConfig = shell_exec("sudo cat /etc/nginx/nginx.conf 2>/dev/null");
		
		$nginxConfig = preg_replace('#[\r\n]+\s*location\s*'.($dir).'/\s*{\s*return\s*444;\s*}#i', '', $nginxConfig);

		shell_exec("sudo \cp /etc/nginx/nginx.conf /etc/nginx/nginx.conf.bak 2>/dev/null");
		
		$descriptorspec = array(
		   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
		);

		$process = proc_open('sudo tee /etc/nginx/nginx.conf', $descriptorspec, $pipes);

		if (is_resource($process)) {
			// $pipes now looks like this:
			// 0 => writeable handle connected to child stdin
			// 1 => readable handle connected to child stdout

			fwrite($pipes[0], $nginxConfig);
			fclose($pipes[0]);
		}
		#shell_exec(sprintf('echo "%s" | sudo tee "%s"', $nginxConfig, '/etc/nginx/nginx.conf'));
		
		shell_exec('sudo /sbin/service nginx reload');
	}

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
		$relativeTopDir = '/'.$topDir[0];
		$topDir = $ftpHome.'/'.$topDir[0];
		
		return array('relativeFile' => $relativeFile, 'topDir' => $topDir, 'relativeTopDir' => $relativeTopDir);
	}

}
