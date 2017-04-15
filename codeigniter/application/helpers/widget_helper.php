<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('widget_recentNews'))
{
	function widget_recentNews($limit = 5, $offset = 0)
	{
		$CI =& get_instance();
		$CI->db->order_by("postID", "desc");
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('posts');
		$posts = '<table class="table table-hover"><thead><tr><th>#</th><th>' . phrase('news_title') . '</th></tr></thead><tbody>';
		$n = 1;
		foreach($query->result_array() as $c):
			$posts .= '
				<tr>
					<td>' . $n++ . '</td>
					<td><a href="' . base_url('posts/' . $c['postSlug']) . '" target="_blank">' . htmlspecialchars($c['postTitle']) . '</a></td>
				</tr>
			';
		endforeach;
		$posts .= '</tbody></table>';
		return $posts;
	}
}

if ( ! function_exists('widget_sidebarNews'))
{
	function widget_sidebarNews($limit = 5, $offset = 0)
	{
		$CI =& get_instance();
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->order_by("timestamp", "desc");
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('posts');
		
		$posts = '<ul style="list-style:none;padding-left:0">';
		
		foreach($query->result_array() as $c)
		{
			$posts .= '<li><h5><a href="' . base_url('posts/' . $c['postSlug']) . '" class="ajaxLoad">'.truncate($c['postTitle'], 60).'</a></h5></li>';
		};
		
		$posts .= '</ul>';
		return $posts;
	}
}

if ( ! function_exists('widget_mostViewNews'))
{
	function widget_mostViewNews($limit = 5, $offset = 0)
	{
		$CI =& get_instance();
		$CI->db->order_by("visits_count", "desc");
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('posts');
		
		$posts = '<ul style="list-style:none;padding-left:0;margin:0">';
		
		foreach($query->result_array() as $c)
		{
			$posts .= '
				<li>
					<hr />
					<a href="' . base_url('posts/' . $c['postSlug']) . '" class="ajaxLoad">'.truncate($c['postTitle'], 60).'</a>
					<div class="row nopadding nomargin">
						<div class="col-xs-4 nopadding nomargin text-center">
							<span class="badge"><i class="fa fa-eye"></i> &nbsp; ' . $c['visits_count'] . '</span>
						</div>
						<div class="col-xs-4 nopadding nomargin text-center">
							<span class="badge"><i class="fa fa-comments"></i> &nbsp; ' . countComments('posts', $c['postID']) . '</span>
						</div>
						<div class="col-xs-4 nopadding nomargin text-center">
							<span class="badge"><i class="fa fa-thumbs-up"></i> &nbsp; ' . countLikes('posts', $c['postID']) . '</span>
						</div>
					</div>
				</li>
			';
		};
		
		$posts .= '</ul>';
		return $posts;
	}
}

if ( ! function_exists('widget_topCommentNews'))
{
	function widget_topCommentNews($limit = 5, $offset = 0)
	{
		$CI =& get_instance();
		$CI->db->join('posts', 'posts.postID = comments.postID', 'left');
		$CI->db->where('comments.commentType', 1);
		$CI->db->order_by('COUNT(comments.*)', 'desc');
		$CI->db->group_by('comments.postID');
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('comments');
		
		$posts = '<ul style="list-style:none;padding-left:0;margin:0">';
		
		foreach($query->result_array() as $c)
		{
			$posts .= '<li><h5><a href="' . base_url('posts/' . $c['postSlug']) . '" class="ajaxLoad">'.truncate($c['postTitle'], 60).'</a></h5></li>';
		};
		
		$posts .= '</ul>';
		return $posts;
	}
}

