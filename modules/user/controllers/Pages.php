<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller
{
	public $settings = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Pages_model', 'model');
		
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
		if(!$this->session->userdata('loggedIn') && $this->session->userdata('user_level') != 1) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		
		$data['meta']		= array(
			'title' 		=> phrase('pages'),
			'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
			'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
			'image'			=> guessImage('pages'),
			'author'		=> $this->settings['siteTitle']
		);
		if($this->input->is_ajax_request())
		{
			$this->output->set_content_type('application/json');
			$this->output->set_output(
				json_encode(
					array(
						'meta'		=> $data['meta'],
						'html'		=> $this->load->view('pages/posts', $data, true)
					)
				)
			);
		}
		else
		{
			$this->template->set_partial('navigation', 'dashboard_navigation');
			$this->template->build('pages/posts', $data);
		}
	}
	
	function add()
	{
		if(!$this->session->userdata('loggedIn') && $this->session->userdata('user_level') != 1) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		if($this->input->post('hash'))
		{
			$this->form_validation->set_rules('pageTitle', phrase('page_title'), 'trim|xss_clean|required|is_unique[pages.pageTitle]|max_length[260]');
			$this->form_validation->set_rules('content', phrase('content'), 'trim|xss_clean|required');
			$this->form_validation->set_rules('language', phrase('language'), 'trim|xss_clean|required');
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				$fields = array(
					'contributor'	=> $this->session->userdata('userID'),
					'pageTitle'		=> $this->input->post('pageTitle'),
					'pageSlug'		=> format_uri($this->input->post('pageTitle')),
					'pageContent'	=> $this->input->post('content'),
					'pageExcerpt'	=> truncate($this->input->post('content'), 260),
					'language'		=> $this->input->post('language'),
					'timestamp'		=> time()
				);
				if($this->model->createPost($fields))
				{
					$this->session->set_flashdata('success', phrase('page_was_created_successfully'));
					echo json_encode(array("status" => 200, "redirect" => base_url('pages/' . format_uri($this->input->post('pageTitle')))));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_add_page')));
				}
			}
		}
		else
		{
			$data['meta']			= array(
				'title' 			=> phrase('add_page'),
				'descriptions'		=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'			=> 'post, dwitri, blogs, article, social, blogging',
				'image'				=> guessImage('pages'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				if(null != $this->input->post('modal'))
				{
					$data['modal']	= true;
					$this->load->view('pages/posts_add', $data);
				}
				else
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('pages/posts_add', $data, true)
							)
						)
					);
				}
			}
			else
			{
				$this->template->build('pages/posts_add', $data);
			}
	  	}
	}
	
	function edit()
	{
		if(!$this->session->userdata('loggedIn') && $this->session->userdata('user_level') != 1) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		if($this->input->post('hash'))
		{
			$this->form_validation->set_rules('pageTitle', phrase('page_title'), 'trim|xss_clean|required|is_unique[pages.pageTitle.pageSlug.'.$this->uri->segment(4).']|max_length[260]');
			$this->form_validation->set_rules('content', phrase('content'), 'trim|xss_clean|required');
			$this->form_validation->set_rules('language', phrase('language'), 'trim|xss_clean|required');
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				$fields = array(
					'contributor'	=> $this->session->userdata('userID'),
					'pageTitle'		=> $this->input->post('pageTitle'),
					'pageSlug'		=> format_uri($this->input->post('pageTitle')),
					'pageContent'	=> $this->input->post('content'),
					'pageExcerpt'	=> truncate($this->input->post('content'), 260),
					'language'		=> $this->input->post('language'),
					'timestamp'		=> time()
				);
				if($this->model->updatePost($fields, $this->uri->segment(4)))
				{
					$this->session->set_flashdata('success', phrase('page_was_successfully_updated'));
					echo json_encode(array("status" => 200, "redirect" => base_url('user/pages')));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_update_page')));
				}
			}
		}
		else
		{
			$data['page'] 			= $this->model->getPost($this->uri->segment(4));
			$data['meta']			= array(
				'title' 			=> phrase('add_page'),
				'descriptions'		=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'			=> 'post, dwitri, blogs, article, social, blogging',
				'image'				=> guessImage('pages'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				if(null != $this->input->post('modal'))
				{
					$data['modal']	= true;
					$this->load->view('pages/posts_edit', $data);
				}
				else
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('pages/posts_edit', $data, true)
							)
						)
					);
				}
			}
			else
			{
				$this->template->build('pages/posts_edit', $data);
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
}