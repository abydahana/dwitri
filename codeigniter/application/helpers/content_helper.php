<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('getPosts'))
{
	function getPosts($type = null, $contributor = null, $categoryID = null, $limit = 10, $offset = 0)
	{
		$CI =& get_instance();
		
		if($type == 'posts')
		{
			$CI->db->from('posts');
		}
		else if($type == 'categories')
		{
			$CI->db->from('posts_category');
		}
		else if($type == 'posts_category')
		{
			$CI->db->from('posts');
			if($categoryID != null)
			{
				$CI->db->like('categoryID', '"' . $categoryID . '"');
			}
		}
		else if($type == 'snapshots')
		{
			$CI->db->from('snapshots');
		}
		else if($type == 'openletters')
		{
			$CI->db->from('openletters');
		}
		else if($type == 'tv')
		{
			$CI->db->from('tv');
		}
		else if($type == 'pages')
		{
			$CI->db->from('pages');
		}
		
		if($contributor != null)
		{
			$CI->db->where('contributor', $contributor);
		}
		
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->order_by('timestamp', 'DESC');
		$CI->db->limit($limit, $offset);
		
		$query = $CI->db->get();
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('getCategoryNews'))
{
	function getCategoryNews($categoryID = 0, $limit = 12, $offset = 0)
	{
		$CI =& get_instance();
		$CI->db->like('categoryID', '"' . $categoryID . '"');
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->order_by('timestamp', 'DESC');
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('posts');
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
}

if ( ! function_exists('countCategoryNews'))
{
	function countCategoryNews($catID = null)
	{
		$CI =& get_instance();
		$CI->db->like('categoryID', '"' . $catID . '"');
		$query = $CI->db->get('posts');
		$totPosts = $query->num_rows();
		if ($totPosts > 0)
		{
			return $totPosts;
		}
		else
		{
			return 0;
		}
	}
}

if(!function_exists('listUsers'))
{
	function listUsers($status = null, $limit = 10, $offset = 0)
	{
		$CI =& get_instance();
		
		if($status != null)
		{
			$CI->db->where('status', $status);
		}
		$CI->db->limit($limit, $offset);
		$CI->db->order_by('last_login', 'desc');
		
		$query = $CI->db->get('users');
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('generatePagination'))
{
	function generatePagination($type = null, $category = null, $userID = null, $slug = null, $limit = 12, $offset = 0, $timestamp = null)
	{
		$CI = &get_instance();
		if($type == 'notifications')
		{
			$type			= 'user/notifications';
			$segment		= 3;
			$CI->db->where('toID', $userID);
			$num			= $CI->db->get('notifications')->num_rows();
		}
		elseif($type == 'search')
		{
			$type			= 'search/' . $category;
			$segment		= 3;
			$num			= getSearchCount($category);
		}
		elseif($type == 'posts')
		{
			if($category != null)
			{
				$type		= 'posts/' . getCategorySlugByID($category);
				$segment	= 3;
				$CI->db->like('categoryID', '"' . $category . '"');
				$num		= $CI->db->get('posts')->num_rows();
			}
			elseif($userID != null)
			{
				$type		= 'user/posts';
				$segment	= 3;
				$CI->db->where('contributor', $userID);
				$num		= $CI->db->get('posts')->num_rows();
			}
			elseif($slug != null)
			{
				$type		= 'user/posts';
				$segment	= 3;
				$num		= $CI->db->get('posts')->num_rows();
			}
			else
			{
				$segment	= 2;
				$num		= $CI->db->count_all('posts');
			}
		}
		elseif($type == 'snapshots')
		{
			if($userID != null)
			{
				$type		= 'user/snapshots';
				$segment	= 3;
				$CI->db->where('contributor', $userID);
				$num		= $CI->db->get('snapshots')->num_rows();
			}
			elseif($slug != null)
			{
				$type		= 'user/snapshots';
				$segment	= 3;
				$num		= $CI->db->get('snapshots')->num_rows();
			}
			else
			{
				$segment	= 2;
				$num		= $CI->db->count_all('snapshots');
			}
		}
		elseif($type == 'openletters')
		{
			if($userID != null)
			{
				$type		= 'user/openletters';
				$segment	= 3;
				$CI->db->where('contributor', $userID);
				$num		= $CI->db->get('openletters')->num_rows();
			}
			elseif($slug != null)
			{
				$type		= 'user/openletters';
				$segment	= 3;
				$num		= $CI->db->get('openletters')->num_rows();
			}
			else
			{
				$segment	= 2;
				$num		= $CI->db->count_all('openletters');
			}
		}
		elseif($type == 'tv')
		{
			if($userID != null)
			{
				$type		= 'user/tv';
				$segment	= 3;
				$CI->db->where('contributor', $userID);
				$num		= $CI->db->get('tv')->num_rows();
			}
			elseif($slug != null)
			{
				$type		= 'user/tv';
				$segment	= 3;
				$num		= $CI->db->get('tv')->num_rows();
			}
			else
			{
				$segment	= 2;
				$num		= $CI->db->count_all('tv');
			}
		}
		elseif($type == 'pages')
		{
			if($userID != null)
			{
				$type		= 'user/pages';
				$segment	= 3;
				$CI->db->where('contributor', $userID);
				$num		= $CI->db->get('pages')->num_rows();
			}
			elseif($slug != null)
			{
				$type		= 'user/pages';
				$segment	= 3;
				$num		= $CI->db->get('pages')->num_rows();
			}
			else
			{
				$segment	= 2;
				$num		= $CI->db->count_all('pages');
			}
		}
		elseif($type == 'users')
		{
			if($slug != null)
			{
				$type		= 'user/users';
				$segment	= 3;
				$num		= $CI->db->get('users')->num_rows();
			}
			else
			{
				$segment	= 2;
				$num		= $CI->db->count_all('users');
			}
		}
		elseif($type == 'userSearch')
		{
			if($slug != null)
			{
				$type		= 'users/' . $slug;
				$segment	= 3;
		
				$CI->db->like('userName', $slug);
				$CI->db->or_like('full_name', $slug);
				$CI->db->or_like('email', $slug);
				$CI->db->or_like('address', $slug);
				$CI->db->or_like('mobile', $slug);
				$CI->db->or_like('bio', $slug);
				
				$num		= $CI->db->get('users')->num_rows();
			}
			else
			{
				$segment	= 2;
				$num		= $CI->db->count_all('users');
			}
		}
		elseif($type == 'friends')
		{
			$segment		= 2;
			$CI->db->where('toID', $userID);
			$num			= $CI->db->get('friendships')->num_rows();
		}
		elseif($type == 'followers')
		{
			$segment		= 2;
			$CI->db->where('is_following', $userID);
			$num			= $CI->db->get('followers')->num_rows();
		}
		elseif($type == 'following')
		{
			$segment		= 2;
			$CI->db->where('userID', $userID);
			$num			= $CI->db->get('followers')->num_rows();
		}
		elseif($type == 'categories')
		{
			$type			= 'user/categories';
			$segment		= 3;
			$num			= $CI->db->count_all('posts_category');
		}
		elseif($type == 'translations')
		{
			$type			= 'user/translate/' . $slug;
			$segment		= 4;
			$num			= $CI->db->count_all('language');
		}
		
		$config['base_url'] 			= base_url($type);
	   
		$config['total_rows'] 			= $num;
		$config['per_page'] 			= $limit;
		$config['uri_segment'] 			= $segment;
		$config['num_links']			= 1;
		$config['full_tag_open'] 		= '<ul class="pagination">';
		$config['full_tag_close'] 		= '</ul>';
		$config['num_tag_open'] 		= '<li>';
		$config['num_tag_close'] 		= '</li>';
		$config['cur_tag_open'] 		= '<li class="active"><a href="">';
		$config['cur_tag_close'] 		= '<span class="sr-only"></span></a></li>';
		$config['next_tag_open'] 		= '<li>';
		$config['next_tagl_close']		= '</li>';
		$config['prev_tag_open'] 		= '<li>';
		$config['prev_tagl_close'] 		= '</li>';
		$config['first_tag_open'] 		= '<li>';
		$config['first_tagl_close'] 	= '</li>';
		$config['last_tag_open'] 		= '<li>';
		$config['last_tagl_close']		= '</li>';
	  
		$CI->pagination->initialize($config);
	   
		return $CI->pagination->create_links();
	}
}
	
if(!function_exists('getPostTitleByID'))
{
	function getPostTitleByID($post_id = '')
	{
		$CI =& get_instance();
        $CI->db->select('postTitle');
		$CI->db->where('postID', $post_id);
        $CI->db->limit(1);
        $query = $CI->db->get('posts');
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
}
	
if(!function_exists('notificationPostTitle'))
{
	function notificationPostTitle($postType = null, $itemID = null)
	{
		$CI =& get_instance();
		
		if($postType == 0)
		{
			$select = 'updateContent';
			$where	= 'updateID';
			$get	= 'updates';
		}
		elseif($postType == 1)
		{
			$select = 'postTitle';
			$where	= 'postID';
			$get	= 'posts';
		}
		elseif($postType == 2)
		{
			$select = 'snapshotContent';
			$where	= 'snapshotID';
			$get	= 'snapshots';
		}
		elseif($postType == 3)
		{
			$select = 'title';
			$where	= 'letterID';
			$get	= 'openletters';
		}
		elseif($postType == 4)
		{
			$select = 'tvTitle';
			$where	= 'tvID';
			$get	= 'tv';
		}
        $CI->db->select($select);
		$CI->db->where($where, $itemID);
        $CI->db->limit(1);
        $query = $CI->db->get($get);
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u[$select];
			}
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('getPostAuthor'))
{
	function getPostAuthor($type = 0, $postID = 0)
	{
		$CI =& get_instance();
		
		if($type == 0)
		{
			$CI->db->select('userID');
			$CI->db->where('updateID', $postID);
			$CI->db->limit(1);
			$query = $CI->db->get('updates');
		}
		elseif($type == 1)
		{
			$CI->db->select('contributor as userID');
			$CI->db->where('postID', $postID);
			$CI->db->limit(1);
			$query = $CI->db->get('posts');
		}
		elseif($type == 2)
		{
			$CI->db->select('contributor as userID');
			$CI->db->where('snapshotID', $postID);
			$CI->db->limit(1);
			$query = $CI->db->get('snapshots');
		}
		elseif($type == 3)
		{
			$CI->db->select('contributor as userID');
			$CI->db->where('letterID', $postID);
			$CI->db->limit(1);
			$query = $CI->db->get('openletters');
		}
		else
		{
			return false;
		}
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u['userID'];
			}
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('getPostIDBySlug'))
{
	function getPostIDBySlug($slug = '')
	{
		$CI =& get_instance();
        $CI->db->select('postID');
		$CI->db->where('postSlug', $slug);
        $CI->db->limit(1);
        $query = $CI->db->get('posts');
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

if(!function_exists('getPostSlugByID'))
{
	function getPostSlugByID($post_id = '')
	{
		$CI =& get_instance();
        $CI->db->select('postSlug');
		$CI->db->where('postID', $post_id);
        $CI->db->limit(1);
        $query = $CI->db->get('posts');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u['postSlug'];
			}
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('getCategories'))
{
	function getCategories()
	{
		$CI =& get_instance();
		
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->order_by('categoryTitle', 'ASC');
		$query = $CI->db->get('posts_category');
		
		return $query->result_array();
	}
}

if(!function_exists('getCategoryByID'))
{
	function getCategoryByID($id = '')
	{
		$CI =& get_instance();
        $CI->db->select('categoryTitle');
		$CI->db->where('categoryID', $id);
        $CI->db->limit(1);
        $query = $CI->db->get('posts_category');
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
			return FALSE;
		}
	}
}

if(!function_exists('getCategorySlugByID'))
{
	function getCategorySlugByID($id = '')
	{
		$CI =& get_instance();
        $CI->db->select('categorySlug');
		$CI->db->where('categoryID', $id);
        $CI->db->limit(1);
        $query = $CI->db->get('posts_category');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u['categorySlug'];
			}
		}
		else
		{
			return FALSE;
		}
	}
}

if(!function_exists('getCategoryIDBySlug'))
{
	function getCategoryIDBySlug($slug = '')
	{
		$CI =& get_instance();
        $CI->db->select('categoryID');
		$CI->db->where('categorySlug', $slug);
        $CI->db->limit(1);
        $query = $CI->db->get('posts_category');
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
			return FALSE;
		}
	}
}

if(!function_exists('getLatestNews'))
{
	function getLatestNews($limit = 10, $offset = 0)
	{
		$CI =& get_instance();
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->order_by('timestamp', 'DESC');
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('posts');
		
		return $query->result_array();
	}
}

if(!function_exists('getHeadlineNews'))
{
	function getHeadlineNews($categoryID = 0, $limit = 10)
	{
		$CI =& get_instance();
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->order_by('timestamp', 'DESC');
		$CI->db->limit($limit);
		if(is_numeric($categoryID))
		{
			$CI->db->like('categoryID', '"' . $categoryID . '"');
		}
		$CI->db->where('postHeadline', 'Y');
		$query = $CI->db->get('posts');
		if($query->num_rows() > 0)
		{
			$posts = '<div id="carousel" class="carousel slide rounded-sm bg-dark" data-ride="carousel"><ol class="carousel-indicators">'."\r\n";
			
			$s = 0;
			foreach($query->result_array() as $c)
			{
				$posts .= '
					<li data-target="#carousel" data-slide-to="'.$s.'"' . ($s == 0 ? ' class="active"' : '') . '></li>
				'."\r\n";
				$s++;
			}
			
			$s = 0;
			$posts .= '
				</ol>
				<div class="carousel-inner" role="listbox">
			'."\r\n";
			
			foreach($query->result_array() as $c)
			{
				$posts .= '
					<div class="item' . ($s == 0 ? ' active' : '') . '">
						<div style="background:url(' . getFeaturedImage($c['postID'], 1) . ') center center no-repeat;background-size:cover;-moz-background-size:cover;height:360px" class="rounded-sm"></div>
						<div class="carousel-caption">
							<h3 class="text-shadow">' . ($c['postTitle'] != '' ? truncate($c['postTitle'], 80) : phrase('no_title')) . '</h3>
							<div class="clearfix"></div>
							<p class="text-shadow">' . ($c['postExcerpt'] != '' ? truncate($c['postExcerpt'], 80) : phrase('no_content')) . '</p>
							<div class="clearfix"></div>
							<br />
							<a href="' . base_url('posts/' . $c['postSlug']) . '" class="ajaxLoad btn btn-primary"><i class="fa fa-search"></i> &nbsp; '.phrase('read_more').'</a>
						</div>
					</div>
				'."\r\n";
				
				$s++;
			}
			
			$posts .= '
				</div>
			</div>';
			return $posts;
		}
		else
		{
			return null;
		}
	}
}

if(!function_exists('getNewSnapshot'))
{
	function getNewSnapshot($limit = 10)
	{
		$CI =& get_instance();
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->order_by('timestamp', 'DESC');
		$CI->db->limit($limit);
		$query = $CI->db->get('snapshots');
		if($query->num_rows() > 0)
		{
			$posts = '<div id="snapshot" class="carousel slide rounded-sm bg-dark" data-ride="carousel"><ol class="carousel-indicators">'."\r\n";
			
			$s = 0;
			foreach($query->result_array() as $c)
			{
				$posts .= '
					<li data-target="#snapshot" data-slide-to="'.$s.'"' . ($s == 0 ? ' class="active"' : '') . '></li>
				'."\r\n";
				$s++;
			}
			
			$s = 0;
			$posts .= '
				</ol>
				<div class="carousel-inner" role="listbox">
			'."\r\n";
			
			foreach($query->result_array() as $c)
			{
				$posts .= '
					<div class="item' . ($s == 0 ? ' active' : '') . '">
						<div style="background:url(' . base_url('uploads/snapshots/thumbs/' . imageCheck('snapshots', $c['snapshotFile'], 1)) . ') center center no-repeat;background-size:cover;-moz-background-size:cover;height:360px" class="rounded-sm"></div>
						<div class="carousel-caption">
							<h3 class="text-shadow"> &nbsp; </h3>
							<div class="clearfix"></div>
							<p class="text-shadow">' . ($c['snapshotContent'] != '' ? truncate($c['snapshotContent'], 80) : phrase('no_content')) . '</p>
							<div class="clearfix"></div>
							<br />
							<a href="' . base_url('snapshots/' . $c['snapshotSlug']) . '" class="ajax btn btn-primary"><i class="fa fa-search"></i> &nbsp; '.phrase('view').'</a>
						</div>
					</div>
				'."\r\n";
				
				$s++;
			}
			
			$posts .= '
				</div>
			</div>';
			return $posts;
		}
		else
		{
			return null;
		}
	}
}

if(!function_exists('getFeaturedImage'))
{
	function getFeaturedImage($postID = null, $thumb = 0) {
		$CI =& get_instance();
        $CI->db->select('postContent');
		$CI->db->where('postID', $postID);
        $CI->db->limit(1);
        $query = $CI->db->get('posts');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				if(preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $u['postContent'], $matches))
				{
					if($matches[1][0])
					{
						$r = parse_url($matches[1][0]);
						$s = parse_url(base_url());
						
						if($thumb == 1 && $r['host'] == $s['host'])
						{
							return base_url('uploads/posts/thumbs/' . basename($matches[1][0]));
						}
						else
						{
							return $matches[1][0];
						}
					}
					else
					{
						if($thumb == 1)
						{
							return base_url('uploads/posts/thumbs/placeholder.jpg');
						}
						else
						{
							return base_url('uploads/posts/placeholder.jpg');
						}
					}
				}
				else
				{
					if($thumb == 1)
					{
						return base_url('uploads/posts/thumbs/placeholder.jpg');
					}
					else
					{
						return base_url('uploads/posts/placeholder.jpg');
					}
				}
			}
		}
		else
		{
			if($thumb == 1)
			{
				return base_url('uploads/posts/thumbs/placeholder.jpg');
			}
			else
			{
				return base_url('uploads/posts/placeholder.jpg');
			}
		}
	}
}

