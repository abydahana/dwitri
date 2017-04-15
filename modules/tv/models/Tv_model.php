<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tv_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function getTV($slug = null)
	{
		$query = $this->db->where('tvSlug', $slug)->limit(1)->get('tv');
		if($query->num_rows() > 0)
		{
			$this->db->where('tvSlug', $slug);
			$this->db->limit(1);
			$this->db->set('last_visits', time(), FALSE);
			$this->db->set('visits_count', 'visits_count+1', FALSE);
			$this->db->update('tv');
			
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function getTVTitle($slug = '')
	{
		$this->db->select('tvTitle');
		$this->db->where('tvSlug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('tv');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u['tvTitle'];
			}
		}
		else
		{
			return false;
		}
	}
	
	function getTVContent($slug = '')
	{
		$this->db->select('tvContent');
		$this->db->where('tvSlug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('tv');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return truncate($u['tvContent'], 160);
			}
		}
		else
		{
			return false;
		}
	}
	
	function getTVContributor($slug = '')
	{
		$this->db->select('
			p.contributor,
			u.full_name
		')
		->from('tv p')
		->join('users u', 'u.userID = p.contributor', 'left')
		->where('p.tvSlug', $slug)
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