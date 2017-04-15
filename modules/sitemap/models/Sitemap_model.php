<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemap_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
		
	function generateURL()
	{
		// Category
		$this->db->select('
			categoryID,
			categorySlug
		');
		$this->db->limit(150);
		$this->db->order_by('categoryTitle', 'asc');
		$query = $this->db->get('posts_category');
		if ($query->num_rows() > 0)
		{
			$categories		= $query->result_array();
		}
		else
		{
			$categories		= array();
		}
		
		// Posts
		$this->db->select('
			postID,
			postSlug
		');
		$this->db->limit(150);
		$this->db->order_by('timestamp', 'desc');
		$query = $this->db->get('posts');
		if ($query->num_rows() > 0)
		{
			$posts			= $query->result_array();
		}
		else
		{
			$posts			= array();
		}
		
		// Snapshots
		$this->db->select('
			snapshotID,
			snapshotSlug
		');
		$this->db->limit(150);
		$this->db->order_by('timestamp', 'desc');
		$query = $this->db->get('snapshots');
		if ($query->num_rows() > 0)
		{
			$snapshots 		= $query->result_array();
		}
		else
		{
			$snapshots 		= array();
		}
		
		// Openletters
		$this->db->select('
			letterID,
			slug
		');
		$this->db->limit(150);
		$this->db->order_by('timestamp', 'desc');
		$query = $this->db->get('openletters');
		if ($query->num_rows() > 0)
		{
			$openletters 	= $query->result_array();
		}
		else
		{
			$openletters 	= array();
		}
		
		// TV Channels
		$this->db->select('
			tvID,
			tvSlug
		');
		$this->db->limit(150);
		$this->db->order_by('timestamp', 'desc');
		$query = $this->db->get('tv');
		if ($query->num_rows() > 0)
		{
			$channels 	= $query->result_array();
		}
		else
		{
			$channels 	= array();
		}
		
		// Users
		$this->db->select('
			userID,
			userName
		');
		$this->db->limit(150);
		$this->db->order_by('userID', 'desc');
		$query = $this->db->get('users');
		if ($query->num_rows() > 0)
		{
			$users		 	= $query->result_array();
		}
		else
		{
			$users		 	= array();
		}
		
		// Search
		$this->db->select('
			searchID,
			query
		');
		$this->db->limit(150);
		$this->db->order_by('timestamp', 'desc');
		$query = $this->db->get('search');
		if ($query->num_rows() > 0)
		{
			$search		 	= $query->result_array();
		}
		else
		{
			$search		 	= array();
		}
		
		return array_merge($categories, $posts, $snapshots, $openletters, $channels, $users, $search);
	}
}