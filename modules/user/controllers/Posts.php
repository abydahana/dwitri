<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts extends CI_Controller
{
	public $settings = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Posts_model', 'model');
		
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
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		
		$data['meta']		= array(
			'title' 		=> phrase('posts'),
			'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
			'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
			'image'			=> guessImage('posts'),
			'author'		=> $this->settings['siteTitle']
		);
		if($this->input->is_ajax_request())
		{
			$this->output->set_content_type('application/json');
			$this->output->set_output(
				json_encode(
					array(
						'meta'		=> $data['meta'],
						'html'		=> $this->load->view('posts/posts', $data, true)
					)
				)
			);
		}
		else
		{
			$this->template->set_partial('navigation', 'dashboard_navigation');
			$this->template->build('posts/posts', $data);
		}
	}
	
	function add()
	{
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		if($this->input->post('hash'))
		{
			$this->form_validation->set_rules('postTitle', phrase('post_title'), 'trim|xss_clean|required|is_unique[posts.postTitle]|min_length[10]|max_length[260]');
			$this->form_validation->set_rules('content', phrase('content'), 'trim|xss_clean|required|min_length[160]');
			$this->form_validation->set_rules('categoryID[]', phrase('category_id'), 'trim|xss_clean|required');
			$this->form_validation->set_rules('tags', phrase('tags'), 'trim|xss_clean|max_length[160]|callback_alphaCheck');
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				if (null !== $this->input->post('postHeadline') && $this->input->post('postHeadline') == 'Y')
				{
					$headline = 'Y';
				}
				else
				{
					$headline = 'N';
				}
				$fields = array(
					'contributor'		=> $this->session->userdata('userID'),
					'postTitle'			=> $this->input->post('postTitle'),
					'categoryID'		=> json_encode($this->input->post('categoryID')),
					'postSlug'			=> format_uri($this->input->post('postTitle')),
					'postContent'		=> $this->input->post('content'),
					'postExcerpt'		=> truncate($this->input->post('content'), 260),
					'tags'				=> str_replace(' ', '', $this->input->post('tags')),
					'postHeadline'		=> $headline,
					'language'			=> $this->session->userdata('language'),
					'timestamp'			=> time()
				);
				if($this->model->createPost($fields))
				{
					$this->session->set_flashdata('success', phrase('article_was_submitted_successfully'));
					echo json_encode(array("status" => 200, "redirect" => base_url('posts/' . format_uri($this->input->post('postTitle')))));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_save_article')));
				}
			}
		}
		else
		{
			$data['current'] 		= $this->uri->segment(2);
			$data['post'] 			= $this->model->getPost($this->uri->segment(4));
			$data['categories']		= $this->model->getCategories();
			$data['meta']			= array(
				'title' 			=> phrase('add_article'),
				'descriptions'		=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'			=> 'post, dwitri, blogs, article, social, blogging',
				'image'				=> guessImage('posts'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				if(null != $this->input->post('modal'))
				{
					$data['modal']	= true;
					$this->load->view('posts/posts_add', $data);
				}
				else
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('posts/posts_add', $data, true)
							)
						)
					);
				}
			}
			else
			{
				$this->template->build('posts/posts_add', $data);
			}
	  	}
	}
	
	function edit()
	{
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		if($this->input->post('hash'))
		{
			$this->form_validation->set_rules('postTitle', phrase('post_title'), 'trim|xss_clean|required|is_unique[posts.postTitle.postSlug.'.$this->uri->segment(4).']|min_length[10]|max_length[260]');
			$this->form_validation->set_rules('content', phrase('content'), 'trim|xss_clean|required|min_length[160]');
			$this->form_validation->set_rules('categoryID[]', phrase('category_id'), 'trim|xss_clean|required');
			$this->form_validation->set_rules('tags', phrase('tags'), 'trim|xss_clean|max_length[160]|callback_alphaCheck');
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				if (null !== $this->input->post('postHeadline') && $this->input->post('postHeadline') == 'Y')
				{
					$headline = 'Y';
				}
				else
				{
					$headline = 'N';
				}
				$fields = array(
					'postTitle'			=> $this->input->post('postTitle'),
					'categoryID'		=> json_encode($this->input->post('categoryID')),
					'postSlug'			=> format_uri($this->input->post('postTitle')),
					'postContent'		=> $this->input->post('content'),
					'postExcerpt'		=> truncate($this->input->post('content'), 260),
					'tags'				=> str_replace(' ', '', $this->input->post('tags')),
					'postHeadline'		=> $headline,
					'language'			=> $this->session->userdata('language'),
					'timestamp'			=> time()
				);
				if($this->model->updatePost($fields, $this->uri->segment(4)))
				{
					$this->session->set_flashdata('success', phrase('article_was_updated_successfully'));
					echo json_encode(array("status" => 200, "redirect" => base_url('user/posts')));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_update_article')));
				}
			}
		}
		else
		{
			$data['current'] 		= $this->uri->segment(2);
			$data['post'] 			= $this->model->getPost($this->uri->segment(4));
			$data['categories']		= $this->model->getCategories();
			$data['meta']			= array(
				'title' 			=> phrase('edit_article'),
				'descriptions'		=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'			=> 'post, dwitri, blogs, article, social, blogging',
				'image'				=> guessImage('posts'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				if(null != $this->input->post('modal'))
				{
					$data['modal']	= true;
					$this->load->view('posts/posts_edit', $data);
				}
				else
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('posts/posts_edit', $data, true)
							)
						)
					);
				}
			}
			else
			{
				$this->template->build('posts/posts_edit', $data);
			}
	  	}
	}
	
	function remove()
	{
		if(!$this->input->is_ajax_request()) return error(404, ($this->input->is_ajax_request() ? 'ajax' : null));
		
		if(!$this->session->userdata('loggedIn'))
		{
			echo json_encode(array('status' => 403));
		}
		else
		{
			if($this->model->removePost($this->uri->segment(4)))
			{
				echo json_encode(array('status' => 200));
			}
			else
			{
				echo json_encode(array('status' => 500));
			}
		}
	}
	
	function alphaCheck($str = null) 
	{
		if($str != null)
		{
			if(!preg_match('/^[a-z, \-]+$/i',$str))
			{
				$this->form_validation->set_message('alphaCheck', phrase('tags_contain_unsupported_characters')); 
				return false;
			}
		}
		else
		{
			return true;
		}
	}
}