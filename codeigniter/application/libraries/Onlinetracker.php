<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Onlinetracker{
	public $file = 'online.tmp';
	public $data;
	public $ip;
	
	public function __construct()
	{
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->data = @unserialize(file_get_contents($this->file));
		$aryData = $this->data['useronline'];
		if(!$this->data) $this->data = array();
		$timeout = time()-120;
		
		//Removes expired data			
		foreach($aryData as $key => $value){
			if($value['time'] <= $timeout) {
				if($value['username']) {
					$this->data['memonline']--; 
				}
				else $this->data['guestonline']--;
				unset($aryData[$key]);
			}
		}

		//If it's the first hit, add the information to database
		if(!isset($aryData[$this->ip])){
			$CI =& get_instance();
			$aryData[$this->ip]['time'] = time();
			$aryData[$this->ip]['uri'] = $_SERVER['REQUEST_URI'];
			
			$username = $CI->session->userdata('username');
			$aryData[$this->ip]['username'] = $username;
			
			if($username) {
				$this->data['memonline']++;
			}
			else {
				$this->data['guestonline']++;
			}

			$this->data['totalvisit']++;
			
			//Loads the USER_AGENT class if it's not loaded yet
			$class_loaded = null;
			if(!isset($CI->agent)) { $CI->load->library('user_agent'); $class_loaded = true; }
			if($CI->agent->is_robot())
				$aryData[$this->ip]['bot'] = $CI->agent->robot();
			else
				$aryData[$this->ip]['bot'] = false;
			
			//Destroys the USER_AGENT class so it can be loaded again on the controller
			if($class_loaded) unset($class_loaded, $CI->agent);
		}
		else {
			$aryData[$this->ip]['time'] = time();
			$aryData[$this->ip]['uri'] = $_SERVER['REQUEST_URI'];
		}
		
		$this->data['useronline'] = $aryData;
		$this->_save();
	}
	
	//this function return the total number of online users
	function users()
	{
		return count($this->data['useronline']);
	}

	//this function return the total number of online members
	function members()
	{
		return @$this->data['memonline'];
	}
	
	//this function return the total number of online guest
	function guests()
	{
		return @$this->data['guestonline'];
	}
	
	//this function return the total number of total visit
	function visits()
	{
		return @$this->data['totalvisit'];
	}
	
	//this function return the total number of online robots
	function robots()
	{
		$i=0;
		foreach($this->data as $value)
		{
			if($value['is_robot']) $i++;
		}
		return $i;
	}
	
	//Used to set custom data
	function set_data($data=false, $force_update=false)
	{
		if(!is_array($data)){ return false;}

		$tmp=false; //Used to control if there are changes

		foreach($data as $key => $value)
		{
			if(!isset($aryData[$this->ip]['data'][$key]) || $force_update)
			{
				$aryData[$this->ip]['data'][$key] = $value; 
				$tmp=true;
			}
		}
		
		//Check if the user's already have this setting and skips the wiriting file process (saves CPU)
		if(!$tmp) return false;
		return $this->_save();
	}
	//
	function get_info()
	{
		return @$this->data;
	}	
	
	//Save current data into file
	function _save()
	{
		$fp = fopen($this->file,'w');
		flock($fp, LOCK_EX);
		$write = fwrite($fp, serialize($this->data));
		fclose($fp);
		return $write;
	}
}