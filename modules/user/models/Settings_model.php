<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
		
	function getSettings()
	{
		$this->db->select('*');
		$this->db->where("siteID", 0);
		$query = $this->db->get('settings');
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function updateSettings($fields = array())
	{
		$this->db->where('siteID', 0);
		if($this->db->update('settings', $fields))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}