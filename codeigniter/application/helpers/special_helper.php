<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('redirect_back'))
{
    function redirect_back($hash = null)
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            header('Location: '.$_SERVER['HTTP_REFERER'].($hash != null ? $hash : ''));
        }
        else
        {
            header('Location: http://'.$_SERVER['SERVER_NAME'].($hash != null ? $hash : ''));
        }
        exit;
    }
}
	
if(!function_exists('generatePageNav'))
{
	function generatePageNav($li = false)
	{
		$CI 	=& get_instance();
		$CI->db->select('
			pageTitle,
			pageSlug
		');
		$CI->db->where('language', $CI->session->userdata('language'));
		$query = $CI->db->get('pages');
		if ($query->num_rows() > 0)
		{
			$link	= ($li ? '<ul style="list-style-type:none;padding:0">' : '');
            foreach($query->result_array() as $nav)
			{
				if($li)
				{
					$link .= '<li><a href="' . base_url('pages/' . $nav['pageSlug']) . '"' . ($CI->uri->segment(2) == $nav['pageSlug'] ? ' class="text-muted ajaxLoad"' : ' class="ajaxLoad"') . '><i class="fa fa-circle-o"></i> ' . $nav['pageTitle'] . '</a></li>';
				}
				else
				{
					$link .= '<a href="' . base_url('pages/' . $nav['pageSlug']) . '"' . ($CI->uri->segment(2) == $nav['pageSlug'] ? ' class="text-muted ajaxLoad"' : ' class="ajaxLoad"') . '>' . $nav['pageTitle'] . '</a> &nbsp; - &nbsp; ';
				}
			}
			$link	.= ($li ? '</ul>' : '');
			return $link;
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('instantLoginURL'))
{	
	function instantLoginURL()
	{
		$CI 	=& get_instance();
		$user 	= $CI->facebook->getUser();
        if($user)
		{
			if($CI->session->userdata('loggedIn'))
			{
				return $CI->facebook->getLoginUrl(array(
					'redirect_uri' => base_url('user/connect'),
					'scope' => array('email', 'user_birthday', 'user_location', 'user_work_history', 'user_about_me', 'user_hometown')
				));
			}
			else
			{
				return $CI->facebook->getLoginUrl(array(
					'redirect_uri' => base_url('user/login'),
					'scope' => array('email', 'user_birthday', 'user_location', 'user_work_history', 'user_about_me', 'user_hometown')
				));
			}
		}
		else
		{
			return $CI->facebook->getLoginUrl(array(
				'redirect_uri' => base_url('user/login'),
				'scope' => array('email', 'user_birthday', 'user_location', 'user_work_history', 'user_about_me', 'user_hometown')
			));
		}
	}
}

if(!function_exists('insertVisitor'))
{	
	function insertVisitor()
	{
		$CI 	=& get_instance();
		$ip		= getenv('remote_addr');
		$CI->db->where(array('ip' => $ip, 'date' => strtotime(date('m/d/Y'))));
		$CI->db->limit(1);
		$query = $CI->db->get('visitors');
		if($query->num_rows() > 0)
		{
			$CI->db->set('amount', 'amount+1', FALSE);
			$CI->db->where(array('ip' => $ip, 'date' => strtotime(date('m/d/Y'))));
			$CI->db->update('visitors');
		}
		else
		{
			$db = array(
				'ip' => $ip,
				'user_agent' => $_SERVER['HTTP_USER_AGENT'],
				'amount' => 1,
				'date' => strtotime(date('m/d/Y')),
				'timestamp' => time()
			);
			$CI->db->insert('visitors', $db);
		}
	}
}

if(!function_exists('globalSettings'))
{
    function globalSettings()
    {
		$CI 	=& get_instance();
		$CI->db->where('siteID', 0);
		$CI->db->limit(1);
		$query 	= $CI->db->get('settings');
		if($query->num_rows() > 0)
		{
			$results = $query->result_array();
			foreach ($results as $u): 
				$page = array(
					'pageTitle'    			=> $u['siteTitle'],
					'pageDescription'    	=> htmlspecialchars($u['siteDescription']),
					'pageKeywords'    		=> format_tag($u['siteDescription']),
					'siteTitle'    			=> htmlspecialchars($u['siteTitle']),
					'siteDescription'    	=> htmlspecialchars($u['siteDescription']),
					'siteLogo'    			=> $u['siteLogo'],
					'siteFooter'    		=> $u['siteFooter'],
					'siteAddress'    		=> $u['siteAddress'],
					'sitePhone'    			=> $u['sitePhone'],
					'siteFax'    			=> $u['siteFax'],
					'siteEmail'    			=> $u['siteEmail'],
					'siteYM'    			=> $u['siteYM'],
					'siteBBM'    			=> $u['siteBBM'],
					'theme'    				=> str_replace('/', '', $u['siteTheme']),
					'defaultImage' 			=> base_url('uploads/logo.png')
				);      	
			endforeach; 
			return $page;
		
		}
		else
		{
			return false;
		}
    }
}

if(!function_exists('getFullnameByID'))
{
	function getFullnameByID($id = '')
	{
		$CI =& get_instance();
        $CI->db->select('full_name');
		$CI->db->where('userID', $id);
        $CI->db->limit(1);
        $query = $CI->db->get('users');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u) {
				return $u['full_name'];
			}
		} else {
			return FALSE;
		}
	}
}

if(!function_exists('getUsernameByID'))
{
	function getUsernameByID($id = '')
	{
		$CI =& get_instance();
        $CI->db->select('username');
		$CI->db->where('userID', $id);
        $CI->db->limit(1);
        $query = $CI->db->get('users');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return $u['username'];
			}
		}
		else
		{
			return FALSE;
		}
	}
}

