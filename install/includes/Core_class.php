<?php
class Core
{
	public function validate_post($data)
	{
		return !empty($data['hostname']) && !empty($data['username']) && !empty($data['database']);
	}

	public function write_config($data)
	{
		$hostname		= (isset($data['hostname']) ? $data['hostname'] : null);
		$username		= (isset($data['username']) ? $data['username'] : null);
		$password		= (isset($data['password']) ? $data['password'] : null);
		$database		= (isset($data['database']) ? $data['database'] : null);
		$template_path	= 'config/config.php';
		$output_path	= '../config.php';
		$database_file	= file_get_contents($template_path);

		$new			= str_replace("%HOSTNAME%", $hostname, $database_file);
		$new			= str_replace("%USERNAME%", $username, $new);
		$new			= str_replace("%PASSWORD%", $password, $new);
		$new			= str_replace("%DATABASE%", $database, $new);

		$handle			= fopen($output_path, 'w+');

		chmod($output_path, 0777);

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
	
	public function is_mod_rewrite_enabled()
	{
		if((isset($_SERVER['HTTP_MOD_REWRITE']) && strtolower($_SERVER['HTTP_MOD_REWRITE']) == 'on') || (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules())))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}