if ( ! function_exists('widget_mostCommentNews'))
{
	function widget_mostCommentNews($limit = 5, $offset = 0)
	{
		$CI =& get_instance();
		$CI->db->from('comments');
		$CI->db->join('posts', 'posts.postID = comments.itemID', 'left');
		$CI->db->where('comments.commentType', 1);
		$CI->db->order_by('comments.commentID', 'DESC');
		$CI->db->group_by('comments.itemID');
		$CI->db->order_by('FIELD(posts.language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get();
		
		$posts = '<ul style="list-style:none;padding-left:0;margin:0">';
		
		foreach($query->result_array() as $c)
		{
			$posts .= '
				<li>
					<hr />
					<a href="' . base_url('posts/' . $c['postSlug']) . '" class="ajaxLoad">'.truncate($c['postTitle'], 60).'</a>
					<div class="row nopadding nomargin">
						<div class="col-xs-4 nopadding nomargin text-center">
							<span class="badge"><i class="fa fa-comments"></i> &nbsp; ' . countComments('posts', $c['itemID']) . '</span>
						</div>
						<div class="col-xs-4 nopadding nomargin text-center">
							<span class="badge"><i class="fa fa-thumbs-up"></i> &nbsp; ' . countLikes('posts', $c['itemID']) . '</span>
						</div>
						<div class="col-xs-4 nopadding nomargin text-center">
							<span class="badge"><i class="fa fa-eye"></i> &nbsp; ' . $c['visits_count'] . '</span>
						</div>
					</div>
				</li>
			';
		};
		
		$posts .= '</ul>';
		return $posts;
	}
}

if ( ! function_exists('widget_newOpenletters'))
{
	function widget_newOpenletters($limit = 5, $offset = 0)
	{
		$CI =& get_instance();
		$CI->db->order_by("letterID", "desc");
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('openletters');
		
		$posts = '<ul style="list-style:none;padding-left:0;margin:0">';
		
		foreach($query->result_array() as $c)
		{
			$posts .= '
				<li>
					<hr />
					<div class="row">
						<div class="col-xs-3 col-sm-2 nomargin">
							<a href="' . base_url(getUsernameByID($c['contributor'])) . '" class="ajaxLoad hoverCard">
								<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($c['contributor']), 1)) . '" class="img-responsive img-rounded img-bordered" alt="" style="max-height:80px" />
							</a>
						</div>
						<div class="col-xs-9 col-sm-10 nomargin">
							<a href="' . base_url('openletters/' . $c['slug']) . '" title="' . $c['title'] . '" class="ajaxLoad"><b>'.truncate($c['title'], 32).'</b></a>
							<br />
							<small>
								' . phrase('by') . ' <a href="' . base_url(getUsernameByID($c['contributor'])) . '" class="ajaxLoad hoverCard">' . getFullnameByID($c['contributor']) . '</a>
								<span class="hidden-xs">
									&nbsp; / &nbsp; 
									<i class="fa fa-eye"></i> ' . $c['visits_count'] . ' - <i class="fa fa-comments"></i> ' . countComments('openletters', $c['letterID']) . ' - <i class="fa fa-thumbs-up"></i> ' . countLikes('openletters', $c['letterID']) . '
								</span>
							</small>
						</div>
				</li>
			';
		};
		
		$posts .= '</ul>';
		return $posts;
	}
}

if ( ! function_exists('widget_activePosts'))
{
	function widget_activePosts($limit = 5, $offset = 0, $except = null)
	{
		$CI =& get_instance();
		if($except)
		{
			$CI->db->where('postID != ' . $except);
		}
		$CI->db->order_by("rand()");
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('posts');
		
		$posts = '<ul style="list-style:none;padding-left:0">';
		
		foreach($query->result_array() as $c)
		{
			$posts .= '<li><h5><a href="' . base_url('posts/' . $c['postSlug']) . '" class="ajaxLoad">'.truncate($c['postTitle'], 60).'</a></h5></li>';
		};
		
		$posts .= '</ul>';
		return $posts;
	}
}

if ( ! function_exists('widget_activeSnapshots'))
{
	function widget_activeSnapshots($limit = 4, $offset = 0, $except = null)
	{
		$CI =& get_instance();
		if($except)
		{
			$CI->db->where('snapshotID != ' . $except);
		}
		$CI->db->order_by("rand()");
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('snapshots');
		
		$posts = '<div class="row">';
		
		foreach($query->result_array() as $c)
		{
			$posts .= '
				<div class="col-sm-3">
					<a href="' . base_url('snapshots/' . $c['snapshotSlug']) . '" class="ajax">
						<img src="' . base_url('uploads/snapshots/thumbs/' . imageCheck('snapshots', $c['snapshotFile'], 1)) . '" class="img-responsive img-thumbnail" width="100%" style="max-height:100px" />
					</a>
				</div>
			';
		};
		
		$posts .= '</div>';
		return $posts;
	}
}

