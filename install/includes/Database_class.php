<?php
error_reporting(0);
class Database
{
	public function create_database($data)
	{
		$mysqli = new mysqli($data['hostname'], $data['username'], $data['password'], '');
		
		if(mysqli_connect_errno())
		{
			return false;
		}
		else
		{
			$mysqli->query("CREATE DATABASE IF NOT EXISTS ".$data['database']);
			$mysqli->close();
			return true;
		}
	}
	
	public function create_tables($data)
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '20000M');
		
		$mysqli = new mysqli($data['hostname'], $data['username'], $data['password'], $data['database']);
		
		if(mysqli_connect_errno())
		{
			return false;
		}
		else
		{
			$query = file_get_contents('assets/DATABASE.sql');
			if($mysqli->multi_query($query))
			{
				do
				{
					if($result = $mysqli->store_result())
					{
						$result->free();
					}

				}
				while($mysqli->next_result());
			}
			$mysqli->close();
			return true;
		}
	}
}