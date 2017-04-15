<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Snapshots_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
	
	function getUserPosts($limit = 10, $offset = 0)
	{
		$this->db->where('contributor', $this->session->userdata('userID'));
		$this->db->limit($limit, $offset);
		$query = $this->db->get('snapshots');
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
		$query = $this->db->limit(1)->where('snapshotSlug', $slug)->get('snapshots');
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
		if($this->db->insert('snapshots', $fields))
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
		$photo		= $this->db->limit(1)->get_where('snapshots', array('snapshotSlug' => $slug));
		if($photo->num_rows() > 0)
		{
			if(isset($fields['snapshotFile']))
			{
				@unlink('uploads/snapshots/' . $photo->row()->snapshotFile);
				@unlink('uploads/snapshots/thumbs/' . $photo->row()->snapshotFile);
			}
			$this->db->where('snapshotSlug', $slug);
			if($this->db->update('snapshots', $fields))
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
	
	function removePost($slug = null)
	{
		$photo		= $this->db->limit(1)->get_where('snapshots', array('snapshotSlug' => $slug))->row()->snapshotFile;
		if($this->session->userdata('user_level') == 1)
		{
			$this->db->where('snapshotSlug', $slug);
		}
		else
		{
			$this->db->where(array('snapshotSlug' => $slug, 'contributor' => $this->session->userdata('userID')));
		}
		if($this->db->limit(1)->delete('snapshots'))
		{
			@unlink('uploads/snapshots/' . $photo);
			@unlink('uploads/snapshots/thumbs/' . $photo);
			$this->db->delete('comments', array('itemID' => $this->getPostIDBySlug($slug), 'commentType' => 2));
			$this->db->delete('likes', array('itemID' => $this->getPostIDBySlug($slug), 'commentType' => 2));
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
		$this->db->select('snapshotID');
		$this->db->where('snapshotSlug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('snapshots');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u['snapshotID'];
			}
		}
		else
		{
			return false;
		}
	}
}