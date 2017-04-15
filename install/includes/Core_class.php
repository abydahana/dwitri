<?php
class Core
{
	function validate_post($data)
	{
		return !empty($data['hostname']) && !empty($data['username']) && !empty($data['database']);
	}

	function write_config($data)
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '20000M');
		
		$hostname		= (isset($data['hostname']) ? $data['hostname'] : null);
		$username		= (isset($data['username']) ? $data['username'] : null);
		$password		= (isset($data['password']) ? $data['password'] : null);
		$database		= (isset($data['database']) ? $data['database'] : null);
		$template_path	= 'config/database.php';
		$output_path	= '../codeigniter/application/config/database.php';
		$database_file	= file_get_contents($template_path);

		$new			= str_replace("%HOSTNAME%", $hostname, $database_file);
		$new			= str_replace("%USERNAME%", $username, $new);
		$new			= str_replace("%PASSWORD%", $password, $new);
		$new			= str_replace("%DATABASE%", $database, $new);

		$handle			= fopen($output_path, 'w+');

		@chmod($output_path, 0777);

		if(is_writable($output_path))
		{
			if(fwrite($handle, $new))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}