if(!function_exists('getUseridByUsername'))
{
	function getUseridByUsername($username = '')
	{
		$CI =& get_instance();
        $CI->db->select('userID');
		$CI->db->where('username', $username);
        $CI->db->limit(1);
        $query = $CI->db->get('users');
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
			return FALSE;
		}
	}
}

if(!function_exists('getUserDetails'))
{
	function getUserDetails($userID = 0, $bio = false)
	{
		$CI =& get_instance();
		$CI->db->where('userID', $userID);
		$CI->db->limit(1);
		$query = $CI->db->get('users');
		if($query->num_rows() > 0)
		{
			$user = '';
			
			foreach($query->result_array() as $c)
			{
				$hex		= '#' . random_hex();
				$user .= '
					<div class="first image-placeholder relative">
						<div class="col-sm-12 nomargin nogap_ltr rounded-top">
							<div class="row article_cover" style="background:' . $hex . ' url(' . base_url('uploads/users/covers/' . imageCheck('covers', getUserCover($c['userID']), 1)) . ') center center no-repeat;background-size:cover;-webkit-background-size:cover">
								<div class="col-sm-12 nomargin absolute text-shadow" style="width:100%">
									<div class="col-xs-3">
										<a href="' . base_url($c['userName']) . '" class="ajaxLoad hoverCard">
											<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($c['userID']), 1)) . '" class="img-rounded bordered img-responsive" alt="" />
										</a>
									</div>
									<div class="col-xs-9 relative">
										<h2 class="pull-right">#' . $c['visits_count'] . '</h2>
										<a href="' . base_url($c['userName']) . '" class="ajaxLoad hoverCard">
											<b>' . $c['full_name'] . '</b> 
										</a>
										<br />
										<small>@' . $c['userName'] . ' - ' . time_since(strtotime($c['last_login'])) . '</small>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<p class="text-muted">
								' . $c['bio'] . '
							</p>
							' . ($c['userID'] != $CI->session->userdata('userID') ? '
								<div class="btn-group btn-group-justified">
							' . (is_userConnect('following', $c['userID']) ? '
								<a class="btn btn-sm btn-warning" id="follow" href="' . base_url('user/follow/' . $c['userName']) . '"><i class="fa fa-refresh" id="follow-icon"></i> <span class="follow-text">' . phrase('unfollow') . '</span></a>
							' : '
								<a class="btn btn-sm btn-info" id="follow" href="' . base_url('user/follow/' . $c['userName']) . '"><i class="fa fa-refresh" id="follow-icon"></i> <span class="follow-text">' . phrase('follow') . '</span></a>
							') . '
							' . (is_userConnect('friendship', $c['userID']) ? '
								<a class="btn btn-sm btn-danger" id="friendship" href="' . base_url('user/friendship/' . $c['userName']) . '""><i class="fa fa-user" id="friend-icon"></i> <span class="friend-text">' . phrase('unfriend') . '</span></a>
							' : (is_userConnect('pending', $c['userID']) ? '
								<a class="btn btn-sm btn-warning" id="friendship" href="' . base_url('user/friendship/' . $c['userName']) . '""><i class="fa fa-user" id="friend-icon"></i> <span class="friend-text">' . phrase(($c['userID'] == $CI->session->userdata('userID') ? 'cancel' : 'accept')) . '</span></a>
							' : '
								<a class="btn btn-sm btn-success" id="friendship" href="' . base_url('user/friendship/' . $c['userName']) . '"><i class="fa fa-user" id="friend-icon"></i> <span class="friend-text">' . phrase('add_friend') . '</span></a>
							')) . '
								</div>
							' : '
							') . '
						</div>
					</div>
				';
			}
			
			return $user;
		}
		else
		{
			return null;
		}
	}
}