if ( ! function_exists('widget_activeOpenletters'))
{
	function widget_activeOpenletters($limit = 5, $offset = 0, $except = null)
	{
		$CI =& get_instance();
		if($except)
		{
			$CI->db->where('letterID != ' . $except);
		}
		$CI->db->order_by("rand()");
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('openletters');
		
		$posts = '<ul style="list-style:none;padding-left:0">';
		
		foreach($query->result_array() as $c)
		{
			$posts .= '<li><h5><a href="' . base_url('openletters/' . $c['slug']) . '" class="ajaxLoad">'.truncate($c['title'], 60).'</a></h5></li>';
		};
		
		$posts .= '</ul>';
		return $posts;
	}
}

if ( ! function_exists('widget_activeTV'))
{
	function widget_activeTV($limit = 4, $offset = 0, $except = null, $grid = 'col-sm-3')
	{
		$CI =& get_instance();
		if($except)
		{
			$CI->db->where('tvID != ' . $except);
		}
		$CI->db->order_by("rand()");
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('tv');
		
		$posts = '<div class="row">';
		
		foreach($query->result_array() as $c)
		{
			$posts .= '
				<div class="' . $grid . '">
					<a href="' . base_url('tv/' . $c['tvSlug']) . '" class="ajax">
						<img src="' . base_url('uploads/tv/thumbs/' . imageCheck('tv', $c['tvFile'], 1)) . '" class="img-responsive img-thumbnail" width="100%" />
					</a>
				</div>
			';
		};
		
		$posts .= '</div>';
		return $posts;
	}
}

if ( ! function_exists('widget_updateStream'))
{
	function widget_updateStream($limit = 6, $offset = 0)
	{
		$CI =& get_instance();
		$CI->db->where(array('visibility' => 0, 'updateContent !=' => ''));
		$CI->db->order_by("timestamp", "desc");
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('updates');
		if($query->num_rows() > 0)
		{
			$posts = '<div id="updates" class="carousel slide rounded" data-ride="carousel"><div class="carousel-inner" role="listbox">';
			
			$s = 0;
			foreach($query->result_array() as $c)
			{
				$posts .= '
					<div class="item' . ($s == 0 ? ' active' : '') . '">
						<div class="row carousel-content">
							<div class="col-xs-3 col-sm-2">
								<a href="' . base_url('/' . getUsernameByID($c['userID'])) . '" class="ajaxLoad">
									<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($c['userID']), 1)) . '" class="img-responsive img-rounded img-bordered" alt="" style="max-height:80px" />
								</a>
							</div>
							<div class="col-xs-9 col-sm-10">
								<a href="' . base_url('/' . getUsernameByID($c['userID'])) . '" class="ajaxLoad"><b>' . getFullnameByID($c['userID']) . '</b></a>
								<br />
								<small class="text-muted">@' . getUsernameByID($c['userID']) . '</small>
								<small class="pull-right">' . time_since($c['timestamp']) . '</small>
								<br />
								<p>
									' . nl2br(special_parse($c['updateContent'])) . '
								</p>
							</div>
						</div>
					</div>
				';
				
				$s++;
			};
			
			$posts .= "</div></div>"."\r\n";
			return $posts;
		}
		else
		{
			return null;
		}
	}
}

