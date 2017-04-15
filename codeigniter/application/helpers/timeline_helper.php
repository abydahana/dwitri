<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('getTimeline'))
{
	function getTimelines($userID = 0, $limit = 12, $offset = 0, $timestamp = null)
	{
		$CI =& get_instance();
		if($userID == $CI->session->userdata('userID'))
		{
			$users		= array($CI->session->userdata('userID'));
			$following	= $CI->db->select('is_following')->where('userID', $CI->session->userdata('userID'))->get('followers')->result_array();
			if(@sizeof($following) > 0)
			{
				foreach($following as $key)
				{
					$users[]	= $key['is_following'];
				}
			}
			$friendship	= $CI->db->select('fromID')->where('(fromID = ' . $CI->session->userdata('userID') . ' OR toID = ' . $userID . ') OR (fromID = ' . $userID . ' OR toID = ' . $CI->session->userdata('userID') . ') AND fromID != ' . $CI->session->userdata('userID'))->get('friendships')->result_array();
			if(@sizeof($friendship) > 0)
			{
				foreach($friendship as $key)
				{
					$users[]	= $key['fromID'];
				}
			}
		}
		else
		{
			$users		= array($userID);
		}
		
		$query	= $CI->db->query('
			SELECT *
				FROM
					(
						SELECT
							timestamp,
							updateID as groupID,
							updateID as groupOrder,
							IF(updateID IS NOT NULL, "YES", NULL) as is_update,
							updateID,
							userID as update_contributor,
							timestamp as update_time,
							updateID as is_post,
							updateID as postID,
							updateID as post_contributor,
							updateID as post_time,
							updateID as is_snapshot,
							updateID as snapshotID,
							updateID as snapshot_contributor,
							updateID as snapshot_time,
							updateID as is_openletter,
							updateID as letterID,
							updateID as openletter_contributor,
							updateID as openletter_time,
							updateID as is_tv,
							updateID as tvID,
							updateID as tv_contributor,
							updateID as tv_time,
							updateID as is_comment,
							updateID as commentID,
							updateID as commenterID,
							updateID as item_commented,
							updateID as commentType,
							updateID as comment_time,
							updateID as is_like,
							updateID as likeID,
							updateID as likerID,
							updateID as item_liked,
							updateID as likeType,
							updateID as like_time,
							updateID as is_repost,
							updateID as repostID,
							updateID as reposterID,
							updateID as item_reposted,
							updateID as repostType,
							updateID as repost_time
						FROM
							updates
						WHERE
							userID IN (' . join(',', $users) . ')
						' . ($timestamp != null ? 'AND MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))' : '') . '
						UNION ALL
						SELECT
							timestamp,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							IF(postID IS NOT NULL, "YES", NULL),
							postID,
							contributor,
							timestamp,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID,
							postID
						FROM
							posts
						WHERE
							contributor IN (' . join(',', $users) . ')
						' . ($timestamp != null ? 'AND MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))' : '') . '
						UNION ALL
						SELECT
							timestamp,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							IF(snapshotID IS NOT NULL, "YES", NULL),
							snapshotID,
							contributor,
							timestamp,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID,
							snapshotID
						FROM
							snapshots
						WHERE
							contributor IN (' . join(',', $users) . ')
					' . ($timestamp != null ? 'AND MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))' : '') . '
						UNION ALL
						SELECT
							timestamp,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							IF(letterID IS NOT NULL, "YES", NULL),
							letterID,
							contributor,
							timestamp,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID,
							letterID
						FROM
							openletters
						WHERE
							contributor IN (' . join(',', $users) . ')
						' . ($timestamp != null ? 'AND MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))' : '') . '
						UNION ALL
						SELECT
							timestamp,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							IF(tvID IS NOT NULL, "YES", NULL),
							tvID,
							contributor,
							timestamp,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID,
							tvID
						FROM
							tv
						WHERE
							contributor IN (' . join(',', $users) . ')
						' . ($timestamp != null ? 'AND MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))' : '') . '
						UNION ALL
						SELECT
							timestamp,
							itemID,
							commentType,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							IF(commentID IS NOT NULL, "YES", NULL),
							commentID,
							userID,
							itemID,
							commentType,
							timestamp,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID,
							commentID
						FROM
							comments
						WHERE
							userID IN (' . join(',', $users) . ')
						' . ($timestamp != null ? 'AND MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))' : '') . '
						UNION ALL
						SELECT
							timestamp,
							itemID,
							likeType,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							IF(likeID IS NOT NULL, "YES", NULL),
							likeID,
							userID,
							itemID,
							likeType,
							timestamp,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID,
							likeID
						FROM
							likes
						WHERE
							userID IN (' . join(',', $users) . ')
						' . ($timestamp != null ? 'AND MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))' : '') . '
						UNION ALL
						SELECT
							timestamp,
							itemID,
							repostType,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							repostID,
							IF(repostID IS NOT NULL, "YES", NULL),
							repostID,
							userID,
							itemID,
							repostType,
							timestamp
						FROM
							reposts
						WHERE
							userID IN (' . join(',', $users) . ')
						' . ($timestamp != null ? 'AND MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))' : '') . '
						ORDER BY timestamp DESC
					) union_fetch
				GROUP BY groupID, groupOrder
				ORDER BY timestamp DESC
				LIMIT
					' . $offset . ', '. $limit . '
		')->result_array();
		return $query;
	}
}

