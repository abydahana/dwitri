<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('special_parse'))
{
	function special_parse($string = '')
	{
		$hash_regex		= '/#+([a-zA-Z0-9_]+)/';
		$mention_regex	= '/@+([a-zA-Z0-9_.]+)/';
		$img_regex		= '/<img.*src="(.*?)".*?>/';
		
		$string			= preg_replace($hash_regex, '<a href="' . base_url('search/$1') . '" class="ajaxLoad">$0</a>', $string);
		$match			= preg_match_all($mention_regex, $string, $callback);

		if(count($callback[1]) > 0)
		{
			$unique_callback = array_unique($callback[1]);
			for($i=0;$i<=count($unique_callback)-1;$i++)
			{
				if(isset($unique_callback[$i]) && $unique_callback[$i] != '')
				{
					if(checkUsername($unique_callback[$i]))
					{
						$string = str_replace('@'.$unique_callback[$i], '<a href="' . base_url($unique_callback[$i]) . '" class="ajaxLoad hoverCard">@' . $unique_callback[$i] . '</a>', $string);
					}
				}
			}
		}
		/*
		if(preg_match_all($img_regex, $string, $url))
		{
			if(count($url[1]) > 0)
			{
				$img = array_unique($url[1]);
				for($i=0;$i<=count($img)-1;$i++)
				{
					$string = preg_replace($img_regex, '<a href="' . $img[$i] . '" target="_blank"><img src="' . $img[$i] . '" /></a>', $string);
				}
			}
			else
			{
				$string = preg_replace($img_regex, '<img src="' . base_url('uploads/posts/placeholder.jpg') . '" />', $string);
			}
		}
		*/
		
		$filter	= array(
			':angry'			=> 'angry',
			':astonished'		=> 'astonished',
			':baby'				=> 'baby',
			':balloon'			=> 'balloon',
			':bank'				=> 'bank',
			':baseball'			=> 'baseball',
			':basketball'		=> 'basketball',
			':beers'			=> 'beers',
			':beer'				=> 'beer',
			':bell'				=> 'bell',
			':bike'				=> 'bike',
			':blush'			=> 'blush',
			':boar'				=> 'boar',
			':bomb'				=> 'bomb',
			':book'				=> 'book',
			':boot'				=> 'boot',
			':bow'				=> 'bow',
			':boy'				=> 'boy',
			':bread'			=> 'bread',
			':brokenheart'		=> 'broken_heart',
			':bulb'				=> 'bulb',
			':bus'				=> 'bus',
			':camera'			=> 'camera',
			':car'				=> 'car',
			':cloud'			=> 'cloud',
			':cocktail'			=> 'cocktail',
			':computer'			=> 'computer',
			':confounded'		=> 'confounded',
			':cop'				=> 'cop',
			':couplekiss'		=> 'couplekiss',
			':couple'			=> 'couple',
			':cow'				=> 'cow',
			':crazy'			=> 'wink2',
			':cry'				=> 'cry',
			':dancers'			=> 'dancers',
			':dancer'			=> 'dancer',
			':dart'				=> 'dart',
			':disappointed'		=> 'disappointed',
			':dress'			=> 'dress',
			':grass'			=> 'ear_of_rice',
			':egg'				=> 'egg',
			':terong'			=> 'eggplant',
			':eyes'				=> 'eyes',
			':fear'				=> 'fearful',
			':fire'				=> 'fire',
			':fish'				=> 'fish',
			':fist'				=> 'fist',
			':flushed'			=> 'flushed',
			':eat'				=> 'fork_and_knife',
			':fries'			=> 'fries',
			':frog'				=> 'frog',
			':fuel'				=> 'fuelpump',
			':gem'				=> 'gem',
			':girl'				=> 'girl',
			':grin'				=> 'grin',
			':guitar'			=> 'guitar',
			':gun'				=> 'gun',
			':burger'			=> 'hamburger',
			':hand'				=> 'hand',
			':hearteyes'		=> 'heart_eyes',
			':heart'			=> 'heart',
			':love'				=> 'heart',
			':highheel'			=> 'high_heel',
			':horse'			=> 'horse',
			':hospital'			=> 'hospital',
			':hotel'			=> 'hotel',
			':house'			=> 'house',
			':icecream'			=> 'icecream',
			':imp'				=> 'imp',
			':joy'				=> 'joy',
			':kecup'			=> 'kissin_face',
			':kissingheart'		=> 'kissing_heart',
			':kiss'				=> 'kiss',
			':koala'			=> 'koala',
			':lipstick'			=> 'lipstick',
			':lips'				=> 'lips',
			':lock'				=> 'lock',
			':man'				=> 'man',
			':mask'				=> 'mask',
			':massage'			=> 'massage',
			':mega'				=> 'mega',
			':metal'			=> 'metal',
			':money'			=> 'moneybag',
			':monkey'			=> 'monkey',
			':moon'				=> 'moon',
			':mouse'			=> 'mouse',
			':muscle'			=> 'muscle',
			':new'				=> 'new',
			':stop'				=> 'no_good',
			':music'			=> 'notes',
			':ok'				=> 'ok',
			':oldman'			=> 'older_man',
			':oldwoman'			=> 'older_woman',
			':pensive'			=> 'pensive',
			':pig'				=> 'pig',
			':pill'				=> 'pill',
			':police'			=> 'police_car',
			':poop'				=> 'poop',
			':pray'				=> 'pray',
			':princess'			=> 'princess',
			':punch'			=> 'punch',
			':question'			=> 'question',
			':rabbit'			=> 'rabbit',
			':radio'			=> 'radio',
			':rage'				=> 'rage',
			':ramen'			=> 'ramen',
			':relax'			=> 'relaxed',
			':rice'				=> 'rice',
			':ring'				=> 'ring',
			':rocket'			=> 'rocket',
			':rose'				=> 'rose',
			':run'				=> 'runner',
			':satisfied'		=> 'satisfied',
			':scream'			=> 'scream',
			':teler'			=> 'shaved_ice',
			':sheep'			=> 'sheep',
			':shell'			=> 'shell',
			':shirt'			=> 'shirt',
			':shit'				=> 'shit',
			':shoe'				=> 'shoe',
			':skull'			=> 'skull',
			':sleepy'			=> 'sleepy',
			':smile'			=> 'smile',
			':smirk'			=> 'smirk',
			':smoking'			=> 'smoking',
			':snake'			=> 'snake',
			':sob'				=> 'sob',
			':soccer'			=> 'soccer',
			':spaghetti'		=> 'spaghetti',
			':speaker'			=> 'speaker',
			':star'				=> 'star',
			':station'			=> 'station',
			':stew'				=> 'stew',
			':strawberry'		=> 'strawberry',
			':sunny'			=> 'sunny',
			':sweat'			=> 'sweat',
			':syringe'			=> 'syringe',
			':taxi'				=> 'taxi',
			':tea'				=> 'tea',
			':telephone'		=> 'telephone',
			':tennis'			=> 'tennis',
			':thumbsdown'		=> 'thumbsdown',
			':thumbsup'			=> 'thumbsup',
			':tiger'			=> 'tiger',
			':toilet'			=> 'toilet',
			':tongue'			=> 'tongue',
			':trollface'		=> 'trollface',
			':trophy'			=> 'trophy',
			':tshirt'			=> 'tshirt',
			':umbrella'			=> 'umbrella',
			':unamused'			=> 'unamused',
			':unlock'			=> 'unlock',
			':warning'			=> 'warning',
			':watermelon'		=> 'watermelon',
			':wc'				=> 'wc',
			':wink'				=> 'wink',
			':woman'			=> 'woman',
			':zap'				=> 'zap',
			':zzz'				=> 'zzz',
			':p'				=> 'wink2',
			':D'				=> 'smile',
			':)'				=> 'smile',
			':('				=> 'disappointed',
			':\'('				=> 'cry',
			'T_T'				=> 'sob',
			'T-T'				=> 'sob',
			':o'				=> 'astonished',
			';)'				=> 'wink',
			':v'				=> 'trollface',
			'(y)'				=> 'thumbsup'
		);
		foreach($filter as $key => $emoji)
		{
			$string = str_replace($key, '<span class="em em-' . $emoji . '" title="' . str_replace(':', '', $key) . '"></span>', $string);
		}
		return $string;
	}
}