if ( ! function_exists('widget_topContributors'))
{
	function widget_topContributors($limit = 10, $offset = 0)
	{
		$CI =& get_instance();
		$CI->db->join('posts', 'posts.contributor = users.userID', 'left');
		$CI->db->join('snapshots', 'snapshots.contributor = users.userID', 'left');
		$CI->db->join('openletters', 'openletters.contributor = users.userID', 'left');
		$CI->db->join('tv', 'tv.contributor = users.userID', 'left');
		$CI->db->where('posts.contributor !=', null);
		$CI->db->or_where('snapshots.contributor !=', null);
		$CI->db->order_by('COUNT(posts.contributor), COUNT(snapshots.contributor), COUNT(openletters.contributor), COUNT(tv.contributor)', 'desc');
		$CI->db->group_by('users.userID');
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('users');
		
		$posts = '';
		
		foreach($query->result_array() as $c)
		{
			$posts .= '
				<div class="row">
					<div class="col-xs-3 nomargin">
						<a href="' . base_url('/' . getUsernameByID($c['userID'])) . '" class="ajaxLoad">
							<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($c['userID']), 1)) . '" class="img-responsive img-rounded img-bordered" alt="" style="max-height:80px" />
						</a>
					</div>
					<div class="col-xs-9 nomargin">
						<a href="' . base_url($c['userName']).'" class="ajaxLoad hoverCard"><b>'.truncate($c['full_name'], 12).'</b></a>
						<br />
						<small><i class="fa fa-newspaper-o"></i> ' . (countPosts('posts', $c['userID']) + countPosts('snapshots', $c['userID'])) . ' / <i class="fa fa-users"></i> ' . getUserFollowers('followers', $c['userID']) . '</small>
					</div>
				</div>
			';
		};
		
		return $posts;
	}
}

if ( ! function_exists('widget_lastUsers'))
{
	function widget_lastUsers($limit = 10, $offset = 0)
	{
		$CI =& get_instance();
		$CI->db->join('posts', 'posts.contributor = users.userID', 'left');
		$CI->db->join('snapshots', 'snapshots.contributor = users.userID', 'left');
		$CI->db->order_by('users.userID', 'DESC');
		$CI->db->group_by('users.userID');
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('users');
		
		$posts = '';
		
		foreach($query->result_array() as $c)
		{
			$posts .= '
				<hr />
				<div class="row">
					<div class="col-xs-3 col-sm-2 nomargin">
						<a href="' . base_url('/' . getUsernameByID($c['userID'])) . '" class="ajaxLoad">
							<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($c['userID']), 1)) . '" class="img-responsive img-rounded img-bordered" alt="" style="max-height:80px" />
						</a>
					</div>
					<div class="col-xs-9 col-sm-10 nomargin">
						<a href="' . base_url($c['userName']).'" class="ajaxLoad hoverCard"><b>'.truncate($c['full_name'], 60).'</b></a>
						<br />
						<small><i class="fa fa-newspaper-o"></i> ' . (countPosts('posts', $c['userID']) + countPosts('snapshots', $c['userID'])) . ' / <i class="fa fa-users"></i> ' . getUserFollowers('followers', $c['userID']) . '</small>
					</div>
				</div>
			';
		};
		
		return $posts;
	}
}

if ( ! function_exists('widget_sidebarCategory'))
{
	function widget_sidebarCategory()
	{
		$CI =& get_instance();
		$CI->db->order_by("categoryTitle", "asc");
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$query = $CI->db->get('posts_category');
		$categories = '<div class="list-group">';
		
		foreach($query->result_array() as $c)
		{
			$CI->db->like('categoryID', '"' . $c['categoryID'] . '"');
			$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
			$query = $CI->db->get('posts');
			$totPosts = $query->num_rows();
			if ($totPosts > 0)
			{
				$categories .= '<a href="' . base_url('posts/' . $c['categorySlug']) . '" class="ajaxLoad list-group-item' . (($CI->uri->segment(2) == $c['categorySlug']) ? ' active' : '') . '">' . truncate(htmlspecialchars($c['categoryTitle']), 18) . ' &nbsp; <span class="badge">' . $totPosts . '</span></a>';
			}
		}
		
		$categories .= "</div>";
		return $categories;
	}
}

if ( ! function_exists('widget_categoryNav'))
{
	function widget_categoryNav()
	{
		$CI =& get_instance();
		$CI->db->order_by("categoryTitle", "asc");
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$query = $CI->db->get('posts_category');
		$categories = '';
		
		foreach($query->result_array() as $c)
		{
			$CI->db->like('categoryID', '"' . $c['categoryID'] . '"');
			$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
			$query = $CI->db->get('posts');
			$totPosts = $query->num_rows();
			if ($totPosts > 0)
			{
				$categories .= '<li><a href="' . base_url('posts/' . $c['categorySlug']) . '"' . (($CI->uri->segment(2) == $c['categorySlug']) ? ' class="active ajaxLoad"' : 'class="ajaxLoad"') . '><span class="badge pull-right">'.$totPosts.'</span> &nbsp; '.htmlspecialchars($c['categoryTitle']).'</a></li>';
			}
		}
		return $categories;
	}
}

