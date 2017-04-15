<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Snapshots_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function getSnapshot($slug = null)
	{
		$query = $this->db->where('snapshotSlug', $slug)->limit(1)->get('snapshots');
		if($query->num_rows() > 0)
		{
			$this->db->where('snapshotSlug', $slug);
			$this->db->limit(1);
			$this->db->set('visits_count', 'visits_count+1', FALSE);
			$this->db->update('snapshots');
			
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function getSnapshotTitle($slug = '')
	{
		$this->db->select('snapshotContent');
		$this->db->where('snapshotSlug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('snapshots');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return truncate($u['snapshotContent'], 70);
			}
		}
		else
		{
			return false;
		}
	}
	
	function getSnapshotContributor($slug = '')
	{
		$this->db->select('
			p.contributor,
			u.full_name
		')
		->from('snapshots p')
		->join('users u', 'u.userID = p.contributor', 'left')
		->where('p.snapshotSlug', $slug)
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