if(!function_exists('categoryCheck'))
{
	function categoryCheck($slug = null)
	{
		$CI =& get_instance();
        $CI->db->select('categoryID');
		$CI->db->where('categorySlug', $slug);
        $CI->db->limit(1);
        $query = $CI->db->get('posts_category');
        if($query->num_rows() > 0)
		{
			return $query->row()->categoryID;
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('arrayPostByID'))
{
	function arrayPostByID($type = null, $postID = 0)
	{
		$CI =& get_instance();
		
		if($type == 'updates')
		{
			$CI->db->where('updateID', $postID);
		}
		elseif($type == 'posts')
		{
			$CI->db->where('postID', $postID);
		}
		elseif($type == 'snapshots')
		{
			$CI->db->where('snapshotID', $postID);
		}
		elseif($type == 'openletters')
		{
			$CI->db->where('letterID', $postID);
		}
		elseif($type == 'tv')
		{
			$CI->db->where('tvID', $postID);
		}
		else
		{
			$type = 'posts';
			$CI->db->where('postID', $postID);
		}
		
		$CI->db->limit(1);
		$query = $CI->db->get($type);
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('countPosts'))
{
	function countPosts($type = null, $contributor = null, $categoryID = null)
	{
		$CI =& get_instance();
		
		if($type == 'posts')
		{
			$CI->db->from('posts');
		}
		if($type == 'categories')
		{
			$CI->db->from('posts_category');
		}
		else if($type == 'posts_category')
		{
			$CI->db->from('posts');
			if($categoryID != null)
			{
				$CI->db->like('categoryID', '"' . $categoryID . '"');
			}
		}
		else if($type == 'snapshots')
		{
			$CI->db->from('snapshots');
		}
		else if($type == 'openletters')
		{
			$CI->db->from('openletters');
		}
		else if($type == 'tv')
		{
			$CI->db->from('tv');
		}
		else if($type == 'users')
		{
			$CI->db->from('users');
		}
		
		if($contributor != null)
		{
			$CI->db->where('contributor', $contributor);
		}
		
		$query = $CI->db->get();
		
		if($query->num_rows() > 0)
		{
			return $query->num_rows();
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('getComments'))
{
	function getComments($type = null, $itemID = 0, $limit = 10)
	{
		$CI =& get_instance();
		if($type == 'updates')
		{
			$CI->db->where('commentType', 0);
		}
		if($type == 'posts')
		{
			$CI->db->where('commentType', 1);
		}
		elseif($type == 'snapshots')
		{
			$CI->db->where('commentType', 2);
		}
		elseif($type == 'openletters')
		{
			$CI->db->where('commentType', 3);
		}
		elseif($type == 'tv')
		{
			$CI->db->where('commentType', 4);
		}
		
		$CI->db->where('itemID', $itemID);
		$CI->db->order_by('commentID', 'DESC');
		$CI->db->limit($limit);
		$query = $CI->db->get('comments');
		
		$comments = 
			($CI->session->userdata('loggedIn') ? '
			<form id="addCommentForm" method="post" class="form-horizontal bg-info rounded-top" action="' . base_url('user/comment/' . $type . '/' . $itemID) . '" data-id="' . $type . $itemID . '">
				<div class="col-sm-12">
					<div class="form-group commentArea">
						<div class="col-sm-12 nopadding">
							<div class="input-group">
								<span class="input-group-addon" style="padding:0;width:30px">
									<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($CI->session->userdata('userID')), 1)) . '" width="30" height="30" alt="..." class="rounded" />
								</span>
								<textarea name="comments" id="commentInput" class="hitAction form-control" placeholder="' . phrase('write_comments') . '"></textarea>
								<span class="input-group-addon">
									<input type="hidden" name="hash" value="' . sha1('p0st' . $itemID) . '" />
									<button class="btn nopadding commentBtn" style="padding-right:0;padding-left:0" type="submit"><i class="fa fa-check-circle"></i></button>
								</span>
							</div>
							<div class="statusHolder"></div>
						</div>
					</div>
				</div>
			</form>
			' : '
			<div class="bordered text-center bg-info rounded-top" style="background:#fff;padding:10px">
				<a data-toggle="modal" data-dismiss="modal" href="#login" class="btn btn-warning"><i class="fa fa-lock"></i> &nbsp; ' . phrase('please_login_to_comment') . '</a>
			</div>
			')
		;
		$comments .= '
			<div class="col-sm-12 rounded-bottom bordered nomargin comments_holder-' . $type . $itemID . '" style="margin-bottom:10px!important">
				<div class="fetch_new_comment"></div>
		';
		if($query->num_rows() > 0)
		{
			$n = 0;
			foreach($query->result_array() as $row)
			{
				$comments .=
				'
					<div class="row comment-tree comment' . $row['commentID'] . '"' . ($n++ != 0 ? ' style="border-top:1px solid #eee"' : '') . '>
						<div class="col-xs-2 col-sm-1" style="padding-right:0">
							<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($row['userID']), 1)) . '" class="rounded" width="30" height="30" alt="..." />
						</div>
						<div class="col-xs-10 col-sm-11">
							<p class="comment-text relative">
								<a href="' . base_url(getUsernameByID($row['userID'])) . '" class="ajaxLoad hoverCard">
									<b>' . getFullnameByID($row['userID']) . '</b> &nbsp; 
								</a>
								<span id="rcomment' . $row['commentID'] . '">' . nl2br(special_parse($row['comments'])) . '</span>
								<br />
								<small class="comment-tools text-muted">
									<i class="fa fa-clock-o"></i>' . time_since($row['timestamp']) . '
								</small>
								' . ($CI->session->userdata('loggedIn') ? '
								<div class="btn-group absolute" style="right:10px;top:10px">
									' . ($CI->session->userdata('userID') == $row['userID'] ? '<a class="delete-comment btn btn-xs btn-default btn-icon-only" href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/comments/' . $row['commentID'] . '/' . $type . '-' . $itemID) . '\', \'comment' . $row['commentID'] . '\', \'' . $type . $itemID . '\')" data-push="tooltip" data-placement="top" data-title="' . phrase('remove') . '"><i class="fa fa-times"></i></a>' : '<a class="reply-comment btn btn-xs btn-default btn-icon-only" href="javascript:void(0)" data-reply="' . $row['commentID'] . '" data-summon="' . getUsernameByID($row['userID']) . '" data-push="tooltip" data-placement="top" data-title="' . phrase('reply') . '"><i class="fa fa-reply"></i></a><a class="report-comment btn btn-xs btn-default btn-icon-only" href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/comments/' . $row['commentID'] . '/' . $type . '-' . $itemID) . '\', \'comment' . $row['commentID'] . '\', \'' . $type . $itemID . '\')" data-push="tooltip" data-plcement="top" data-title="' . phrase('report') . '"><i class="fa fa-ban"></i></a>') . '
								</div>
								' : '') . '
							</p>
						</div>
					</div>
				';
				$n++;
			}
		}
		else
		{
			$comments .= '<div class="col-sm-12 text-center text-muted"><small>' . phrase('be_first_to_comment') . '</small></div>';
		}
		$comments .='</div><div class="clearfix"></div>';
		
		return $comments;
	}
}

