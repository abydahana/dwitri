<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Openletters_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function getPost($slug = null)
	{
		// Get the post details
		$this->db->select("*");
		$this->db->where("slug", $slug);
		$query = $this->db->get('openletters');
		if ($query->num_rows() > 0)
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
		if($this->db->insert('openletters', $fields))
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
		$this->db->where('slug', $slug);
		if($this->db->update('openletters', $fields))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function removePost($itemID = 0)
	{
		if($this->session->userdata('user_level') == 1)
		{
			$this->db->where('letterID', $itemID);
		}
		else
		{
			$this->db->where(array('letterID' => $itemID, 'contributor' => $this->session->userdata('userID')));
		}
		if($this->db->limit(1)->delete('openletters'))
		{
			$this->db->delete('comments', array('itemID' => $itemID, 'commentType' => 3));
			$this->db->delete('likes', array('itemID' => $itemID, 'likeType' => 3));
			$this->db->delete('notifications', array('itemID' => $itemID, 'notificationType' => 3));
			$this->db->delete('reposts', array('itemID' => $itemID, 'repostType' => 3));
			
			return true;
		}
		else
		{
			return false;
		}
	}
}