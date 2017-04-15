<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Openletters extends CI_Controller
{
	public $settings = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Openletters_model', 'model');
		
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
	
	function index($limit = 10, $offset = 0)
	{
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		
		$data['meta']		= array(
			'title' 		=> phrase('open_letters'),
			'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
			'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
			'image'			=> guessImage('openletters'),
			'author'		=> $this->settings['siteTitle']
		);
		if($this->input->is_ajax_request())
		{
			$this->output->set_content_type('application/json');
			$this->output->set_output(
				json_encode(
					array(
						'meta'		=> $data['meta'],
						'html'		=> $this->load->view('openletters/posts', $data, true)
					)
				)
			);
		}
		else
		{
			$this->template->set_partial('navigation', 'dashboard_navigation');
			$this->template->build('openletters/posts', $data);
		}
	}
	
	function add()
	{
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		if($this->input->post('hash'))
		{
			$this->form_validation->set_rules('title', phrase('letter_headline'), 'trim|xss_clean|required|is_unique[openletters.title]|max_length[260]');
			$this->form_validation->set_rules('targetName', phrase('aimed_to'), 'trim|xss_clean|required');
			$this->form_validation->set_rules('targetDetails', phrase('target_details'), 'trim|xss_clean|required');
			$this->form_validation->set_rules('content', phrase('letter_content'), 'trim|xss_clean|required|min_length[160]');
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				if (null !== $this->input->post('openletterHeadline') && $this->input->post('openletterHeadline') == 'Y') {
					$headline = 'Y';
				} else {
					$headline = 'N';
				}
				$fields = array(
					'contributor'	=> $this->session->userdata('userID'),
					'title'			=> $this->input->post('title'),
					'slug'			=> format_uri($this->input->post('title')),
					'content'		=> $this->input->post('content'),
					'targetName'	=> $this->input->post('targetName'),
					'targetDetails'	=> $this->input->post('targetDetails'),
					'headline'		=> $headline,
					'language'		=> $this->session->userdata('language'),
					'timestamp'		=> time()
				);
				if($this->model->createPost($fields))
				{
					$this->session->set_flashdata('success', phrase('letter_was_successfully_submitted'));
					echo json_encode(array("status" => 200, "redirect" => base_url('openletters/' . format_uri($this->input->post('title')))));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_submit_letter')));
				}
			}
		}
		else
		{
			$data['meta']		= array(
				'title' 		=> phrase('add_open_letter'),
				'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
				'image'			=> guessImage('openletters'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				if(null != $this->input->post('modal'))
				{
					$data['modal']	= true;
					$this->load->view('openletters/posts_add', $data);
				}
				else
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('openletters/posts_add', $data, true)
							)
						)
					);
				}
			}
			else
			{
				$this->template->build('openletters/posts_add', $data);
			}
	  	}
	}
	
	function edit()
	{
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		if($this->input->post('hash'))
		{
			$this->form_validation->set_rules('title', phrase('letter_headline'), 'trim|xss_clean|required|is_unique[openletters.title.slug.'.$this->uri->segment(4).']|max_length[260]');
			$this->form_validation->set_rules('targetName', phrase('aimed_to'), 'trim|xss_clean|required');
			$this->form_validation->set_rules('targetDetails', phrase('target_details'), 'trim|xss_clean|required');
			$this->form_validation->set_rules('content', phrase('content'), 'trim|xss_clean|required|min_length[160]');
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				if (null !== $this->input->post('openletterHeadline') && $this->input->post('openletterHeadline') == 'Y') {
					$headline = 'Y';
				} else {
					$headline = 'N';
				}
				$fields = array(
					'title'			=> $this->input->post('title'),
					'slug'			=> format_uri($this->input->post('title')),
					'content'		=> $this->input->post('content'),
					'targetName'	=> $this->input->post('targetName'),
					'targetDetails'	=> $this->input->post('targetDetails'),
					'headline'		=> $headline,
					'language'		=> $this->session->userdata('language'),
					'timestamp'		=> time()
				);
		
				if($this->model->updatePost($fields, $this->uri->segment(4)))
				{
					$this->session->set_flashdata('success', phrase('letter_was_updated_successfully'));
					echo json_encode(array("status" => 200, "redirect" => base_url('user/openletters')));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_update_letter')));
				}
			}
		}
		else
		{
			$data['post'] 		= $this->model->getPost($this->uri->segment(4));
			$data['meta']		= array(
				'title' 		=> phrase('edit_open_letter'),
				'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
				'image'			=> guessImage('openletters'),
				'author'		=> $this->settings['siteTitle']
			);
			
			if($this->input->is_ajax_request())
			{
				if(null != $this->input->post('modal'))
				{
					$data['modal']	= true;
					$this->load->view('openletters/posts_edit', $data);
				}
				else
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('openletters/posts_edit', $data, true)
							)
						)
					);
				}
			}
			else
			{
				$this->template->build('openletters/posts_edit', $data);
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