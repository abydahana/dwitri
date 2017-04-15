<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Updates_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
	
	function getUpdates($updateID = null)
	{
		$query = $this->db->where('updateID', $updateID)->limit(1)->get('updates');
		if($query->num_rows() > 0)
		{
			$this->db->where('updateID', $updateID);
			$this->db->limit(1);
			$this->db->set('visits_count', 'visits_count+1', FALSE);
			$this->db->update('updates');
			
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function getUpdatesTitle($updateID = '')
	{
		$this->db->select('updateContent');
		$this->db->where('updateID', $updateID);
		$this->db->limit(1);
		$query = $this->db->get('updates');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return truncate($u['updateContent'], 60);
			}
		}
		else
		{
			return false;
		}
	}
	
	function getUpdatesExcerpt($updateID = '')
	{
		$this->db->select('updateContent');
		$this->db->where('updateID', $updateID);
		$this->db->limit(1);
		$query = $this->db->get('updates');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return truncate($u['updateContent'], 160);
			}
		}
		else
		{
			return false;
		}
	}
	
	function getUpdatesTags($updateID = '')
	{
		$this->db->select('updateContent');
		$this->db->where('updateID', $updateID);
		$this->db->limit(1);
		$query = $this->db->get('updates');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return format_tag($u['updateContent']);
			}
		}
		else
		{
			return false;
		}
	}
	
	function getUpdatesContributor($updateID = '')
	{
		$this->db->select('
			p.userID,
			u.full_name
		')
		->from('updates p')
		->join('users u', 'u.userID = p.userID', 'left')
		->where('p.updateID', $updateID)
		->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			return $query->row()->full_name;
		}
		else
		{
			return false;
		}
	}
}