if(!function_exists('timelinePaging'))
{
	function timelinePaging($userID = null, $limit = 12, $offset = 0, $timestamp = null)
	{
		$CI = &get_instance();
		if($userID == $CI->session->userdata('userID'))
		{
			$users		= array($CI->session->userdata('userID'));
			$following	= $CI->db->select('is_following')->where('userID', $CI->session->userdata('userID'))->get('followers')->result_array();
			if(@sizeof($following) > 0)
			{
				foreach($following as $key)
				{
					$users[]	= $key['is_following'];
				}
			}
		}
		else
		{
			$users		= array($userID);
		}
		if($timestamp)
		{
			$type			= getUsernameByID($userID) . '/' . $CI->uri->segment(2);
			$segment		= 3;
			$num			 = $CI->db->where_in('userID', $users)->where('MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))')->get('updates')->num_rows();
			$num			+= $CI->db->where_in('contributor', $users)->where('MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))')->get('posts')->num_rows();
			$num			+= $CI->db->where_in('contributor', $users)->where('MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))')->get('snapshots')->num_rows();
			$num			+= $CI->db->where_in('contributor', $users)->where('MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))')->get('openletters')->num_rows();
			$num			+= $CI->db->where_in('contributor', $users)->where('MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))')->get('tv')->num_rows();
			$num			+= $CI->db->where_in('userID', $users)->where('MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))')->group_by('itemID')->get('comments')->num_rows();
			$num			+= $CI->db->where_in('userID', $users)->where('MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))')->group_by('itemID')->get('likes')->num_rows();
			$num			+= $CI->db->where_in('userID', $users)->where('MONTH(FROM_UNIXTIME(timestamp)) = MONTH(FROM_UNIXTIME(' . $timestamp . '))')->group_by('itemID')->get('reposts')->num_rows();
		}
		else
		{
			$type			= getUsernameByID($userID);
			$segment		= 2;
			$num			= $CI->db->where_in('userID', $users)->get('updates')->num_rows();
			$num			+= $CI->db->where_in('contributor', $users)->get('posts')->num_rows();
			$num			+= $CI->db->where_in('contributor', $users)->get('snapshots')->num_rows();
			$num			+= $CI->db->where_in('contributor', $users)->get('openletters')->num_rows();
			$num			+= $CI->db->where_in('contributor', $users)->get('tv')->num_rows();
			$num			+= $CI->db->where_in('userID', $users)->group_by('itemID')->get('comments')->num_rows();
			$num			+= $CI->db->where_in('userID', $users)->group_by('itemID')->get('likes')->num_rows();
			$num			+= $CI->db->where_in('userID', $users)->group_by('itemID')->get('reposts')->num_rows();
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