if(!function_exists('getSnapshotSlugByID'))
{
	function getSnapshotSlugByID($snapshotID = '')
	{
		$CI =& get_instance();
        $CI->db->select('snapshotSlug');
		$CI->db->where('snapshotID', $snapshotID);
        $CI->db->limit(1);
        $query = $CI->db->get('snapshots');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u['snapshotSlug'];
			}
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('getSnapshotIDBySlug'))
{
	function getSnapshotIDBySlug($slug = null)
	{
		$CI =& get_instance();
        $CI->db->select('snapshotID');
		$CI->db->where('snapshotSlug', $slug);
        $CI->db->limit(1);
        $query = $CI->db->get('snapshots');
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
			return 0;
		}
	}
}

if(!function_exists('getOpenletterIDBySlug'))
{
	function getOpenletterIDBySlug($slug = '')
	{
		$CI =& get_instance();
        $CI->db->select('letterID');
		$CI->db->where('slug', $slug);
        $CI->db->limit(1);
        $query = $CI->db->get('openletters');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u['letterID'];
			}
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('getSearch'))
{
	function getSearch($keywords = '', $limit = 12, $offset = 0)
	{
		$CI = &get_instance();
		
		$CI->db->select('
			postID,
			contributor,
			postTitle,
			postExcerpt,
			postSlug,
			categoryID,
			tags,
			visits_count,
			timestamp
		');
		$CI->db->like('postTitle', $keywords);
		$CI->db->or_like('postContent', $keywords);
		$CI->db->or_like('tags', $keywords);
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->limit($limit, $offset);
		$CI->db->order_by('timestamp', 'desc');
		$query = $CI->db->get('posts');
		if ($query->num_rows() > 0)
		{
			$stmt = $CI->db->limit(1)->where('query', $keywords)->get('search');
			if($stmt->num_rows() > 0)
			{
				$CI->db->set('count', 'count+1', FALSE);
				$CI->db->where('query', $keywords);
				$CI->db->limit(1);
				$CI->db->update('search');
			}
			else
			{
				$prepare		= array(
					'query'	 	=> $keywords,
					'type'		=> 'posts',
					'timestamp' => time()
				);
				$CI->db->insert('search', $prepare);
			}
			
			$posts 		= $query->result_array();
		}
		else
		{
			$posts = array();
		}
		
		$CI->db->select('
			snapshotID,
			contributor,
			snapshotContent,
			snapshotSlug,
			snapshotFile,
			visits_count,
			timestamp
		');
		$CI->db->like('snapshotContent', $keywords);
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->limit($limit, $offset);
		$CI->db->order_by('timestamp', 'desc');
		$query = $CI->db->get('snapshots');
		if ($query->num_rows() > 0)
		{
			$stmt = $CI->db->limit(1)->where('query', $keywords)->get('search');
			if($stmt->num_rows() > 0)
			{
				$CI->db->set('count', 'count+1', FALSE);
				$CI->db->where('query', $keywords);
				$CI->db->limit(1);
				$CI->db->update('search');
			}
			else
			{
				$prepare		= array(
					'query'	 	=> $keywords,
					'type'		=> 'snapshots',
					'timestamp' => time()
				);
				$CI->db->insert('search', $prepare);
			}
			
			$snapshots 	= $query->result_array();
		}
		else
		{
			$snapshots = array();
		}
		
		$result	= array();
		foreach($posts as $key => $value)
		{
			if(isset($snapshots[$key]))
			{
				if($value['timestamp'] == $snapshots[$key]['timestamp'])
				{
					$result[$key] = array_merge($value, $snapshots[$key]);
				} 
				else
				{
					$result[$key] = $snapshots[$key];
					$result[rand(0,99)]= $value;
				}
			}
			else
			{
				$result[$key] = $value;
			}
		}
		return array_values($result);
	}
}

if(!function_exists('getSearchCount'))
{
	function getSearchCount($keywords = '')
	{
		$CI = &get_instance();
		
		$CI->db->select('
			postID
		');
		$CI->db->like('postTitle', $keywords);
		$CI->db->or_like('postContent', $keywords);
		$CI->db->or_like('tags', $keywords);
		$query 		= $CI->db->get('posts');
		$posts		= $query->num_rows();
		
		$CI->db->select('
			snapshotID
		');
		$CI->db->like('snapshotContent', $keywords);
		$query		= $CI->db->get('snapshots');
		$snapshots 	= $query->num_rows();
		
		return $posts + $snapshots;
	}
}

if(!function_exists('userSearch'))
{
	function userSearch($keywords = '', $limit = 12, $offset = 0)
	{
		$CI =& get_instance();
		
		$CI->db->like('userName', $keywords);
		$CI->db->or_like('full_name', $keywords);
		$CI->db->or_like('email', $keywords);
		$CI->db->or_like('address', $keywords);
		$CI->db->or_like('mobile', $keywords);
		$CI->db->or_like('bio', $keywords);
		
		$CI->db->limit($limit, $offset);
		$CI->db->order_by('last_login', 'desc');
		
		$query = $CI->db->get('users');
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return array();
		}
	}
}

if(!function_exists('userSearchCount'))
{
	function userSearchCount($keywords = '')
	{
		$CI =& get_instance();
		
		$CI->db->like('userName', $keywords);
		$CI->db->or_like('full_name', $keywords);
		$CI->db->or_like('email', $keywords);
		$CI->db->or_like('address', $keywords);
		$CI->db->or_like('mobile', $keywords);
		$CI->db->or_like('bio', $keywords);
		
		$query = $CI->db->get('users');
		
		if($query->num_rows() > 0)
		{
			return $query->num_rows();
		}
		else
		{
			return false;
		}
	}
}