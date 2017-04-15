<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{
	public $settings = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model(array('User_model', 'Actions_model'));
		
		/* CACHE CONTROL*/
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
		
		$this->settings = globalSettings();
		
		if(!$this->session->userdata('online'))
		{
			$ip		= getenv('remote_addr');
			$this->session->set_userdata('online', TRUE);
			insertVisitor();
		}
	}
	
	public function _remap($method = null)
	{
		if(method_exists($this, $method))
		{
			call_user_func(array($this, $method));
			return false;
		}
		else
		{
			$this->index($method);
		}
	}
	
	function index()
	{
		$slug		= $this->uri->segment(1);
		$type		= $this->uri->segment(2);
		$limit		= $this->uri->segment(3);
		$offset		= $this->uri->segment(4);
		
		if($this->User_model->getUser($slug))
		{
			if($type == 'followers')
			{
				$data['profile']	= $this->User_model->getUser($slug);
				$data['followers']	= $this->User_model->getUserFollowers($slug, $limit, $offset);
				$data['meta']		= array(
					'title' 		=> $this->User_model->getUserTitle($slug),
					'descriptions'	=> $this->User_model->getUserExcerpt($slug),
					'keywords'		=> $this->User_model->getUserTags($slug),
					'image'			=> guessImage('users', $slug),
					'author'		=> $this->settings['siteTitle']
				);
				if($this->input->is_ajax_request())
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('followers', $data, true)
							)
						)
					);
				}
				else
				{
					$this->template->build('followers', $data);
				}
			}
			elseif($type == 'following')
			{
				$data['profile']	= $this->User_model->getUser($slug);
				$data['following']	= $this->User_model->getUserFollowing($slug, $limit, $offset);
				$data['meta']		= array(
					'title' 		=> $this->User_model->getUserTitle($slug),
					'descriptions'	=> $this->User_model->getUserExcerpt($slug),
					'keywords'		=> $this->User_model->getUserTags($slug),
					'image'			=> guessImage('users', $slug),
					'author'		=> $this->settings['siteTitle']
				);
				if($this->input->is_ajax_request())
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('following', $data, true)
							)
						)
					);
				}
				else
				{
					$this->template->build('following', $data);
				}
			}
			elseif($type == 'friends')
			{
				$data['profile']	= $this->User_model->getUser($slug);
				$data['friends']	= $this->User_model->getUserFriends($slug, $limit, $offset);
				$data['meta']		= array(
					'title' 		=> $this->User_model->getUserTitle($slug),
					'descriptions'	=> $this->User_model->getUserExcerpt($slug),
					'keywords'		=> $this->User_model->getUserTags($slug),
					'image'			=> guessImage('users', $slug),
					'author'		=> $this->settings['siteTitle']
				);
				if($this->input->is_ajax_request())
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('friends', $data, true)
							)
						)
					);
				}
				else
				{
					$this->template->build('friends', $data);
				}
			}
			else
			{
				$this->load->helper('timeline');
				$data['profile']	= $this->User_model->getUser($slug);
				$data['meta']		= array(
					'title' 		=> $this->User_model->getUserTitle($slug),
					'descriptions'	=> $this->User_model->getUserExcerpt($slug),
					'keywords'		=> $this->User_model->getUserTags($slug),
					'image'			=> guessImage('users', $slug),
					'author'		=> $this->settings['siteTitle']
				);
				if($this->input->is_ajax_request())
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('timeline', $data, true)
							)
						)
					);
				}
				else
				{
					$this->template->build('timeline', $data);
				}
			}
		}
		else
		{
			$data['meta']		= array(
				'title' 		=> phrase('welcome_back'),
				'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
				'image'			=> base_url('uploads/logo.png'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				$this->output->set_content_type('application/json');
				$this->output->set_output(
					json_encode(
						array(
							'meta'		=> $data['meta'],
							'html'		=> $this->load->view('home', $data, true)
						)
					)
				);
			}
			else
			{
				$this->template->build('home', $data);
			}
		}
	}
	
	function dashboard()
	{
		if(!$this->session->userdata('loggedIn')) return error(403);
		$data['meta']			= array(
			'title' 			=> phrase('dashboard'),
			'descriptions'		=> phrase('whatever_you_writing_for_is_a_reportations'),
			'keywords'			=> 'post, dwitri, blogs, article, social, blogging',
			'image'				=> guessImage('users'),
			'author'			=> $this->settings['siteTitle']
		);
        $data['visitors']		= $this->stats('visitors');
        $data['posts']			= $this->stats('posts');
        $data['snapshots']		= $this->stats('snapshots');
        $data['openletters']	= $this->stats('openletters');
		if($this->input->is_ajax_request())
		{
			$this->output->set_content_type('application/json');
			$this->output->set_output(
				json_encode(
					array(
						'meta'		=> $data['meta'],
						'html'		=> $this->load->view('dashboard', $data, true)
					)
				)
			);
		}
		else
		{
			$this->template->set_partial('navigation', 'dashboard_navigation');
			$this->template->build('dashboard', $data);
		}
	}
	
	function updates()
	{
		if(!$this->input->is_ajax_request()) return error(404);
		
		if(!$this->session->userdata('loggedIn'))
		{
			echo json_encode(array('status' => 403));
		}
		else
		{
			$this->form_validation->set_rules('content', phrase('update_content'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('visibility', phrase('visibility'), 'trim|required|numeric|xss_clean');
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else 
			{
				$content 	= $this->input->post('content');
				$visibility	= $this->input->post('visibility');
				$exec		= $this->Actions_model->statusUpdate();
				if($exec)
				{
					$updateID = $exec;
					echo json_encode(array('status' => 200, 'html' => $this->updates_markup($updateID, $content, $visibility)));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_update_your_status')));
				}
			}
		}
	}
	
	function comment()
	{
		if(!$this->input->is_ajax_request()) return error(404);
		
		if(!$this->session->userdata('loggedIn'))
		{
			echo json_encode(array('status' => 403));
		}
		else
		{
			$type 	= $this->uri->segment(3);
			$postID = $this->uri->segment(4);
			if($this->input->post('hash') && sha1('p0st' . $postID) == $this->input->post('hash'))
			{
				$this->form_validation->set_rules('comments', phrase('comments'), 'trim|required|xss_clean');
				
				if($this->form_validation->run() == FALSE)
				{
					echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
				}
				else 
				{
					$comments  	= $this->input->post('comments');
					$exec		= $this->Actions_model->submitComment($type, $postID, $comments);
					if($exec)
					{
						$commentID = $exec;
						echo json_encode(array('status' => 200, 'count' => countComments($type, $postID), 'html' => $this->comment_markup($type, $postID, $commentID, $comments)));
					}
					else
					{
						echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_submit_your_comment')));
					}
				}
			}
			else
			{
				return error(404);
			}
		}
	}
	
	function like()
	{
		if(!$this->input->is_ajax_request()) return error(404);
		
		if(!$this->session->userdata('loggedIn'))
		{
			echo json_encode(array('status' => 403));
		}
		else
		{
			$type 	= $this->uri->segment(3);
			$postID = $this->uri->segment(4);
			$exec	= $this->Actions_model->likePost($type, $postID);
			if($exec == 'liked')
			{
				echo json_encode(array('status' => 'liked', 'count' => countLikes($type, $postID)));
			}
			elseif($exec == 'disliked')
			{
				echo json_encode(array('status' => 'disliked', 'count' => countLikes($type, $postID)));
			}
			else
			{
				echo json_encode(array('status' => 500, 'count' => countLikes($type, $postID)));
			}
		}
	}
	
	function repost()
	{
		if(!$this->input->is_ajax_request()) return error(404);
		
		if(!$this->session->userdata('loggedIn'))
		{
			echo json_encode(array('status' => 403));
		}
		else
		{
			$type 	= $this->uri->segment(3);
			$postID = $this->uri->segment(4);
			
			$this->form_validation->set_rules('messages', phrase('messages'), 'trim|xss_clean');
			
			if($this->form_validation->run() == FALSE)
			{
				if($type == 'updates')
				{
					$data['type']	= 'update_repost';
				}
				elseif($type == 'posts')
				{
					$data['type']	= 'post_repost';
				}
				elseif($type == 'snapshots')
				{
					$data['type']	= 'snapshot_repost';
				}
				elseif($type == 'openletters')
				{
					$data['type']	= 'openletter_repost';
				}
				elseif($type == 'tv')
				{
					$data['type']	= 'channel_repost';
				}
				else
				{
					$data['type']	= null;
				}
				$data['modal']		= arrayPostByID($type, $postID);
				$this->load->view('modal', $data);
			}
			else
			{
				if($this->Actions_model->rePost($type, $postID, $this->input->post('messages')))
				{
					echo json_encode(array('status' => 200, 'repost' => 1, 'count' => countReposts($type, $postID), 'messages' => phrase('repost_success')));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('error_to_repost')));
				}
			}
		}
	}
	
	function remove()
	{
		if(!$this->input->is_ajax_request()) return error(404);
		
		if(!$this->session->userdata('loggedIn'))
		{
			echo json_encode(array('status' => 403));
		}
		else
		{
			$type		= $this->uri->segment(3);
			$itemID		= $this->uri->segment(4);
			$suffix		= $this->uri->segment(5);
			$postType	= substr($suffix, 0, strrpos($suffix, '-'));
			$postID		= substr($suffix, strrpos($suffix, '-') + 1);
			
			if($this->Actions_model->removeAny($type, $itemID, $suffix))
			{
				if($suffix)
				{
					echo json_encode(array('status' => 200, 'messages' => phrase($type . '_was_successfully_removed'), 'count' => countComments($postType, $postID)));
				}
				else
				{
					echo json_encode(array('status' => 200, 'messages' => phrase($type . '_was_successfully_removed'), 'count' => countComments($type, $itemID)));
				}
			}
			else
			{
				if($suffix)
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_delete_this_' . $type), 'count' => countComments($postType, $postID)));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_delete_this_' . $type), 'count' => countComments($type, $itemID)));
				}
			}
		}
	}
	
	function report()
	{
		if(!$this->input->is_ajax_request()) return error(404);
		
		if(!$this->session->userdata('loggedIn'))
		{
			echo json_encode(array('status' => 403));
		}
		else
		{
			$type		= $this->uri->segment(3);
			$itemID		= $this->uri->segment(4);
			
			if($this->Actions_model->reportAny($type, $itemID))
			{
				echo json_encode(array('status' => 200, 'messages' => phrase($type . '_was_successfully_reported'), 'count' => countComments($type, $itemID)));
			}
			else
			{
				echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_report_this_' . $type), 'count' => countComments($type, $itemID)));
			}
		}
	}
	
	function follow()
	{
		if(!$this->input->is_ajax_request()) return error(404);
		
		if(!$this->session->userdata('loggedIn'))
		{
			echo json_encode(array('status' => 403));
		}
		else
		{
			$userName 	= $this->uri->segment(3);
			$exec		= $this->Actions_model->userFollow($userName);
			if($exec == 'followed')
			{
				echo json_encode(array('status' => 'followed', 'count' => getUserFollowers('followers', getUseridByUsername($userName))));
			}
			elseif($exec == 'unfollowed')
			{
				echo json_encode(array('status' => 'unfollowed', 'count' => getUserFollowers('followers', getUseridByUsername($userName))));
			}
			else
			{
				echo json_encode(array('status' =>500, 'count' => getUserFollowers('followers', getUseridByUsername($userName))));
			}
		}
	}
	
	function friendship()
	{
		if(!$this->input->is_ajax_request()) return error(404);
		
		if(!$this->session->userdata('loggedIn'))
		{
			echo json_encode(array('status' => 403));
		}
		else
		{
			$userName 	= $this->uri->segment(3);
			$exec		= $this->Actions_model->friendAdd($userName);
			if($exec == 'added')
			{
				echo json_encode(array('status' => 'added', 'count' => getUserFriends('active', getUseridByUsername($userName))));
			}
			elseif($exec == 'confirmed')
			{
				echo json_encode(array('status' => 'confirmed', 'count' => getUserFriends('active', getUseridByUsername($userName))));
			}
			elseif($exec == 'cancelled')
			{
				echo json_encode(array('status' => 'cancelled', 'count' => getUserFriends('active', getUseridByUsername($userName))));
			}
			elseif($exec == 'removed')
			{
				echo json_encode(array('status' => 'removed', 'count' => getUserFriends('active', getUseridByUsername($userName))));
			}
			else
			{
				echo json_encode(array('status' => 500, 'count' => getUserFriends('active', getUseridByUsername($userName))));
			}
		}
	}
	
	function notifications($limit = 10, $offset = 0)
	{
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		
		$fetch = $this->Actions_model->loadNotifications();
		
		$data['meta']		= array(
			'title' 		=> phrase('notifications'),
			'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
			'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
			'image'			=> guessImage('users'),
			'author'		=> $this->settings['siteTitle']
		);
		if($this->input->is_ajax_request())
		{
			$this->output->set_content_type('application/json');
			$this->output->set_output(
				json_encode(
					array(
						'meta'		=> $data['meta'],
						'html'		=> $this->load->view('notifications', $data, true)
					)
				)
			);
		}
		else
		{
			$this->template->set_partial('navigation', 'dashboard_navigation');
			$this->template->build('notifications', $data);
		}
	}
	
	function load_alerts()
	{
		if(!$this->input->is_ajax_request()) return error(404);
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		
		$fetch = $this->Actions_model->loadNotifications();
		if($fetch)
		{
			$html 	= '';
			$n		= 0;
			foreach($fetch as $row)
			{
				if($row['notificationType'] == 0)
				{
					$type		= 'updates';
				}
				elseif($row['notificationType'] == 1)
				{
					$type		= 'article';
				}
				elseif($row['notificationType'] == 2)
				{
					$type		= 'snapshot';
				}
				elseif($row['notificationType'] == 3)
				{
					$type		= 'openletter';
				}
				elseif($row['notificationType'] == 4)
				{
					$type		= 'channel';
				}
				
				if($row['type'] == 'comment')
				{
					$icon		= 'comments text-info';
					$actions 	= phrase('commented_on_' . $type);
				}
				elseif($row['type'] == 'like')
				{
					$icon		= 'thumbs-up text-success';
					$actions 	= phrase('liking_on_' . $type);
				}
				elseif($row['type'] == 'repost')
				{
					$icon		= 'retweet text-info';
					$actions 	= phrase('reposting_' . $type);
				}
				elseif($row['type'] == 'following')
				{
					$icon		= 'refresh text-warning';
					$actions 	= phrase('is_following_you');
				}
				elseif($row['type'] == 'friendship')
				{
					$icon		= 'user-plus text-warning';
					$actions 	= phrase('requesting_friendship_to_you');
				}
				elseif($row['type'] == 'confirmed')
				{
					$icon		= 'check-circle text-info';
					$actions 	= phrase('accepted_your_friend_request');
				}
				else
				{
					$icon		= 'info';
					$actions 	= null;
				}
					
				$html .= '
					<a href="' . $row['targetURL'] . '" class="ajaxLoad">
						<div class="row" style="' . ($n++ != 0 ? 'border-top:1px solid #ddd;' : '') . 'margin-right:0;margin-left:0;' . ($row['status'] == 0 ? 'color:#000' : 'color:#aaa') . '">
							<div class="col-xs-2 nomargin">
								<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($row['fromID']), 1)) . '" width="30" height="30" style="margin-top:6px" class="img-rounded img-bordered alt="..." />
							</div>
							<div class="col-xs-10 nomargin">
								<small>
									<b>' . getFullnameByID($row['fromID']) . '</b>
									' . $actions . ' <b>' . truncate(notificationPostTitle($row['notificationType'], $row['itemID']), 50 - strlen(getFullnameByID($row['fromID']))) . '</b><br />
									<i class="fa fa-' . $icon . '"></i> <span class="tex-muted" style="font-size:11px">' . time_since($row['timestamp']) . '</span>
								</small>
							</div>
						</div>
					</a>
				';
				$n++;
			}
			echo json_encode(array('status' => 200, 'html' => $html));
		}
		else
		{
			echo json_encode(array('status' => null, 'html' => phrase('you_do_not_have_any_notification')));
		}
	}
	
	function alerts()
	{
		if(!$this->input->is_ajax_request()) return error(404);
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		
		$fetch = countAlert($this->session->userdata('userID'));
		if($fetch > 0)
		{
			echo json_encode(array('status' => 200, 'count' => $fetch));
		}
		else
		{
			echo json_encode(array('status' => null, 'count' => 0));
		}
	}
	
	function remove_alert()
	{
		if(!$this->input->is_ajax_request()) return error(404);
		
		if(!$this->session->userdata('loggedIn'))
		{
			echo json_encode(array('status' => 403));
		}
		else
		{
			if($this->Actions_model->removeNotification($this->uri->segment(3)))
			{
				echo json_encode(array('status' => 200));
			}
			else
			{
				echo json_encode(array('status' => 500));
			}
		}
	}
	
	function remove_alerts()
	{
		if(!$this->input->is_ajax_request()) return error(404);
		
		if(!$this->session->userdata('loggedIn'))
		{
			echo json_encode(array('status' => 403));
		}
		else
		{
			if($this->Actions_model->removeNotifications())
			{
				echo json_encode(array('status' => 200));
			}
			else
			{
				echo json_encode(array('status' => 500));
			}
		}
	}
	
    function translate()
    {
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		
		$language	= $this->uri->segment(3);
		$offset		= $this->uri->segment(4);
		if(null != $this->input->post('hash'))
		{
			$this->form_validation->set_rules('phrase_start', phrase('number_of_phrase'), 'numeric|required');
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				$start	= $this->input->post('phrase_start');
				for($i = $start; $i <= $start+60 ; $i++)
				{
					$this->db->where('phrase_id' , $i);
					if(!$this->db->update('language' , array($language => htmlspecialchars($this->input->post('phrase'.$i))))) continue;
				}
				$this->session->set_flashdata('success', phrase('translation_was_updated_successfully'));
				echo json_encode(array("status" => 200, "redirect" => current_url() . '/' . $start));
			}
		}
		else
		{
			if($language == 'delete_language')
			{
				$this->load->dbforge();
				$this->dbforge->drop_column('language', $offset);
				$this->session->set_flashdata('success', phrase('settings_updated'));
				
				redirect_back();
			}
			else
			{
				$data['edit_phrase']	= $language;
				$data['phrase']			= $this->db->limit(60, ($offset ? $offset : 0))->get('language')->result_array();
				$data['profile']		= $this->User_model->getOwnProfile();
				$data['meta']			= array(
					'title' 			=> phrase('became_translator'),
					'descriptions'		=> phrase('whatever_you_writing_for_is_a_reportations'),
					'keywords'			=> 'post, dwitri, blogs, article, social, blogging',
					'image'				=> guessImage('users'),
					'author'			=> $this->settings['siteTitle']
				);
				if($this->input->is_ajax_request())
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('translate/translate', $data, true)
							)
						)
					);
				}
				else
				{
					$this->template->set_partial('navigation', 'dashboard_navigation');
					$this->template->build('translate/translate', $data);
				}
			}
		}
    }
	
	function language()
	{
		$translation = $this->uri->segment(3);
		if($translation != null)
		{
			if($this->db->field_exists($translation, 'language'))
			{
				$this->session->set_userdata('language', $translation);
				if($this->session->userdata('loggedIn'))
				{
					$this->User_model->setLanguage();
				}
				redirect_back();
			}
			else
			{
				return error(404);
			}
		}
		else
		{
			return error(404);
		}
	}
	
	function edit_profile()
	{
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		
		if($this->input->post('hash') && sha1($this->session->userdata('userName')) == $this->input->post('hash'))
		{
			$this->form_validation->set_rules('full_name', phrase('full_name'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('gender', phrase('gender'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('age', phrase('age'), 'trim|required|xss_clean|numeric');
			$this->form_validation->set_rules('mobile', phrase('mobile'), 'trim|required|xss_clean|numeric');
			$this->form_validation->set_rules('address', phrase('address'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('language', phrase('language'), 'trim|xss_clean');
			$this->form_validation->set_rules('bio', phrase('address'), 'trim|xss_clean');
			$this->form_validation->set_rules('email', phrase('email_address'), 'trim|required|valid_email|is_unique[users.email.userID.'.$this->session->userdata('userID').']');
			
			if($this->session->userdata('newRegister'))
			{
				$this->form_validation->set_rules('username', phrase('username'), 'trim|required|alpha_dash|is_unique[users.userName.userID.'.$this->session->userdata('userID').']');
			}
			
			if(null != $this->input->post('password'))
			{
				$this->form_validation->set_rules('password', phrase('password'), 'trim|required|min_length[4]|max_length[32]');
				$this->form_validation->set_rules('con_password', phrase('confirmation_password'),'trim|required|matches[password]');
			}
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				$fields = array(
					'full_name'			=> $this->input->post('full_name'),
					'gender'			=> $this->input->post('gender'),
					'age'				=> $this->input->post('age'),
					'mobile'			=> $this->input->post('mobile'),
					'address'			=> $this->input->post('address'),
					'email'				=> $this->input->post('email'),
					'language'			=> $this->input->post('language'),
					'bio'				=> $this->input->post('bio')
				);
				if($this->session->userdata('newRegister'))
				{
					$fields['userName']	= strtolower(str_replace(' ', '_', $this->input->post('username')));
				}
				if(null != $this->input->post('con_password'))
				{
					$fields['password']	= sha1($this->input->post('con_password') . SALT);
				}
		
				if($this->Actions_model->updateProfile($fields))
				{
					$this->session->set_flashdata('success', phrase('profile_was_updated_successfully'));
					echo json_encode(array("status" => 200, "redirect" => current_url()));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_update_your_profile')));
				}
			}
		}
		else
		{
			$data['profile']	= $this->User_model->getOwnProfile();
			$data['meta']		= array(
				'title' 		=> phrase('edit_profile'),
				'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
				'image'			=> guessImage('users'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				$this->output->set_content_type('application/json');
				$this->output->set_output(
					json_encode(
						array(
							'meta'		=> $data['meta'],
							'html'		=> $this->load->view('edit_profile', $data, true)
						)
					)
				);
			}
			else
			{
				$this->template->set_partial('navigation', 'dashboard_navigation');
				$this->template->build('edit_profile', $data);
			}
		}
	}
	
	function connect()
 	{
		if(!$this->session->userdata('loggedIn')) redirect();
		
		$user = $this->facebook->getUser();
		if($user)
		{
			$this->Actions_model->facebookConnect($user);
			$this->session->set_flashdata('success', phrase('account_connected_to_facebook'));
			redirect('user/edit_profile');
		}
		else
		{
			$redir = $this->facebook->getLoginUrl(array(
                'redirect_uri' => base_url('user/connect'),
				'scope' => array('email', 'user_birthday', 'user_location', 'user_work_history', 'user_about_me', 'user_hometown')
            ));
			redirect($redir);
		}
	}
	
	function login()
 	{
		if($this->session->userdata('loggedIn')) redirect($this->session->userdata('userName'));
		if($this->input->post('hash'))
		{
			$this->form_validation->set_rules('username', phrase('username_or_email'),'trim|required|xss_clean');
			$this->form_validation->set_rules('password', phrase('password'),'trim|required|xss_clean');
			$this->form_validation->set_rules('hash', phrase('hash'),'trim|required|xss_clean');
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				$username		= $this->input->post('username');
				$password		= sha1($this->input->post('password') . SALT);
				if($this->Actions_model->loginCheck($username, $password))
				{
					$this->session->set_flashdata('success', phrase('welcome_back') . ', ' . $this->session->userdata('full_name'));
					echo json_encode(array("status" => 200, "redirect" => (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['SERVER_NAME'])));
				}
				else
				{
					echo json_encode(array('status' => 406, 'messages' => phrase('username_or_password_did_not_match')));
				}
			}
		}
		else
		{
			$user = $this->facebook->getUser();
			if($user)
			{
				if($this->Actions_model->facebookLogin($user))
				{
					redirect($this->session->userdata('userName'));
				}
			}
			$data['meta']		= array(
				'title' 		=> phrase('login'),
				'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
				'image'			=> guessImage('users'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				$this->output->set_content_type('application/json');
				$this->output->set_output(
					json_encode(
						array(
							'meta'		=> $data['meta'],
							'html'		=> $this->load->view('login', $data, true)
						)
					)
				);
			}
			else
			{
				$this->template->build('login', $data);
			}
		}
	}
	
	function register()
 	{
		if($this->session->userdata('loggedIn')) redirect();
		
		if(null != $this->input->post('hash'))
		{
			$this->form_validation->set_rules('full_name', phrase('full_name'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('gender', phrase('gender'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('age', phrase('age'), 'trim|required|xss_clean|numeric');
			$this->form_validation->set_rules('mobile', phrase('mobile'), 'trim|required|xss_clean|numeric');
			$this->form_validation->set_rules('address', phrase('address'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('username', phrase('username'), 'trim|alpha_dash|required|is_unique[users.userName]');
			$this->form_validation->set_rules('email', phrase('email_address'), 'trim|required|valid_email|is_unique[users.email]');
			$this->form_validation->set_rules('password', phrase('password'), 'trim|required|min_length[4]|max_length[32]');
			$this->form_validation->set_rules('con_password', phrase('confirmation_password'),'trim|required|matches[password]');
			$this->form_validation->set_rules('language', phrase('language'), 'trim|xss_clean');
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				$fields = array(
					'full_name'			=> $this->input->post('full_name'),
					'gender'			=> $this->input->post('gender'),
					'age'				=> $this->input->post('age'),
					'mobile'			=> $this->input->post('mobile'),
					'address'			=> $this->input->post('address'),
					'userName'			=> strtolower(str_replace(' ', '_', $this->input->post('username'))),
					'email'				=> $this->input->post('email'),
					'password'			=> sha1($this->input->post('con_password'). SALT),
					'language'			=> $this->input->post('language'),
					'register_since'	=> date('Y-m-d H:i')
				);
				$username				= $this->input->post('email');
				$password				= sha1($this->input->post('con_password'). SALT);
				if($this->Actions_model->createProfile($fields, $username, $password))
				{
					$this->session->set_flashdata('success', phrase('your_account_was_created'));
					echo json_encode(array("status" => 200, "redirect" => base_url($fields['userName'])));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_registering_your_account')));
				}
			}
		}
		else
		{
			$user = $this->facebook->getUser();
			if($user)
			{
				if($this->Actions_model->facebookLogin($user))
				{
					redirect($this->session->userdata('userName'));
				}
			}
			
			$data['meta']		= array(
				'title' 		=> phrase('register'),
				'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
				'image'			=> guessImage('users'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				$this->output->set_content_type('application/json');
				$this->output->set_output(
					json_encode(
						array(
							'meta'		=> $data['meta'],
							'html'		=> $this->load->view('register', $data, true)
						)
					)
				);
			}
			else
			{
				$this->template->build('register', $data);
			}
		}
	}
	
	function uploads()
	{
		if(!$this->input->is_ajax_request()) return error(404);
		
		if(!$this->session->userdata('loggedIn'))
		{
			echo json_encode(array('status' => 403, 'messages' => phrase('please_login_to_change_photo_or_cover')));
		}
		else
		{
			$type = $this->uri->segment(3);
			
			if($type == 'photo')
			{
				$config['upload_path'] 	= 'uploads/users';
			}
			elseif($type == 'cover')
			{
				$config['upload_path'] 	= 'uploads/users/covers';
			}
			else
			{
				return false;
			}
			$config['allowed_types'] 	= 'jpg|jpeg|png';
			$config['max_size']      	= 1024*2; // 2MB
			$config['encrypt_name']	 	= TRUE;
			
			$this->upload->initialize($config); 
			
			if(!$this->upload->do_upload())
			{
				echo json_encode(array('status' => 1024, 'messages' => array(str_replace(array('<p>', '</p>'),'', $this->upload->display_errors()))));
			} 
			else
			{
				if($type == 'photo')
				{
					$this->upload_data['userfile'] = $this->upload->data();

					//upload successful generate a thumbnail
					$config['image_library'] 	= 'gd2';
					$config['source_image'] 	= 'uploads/users/' . $this->upload_data['userfile']['file_name'];
					$config['create_thumb'] 	= FALSE;
					$config['maintain_ratio'] 	= TRUE;
					$config['width']     		= 500;
					$config['height']   		= 500;

					$this->load->library('image_lib', $config);
					$this->image_lib->initialize($config);

					if($this->image_lib->resize())
					{
						$this->image_lib->clear();
						generateThumbnail('users', $this->upload_data['userfile']['file_name']);
					}
				}
				elseif($type == 'cover')
				{
					$this->upload_data['userfile'] = $this->upload->data();

					//upload successful generate a thumbnail
					$config['image_library'] 	= 'gd2';
					$config['source_image'] 	= 'uploads/users/covers/' . $this->upload_data['userfile']['file_name'];
					$config['create_thumb'] 	= FALSE;
					$config['maintain_ratio'] 	= TRUE;
					$config['width']     		= 1024;
					$config['height']   		= 400;

					$this->load->library('image_lib', $config);
					$this->image_lib->initialize($config);

					$this->image_lib->resize();
					$this->image_lib->clear();
				}
				
				$this->db->select($type);
				$this->db->where('userID', $this->session->userdata('userID'));
				$this->db->limit(1);
				$query = $this->db->get('users');
				
				if($query->num_rows() > 0)
				{
					foreach($query->result_array() as $row)
					{
						if($type == 'photo' && file_exists('uploads/users/' . $row['photo']))
						{
							unlink('uploads/users/' . $row['photo']);
							if(file_exists('uploads/users/thumbs/' . $row['photo']))
							{
								unlink('uploads/users/thumbs/' . $row['photo']);
							}
						}
						elseif($type == 'cover' && file_exists('uploads/users/covers/' . $row['cover']))
						{
							unlink('uploads/users/covers/' . $row['cover']);
						}
					}
				}
				
				if($this->Actions_model->updatePhoto($type, $this->upload_data['userfile']['file_name']))
				{
					echo json_encode(array('status' => 200, 'messages' => phrase($type . '_changed_successfully')));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_change_' . $type)));
				}
			}
		}
	}
	
	function logout()
	{
        $this->facebook->destroySession();
		
		foreach($this->session->all_userdata() as $key => $val)
		{
		   if($key != 'language') $this->session->unset_userdata($key);
		}
		//$this->session->sess_destroy();
		if($this->input->is_ajax_request())
		{
			$this->output->set_content_type('application/json');
			$this->output->set_output(
				json_encode(
					array(
						'redirect'	=> TRUE,
						'url'		=> base_url()
					)
				)
			);
		}
		else
		{
			redirect();
		}
	}

	function updates_markup($updateID = 0, $content = '', $visibility = 0)
	{
		return '
			<div id="update' . $updateID . '">
				<div class="image-placeholder">
					<div class="col-sm-12">
						<div class="row">
							<div class="col-xs-2 hidden-xs">
								<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($this->session->userdata('userID')), 1)) . '" style="height:40px;width:40px" class="img-rounded img-bordered" alt="" />
							</div>
							<div class="col-xs-10">
								<b>
									<a href="' . base_url(getUsernameByID($this->session->userdata('userID'))) . '">
										<b>' . getFullnameByID($this->session->userdata('userID')) . '</b>
									</a>
								</b>
								<a href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/updates/' . $updateID) . '\', \'update' . $updateID . '\', \'update' . $updateID . '\')" class="pull-right text-danger"><i class="fa fa-times"></i></a>
								<br />
								<small class="text-muted">@' . getUsernameByID($this->session->userdata('userID')) . ' - ' . time_since(time()) . '</small>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 nomargin">
								<p>
									' . special_parse($content) . '
								</p>
								<div class="btn-group btn-group-justified">
									<a href="#" class="btn btn-default ajax"><i class="fa fa-comments"></i> <span class="comments-count-updates' . $updateID . '">' . countComments('updates', $updateID) . '</span> ' . phrase('comments') . '</a>
									<a class="like like-updates' . $updateID . ' btn btn-default' . (is_userLike('updates', $updateID) ? ' active' : '') . '" href="' . base_url('user/like/updates/' . $updateID) . '" data-id="updates' . $updateID . '"><i class="like-icon fa fa-thumbs-up"></i> <span class="likes-count">' . countLikes('updates', $updateID) . '</span> ' . phrase('likes') . '</a>
									<a href="' . base_url('user/repost/updates/' . $updateID) . '" class="btn btn-default repost" data-id="' . $updateID . '"><i class="fa fa-retweet"></i> <span class="reposts-count' . $updateID . '">' . countReposts('updates', $updateID) . '</span> ' . phrase('reposts') . '</a>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 nomargin">
							
								' . getComments('updates', $updateID) . '
									
							</div>
						</div>
					</div>
				</div>
			</div>
		';
	}

	function comment_markup($type = null, $postID = 0, $commentID = 0, $content = '')
	{
		return '
			<div class="row comment-tree comment' . $commentID . '" style="border-bottom:1px solid #eee">
				<div class="col-xs-2 col-sm-1" style="padding-right:0">
					<img src="' . base_url('uploads/users/thumbs/' . imageCheck('users', getUserPhoto($this->session->userdata('userID')), 1)) . '" class="rounded" width="30" height="30" alt="..." />
				</div>
				<div class="col-xs-10 col-sm-11">
					<p class="comment-text relative">
						<a href="' . base_url(getUsernameByID($this->session->userdata('userID'))) . '" class="ajaxLoad hoverCard">
							<b>' . getFullnameByID($this->session->userdata('userID')) . '</b> &nbsp; 
						</a>
						<span id="rcomment' . $commentID . '">' . nl2br(special_parse($content)) . '</span>
						<br />
						<small class="comment-tools text-muted">
							<i class="fa fa-clock-o"></i>' . time_since(time()) . '
						</small>
						' . ($this->session->userdata('loggedIn') ? '
						<div class="btn-group absolute" style="right:10px;top:10px">
							' . ($this->session->userdata('userID') == $this->session->userdata('userID') ? '<a class="delete-comment btn btn-xs btn-default btn-icon-only" href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/remove/comments/' . $commentID . '/' . $type . '-' . $postID) . '\', \'comment' . $commentID . '\', \'' . $type . $postID . '\')" data-push="tooltip" data-plcement="top" data-title="' . phrase('remove') . '"><i class="fa fa-times"></i></a>' : '<a class="reply-comment btn btn-xs btn-default btn-icon-only" href="javascript:void(0)" data-reply="' . $commentID . '" data-summon="' . getUsernameByID($this->session->userdata('userID')) . '" data-push="tooltip" data-plcement="top" data-title="' . phrase('reply') . '"><i class="fa fa-reply"></i></a><a class="delete-comment btn btn-xs btn-default btn-icon-only" href="javascript:void(0)" onclick="confirm_modal(\'' . base_url('user/report/comments/' . $postID . '_' . time_since(time())) . '\')" data-push="tooltip" data-plcement="top" data-title="' . phrase('report') . '"><i class="fa fa-ban"></i></a>') . '
							' : '') . '
						</div>
					</p>
				</div>
			</div>
		';
	}
	
	function stats($type = null)
	{
		for ($i = (date('d') >= 6 ? date('d') - 6 : 1); $i <= date('d'); $i++) {
			$start_date = strtotime(date('Y-m-' . sprintf('%02d', $i) . ' 00:00:00'));
			$end_date = strtotime(date('Y-m-' . sprintf('%02d', $i) . ' H:i:s'));
			$get_all_report[$i] = $this->User_model->stats($type, $start_date, $end_date);
		}
		return $get_all_report;
	}
}