if ( ! function_exists('widget_LatestNews'))
{
	function widget_LatestNews($limit=10,$offset=0)
	{
		$CI =& get_instance();
		$CI->db->order_by('timestamp', 'DESC');
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('posts');
		$posts = '';
		
		foreach($query->result_array() as $c):
			$posts .= '
				<hr />
				<div class="row">
					<div class="col-xs-2">
						<a href="' . base_url('posts/' . $c['postSlug']) . '" class="ajaxLoad"><img class="img-responsive" src="' . base_url() . 'uploads/'.getFeaturedImage($c['postID']).'" alt="'.truncate($c['postTitle']).'"/></a>
					</div>
					<div class="col-xs-10">
						<b style="margin-bottom:0"><a href="' . base_url('posts/' . $c['postSlug']) . '" class="ajaxLoad">'.truncate($c['postTitle'], 40).'</a></b>
						<p class="meta">'.time_since($c['timestamp']).'</p>
						<p>'.truncate($c['postExcerpt'], 80).'</p>
					</div>
				</div>
			';
		endforeach;
		
		return $posts;
	}
}

if ( ! function_exists('widget_latestComments'))
{
	function widget_latestComments($limit = 5, $offset = 0)
	{
		$CI =& get_instance();
		$CI->db->order_by('commentID', 'desc');
		$CI->db->limit($limit, $offset);
		$query = $CI->db->get('comments');
		$posts = '<table class="table table-hover"><thead><tr><th>#</th><th>' . phrase('user') . '</th><th>' . phrase('comments') . '</th><th>' . phrase('act') . '</th></tr></thead><tbody>';
		$n = 1;
		foreach($query->result_array() as $c):
			$posts .= '
				<tr>
					<td>' . $n++ . '</td>
					<td>' . htmlspecialchars(getUsernameByID($c['userID'])) . '</td>
					<td>' . truncate($c['comments'], 60) . '</td>
					<td><a href="' . base_url('posts/' . articleLinkByID($c['postID']) . '#comment' . $c['timestamp']) . '" target="_blank">' . phrase('show') . '</a></td>
				</tr>
			';
		endforeach;
		$posts .= '</tbody></table>';
		return $posts;
	}
}

if ( ! function_exists('widget_timeLine'))
{
	function widget_timeLine($userName = null)
	{
		$timeline		= '<ul class="list">';
		for ($i=0; $i<=11; $i++)
		{ 
			$timeline	.= '<li><a href="' . base_url($userName . '/' . strtotime("-$i month") . '/0') . '" class="ajaxLoad"><i class="fa fa-clock-o"></i> ' . phrase(strtolower(date('F', strtotime("-$i month")))) . '</a></li>';
		}
		$timeline		.= '</ul>';
		return $timeline;
	}
}

if ( ! function_exists('widget_hashTags'))
{
	function widget_hashTags($list = false, $limit = 10)
	{
		$CI =& get_instance();
		
		$CI->db->limit($limit);
		$CI->db->where('LENGTH(query) > 4', null, false);
		$CI->db->order_by('count', 'desc');
		$CI->db->order_by('FIELD(language, "' . $CI->session->userdata('language') . '") DESC', FALSE);
		$query = $CI->db->get('search');
		if($query->num_rows() > 0)
		{
			$hashtag = ($list ? '<ul style="list-style:none;padding-left:0;margin:0">' : '');
			foreach($query->result_array() as $row)
			{
				$hashtag .= ($list ? '<li><h4>' : '') . '<a href="' . base_url('search/' . format_uri($row['query'])) . '" class="ajaxLoad">#' . $row['query'] . '</a>' . ($list ? '</h4></li>' : ' - ');
			}
			$hashtag .= ($list ? '</ul>' : '');
			
			return $hashtag;
		}
		else
		{
			return false;
		}
	}
}

if ( ! function_exists('widget_randomAds'))
{
	function widget_randomAds($type = null)
	{
		return '
			<div class="row">
				<div class="col-sm-12">
					<div class="adsHolder text-center bordered rounded">
					</div>
				</div>
			</div>
		';
	}
}