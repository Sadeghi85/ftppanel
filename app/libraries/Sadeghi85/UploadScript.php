<?php namespace Libraries\Sadeghi85;

class UploadScript {

	public static function setReadonly($dir)
	{
		shell_exec("find $dir/ -type f | sed 's/^/\"/g' | sed 's/$/\"/g' | sed 's/^.*\\/\\..*$/\\n/g' | xargs -I{} sudo chattr +i \"{}\"");
		//shell_exec("find $dir/ -type f | sed 's/^/\"/g' | sed 's/$/\"/g' | xargs -I{} sudo chattr +i \"{}\"");
	}
	
	public static function unsetReadonly($dir)
	{
		shell_exec("find $dir/ -type f | sed 's/^/\"/g' | sed 's/$/\"/g' | sed 's/^.*\\/\\..*$/\\n/g' | xargs -I{} sudo chattr -i \"{}\"");
		//shell_exec("find $dir/ -type f | sed 's/^/\"/g' | sed 's/$/\"/g' | xargs -I{} sudo chattr -i \"{}\"");
	}
}