if(!function_exists('getUserPhoto'))
{
	function getUserPhoto($userID = null)
	{
		$CI =& get_instance();
        $CI->db->select('photo');
		$CI->db->where('userID', $userID);
        $CI->db->limit(1);
        $query = $CI->db->get('users');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				if(!empty($u['photo']) && file_exists('uploads/users/thumbs/' . $u['photo']))
				{
					return $u['photo'];
				}
				else
				{
					return 'placeholder.jpg';
				}
			}
		}
		else
		{
			return 'placeholder.jpg';
		}
	}
}

if(!function_exists('getUserCover'))
{
	function getUserCover($userID = null)
	{
		$CI =& get_instance();
        $CI->db->select('cover');
		$CI->db->where('userID', $userID);
        $CI->db->limit(1);
        $query = $CI->db->get('users');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				if(!empty($u['cover']) && file_exists('uploads/users/covers/' . $u['cover']))
				{
					return $u['cover'];
				}
				else
				{
					return 'placeholder.jpg';
				}
			}
		}
		else
		{
			return 'placeholder.jpg';
		}
	}
}

if(!function_exists('getUserBio'))
{
	function getUserBio($userID = null)
	{
		$CI =& get_instance();
        $CI->db->select('bio');
		$CI->db->where('userID', $userID);
        $CI->db->limit(1);
        $query = $CI->db->get('users');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				if(!empty($u['bio']))
				{
					return $u['bio'];
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('checkUsername'))
{
	function checkUsername($username = '')
	{
		$CI =& get_instance();
		$CI->db->where('username', $username);
        $CI->db->limit(1);
        $query = $CI->db->get('users');
        if ($query->num_rows() > 0)
		{
            return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

if(!function_exists('getRefererByID'))
{
	function getRefererByID($type = null, $postID = 0)
	{
		if($type == 'updates')
		{
			$segment	= 'updates';
			$select 	= 'updateID';
			$where		= array('updateID' => $postID);
		}
		elseif($type == 'posts')
		{
			$segment	= 'posts';
			$select 	= 'postSlug';
			$where		= array('postID' => $postID);
		}
		elseif($type == 'snapshots')
		{
			$segment	= 'snapshots';
			$select 	= 'snapshotSlug';
			$where		= array('snapshotID' => $postID);
		}
		elseif($type == 'openletters')
		{
			$segment	= 'openletters';
			$select 	= 'slug';
			$where		= array('letterID' => $postID);
		}
		elseif($type == 'tv')
		{
			$segment	= 'tv';
			$select 	= 'tvSlug';
			$where		= array('tvID' => $postID);
		}
		else
		{
			return false;
		}
		
		$CI =& get_instance();
        $CI->db->select($select);
		$CI->db->where($where);
        $CI->db->limit(1);
        $query = $CI->db->get($type);
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return base_url($segment . '/' . $u[$select]);
			}
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('getUserFriends'))
{
	function getUserFriends($type = 0, $userID = null, $loops = false)
	{
		$CI =& get_instance();
		if($type == 'request')
		{
			$query = $CI->db->where('(fromID = ' . $userID . ' OR toID = ' . $userID . ') AND status = 0')->get('friendships');
		}
		elseif($type == 'active')
		{
			$query = $CI->db->where('(fromID = ' . $userID . ' OR toID = ' . $userID . ') AND status = 1')->get('friendships');
		}
		elseif($type == 'banned')
		{
			$query = $CI->db->where('(fromID = ' . $userID . ' OR toID = ' . $userID . ') AND status = 2')->get('friendships');
		}
		else
		{
			return false;
		}
		
        if ($query->num_rows() > 0)
		{
			if($loops)
			{
				return $query->result_array();
			}
			else
			{
				return $query->num_rows();
			}
		}
		else
		{
			if($loops)
			{
				return array();
			}
			else
			{
				return 0;
			}
		}
	}
}

if(!function_exists('getUserFollowers'))
{
	function getUserFollowers($type = null, $userID = null, $loops = false)
	{
		$CI =& get_instance();
		$CI->db->select('*');
		if($type == 'followers')
		{
			$CI->db->where('is_following', $userID);
		}
		elseif($type == 'following')
		{
			$CI->db->where('userID', $userID);
		}
		$query = $CI->db->get('followers');
		
        if ($query->num_rows() > 0)
		{
			if($loops)
			{
				return $query->result_array();
			}
			else
			{
				return $query->num_rows();
			}
		}
		else
		{
			if($loops)
			{
				return false;
			}
			else
			{
				return 0;
			}
		}
	}
}

if(!function_exists('countAlert'))
{
	function countAlert($userID = '')
	{
		$CI =& get_instance();
        $CI->db->select('*');
		$CI->db->where(array('toID' => $userID, 'alert' => 0));
        $query = $CI->db->get('notifications');
		
		return $query->num_rows();
	}
}

if(!function_exists('getNotifications'))
{
	function getNotifications($limit = 10, $offset = 0)
	{
		$CI =& get_instance();
		
		$CI->db->where(array('toID' => $CI->session->userdata('userID'), 'fromID !=' => $CI->session->userdata('userID')));
		$CI->db->limit($limit, $offset);
		$CI->db->order_by('timestamp', 'desc');
		
		$query = $CI->db->get('notifications');
		
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

if(!function_exists('countStatus'))
{
	function countStatus($table = '')
	{
		$CI =& get_instance();
        $CI->db->select('*');
		$CI->db->where(array('status' => 0));
        $query = $CI->db->get($table);
		
		if($query->num_rows() > 0)
		{
			return '<span class="badge pull-right">' . $query->num_rows() . '</span>';
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('imageCheck'))
{
	function imageCheck($type = null, $name = null, $thumb = 0)
	{
		if(($type != null && $thumb == 1) && ($type != 'covers' && $thumb == 1))
		{
			$uri = 'uploads/' . $type . '/thumbs/' . $name;
		}
		elseif(($type != null && $thumb != 1) && ($type != 'covers' && $thumb != 1))
		{
			$uri = 'uploads/' . $type . '/' . $name;
		}
		elseif($type == 'covers')
		{
			$uri = 'uploads/users/covers/' . $name;
		}
		else
		{
			$uri = 'uploads/' . $name;
		}
		if(file_exists($uri))
		{
			return $name;
		}
		else
		{
			return 'placeholder.jpg';
		}
	}
}

if(!function_exists('getSocial'))
{
	function getSocial()
	{
		$CI =& get_instance();
		$CI->db->where("socialEnabled", 1);
		$query=$CI->db->get('social');
		$social = '';
		foreach($query->result_array() as $c)
		{
			$social .= '<a href="'.$c['socialLink'].'" target="_blank"><span class="socicon socicon-'.$c['socialName'].'"></span></a>';
		}
		return $social;
	}
}

if(!function_exists('is_json'))
{
	function is_json($str = array())
	{ 
		if(json_decode($str, true) && (json_last_error() == JSON_ERROR_NONE))
		{
			foreach(json_decode($str, true) as $key => $val)
			{
				if(!empty($val))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('json_add'))
{
	function json_add($array = array(), $item_to_add = null)
	{ 
		$index = array_search($item_to_add, $array);
		if ($index !== false)
		{
			array_push($array, $item_to_add);
		}
		return json_encode($array);
	}
}

if(!function_exists('json_remove'))
{
	function json_remove($array = array(), $item_to_remove = null)
	{ 
		$index = array_search($item_to_remove, $array);
		if ($index !== false)
		{
			unset($array[$index]);
		}
		return json_encode($array);
	}
}

if(!function_exists('format_uri'))
{
	function format_uri($str = '')
	{ 
		if($str != '')
		{
			return preg_replace('![^a-z0-9]+!i', '-', strtolower(strip_tags($str)));
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('format_tag'))
{
	function format_tag($str = '')
	{ 
		if($str != '')
		{
			return str_replace(' ', ', ', strtolower(strip_tags($str)));
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('truncate'))
{
	function truncate($string = null, $limit = 100, $break = '.', $pad = '...')
	{
		$string = preg_replace('/<iframe.*?\/iframe>/i','', $string);
		$string = preg_replace('/<script.*?\/script>/i','', $string);
		$string = preg_replace('/<style.*?\/style>/i','', $string);
		$string = preg_replace('/<link.*/i','', $string);
		$string = preg_replace('/<embed.*?\/embed>/i','', $string);
		$string = preg_replace('/<object.*?\/object>/i','', $string);
		$string = strip_tags($string);
		if(strlen($string) <= $limit)
		{
			$string =  $string;
		}
		else
		{
			$string = substr($string, 0, $limit) . $pad;
		}
		return htmlspecialchars(str_replace(array("\r", "\n"), '', $string));
	}
}

if(!function_exists('time_since'))
{
	function time_since($date = null)
	{
		$chunks = array(
			array(60 * 60 * 24 * 365, phrase('year')),
			array(60 * 60 * 24 * 30, phrase('month')),
			array(60 * 60 * 24 * 7, phrase('week')),
			array(60 * 60 * 24, phrase('day')),
			array(60 * 60, phrase('hour')),
			array(60, phrase('minute')),
		);
	 
		$today = time();
		$since = $today - $date;
	 
		if ($since > 604800)
		{
			$print = date("M jS", $date);
			if ($since > 31536000)
			{
				$print .= ", " . date("Y", $date);
			}
			return $print;
		}
	 
		for ($i = 0, $j = count($chunks); $i < $j; $i++)
		{
			$seconds = $chunks[$i][0];
			$name = $chunks[$i][1];
	 
			if (($count = floor($since / $seconds)) != 0)
			break;
		}
	 
		$print = ($count == 1) ? '1 ' . $name : $count . ' ' . $name;
		return ($print == 0 ? phrase('just_now') : ($print > 1 ? $print . strtolower(phrase('s')) : $print));
	}
}

if(!function_exists('random_hex'))
{
	function random_hex($length = 6)
	{
		$characters = '0123456789ABCDEF';
		$string = '';
		for ($i = 0; $i < $length; $i++)
		{
			$string .= $characters[mt_rand(0, strlen($characters) - 1)];
		}
		return $string;
	}
}

if(!function_exists('generateBreadcrumb'))
{
	function generateBreadcrumb()
	{
		$CI = &get_instance();
		$i = 1;
		$uri = $CI->uri->segment($i);
		$link = '';
	 
		while($uri != '')
		{
			$prep_link = '';
			for($j=1; $j<=$i;$j++)
			{
				$prep_link .= $CI->uri->segment($j).'/';
			}
			$uriSegments = ($CI->uri->segment($i+1));
			if($uriSegments == '')
			{
				$link .= '<li class="active"><a href="'.site_url($prep_link).'" class="ajaxLoad">' . ucwords(str_replace('_', ' ', $CI->uri->segment($i))) . '</a></li>';
			}
			else
			{
				$link .= '<li><a href="'.site_url($prep_link).'" class="ajaxLoad">' . ucwords(str_replace('_', ' ', $CI->uri->segment($i))) . '</a><span class="divider"></span></li>';
			}
		 
			$i++;
			$uri = $CI->uri->segment($i);
		}
		$link .= '';
		return $link;
	}
}

if(!function_exists('countComments'))
{
	function countComments($type = null, $itemID = 0)
	{
		$CI =& get_instance();
		if($type == 'updates')
		{
			$CI->db->where(array('commentType' => 0, 'itemID' => $itemID));
		}
		elseif($type == 'posts')
		{
			$CI->db->where(array('commentType' => 1, 'itemID' => $itemID));
		}
		elseif($type == 'snapshots')
		{
			$CI->db->where(array('commentType' => 2, 'itemID' => $itemID));
		}
		elseif($type == 'openletters')
		{
			$CI->db->where(array('commentType' => 3, 'itemID' => $itemID));
		}
		elseif($type == 'tv')
		{
			$CI->db->where(array('commentType' => 4, 'itemID' => $itemID));
		}
		
		$query = $CI->db->get('comments');
		
		if($query->num_rows() > 0)
		{
			return $query->num_rows();
		}
		else
		{
			return 0;
		}
	}
}

if(!function_exists('countLikes'))
{
	function countLikes($type = null, $itemID = null)
	{
		$CI =& get_instance();
        $CI->db->select("*");
		if($type == 'updates')
		{
			$CI->db->where(array('likeType' => 0, 'itemID' => $itemID));
		}
		elseif($type == 'posts')
		{
			$CI->db->where(array('likeType' => 1, 'itemID' => $itemID));
		}
		elseif($type == 'snapshots')
		{
			$CI->db->where(array('likeType' => 2, 'itemID' => $itemID));
		}
		elseif($type == 'openletters')
		{
			$CI->db->where(array('likeType' => 3, 'itemID' => $itemID));
		}
		elseif($type == 'tv')
		{
			$CI->db->where(array('likeType' => 4, 'itemID' => $itemID));
		}
		
		$CI->db->where('like', 1);
        $query = $CI->db->get('likes');
        if ($query->num_rows() > 0)
		{
            return $query->num_rows();
		}
		else
		{
			return 0;
		}
	}
}

if(!function_exists('countReposts'))
{
	function countReposts($type = null, $itemID = null)
	{
		$CI =& get_instance();
        $CI->db->select("*");
		if($type == 'updates')
		{
			$CI->db->where(array('repostType' => 0, 'itemID' => $itemID));
		}
		elseif($type == 'posts')
		{
			$CI->db->where(array('repostType' => 1, 'itemID' => $itemID));
		}
		elseif($type == 'snapshots')
		{
			$CI->db->where(array('repostType' => 2, 'itemID' => $itemID));
		}
		elseif($type == 'openletters')
		{
			$CI->db->where(array('repostType' => 3, 'itemID' => $itemID));
		}
		elseif($type == 'tv')
		{
			$CI->db->where(array('repostType' => 4, 'itemID' => $itemID));
		}
		
        $query = $CI->db->get('reposts');
        if ($query->num_rows() > 0)
		{
            return $query->num_rows();
		}
		else
		{
			return 0;
		}
	}
}

if(!function_exists('is_userConnect'))
{
	function is_userConnect($type = null, $userID = null)
	{
		$CI =& get_instance();
		if($CI->session->userdata('loggedIn'))
		{
			if($type == 'following')
			{
				$CI->db->where(array('userID' => $CI->session->userdata('userID'), 'is_following' => $userID));
				$query = $CI->db->get('followers');
			}
			elseif($type == 'pending')
			{
				$query = $CI->db->query('SELECT * FROM friendships WHERE ((fromID = "' . $CI->session->userdata('userID') . '" AND toID = "' . $userID . '") OR (fromID = "' . $userID . '" AND toID = "' . $CI->session->userdata('userID') . '")) AND status = 0');
			}
			elseif($type == 'friendship')
			{
				$query = $CI->db->query('SELECT * FROM friendships WHERE ((fromID = "' . $CI->session->userdata('userID') . '" AND toID = "' . $userID . '") OR (fromID = "' . $userID . '" AND toID = "' . $CI->session->userdata('userID') . '")) AND status = 1');
			}
			else
			{
				return false;
			}
			if ($query->num_rows() > 0)
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
}

if(!function_exists('is_userLike'))
{
	function is_userLike($type = null, $itemID = null)
	{
		$CI =& get_instance();
        $CI->db->select('*');
		if($type == 'updates')
		{
			$CI->db->where('likeType', 0);
		}
		if($type == 'posts')
		{
			$CI->db->where('likeType', 1);
		}
		elseif($type == 'snapshots')
		{
			$CI->db->where('likeType', 2);
		}
		elseif($type == 'openletters')
		{
			$CI->db->where('likeType', 3);
		}
		elseif($type == 'tv')
		{
			$CI->db->where('likeType', 4);
		}
		
		$CI->db->limit(1);
		$CI->db->where(array('itemID' => $itemID, 'like' => 1, 'userID' => $CI->session->userdata('userID')));
        $query = $CI->db->get('likes');
        if ($query->num_rows() > 0)
		{
            return true;
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('todayComments'))
{
	function todayComments()
	{
		$CI =& get_instance();
        $CI->db->select('*');
		//$CI->db->where('date', date('m/d/Y'));
        $query = $CI->db->get('comments');
        if ($query->num_rows() > 0)
		{
            return $query->num_rows();
		}
		else
		{
			return 0;
		}
	}
}

if(!function_exists('todayVisits'))
{
	function todayVisits($postID = null)
	{
		$CI =& get_instance();
        $CI->db->select('*');
		$CI->db->where('date', strtotime(date('m/d/Y')));
        $query = $CI->db->get('visitors');
        if ($query->num_rows() > 0)
		{
            return $query->num_rows();
		}
		else
		{
			return 0;
		}
	}
}

if(!function_exists('generateThumbnail'))
{
	function generateThumbnail($type = null, $imageSource = null)
	{
		$CI =& get_instance();
		$config['image_library'] 	= 'gd2';
		$config['source_image'] 	= 'uploads/' . $type . '/' . $imageSource;
		$config['new_image'] 		= 'uploads/' . $type . '/thumbs/' . $imageSource;
		$config['create_thumb'] 	= FALSE;
		$config['maintain_ratio'] 	= TRUE;
		$config['width']     		= 180;
		$config['height']   		= 180;

		$CI->load->library('image_lib', $config);
		$CI->image_lib->initialize($config);
		$CI->image_lib->resize();
		$CI->image_lib->clear();
	}
}

if(!function_exists('guessImage'))
{
	function guessImage($type = null, $slug = null) {
		$CI =& get_instance();
		
		if($type == 'posts')
		{
			if($slug)
			{
				return getFeaturedImage(getPostIDBySlug($slug));
			}
			else
			{
				return base_url('uploads/posts/placeholder.jpg');
			}
		}
		elseif($type == 'snapshots')
		{
			if($slug)
			{
				$CI->db->select('snapshotFile');
				$CI->db->where('snapshotSlug', $slug);
				$CI->db->limit(1);
				$query = $CI->db->get('snapshots');
				
				if($query->num_rows() > 0)
				{
					return base_url('uploads/snapshots/' . imageCheck('snapshots', $query->row()->snapshotFile));
				}
				else
				{
					return base_url('uploads/snapshots/placeholder.jpg');
				}
			}
			else
			{
				return base_url('uploads/snapshots/placeholder.jpg');
			}
		}
		elseif($type == 'openletters')
		{
			return base_url('uploads/openletter.jpg');
		}
		elseif($type == 'tv')
		{
			if($slug)
			{
				$CI->db->select('tvFile');
				$CI->db->where('tvSlug', $slug);
				$CI->db->limit(1);
				$query = $CI->db->get('tv');
				
				if($query->num_rows() > 0)
				{
					return base_url('uploads/tv/' . imageCheck('tv', $query->row()->tvFile));
				}
				else
				{
					return base_url('uploads/tv/placeholder.jpg');
				}
			}
			else
			{
				return base_url('uploads/snapshots/placeholder.jpg');
			}
		}
		elseif($type == 'users')
		{
			if($slug)
			{
				$CI->db->select('photo');
				$CI->db->where('userName', $slug);
				$CI->db->limit(1);
				$query = $CI->db->get('users');
				
				if($query->num_rows() > 0)
				{
					return base_url('uploads/users/' . imageCheck('users', $query->row()->photo));
				}
				else
				{
					return base_url('uploads/users/placeholder.jpg');
				}
			}
			else
			{
				return base_url('uploads/users/placeholder.jpg');
			}
		}
		else
		{
			return base_url('uploads/placeholder.jpg');
		}
	}
}

if(!function_exists('error'))
{
	function error($code = null, $type = null) {
		$CI =& get_instance();
		if($code == 404 && $type != 'ajax')
		{
			$data['meta']		= array(
				'title' 		=> phrase('page_not_found'),
				'descriptions'	=> phrase('page_not_found_descriptions'),
				'keywords'		=> 'dwitri, posts, snapshot, open letter, petition',
				'image'			=> guessImage()
			);
			
			$CI->template->set('code', 404);
			$CI->template->build('error', $data);
		}
		elseif($code == 404 && $type == 'ajax')
		{
			$data['meta']		= array(
				'title' 		=> phrase('page_not_found'),
				'descriptions'	=> phrase('page_not_found_descriptions'),
				'keywords'		=> 'dwitri, posts, snapshot, open letter, petition',
				'image'			=> guessImage()
			);
			
			$CI->template->set('code', 404);
			$CI->template->set_layout('modal');
			$CI->template->build('error', $data);
		}
		elseif($code == 403 && $type != 'ajax')
		{
			$data['meta']		= array(
				'title' 		=> phrase('access_forbidden'),
				'descriptions'	=> phrase('you_have_no_power_here'),
				'keywords'		=> 'dwitri, posts, snapshot, open letter, petition',
				'image'			=> guessImage()
			);
			
			$CI->template->set('code', 403);
			$CI->template->build('error', $data);
		}
		elseif($code == 403 && $type == 'ajax')
		{
			$data['meta']		= array(
				'title' 		=> phrase('access_forbidden'),
				'descriptions'	=> phrase('you_have_no_power_here'),
				'keywords'		=> 'dwitri, posts, snapshot, open letter, petition',
				'image'			=> guessImage()
			);
			
			$CI->template->set('code', 403);
			$CI->template->set_layout('modal');
			$CI->template->build('error', $data);
		}
		else
		{
			$data['meta']		= array(
				'title' 		=> phrase('page_not_found'),
				'descriptions'	=> phrase('page_not_found_descriptions'),
				'keywords'		=> 'dwitri, posts, snapshot, open letter, petition',
				'image'			=> guessImage()
			);
			
			$CI->template->set('code', 404);
			$CI->template->build('error', $data);
		}
	}
}

if(!function_exists('get_filenames_by_extension'))
{
	function get_filenames_by_extension($source_dir, $extensions, $include_path = FALSE, $_recursion = FALSE)
	{
		static $_filedata = array();
				
		if ($fp = @opendir($source_dir))
		{
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === FALSE)
			{
				$_filedata = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}
			
			while (FALSE !== ($file = readdir($fp)))
			{
				if (@is_dir($source_dir.$file) && strncmp($file, '.', 1) !== 0)
				{
					 get_filenames_by_extension($source_dir.$file.DIRECTORY_SEPARATOR, $extensions, $include_path, TRUE);
				}
				elseif (strncmp($file, '.', 1) !== 0)
				{
					// if this is an allowed file extension, add it to the array
					if(in_array(pathinfo($file, PATHINFO_EXTENSION), $extensions))
					{
						$_filedata[] = ($include_path == TRUE) ? $source_dir.$file : $file;
					}					
				}
			}
			return $_filedata;
		}
		else
		{
			return FALSE;
		}
	}
}