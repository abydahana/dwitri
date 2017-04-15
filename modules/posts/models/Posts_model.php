<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Posts_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function getPost($slug = null)
	{
		$query = $this->db->limit(1)->where('postSlug', $slug)->get('posts');
		if($query->num_rows() > 0)
		{
			$this->db->where('postSlug', $slug);
			$this->db->limit(1);
			$this->db->set('visits_count', 'visits_count+1', FALSE);
			$this->db->update('posts');
			
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function getPostTitle($slug = '')
	{
		$this->db->select('postTitle');
		$this->db->where('postSlug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('posts');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u['postTitle'];
			}
		}
		else
		{
			return false;
		}
	}
	
	function getPostExcerpt($slug = '')
	{
		$this->db->select('postExcerpt');
		$this->db->where('postSlug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('posts');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return truncate($u['postExcerpt'], 160);
			}
		}
		else
		{
			return false;
		}
	}
	
	function getPostTags($slug = '')
	{
		$this->db->select('tags, postExcerpt');
		$this->db->where('postSlug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('posts');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				if($u['tags'] != '')
				{
					return $u['tags'];
				}
				else
				{
					return format_tag($u['postExcerpt']);
				}
			}
		}
		else
		{
			return false;
		}
	}
	
	function getPostContributor($slug = '')
	{
		$this->db->select('
			p.contributor,
			u.full_name
		')
		->from('posts p')
		->join('users u', 'u.userID = p.contributor', 'left')
		->where('p.postSlug', $slug)
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
	
	function getCategoryTitle($slug = '')
	{
		$this->db->select('categoryTitle');
		$this->db->where('categorySlug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('posts_category');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u['categoryTitle'];
			}
		}
		else
		{
			return false;
		}
	}
	
	function getCategoryExcerpt($slug = '')
	{
		$this->db->select('categoryDescription');
		$this->db->where('categorySlug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('posts_category');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return truncate($u['categoryDescription'], 160);
			}
		}
		else
		{
			return false;
		}
	}
	
	function getCategoryTags($slug = '')
	{
		$this->db->select('categoryDescription');
		$this->db->where('categorySlug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('posts_category');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return format_tag($u['categoryDescription']);
			}
		}
		else
		{
			return false;
		}
	}
	
	function getCategoryIDBySlug($slug = '')
	{
        $this->db->select('categoryID');
		$this->db->where('categorySlug', $slug);
        $this->db->limit(1);
        $query = $this->db->get('posts_category');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u['categoryID'];
			}
		}
		else
		{
			return false;
		}
	}
}