<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Actions_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function loginCheck($username = null, $password = null)
	{
		$query = $this->db->limit(1)->where('(userName = "' . $username . '" OR email = "' . $username . '")')->where('password', $password)->get('users');
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $rows) {
				$data = array(
					'userID'			=> $rows->userID,
					'facebookUID'		=> $rows->facebookUID,
					'email'				=> $rows->email,
					'full_name'			=> $rows->full_name,
					'userName'			=> $rows->userName,
					'user_level'		=> $rows->level,
					'language'			=> $rows->language,
					'loggedIn'			=> TRUE,
					'online'			=> TRUE
				);

				$this->session->set_userdata($data);
			}
			$this->db->limit(1)->where('(userName = "' . $username . '" OR email = "' . $username . '")')->where('password', $password)->update('users', array('last_login' => date('Y-m-d H:i')));
			return true;
		}
		else
		{
			return false;
		}
	}

	function facebookLogin($uid = null)
	{
		$query = $this->db->limit(1)->where('facebookUID', $uid)->get('users');
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $rows) {
				$data = array(
					'userID'			=> $rows->userID,
					'facebookUID'		=> $rows->facebookUID,
					'email'				=> $rows->email,
					'full_name'			=> $rows->full_name,
					'userName'			=> $rows->userName,
					'user_level'		=> $rows->level,
					'language'			=> $rows->language,
					'loggedIn'			=> TRUE,
					'online'			=> TRUE
				);

				$this->session->set_userdata($data);
			}
			$this->db->limit(1)->where('facebookUID', $uid)->update('users', array('last_login' => date('Y-m-d H:i')));
			return true;
		}
		else
		{
			$profile = $this->facebook->api('/me');
			$data = array(
				'userName' => strtolower(str_replace(' ', '_', $profile['name'])) . '_' . time(),
				'full_name' => $profile['name'],
				'gender' => ($profile['gender'] == 'male' ? 'l' : 'p'),
				'age' => date('Y') - date('Y',strtotime($profile['birthday'])),
				'email' => $profile['email'],
				'password' => sha1($uid . SALT),
				'bio' => $profile['bio'],
				'facebookUID' => $uid
			);
			
			$this->db->insert('users', $data);
			
			$this->session->set_userdata(array('newRegister' => true));
			return $this->loginCheck($data['userName'], $data['password']);
			//return false;
		}
	}

	function facebookConnect($uid = null)
	{
		$query = $this->db->limit(1)->where('facebookUID', $uid)->get('users');
		if($query->num_rows() > 0)
		{
			$this->facebook->api('/me/permissions', 'DELETE');
			$this->facebook->destroySession();
			
			$data = array(
				'facebookUID' => null
			);
			
			$this->db->where(array('userID' => $this->session->userdata('userID'), 'userName' => $this->session->userdata('userName')));
			$this->db->update('users', $data);
			
			return true;
		}
		else
		{
			$data = array(
				'facebookUID' => $uid
			);
			
			$this->db->where(array('userID' => $this->session->userdata('userID'), 'userName' => $this->session->userdata('userName')));
			$this->db->update('users', $data);
			
			return true;
		}
	}
	
	function createProfile($fields = array(), $username = null, $password = null)
	{
		if($this->db->insert('users', $fields))
		{
			$this->loginCheck($username, $password);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function updateProfile($fields)
	{
		$this->db->where('userID', $this->session->userdata('userID'));
		if($this->db->update('users', $fields))
		{
			$sess = array(
				'full_name'		=> $this->input->post('full_name'),
				'gender'		=> $this->input->post('gender'),
				'age'			=> $this->input->post('age'),
				'mobile'		=> $this->input->post('mobile'),
				'address'		=> $this->input->post('address'),
				'email'			=> $this->input->post('email'),
				'language'		=> $this->input->post('language')
			);
			$this->session->unset_userdata('newRegister');
			$this->session->set_userdata($sess);
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function updatePhoto($type, $filename)
	{
		if($type == 'photo')
		{
			$data['photo'] = $filename;
		}
		elseif($type == 'cover')
		{
			$data['cover'] = $filename;
		}
		
		$this->db->where('userID', $this->session->userdata('userID'));
		if($this->db->update('users', $data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
		
	function statusUpdate()
	{
		$status = array(
			'userID' => $this->session->userdata('userID'),
			'updateContent' => $this->input->post('content'),
			'visibility' => $this->input->post('visibility'),
			'timestamp' => time()
		);
		
		if($this->db->insert('updates', $status))
		{
			$updateID	= $this->db->insert_id();
			return $updateID;
		}
		else
		{
			return false;
		}
	}
	
	function submitComment($type = null, $itemID = null, $comments = null)
	{
		if(!$this->db->table_exists($type))
		{
			return false;
		}
		else
		{
			if($type == 'updates')
			{
				$typeID 	= 0;
				$targetURL 	= getRefererByID('updates', $itemID);
				$this->db->where('updateID', $itemID);
			}
			elseif($type == 'posts')
			{
				$typeID 	= 1;
				$targetURL 	= getRefererByID('posts', $itemID);
				$this->db->where('postID', $itemID);
			}
			elseif($type == 'snapshots')
			{
				$typeID		= 2;
				$targetURL 	= getRefererByID('snapshots', $itemID);
				$this->db->where('snapshotID', $itemID);
			}
			elseif($type == 'openletters')
			{
				$typeID		= 3;
				$targetURL 	= getRefererByID('openletters', $itemID);
				$this->db->where('letterID', $itemID);
			}
			elseif($type == 'tv')
			{
				$typeID		= 4;
				$targetURL 	= getRefererByID('tv', $itemID);
				$this->db->where('tvID', $itemID);
			}
			else
			{
				return false;
			}
			
			$this->db->limit(1);
			$query = $this->db->get($type);
			if($query->num_rows() > 0)
			{
				$data = array(
					'itemID'		=> $itemID,
					'commentType'	=> $typeID,
					'userID'		=> $this->session->userdata('userID'),
					'comments'		=> $comments,
					'timestamp'		=> time()
				);
				
				if($this->db->insert('comments', $data))
				{
					$commentID	= $this->db->insert_id();
					$user_check = cath_user($comments);
					if(is_array($user_check))
					{
						foreach($user_check as $username)
						{
							if(checkUsername($username) && getUseridByUsername($username) != $this->session->userdata('userID'))
							{
								$where = array('fromID' => $this->session->userdata('userID'), 'toID' => getUseridByUsername($username), 'itemID' => $itemID, 'type' => 'comment');
								
								if($this->db->get_where('notifications', $where)->num_rows() > 0)
								{
									$notification = array(
										'targetURL' => $targetURL . '/#comment' . $commentID,
										'description' => truncate($comments, 60),
										'timestamp' => time()
									);
									$this->db->where($where);
									$this->db->limit(1);
									$this->db->update('notifications', $notification);
								}
								else
								{
									$notification = array(
										'fromID' => $this->session->userdata('userID'),
										'toID' => getUseridByUsername($username),
										'itemID' => $itemID,
										'commentID' => $commentID,
										'targetURL' => $targetURL . '/#comment' . $commentID,
										'notificationType' => $typeID,
										'type' => 'comment',
										'description' => truncate($comments, 60),
										'timestamp' => time()
									);
									$this->db->insert('notifications', $notification);
								}
							}
						}
					}
					
					$where = array('fromID' => $this->session->userdata('userID'), 'toID' => getPostAuthor($typeID, $itemID), 'itemID' => $itemID, 'type' => 'comment');
					
					if($this->db->get_where('notifications', $where)->num_rows() > 0)
					{
						if(getPostAuthor($typeID, $itemID) != $this->session->userdata('userID'))
						{
							$notification = array(
								'targetURL' => $targetURL . '/#comment' . $commentID,
								'description' => truncate($comments, 60),
								'timestamp' => time()
							);
							$this->db->where($where);
							$this->db->limit(1);
							$this->db->update('notifications', $notification);
						}
					}
					else
					{
						if(getPostAuthor($typeID, $itemID) != $this->session->userdata('userID'))
						{
							$notification = array(
								'fromID' => $this->session->userdata('userID'),
								'toID' => getPostAuthor($typeID, $itemID),
								'itemID' => $itemID,
								'commentID' => $commentID,
								'targetURL' => $targetURL . '/#comment' . $commentID,
								'notificationType' => $typeID,
								'type' => 'comment',
								'description' => truncate($comments, 60),
								'timestamp' => time()
							);
							$this->db->insert('notifications', $notification);
						}
					}
					
					return $commentID;
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
	
	function likePost($type = null, $itemID = 0)
	{
		if($type == 'updates')
		{
			$typeID	 	= 0;
			$targetURL 	= getRefererByID('updates', $itemID);
		}
		elseif($type == 'posts')
		{
			$typeID 	= 1;
			$targetURL 	= getRefererByID('posts', $itemID);
		}
		elseif($type == 'snapshots')
		{
			$typeID 	= 2;
			$targetURL 	= getRefererByID('snapshots', $itemID);
		}
		elseif($type == 'openletters')
		{
			$typeID 	= 3;
			$targetURL 	= getRefererByID('openletters', $itemID);
		}
		elseif($type == 'tv')
		{
			$typeID 	= 4;
			$targetURL 	= getRefererByID('tv', $itemID);
		}
		else
		{
			return false;
		}
		
		$this->db->select('*');
		$this->db->where(array('itemID' => $itemID, 'likeType' => $typeID, 'userID' => $this->session->userdata('userID')));
		$this->db->limit(1);
		$query = $this->db->get('likes');
		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $r)
			{
				if($r['like'] == 0)
				{
					$data = array(
						'like' => 1,
						'dislike' => 0,
						'timestamp' => time()
					);
					$this->db->where(array('itemID' => $itemID, 'likeType' => $typeID, 'userID' => $this->session->userdata('userID')));
					$this->db->limit(1);
					if($this->db->update('likes', $data))
					{
						if(getPostAuthor($typeID, $itemID) != $this->session->userdata('userID'))
						{
							$where = array('fromID' => $this->session->userdata('userID'), 'toID' => getPostAuthor($typeID, $itemID), 'itemID' => $itemID, 'type' => 'like');
							
							if($this->db->get_where('notifications', $where)->num_rows() > 0)
							{
								$notification = array(
									'timestamp' => time()
								);
								$this->db->where($where);
								$this->db->limit(1);
								$this->db->update('notifications', $notification);
							}
							else
							{
								$notification = array(
									'fromID' => $this->session->userdata('userID'),
									'toID' => getPostAuthor($typeID, $itemID),
									'itemID' => $itemID,
									'targetURL' => $targetURL,
									'type' => 'like',
									'notificationType' => $typeID,
									'timestamp' => time()
								);
								$this->db->insert('notifications', $notification);
							}
						}
						return 'liked';
					}
				}
				elseif($r['like'] == 1)
				{
					$data = array(
						'like' => 0,
						'dislike' => 1,
						'timestamp' => time()
					);
					$this->db->where(array('itemID' => $itemID, 'likeType' => $typeID, 'userID' => $this->session->userdata('userID')));
					$this->db->limit(1);
					if($this->db->update('likes', $data))
					{
						if(getPostAuthor($typeID, $itemID) != $this->session->userdata('userID'))
						{
							$where = array('fromID' => $this->session->userdata('userID'), 'toID' => getPostAuthor($typeID, $itemID), 'itemID' => $itemID, 'type' => 'like');
							
							if($this->db->get_where('notifications', $where)->num_rows() > 0)
							{
								$this->db->delete('notifications', $where);
							}
						}
						
						return 'disliked';
					}
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			$data = array(
				'itemID' => $itemID,
				'userID' => $this->session->userdata('userID'),
				'like' => 1,
				'dislike' => 0,
				'likeType' => $typeID,
				'timestamp' => time()
			);
			if($this->db->insert('likes', $data))
			{
				if(getPostAuthor($typeID, $itemID) != $this->session->userdata('userID'))
				{
					$where = array('fromID' => $this->session->userdata('userID'), 'toID' => getPostAuthor($typeID, $itemID), 'itemID' => $itemID, 'type' => 'like');
					
					if($this->db->get_where('notifications', $where)->num_rows() > 0)
					{
						$notification = array(
							'timestamp' => time()
						);
						$this->db->where($where);
						$this->db->limit(1);
						$this->db->update('notifications', $notification);
					}
					else
					{
						$notification = array(
							'fromID' => $this->session->userdata('userID'),
							'toID' => getPostAuthor($typeID, $itemID),
							'itemID' => $itemID,
							'targetURL' => $targetURL,
							'type' => 'like',
							'notificationType' => $typeID,
							'timestamp' => time()
						);
						$this->db->insert('notifications', $notification);
					}
				}
				return 'liked';
			}
			else
			{
				return false;
			}
		}
	}
	
	function rePost($type = null, $itemID = 0, $messages = null)
	{
		if($type == 'updates')
		{
			$typeID		= 0;
			$targetURL 	= getRefererByID('updates', $itemID);
			$where 		= array('itemID' => $itemID, 'repostType' => 0, 'userID' => $this->session->userdata('userID'));
		}
		elseif($type == 'posts')
		{
			$typeID		= 1;
			$targetURL 	= getRefererByID('posts', $itemID);
			$where 		= array('itemID' => $itemID, 'repostType' => 1, 'userID' => $this->session->userdata('userID'));
		}
		elseif($type == 'snapshots')
		{
			$typeID		= 2;
			$targetURL 	= getRefererByID('snapshots', $itemID);
			$where 		= array('itemID' => $itemID, 'repostType' => 2, 'userID' => $this->session->userdata('userID'));
		}
		elseif($type == 'openletters')
		{
			$typeID		= 3;
			$targetURL 	= getRefererByID('openletters', $itemID);
			$where 		= array('itemID' => $itemID, 'repostType' => 3, 'userID' => $this->session->userdata('userID'));
		}
		elseif($type == 'tv')
		{
			$typeID		= 4;
			$targetURL 	= getRefererByID('tv', $itemID);
			$where 		= array('itemID' => $itemID, 'repostType' => 3, 'userID' => $this->session->userdata('userID'));
		}
		else
		{
			return false;
		}
		
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get('reposts');
		if($query->num_rows() > 0)
		{
			$data = array(
				'messages' => $messages,
				'timestamp' => time()
			);
			
			$this->db->where($where);
			if($this->db->update('reposts', $data))
			{
				if(getPostAuthor($typeID, $itemID) != $this->session->userdata('userID'))
				{
					$where = array('fromID' => $this->session->userdata('userID'), 'toID' => getPostAuthor($typeID, $itemID), 'itemID' => $itemID, 'type' => 'repost');
					
					if($this->db->get_where('notifications', $where)->num_rows() > 0)
					{
						$notification = array(
							'timestamp' => time()
						);
						$this->db->where($where);
						$this->db->limit(1);
						$this->db->update('notifications', $notification);
					}
					else
					{
						$notification = array(
							'fromID' => $this->session->userdata('userID'),
							'toID' => getPostAuthor($typeID, $itemID),
							'itemID' => $itemID,
							'notificationType' => $typeID,
							'targetURL' => $targetURL,
							'type' => 'repost',
							'timestamp' => time()
						);
						$this->db->insert('notifications', $notification);
					}
				}
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$data = array(
				'userID' => $this->session->userdata('userID'),
				'itemID' => $itemID,
				'repostType' => $typeID,
				'messages' => $messages,
				'timestamp' => time()
			);
			
			if($this->db->insert('reposts', $data))
			{
				if(getPostAuthor($typeID, $itemID) != $this->session->userdata('userID'))
				{
					$where = array('fromID' => $this->session->userdata('userID'), 'toID' => getPostAuthor($typeID, $itemID), 'itemID' => $itemID, 'type' => 'repost');
					
					if($this->db->get_where('notifications', $where)->num_rows() > 0)
					{
						$notification = array(
							'timestamp' => time()
						);
						$this->db->where($where);
						$this->db->limit(1);
						$this->db->update('notifications', $notification);
					}
					else
					{
						$notification = array(
							'fromID' => $this->session->userdata('userID'),
							'toID' => getPostAuthor($typeID, $itemID),
							'itemID' => $itemID,
							'notificationType' => $typeID,
							'targetURL' => $targetURL,
							'type' => 'repost',
							'timestamp' => time()
						);
						$this->db->insert('notifications', $notification);
					}
				}
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	
	function removeNotification($itemID)
	{
		if($this->db->limit(1)->delete('notifications', array('notifyID' => $itemID, 'toID' => $this->session->userdata('userID'))))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function removeNotifications()
	{
		if($this->db->delete('notifications', array('toID' => $this->session->userdata('userID'))))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function removeAny($type = null, $itemID = null)
	{
		if($type == 'updates')
		{
			$typeID = 0;
			$where = array('updateID' => $itemID, 'userID' => $this->session->userdata('userID'));
		}
		elseif($type == 'comments')
		{
			$typeID = 3;
			$where = array('commentID' => $itemID, 'userID' => $this->session->userdata('userID'));
		}
		elseif($type == 'reposts')
		{
			$typeID = 4;
			$where = array('repostID' => $itemID, 'userID' => $this->session->userdata('userID'));
		}
		else
		{
			return false;
		}
		
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get($type);
		if($query->num_rows() > 0)
		{
			if($this->db->limit(1)->delete($type, $where))
			{
				if($type == 'comments')
				{
					$this->db->limit(1)->delete('notifications', array('commentID' => $itemID, 'type' => 'comment'));
				}
				elseif($type == 'reposts')
				{
					$this->db->limit(1)->delete('notifications', array('repostID' => $itemID, 'type' => 'repost'));
				}
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
	
	function reportAny($type = null, $itemID = null)
	{
		return true;
		/*
		if($type == 'updates')
		{
			$typeID = 0;
			$where = array('updateID' => $itemID, 'userID' => $this->session->userdata('userID'));
		}
		elseif($type == 'comments')
		{
			$typeID = 3;
			$where = array('commentID' => $itemID, 'userID' => $this->session->userdata('userID'));
		}
		elseif($type == 'reposts')
		{
			$typeID = 4;
			$where = array('repostID' => $itemID, 'userID' => $this->session->userdata('userID'));
		}
		else
		{
			return false;
		}
		
		$this->db->where($where);
		$this->db->limit(1);
		$query = $this->db->get($type);
		if($query->num_rows() > 0)
		{
			if($this->db->limit(1)->delete($type, $where))
			{
				if($type == 'comments')
				{
					$this->db->limit(1)->delete('notifications', array('commentID' => $itemID, 'type' => 'comment'));
				}
				elseif($type == 'reposts')
				{
					$this->db->limit(1)->delete('notifications', array('repostID' => $itemID, 'type' => 'repost'));
				}
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
		*/
	}
	
	function userFollow($userName = null)
	{
		$userID	= getUseridByUsername($userName);
		$this->db->where(array('userID' => $this->session->userdata('userID'), 'is_following' => $userID));
		$this->db->limit(1);
		$query = $this->db->get('followers');
		if($query->num_rows() > 0)
		{
			$this->db->where(array('userID' => $this->session->userdata('userID'), 'is_following' => $userID))->limit(1);
			if($this->db->limit(1)->delete('followers'))
			{
				$this->db->delete('notifications', array('fromID' => $this->session->userdata('userID'), 'toID' => $userID, 'type' => 'following'));
				
				return 'unfollowed';
			}
			else
			{
				return false;
			}
		}
		else
		{
			$data	= array(
				'userID' 		=> $this->session->userdata('userID'),
				'is_following'	=> $userID,
				'timestamp'		=> time()
			);
			if($this->db->insert('followers', $data))
			{
				$where = array('fromID' => $this->session->userdata('userID'), 'toID' => $userID, 'type' => 'following');
				
				if($this->db->get_where('notifications', $where)->num_rows() > 0)
				{
					$notification = array(
						'timestamp' => time()
					);
					$this->db->where($where);
					$this->db->limit(1);
					$this->db->update('notifications', $notification);
				}
				else
				{
					$notification = array(
						'fromID' => $this->session->userdata('userID'),
						'toID' => $userID,
						'targetURL' => base_url($this->session->userdata('userName')),
						'type' => 'following',
						'timestamp' => time()
					);
					$this->db->insert('notifications', $notification);
				}
				
				return 'followed';
			}
			else
			{
				return false;
			}
		}
	}
	
	function friendAdd($userName = null)
	{
		$userID	= getUseridByUsername($userName);
		if($this->db->where('((fromID = ' . $this->session->userdata('userID') . ' AND toID = ' . $userID . ') OR (fromID = ' . $userID . ' AND toID = ' . $this->session->userdata('userID') . ')) AND status = 1')->limit(1)->get('friendships')->num_rows() > 0)
		{
			$this->db->where('((fromID = ' . $this->session->userdata('userID') . ' AND toID = ' . $userID . ') OR (fromID = ' . $userID . ' AND toID = ' . $this->session->userdata('userID') . ')) AND status = 1')->limit(1);
			if($this->db->limit(1)->delete('friendships'))
			{
				$this->db->delete('notifications', array('fromID' => $this->session->userdata('userID'), 'toID' => $userID, 'type' => 'friendship'));
				
				return 'removed';
			}
			else
			{
				return false;
			}
		}
		elseif($this->db->where('((fromID = ' . $this->session->userdata('userID') . ' AND toID = ' . $userID . ') OR (fromID = ' . $userID . ' AND toID = ' . $this->session->userdata('userID') . ')) AND status = 0')->get('friendships')->num_rows() > 0)
		{
			$this->db->where('((fromID = ' . $this->session->userdata('userID') . ' AND toID = ' . $userID . ') OR (fromID = ' . $userID . ' AND toID = ' . $this->session->userdata('userID') . ')) AND status = 0')->limit(1);
			if($this->db->where('((fromID = ' . $this->session->userdata('userID') . ' AND toID = ' . $userID . ') OR (fromID = ' . $userID . ' AND toID = ' . $this->session->userdata('userID') . ')) AND status = 0')->limit(1)->update('friendships', array('status' => 1)))
			{
				$notification = array(
					'fromID' => $this->session->userdata('userID'),
					'toID' => getUseridByUsername($userName),
					'targetURL' => base_url(getUsernameByID($this->session->userdata('userID'))),
					'type' => 'confirmed',
					'timestamp' => time()
				);
				$this->db->insert('notifications', $notification);
				
				return 'confirmed';
			}
			else
			{
				return false;
			}
		}
		else
		{
			$data	= array(
				'fromID'	=> $this->session->userdata('userID'),
				'toID'		=> getUseridByUsername($userName),
				'timestamp'	=> time()
			);
			if($this->db->insert('friendships', $data))
			{
				$notification = array(
					'fromID' => $this->session->userdata('userID'),
					'toID' => getUseridByUsername($userName),
					'targetURL' => base_url(getUsernameByID($this->session->userdata('userID'))),
					'type' => 'friendship',
					'timestamp' => time()
				);
				$this->db->insert('notifications', $notification);
				return 'added';
			}
			else
			{
				return false;
			}
		}
	}
	
	function loadNotifications()
	{
		$this->db->where('toID', $this->session->userdata('userID'));
		$this->db->where('fromID !=', $this->session->userdata('userID'));
		$this->db->order_by('timestamp', 'desc');
		$this->db->limit(10);
		$query = $this->db->get('notifications');
		if($query->num_rows() > 0)
		{
			$data = array(
				'alert' => 1
			);
			$this->db->where('toID', $this->session->userdata('userID'));
			$this->db->update('notifications', $data);
			
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
}