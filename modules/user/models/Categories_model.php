<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Categories_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function getCategory($slug = null)
	{
		$query = $this->db->limit(1)->where('categorySlug', $slug)->get('posts_category');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return array();
		}
	}
		
	function createCategory($fields = array())
	{
		if($this->db->insert('posts_category', $fields))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function updateCategory($fields = array(), $slug = null)
	{
		
		$this->db->where('categorySlug', $slug);
		if($this->db->update('posts_category', $fields))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function removeCategory($itemID)
	{
		$usageCheck	= $this->db->like('categoryID', '"' . $itemID . '"')->get('posts');
		if($usageCheck->num_rows() > 0)
		{
			return 403;
		}
		else
		{
			if($this->db->limit(1)->delete('posts_category', array('categoryID' => $itemID)))
			{
				return 200;
			}
			else
			{
				return false;
			}
		}
	}
}