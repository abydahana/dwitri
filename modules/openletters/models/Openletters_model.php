<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Openletters_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function getOpenletter($slug = null)
	{
		$query = $this->db->where('slug', $slug)->limit(1)->get('openletters');
		if($query->num_rows() > 0)
		{
			$this->db->where('slug', $slug);
			$this->db->limit(1);
			$this->db->set('visits_count', 'visits_count+1', FALSE);
			$this->db->update('openletters');
			
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function getOpenletterTitle($slug = '')
	{
		$this->db->select('title');
		$this->db->where('slug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('openletters');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u['title'];
			}
		}
		else
		{
			return false;
		}
	}
	
	function getOpenletterExcerpt($slug = '')
	{
		$this->db->select('content');
		$this->db->where('slug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('openletters');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return truncate($u['content'], 160);
			}
		}
		else
		{
			return false;
		}
	}
	
	function getOpenletterTags($slug = '')
	{
		$this->db->select('title');
		$this->db->where('slug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('openletters');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return format_tag($u['title']);
			}
		}
		else
		{
			return false;
		}
	}
	
	function getOpenletterContributor($slug = '')
	{
		$this->db->select('
			p.contributor,
			u.full_name
		')
		->from('openletters p')
		->join('users u', 'u.userID = p.contributor', 'left')
		->where('p.slug', $slug)
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