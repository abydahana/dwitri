<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
	
	function getUser($slug = null)
	{
		$query = $this->db->where('userName', $slug)->limit(1)->get('users');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function updateUser($fields = array(), $slug = null)
	{
		if($this->db->where('userName', $slug)->limit(1)->update('users', $fields))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function removeUser($slug = null)
	{
		if($this->session->userdata('user_level') == 1)
		{
			$query = $this->db->where('userID', $slug)->limit(1)->get('users');
			if($query->num_rows() > 0)
			{
				if($this->db->limit(1)->delete('users', array('userID' => $slug)))
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
		else
		{
			return false;
		}
	}
}