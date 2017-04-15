<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Posts_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function getUserPosts($limit = 10, $offset = 0)
	{
		$this->db->where('contributor', $this->session->userdata('userID'));
		$this->db->limit($limit, $offset);
		$query = $this->db->get('posts');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
		
	function getCategories()
	{
		// Get a list of all categories
		$this->db->select("*");
		$query = $this->db->get('posts_category');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function getPost($slug = null)
	{
		$query = $this->db->limit(1)->where('postSlug', $slug)->get('posts');
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
		if($this->db->insert('posts', $fields))
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
		$this->db->where('postSlug', $slug);
		if($this->db->update('posts', $fields))
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
		if($this->session->userdata('user_level') == 1)
		{
			$this->db->where('postID', $itemID);
		}
		else
		{
			$this->db->where(array('postID' => $itemID, 'contributor' => $this->session->userdata('userID')));
		}
		if($this->db->limit(1)->delete('posts'))
		{
			$this->db->delete('comments', array('itemID' => $itemID, 'type' => 1));
			$this->db->delete('likes', array('itemID' => $itemID, 'type' => 1));
			$this->db->delete('notifications', array('itemID' => $itemID, 'postType' => 1));
			$this->db->delete('reposts', array('itemID' => $itemID, 'postType' => 1));
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function getPostIDBySlug($slug = '')
	{
		$this->db->select('postID');
		$this->db->where('postSlug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('posts');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u['postID'];
			}
		}
		else
		{
			return false;
		}
	}
}