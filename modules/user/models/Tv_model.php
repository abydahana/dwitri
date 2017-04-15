<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tv_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
	
	function getUserPosts($limit = 10, $offset = 0)
	{
		$this->db->where('contributor', $this->session->userdata('userID'));
		$this->db->limit($limit, $offset);
		$query = $this->db->get('tv');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function getPost($slug = null)
	{
		$query = $this->db->limit(1)->where('tvSlug', $slug)->get('tv');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
		
	function createPost($fields)
	{
		if($this->db->insert('tv', $fields))
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
		$photo		= $this->db->limit(1)->get_where('tv', array('tvSlug' => $slug));
		if($photo->num_rows() > 0)
		{
			if(isset($fields['tvFile']))
			{
				@unlink('uploads/tv/' . $photo->row()->tvFile);
				@unlink('uploads/tv/thumbs/' . $photo->row()->tvFile);
			}
			$this->db->where('tvSlug', $slug);
			if($this->db->update('tv', $fields))
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	
	function removePost($slug = null)
	{
		$photo		= $this->db->limit(1)->get_where('tv', array('tvSlug' => $slug))->row()->tvFile;
		if($this->session->userdata('user_level') == 1)
		{
			$this->db->where('tvSlug', $slug);
		}
		else
		{
			$this->db->where(array('tvSlug' => $slug, 'contributor' => $this->session->userdata('userID')));
		}
		if($this->db->limit(1)->delete('tv'))
		{
			@unlink('uploads/tv/' . $photo);
			@unlink('uploads/tv/thumbs/' . $photo);
			$this->db->delete('comments', array('itemID' => $this->getPostIDBySlug($slug), 'commentType' => 2));
			$this->db->delete('likes', array('itemID' => $this->getPostIDBySlug($slug), 'likeType' => 2));
			$this->db->delete('notifications', array('itemID' => $this->getPostIDBySlug($slug), 'notificationType' => 2));
			$this->db->delete('reposts', array('itemID' => $this->getPostIDBySlug($slug), 'repostType' => 2));
			
			return true;
		}
		else
		{
			return false;
		}
	}

	function getPostIDBySlug($slug = '')
	{
		$this->db->select('tvID');
		$this->db->where('tvSlug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('tv');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u['tvID'];
			}
		}
		else
		{
			return false;
		}
	}
}