if(!function_exists('cath_user'))
{
	function cath_user($string = null)
	{
		if(preg_match_all('/@([A-Za-z0-9\/\.]*)/', $string, $match))
		{
			$arr = array();
			foreach($match as $key => $user)
			{
				$arr = $user;
			}
			return $arr;
		}
	}
}

if(!function_exists('parsingStatusByID'))
{
    function parsingStatusByID($userID = 0, $action = null, $itemID = 0, $actionID = null, $timestamp = null)
    {
		$CI =& get_instance();
		$CI->db->select('users.userID, users.userName, users.full_name, updates.updateID, updates.updateContent, updates.timestamp')
		->join('users', 'users.userID = updates.userID')
		->where('updates.updateID', $itemID)
        ->limit(1);
        $query = $CI->db->get('updates');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $row)
			{
				if($action == 'submitting')
				{
					$actions = phrase('updating_status') . ' ' . ($row['userID'] == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/updates/' . $row['updateID']) . '\', \'updates' . $row['updateID'] . '\', \'updates' . $row['updateID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('delete_this_updates') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/updates/' . $row['updateID']) . '\', \'updates' . $row['updateID'] . '\', \'updates' . $row['updateID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa fa-times"></i></a>');
				}
				elseif($action == 'commenting')
				{
					$actions = ($row['userID'] != $userID ? phrase('commenting_user_updates', array($row['userName'], $row['full_name'])) : phrase('commenting_own_updates')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/updates/' . $actionID) . '\', \'comment' . $row['updateID'] . '\', \'updates' . $row['updateID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('remove_comment') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/comments/' . $actionID) . '\', \'updates' . $row['updateID'] . '\', \'updates' . $row['updateID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				elseif($action == 'liking')
				{
					$actions = ($row['userID'] != $userID ? phrase('liking_user_updates', array($row['userName'], $row['full_name'])) : phrase('liking_own_updates')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/likes/' . $actionID) . '\', \'updates' . $row['updateID'] . '\', \'updates' . $row['updateID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('remove_comment') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/updates/' . $row['updateID']) . '\', \'updates' . $row['updateID'] . '\', \'updates' . $row['updateID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				elseif($action == 'reposting')
				{
					$actions = ($row['userID'] != $userID ? phrase('reposting_user_updates', array($row['userName'], $row['full_name'])) : phrase('reposting_own_updates')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/reposts/' . $actionID) . '\', \'updates' . $row['updateID'] . '\', \'updates' . $row['updateID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('hide_this_feed') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/reposts/' . $actionID) . '\', \'updates' . $row['updateID'] . '\', \'updates' . $row['updateID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				else
				{
					$actions = null;
				}

				return '
					<div id="updates' . $itemID . '">
						<div class="image-placeholder">
							<div class="col-sm-12 nomargin">
								<div class="row">
									<div class="col-xs-2 col-sm-1">
										<a href="' . base_url(($row['userID'] == $userID ? $row['userName'] : getUsernameByID($userID))) . '" class="ajaxLoad hoverCard">
											<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', ($row['userID'] == $userID ? getUserPhoto($row['userID'], 1) : getUserPhoto($userID, 1)))) . '" style="height:40px;width:40px" class="img-rounded img-bordered" alt="" />
										</a>
									</div>
									<div class="col-xs-10 col-sm-11">
										<a href="' . base_url(($row['userID'] == $userID ? $row['userName'] : getUsernameByID($userID))) . '" class="ajaxLoad hoverCard">
											<b>' . ($row['userID'] == $userID ? $row['full_name'] : getFullnameByID($userID)) . '</b> 
										</a>
										' . $actions . ' 
										<br />
										<small class="text-muted">@' . ($row['userID'] == $userID ? $row['userName'] : getUsernameByID($userID)) . ' - ' . time_since(($timestamp ? $timestamp : $row['timestamp'])) . '</small>
									</div>
								</div>
								' . (($actionID && $action == 'commenting' || $actionID && $action == 'reposting') && parsingActionByID($actionID, $action) != null ? '
								<div class="row">
									<div class="col-sm-12">
										<blockquote style="margin-bottom:10px">
											' . truncate(parsingActionByID($actionID, $action), 60) . '
										</blockquote>
									</div>
								</div>
								' : ''
								) . '
								<div class="row">
									<div class="col-sm-12">
										<p>
											' . special_parse($row['updateContent']) . '
										</p>
										<div class="btn-group btn-group-justified">
											<a href="javascript:void(0)" class="btn btn-default ajax"><i class="fa fa-comments"></i> <span class="comments-count-updates' . $row['updateID'] . '">' . countComments('updates', $itemID) . '</span><span class="hidden-xs"> ' . phrase('comments') . '</span></a>
											<a class="like like-updates' . $itemID . ' btn btn-default' . (is_userLike('updates', $itemID) ? ' active' : '') . '" href="' . base_url('user/like/updates/' . $itemID) . '" data-id="updates' . $itemID . '"><i class="like-icon fa fa-thumbs-up"></i> <span class="likes-count">' . countLikes('updates', $itemID) . '</span><span class="hidden-xs"> ' . phrase('likes') . '</span></a>
											<a href="' . base_url('user/repost/updates/' . $itemID) . '" class="btn btn-default repost" data-id="' . $itemID . '"><i class="fa fa-retweet"></i> <span class="reposts-count' . $itemID . '">' . countReposts('updates', $itemID) . '</span><span class="hidden-xs"> ' . phrase('reposts') . '</span></a>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 nomargin">
											
										' . getComments('updates', $row['updateID']) . '
											
									</div>
								</div>
							</div>
						</div>
					</div>
				';
			}
		}
		else
		{
			return false;
		}
    }
}

if(!function_exists('parsingPostByID'))
{
    function parsingPostByID($userID = 0, $action = null, $itemID = 0, $actionID = null, $timestamp = null)
    {
		$CI =& get_instance();
		$CI->db->select('users.userID, users.userName, users.full_name, posts.postID, posts.categoryID, posts.postSlug, posts.postTitle, posts.postExcerpt, posts.tags, posts.visits_count, posts.timestamp')
		->join('users', 'users.userID = posts.contributor')
		->where('posts.postID', $itemID)
		->limit(1);
        $query = $CI->db->get('posts');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $row)
			{
				$post_tag = '';
				if($row['tags'] != '')
				{
					$tags = explode(',', $row['tags']);
					foreach($tags as $tag)
					{
						$post_tag = '<a href="' . base_url('search/' . $tag) . '" class="ajaxLoad"><span class="badge"><i class="fa fa-tag"></i> ' . truncate($tag, 12) . '</span></a> ';
					}
				}
				else
				{
					foreach(json_decode($row['categoryID']) as $key => $val)
					{
						$post_tag = '<a href="' . base_url('category/' . getCategorySlugByID($val)) . '" class="ajaxLoad"><span class="badge"><i class="fa fa-tag"></i> ' . getCategoryByID($val) . '</span></a> ';
					}
				}
				
				if($action == 'submitting')
				{
					$actions = phrase('writing_an_article') . ' ' . ($row['userID'] == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/posts/remove/' . $row['postID']) . '\', \'posts' . $row['postID'] . '\', \'posts' . $row['postID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('delete_this_updates') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/posts/' . $row['postID']) . '\', \'posts' . $row['postID'] . '\', \'posts' . $row['postID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa fa-times"></i></a>');
				}
				elseif($action == 'commenting')
				{
					$actions = ($row['userID'] != $userID ? phrase('commenting_user_post', array($row['userName'], $row['full_name'])) : phrase('commenting_own_post')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/posts/' . $actionID) . '\', \'posts' . $row['postID'] . '\', \'posts' . $row['postID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('remove_comment') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/comments/' . $actionID) . '\', \'posts' . $row['postID'] . '\', \'posts' . $row['postID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				elseif($action == 'liking')
				{
					$actions = ($row['userID'] != $userID ? phrase('liking_user_post', array($row['userName'], $row['full_name'])) : phrase('liking_own_updates')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/likes/' . $actionID) . '\', \'posts' . $row['postID'] . '\', \'posts' . $row['postID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('remove_comment') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/posts/' . $row['postID']) . '\', \'posts' . $row['postID'] . '\', \'posts' . $row['postID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				elseif($action == 'reposting')
				{
					$actions = ($row['userID'] != $userID ? phrase('reposting_user_post', array($row['userName'], $row['full_name'])) : phrase('reposting_own_updates')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/reposts/' . $actionID) . '\', \'posts' . $row['postID'] . '\', \'posts' . $row['postID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('hide_this_feed') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/reposts/' . $actionID) . '\', \'posts' . $row['postID'] . '\', \'posts' . $row['postID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				else
				{
					$actions = null;
				}
				
				return '
					<div id="posts' . $itemID . '">
						<div class="image-placeholder">
							<div class="col-sm-12 nomargin">
								<div class="row">
									<div class="col-xs-2 col-sm-1">
										<a href="' . base_url(($row['userID'] == $userID ? $row['userName'] : getUsernameByID($userID))) . '" class="ajaxLoad hoverCard">
											<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', ($row['userID'] == $userID ? getUserPhoto($row['userID'], 1) : getUserPhoto($userID, 1)))) . '" style="height:40px;width:40px" class="img-rounded img-bordered" alt="" />
										</a>
									</div>
									<div class="col-xs-10 col-sm-11">
										<a href="' . base_url(($row['userID'] == $userID ? $row['userName'] : getUsernameByID($userID))) . '" class="ajaxLoad hoverCard">
											<b>' . ($row['userID'] == $userID ? $row['full_name'] : getFullnameByID($userID)) . '</b> 
										</a>
										' . $actions . ' 
										<br />
										<small class="text-muted">@' . ($row['userID'] == $userID ? $row['userName'] : getUsernameByID($userID)) . ' - ' . time_since(($timestamp ? $timestamp : $row['timestamp'])) . '</small>
									</div>
								</div>
								' . (($actionID && $action == 'commenting' || $actionID && $action == 'reposting') && parsingActionByID($actionID, $action) != null ? '
								<div class="row">
									<div class="col-sm-12">
										<blockquote style="margin-bottom:10px">
											' . truncate(parsingActionByID($actionID, $action), 60) . '
										</blockquote>
									</div>
								</div>
								' : ''
								) . '
								<div class="row">
									<div class="col-xs-3 col-sm-4 nomargin">
										<a href="' . base_url('posts/' . $row['postSlug']) . '" class="ajaxLoad"><img width="100%" class="img-responsive img-rounded" src="' . getFeaturedImage($row['postID'], 1) . '" alt="'.truncate($row['postTitle'], 80).'"/></a>
									</div>
									<div class="col-xs-9 col-sm-8 nomargin">
										<a href="' . base_url('posts/' . $row['postSlug']) . '" class="ajaxLoad"><h4>'.truncate($row['postTitle'], 80).'</h4></a>
										<p class="hidden-xs">'.truncate($row['postExcerpt'], 80).'</p>
										<p class="meta">
											<b><i class="fa fa-comments"></i> '.countComments('posts', $row['postID']).' &nbsp; <i class="fa fa-thumbs-up"></i> '.countLikes('posts', $row['postID']).' &nbsp; <i class="fa fa-eye"></i> '.$row['visits_count'].' <span class="badge pull-right">' . $post_tag . '</span></b>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				';
			}
		}
		else
		{
			return false;
		}
    }
}

if(!function_exists('parsingImageByID'))
{
    function parsingImageByID($userID = 0, $action = null, $itemID = 0, $actionID = null, $timestamp = null)
    {
		$CI =& get_instance();
		$CI->db->select('users.userID, users.userName, users.full_name, snapshots.snapshotID, snapshots.snapshotSlug, snapshots.snapshotContent, snapshots.snapshotFile, snapshots.timestamp')
		->join('users', 'users.userID = snapshots.contributor')
		->where('snapshots.snapshotID', $itemID)
		->limit(1);
        $query = $CI->db->get('snapshots');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $row)
			{
				if($action == 'submitting')
				{
					$actions = phrase('uploading_a_snapshot') . ' ' . ($row['userID'] == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/snapshots/remove/' . $row['snapshotSlug']) . '\', \'snapshots' . $row['snapshotID'] . '\', \'snapshots' . $row['snapshotID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('delete_this_snapshot') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/snapshots/' . $row['snapshotID']) . '\', \'snapshots' . $row['snapshotID'] . '\', \'snapshots' . $row['snapshotID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa fa-times"></i></a>');
				}
				elseif($action == 'commenting')
				{
					$actions = ($row['userID'] != $userID ? phrase('commenting_user_snapshot', array($row['userName'], $row['full_name'])) : phrase('commenting_own_snapshot')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/snapshots/' . $actionID) . '\', \'snapshots' . $row['snapshotID'] . '\', \'snapshots' . $row['snapshotID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('remove_comment') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/comments/' . $actionID) . '\', \'snapshots' . $row['snapshotID'] . '\', \'snapshots' . $row['snapshotID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				elseif($action == 'liking')
				{
					$actions = ($row['userID'] != $userID ? phrase('liking_user_snapshot', array($row['userName'], $row['full_name'])) : phrase('liking_own_snapshot')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/likes/' . $actionID) . '\', \'snapshots' . $row['snapshotID'] . '\', \'snapshots' . $row['snapshotID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('remove_comment') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/snapshots/' . $row['snapshotID']) . '\', \'snapshots' . $row['snapshotID'] . '\', \'snapshots' . $row['snapshotID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				elseif($action == 'reposting')
				{
					$actions = ($row['userID'] != $userID ? phrase('reposting_user_snapshot', array($row['userName'], $row['full_name'])) : phrase('reposting_own_snapshot')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/reposts/' . $actionID) . '\', \'snapshots' . $row['snapshotID'] . '\', \'snapshots' . $row['snapshotID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('hide_this_feed') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/reposts/' . $actionID) . '\', \'snapshots' . $row['snapshotID'] . '\', \'snapshots' . $row['snapshotID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				else
				{
					$actions = null;
				}
				
				return '
					<div id="snapshots' . $itemID . '">
						<div class="image-placeholder">
							<div class="col-sm-12 nomargin">
								<div class="row">
									<div class="col-xs-2 col-sm-1">
										<a href="' . base_url(($row['userID'] == $userID ? $row['userName'] : getUsernameByID($userID))) . '" class="ajaxLoad hoverCard">
											<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', ($row['userID'] == $userID ? getUserPhoto($row['userID'], 1) : getUserPhoto($userID, 1)))) . '" style="height:40px;width:40px" class="img-rounded img-bordered" alt="" />
										</a>
									</div>
									<div class="col-xs-10 col-sm-11">
										<a href="' . base_url(($row['userID'] == $userID ? $row['userName'] : getUsernameByID($userID))) . '" class="ajaxLoad hoverCard">
											<b>' . ($row['userID'] == $userID ? $row['full_name'] : getFullnameByID($userID)) . '</b> 
										</a>
										' . $actions . ' 
										<br />
										<small class="text-muted">@' . ($row['userID'] == $userID ? $row['userName'] : getUsernameByID($userID)) . ' - ' . time_since(($timestamp ? $timestamp : $row['timestamp'])) . '</small>
									</div>
								</div>
								' . (($actionID && $action == 'commenting' || $actionID && $action == 'reposting') && parsingActionByID($actionID, $action) != null ? '
								<div class="row">
									<div class="col-sm-12">
										<blockquote style="margin-bottom:10px">
											' . truncate(parsingActionByID($actionID, $action), 60) . '
										</blockquote>
									</div>
								</div>
								' : ''
								) . '
								<div class="row">
									<div class="col-sm-12">
										<div class="row">
											<a href="' . base_url('snapshots/' . $row['snapshotSlug']).'" class="ajax relative" style="display:block">
												' . (strtolower(substr($row['snapshotFile'], -3)) == 'gif' ? '<span class="gif_play"></span>' : '') . '
												<img width="100%" class="img-responsive" src="' . base_url('uploads/snapshots/thumbs/' . imageCheck('snapshots', $row['snapshotFile'], 1)).'" alt="'.truncate($row['snapshotContent'], 80).'"/>
											</a>
										</div>
										<br />
										<p>
											' . special_parse(truncate($row['snapshotContent'], 160)) . '
										</p>
										<div class="btn-group btn-group-justified">
											<a href="' . base_url('snapshots/' . $row['snapshotSlug']).'" class="btn btn-default ajax"><i class="fa fa-comments"></i> <span class="comments-count-snapshots' . $row['snapshotID'] . '">' . countComments('snapshots', $row['snapshotID']) . '</span> <span class="hidden-xs">' . phrase('comments') . '</span></a>
											<a class="like like-snapshots' . $row['snapshotID'] . ' btn btn-default' . (is_userLike('snapshots', $row['snapshotID']) ? ' active' : '') . '" href="' . base_url('user/like/snapshots/' . $row['snapshotID']) . '" data-id="snapshots' . $row['snapshotID'] . '"><i class="like-icon fa fa-thumbs-up"></i> <span class="likes-count">' . countLikes('snapshots', $row['snapshotID']) . '</span><span class="hidden-xs"> ' . phrase('likes') . '</span></a>
											<a href="' . base_url('user/repost/snapshots/' . $row['snapshotID']) . '" class="repost btn btn-default" data-id="' . $row['snapshotID'] . '"><i class="fa fa-retweet"></i> <span class="reposts-count' . $row['snapshotID'] . '">' . countReposts('snapshots', $row['snapshotID']) . '</span><span class="hidden-xs"> ' . phrase('reposts') . '</span></a>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 nomargin">
											
										' . getComments('snapshots', $row['snapshotID']) . '
											
									</div>
								</div>
							</div>
						</div>
					</div>
				';
			}
		}
		else
		{
			return false;
		}
    }
}

if(!function_exists('parsingOpenletterByID'))
{
    function parsingOpenletterByID($userID = 0, $action = null, $itemID = 0, $actionID = null, $timestamp = null)
    {
		$CI =& get_instance();
		$CI->db->select('users.userID, users.userName, users.full_name, openletters.letterID, openletters.title, openletters.slug, openletters.content, openletters.visits_count, openletters.timestamp')
		->join('users', 'users.userID = openletters.contributor')
		->where('openletters.letterID', $itemID)
		->limit(1);
        $query = $CI->db->get('openletters');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $row)
			{
				if($action == 'submitting')
				{
					$actions = phrase('submitting_an_open_letter') . ' ' . ($row['userID'] == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/openletters/remove/' . $row['letterID']) . '\', \'openletters' . $row['letterID'] . '\', \'openletters' . $row['letterID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('delete_this_openletter') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/openletters/' . $row['letterID']) . '\', \'openletters' . $row['letterID'] . '\', \'openletters' . $row['letterID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa fa-times"></i></a>');
				}
				elseif($action == 'commenting')
				{
					$actions = ($row['userID'] != $userID ? phrase('commenting_user_openletter', array($row['userName'], $row['full_name'])) : phrase('commenting_own_openletter')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/openletters/' . $actionID) . '\', \'openletters' . $row['letterID'] . '\', \'openletters' . $row['letterID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('remove_comment') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/comments/' . $actionID) . '\', \'openletters' . $row['letterID'] . '\', \'openletters' . $row['letterID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				elseif($action == 'liking')
				{
					$actions = ($row['userID'] != $userID ? phrase('liking_user_openletter', array($row['userName'], $row['full_name'])) : phrase('liking_own_openletter')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/likes/' . $actionID) . '\', \'openletters' . $row['letterID'] . '\', \'openletters' . $row['letterID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('remove_comment') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/openletters/' . $row['letterID']) . '\', \'openletters' . $row['letterID'] . '\', \'openletters' . $row['letterID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				elseif($action == 'reposting')
				{
					$actions = ($row['userID'] != $userID ? phrase('reposting_user_openletter', array($row['userName'], $row['full_name'])) : phrase('reposting_own_openletter')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/reposts/' . $actionID) . '\', \'openletters' . $row['letterID'] . '\', \'openletters' . $row['letterID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('hide_this_feed') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/reposts/' . $actionID) . '\', \'openletters' . $row['letterID'] . '\', \'openletters' . $row['letterID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				else
				{
					$actions = null;
				}
				
				return '
					<div id="openletters' . $itemID . '">
						<div class="' . (($actionID && $action == 'commenting' || $actionID && $action == 'reposting') ? 'image-placeholder':'letter-placeholder') . '">
							<div class="' . (($actionID && $action == 'commenting' || $actionID && $action == 'reposting') ? 'col-sm-12 nomargin':'blog_article') . '">
								<div class="row">
									<div class="col-xs-2 col-sm-1">
										<a href="' . base_url(($row['userID'] == $userID ? $row['userName'] : getUsernameByID($userID))) . '" class="ajaxLoad hoverCard">
											<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', ($row['userID'] == $userID ? getUserPhoto($row['userID'], 1) : getUserPhoto($userID, 1)))) . '" style="height:40px;width:40px" class="img-rounded img-bordered" alt="" />
										</a>
									</div>
									<div class="col-xs-10 col-sm-11">
										<a href="' . base_url($row['userName']) . '" class="ajaxLoad hoverCard">
											<b>' . ($row['userID'] == $userID ? $row['full_name'] : getFullnameByID($userID)) . '</b> 
										</a>
										' . $actions . ' 
										<br />
										<small class="text-muted">@' . ($row['userID'] == $userID ? $row['userName'] : getUsernameByID($userID)) . ' - ' . time_since(($timestamp ? $timestamp : $row['timestamp'])) . '</small>
									</div>
								</div>
								' . (($actionID && $action == 'commenting' || $actionID && $action == 'reposting') && parsingActionByID($actionID, $action) != null ? '
								<div class="row">
									<div class="col-sm-12">
										<blockquote style="margin-bottom:10px">
											' . truncate(parsingActionByID($actionID, $action), 60) . '
										</blockquote>
									</div>
								</div>
								' : ''
								) . '
								' . (($actionID && $action == 'commenting' || $actionID && $action == 'reposting') ? '<div class="letter-placeholder"><div class="blog_article">':'') . '
								<div class="row">
									<div class="col-sm-12">
										<a href="' . base_url('openletters/' . $row['slug']) . '" class="ajaxLoad"><h4>'.truncate($row['title'], 80).'</h4></a>
										<p>'.truncate($row['content'], 80).'</p>
										<p class="meta">
											<b><i class="fa fa-comments"></i> '.countComments('openletters', $row['letterID']).' &nbsp; <i class="fa fa-thumbs-up"></i> '.countLikes('openletters', $row['letterID']).' &nbsp; <i class="fa fa-eye"></i> '.$row['visits_count'].'</b> 
											<a href="' . base_url('openletters/' . $row['slug']) . '" class="ajaxLoad btn btn-default pull-right"><i class="fa fa-envelope"></i> ' . phrase('read_letter') . '</a>
										</p>
									</div>
								</div>
								' . (($actionID && $action == 'commenting' || $actionID && $action == 'reposting') ? '</div></div>':'') . '
							</div>
						</div>
					</div>
				';
			}
		}
		else
		{
			return false;
		}
    }
}

if(!function_exists('parsingChannelByID'))
{
    function parsingChannelByID($userID = 0, $action = null, $itemID = 0, $actionID = null, $timestamp = null)
    {
		$CI =& get_instance();
		$CI->db->select('users.userID, users.userName, users.full_name, tv.tvID, tv.tvSlug, tv.tvContent, tv.tvFile, tv.timestamp')
		->join('users', 'users.userID = tv.contributor')
		->where('tv.tvID', $itemID)
		->limit(1);
        $query = $CI->db->get('tv');
        if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $row)
			{
				if($action == 'submitting')
				{
					$actions = phrase('submitting_a_tv_channel') . ' ' . ($row['userID'] == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/tv/remove/' . $row['tvSlug']) . '\', \'tv' . $row['tvID'] . '\', \'tv' . $row['tvID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('delete_this_tv') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/tv/' . $row['tvID']) . '\', \'tv' . $row['tvID'] . '\', \'tv' . $row['tvID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa fa-times"></i></a>');
				}
				elseif($action == 'commenting')
				{
					$actions = ($row['userID'] != $userID ? phrase('comenting_user_tv', array($row['userName'], $row['full_name'])) : phrase('commenting_own_tv')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/tv/' . $actionID) . '\', \'tv' . $row['tvID'] . '\', \'tv' . $row['tvID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('remove_comment') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/comments/' . $actionID) . '\', \'tv' . $row['tvID'] . '\', \'tv' . $row['tvID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				elseif($action == 'liking')
				{
					$actions = ($row['userID'] != $userID ? phrase('liking_user_tv', array($row['userName'], $row['full_name'])) : phrase('liking_own_tv')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/likes/' . $actionID) . '\', \'tv' . $row['tvID'] . '\', \'tv' . $row['tvID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('remove_comment') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/tv/' . $row['tvID']) . '\', \'tv' . $row['tvID'] . '\', \'tv' . $row['tvID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				elseif($action == 'reposting')
				{
					$actions = ($row['userID'] != $userID ? phrase('reposting_user_tv', array($row['userName'], $row['full_name'])) : phrase('reposting_own_tv')) . ($userID == $CI->session->userdata('userID') ? '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/tv/' . $actionID) . '\', \'tv' . $row['tvID'] . '\', \'tv' . $row['tvID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('hide_this_feed') . '"><i class="fa fa-times"></i></a>' : '<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/reposts/' . $actionID) . '\', \'tv' . $row['tvID'] . '\', \'tv' . $row['tvID'] . '\')" class="absolute close" style="top:0;right:10px" data-push="tooltip" data-title="' . phrase('i_do_not_want_to_see_this') . '"><i class="fa ban"></i></a>');
				}
				else
				{
					$actions = null;
				}
				
				return '
					<div id="tv' . $itemID . '">
						<div class="image-placeholder">
							<div class="col-sm-12 nomargin">
								<div class="row">
									<div class="col-xs-2 col-sm-1">
										<a href="' . base_url(($row['userID'] == $userID ? $row['userName'] : getUsernameByID($userID))) . '" class="ajaxLoad hoverCard">
											<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', ($row['userID'] == $userID ? getUserPhoto($row['userID'], 1) : getUserPhoto($userID, 1)))) . '" style="height:40px;width:40px" class="img-rounded img-bordered" alt="" />
										</a>
									</div>
									<div class="col-xs-10 col-sm-11">
										<a href="' . base_url(($row['userID'] == $userID ? $row['userName'] : getUsernameByID($userID))) . '" class="ajaxLoad hoverCard">
											<b>' . ($row['userID'] == $userID ? $row['full_name'] : getFullnameByID($userID)) . '</b> 
										</a>
										' . $actions . ' 
										<br />
										<small class="text-muted">@' . ($row['userID'] == $userID ? $row['userName'] : getUsernameByID($userID)) . ' - ' . time_since(($timestamp ? $timestamp : $row['timestamp'])) . '</small>
									</div>
								</div>
								' . (($actionID && $action == 'commenting' || $actionID && $action == 'reposting') && parsingActionByID($actionID, $action) != null ? '
								<div class="row">
									<div class="col-sm-12">
										<blockquote style="margin-bottom:10px">
											' . truncate(parsingActionByID($actionID, $action), 60) . '
										</blockquote>
									</div>
								</div>
								' : ''
								) . '
								<div class="row">
									<div class="col-sm-12">
										<div class="row">
											<a href="' . base_url('tv/' . $row['tvSlug']).'" class="ajax relative" style="display:block">
												' . (strtolower(substr($row['tvFile'], -3)) == 'gif' ? '<span class="gif_play"></span>' : '') . '
												<img width="100%" class="img-responsive" src="' . base_url('uploads/tv/thumbs/' . imageCheck('tv', $row['tvFile'], 1)).'" alt="'.truncate($row['tvContent'], 80).'"/>
											</a>
										</div>
										<br />
										<p>
											' . special_parse(truncate($row['tvContent'], 160)) . '
										</p>
										<div class="btn-group btn-group-justified">
											<a href="' . base_url('tv/' . $row['tvSlug']).'" class="btn btn-default ajax"><i class="fa fa-comments"></i> <span class="comments-count-tv' . $row['tvID'] . '">' . countComments('tv', $row['tvID']) . '</span> <span class="hidden-xs">' . phrase('comments') . '</span></a>
											<a class="like like-tv' . $row['tvID'] . ' btn btn-default' . (is_userLike('tv', $row['tvID']) ? ' active' : '') . '" href="' . base_url('user/like/tv/' . $row['tvID']) . '" data-id="tv' . $row['tvID'] . '"><i class="like-icon fa fa-thumbs-up"></i> <span class="likes-count">' . countLikes('tv', $row['tvID']) . '</span><span class="hidden-xs"> ' . phrase('likes') . '</span></a>
											<a href="' . base_url('user/repost/tv/' . $row['tvID']) . '" class="btn btn-default repost" data-id="' . $row['tvID'] . '"><i class="fa fa-retweet"></i> <span class="reposts-count' . $row['tvID'] . '">' . countReposts('tv', $row['tvID']) . '</span><span class="hidden-xs"> ' . phrase('reposts') . '</span></a>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 nomargin">
											
										' . getComments('tv', $row['tvID']) . '
											
									</div>
								</div>
							</div>
						</div>
					</div>
				';
			}
		}
		else
		{
			return false;
		}
    }
}

if(!function_exists('parsingActionByID'))
{
    function parsingActionByID($actionID = 0, $action = null)
    {
		$CI =& get_instance();
		
		if($action == 'commenting')
		{
			return $CI->db->select('comments')->limit(1)->get_where('comments', array('commentID' => $actionID))->row()->comments;
		}
		elseif($action == 'reposting')
		{
			return $CI->db->select('messages')->limit(1)->get_where('reposts', array('repostID' => $actionID))->row()->messages;
		}
		else
		{
			return false;
		}
	}
}