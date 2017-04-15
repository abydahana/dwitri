<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pages_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function getPost($slug = null)
	{
		$query = $this->db->limit(1)->where('pageSlug', $slug)->get('pages');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return array();
		}
	}
		
	function createPost($fields = array())
	{
		if($this->db->insert('pages', $fields))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function updatePost($fields = array(), $slug = null)
	{
		$this->db->where('pageSlug', $slug);
		if($this->db->update('pages', $fields))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function removePost($itemID)
	{
		if($this->db->limit(1)->delete('pages', array('pageID' => $itemID, 'contributor' => $this->session->userdata('userID'))))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function generateNavigation()
	{
		$this->db->select('
			pageTitle,
			pageSlug
		');
		$this->db->where('language', $this->session->userdata('language'));
		$query = $this->db->get('pages');
		if ($query->num_rows() > 0)
		{
            return $query->result_array();
		}
		else
		{
			return false;
		}
	}
}