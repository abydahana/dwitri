<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tv extends CI_Controller
{
	public $settings = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Tv_model', 'model');
		
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
			'title' 		=> phrase('tv_channels'),
			'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
			'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
			'image'			=> guessImage('tv'),
			'author'		=> $this->settings['siteTitle']
		);
		if($this->input->is_ajax_request())
		{
			$this->output->set_content_type('application/json');
			$this->output->set_output(
				json_encode(
					array(
						'meta'		=> $data['meta'],
						'html'		=> $this->load->view('tv/posts', $data, true)
					)
				)
			);
		}
		else
		{
			$this->template->set_partial('navigation', 'dashboard_navigation');
			$this->template->build('tv/posts', $data);
		}
	}
	
	function add()
	{
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		if($this->input->post('hash'))
		{
			$this->form_validation->set_rules('title', phrase('channel_title'), 'trim|xss_clean|required|is_unique[tv.tvTitle]|max_length[255]');
			$this->form_validation->set_rules('content', phrase('channel_descriptions'), 'trim|xss_clean|required');
			$this->form_validation->set_rules('url', phrase('channel_url'), 'trim|xss_clean|required|valid_url|callback_real_url');
			$this->form_validation->set_rules('userfile', phrase('channel_logo'), 'callback_upload_checker');
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				$data = array(
					'tvTitle'		=> $this->input->post('title'),
					'tvContent'		=> $this->input->post('content'),
					'tvFile'		=> $this->upload_data['userfile']['file_name'],
					'tvURL'			=> $this->input->post('url'),
					'tvSlug'		=> format_uri($this->input->post('title')),
					'contributor'	=> $this->session->userdata('userID'),
					'language'		=> $this->session->userdata('language'),
					'timestamp'		=> time()
				);
				if($this->model->createPost($fields))
				{
					$this->session->set_flashdata('success', phrase('channel_was_submitted_successfully'));
					echo json_encode(array("status" => 200, "redirect" => base_url('tv/' . format_uri($this->input->post('title')))));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_submit_channel')));
				}
			}
		}
		else
		{
			$data['meta']			= array(
				'title' 			=> phrase('edit_channel'),
				'descriptions'		=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'			=> 'post, dwitri, blogs, article, social, blogging',
				'image'				=> guessImage('tv'),
				'author'			=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				if(null != $this->input->post('modal'))
				{
					$data['modal']	= true;
					$this->load->view('tv/posts_add', $data);
				}
				else
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('tv/posts_add', $data, true)
							)
						)
					);
				}
			}
			else
			{
				$this->template->build('tv/posts_add', $data);
			}
	  	}
	}
	
	function edit()
	{
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		if($this->input->post('hash'))
		{
			$this->form_validation->set_rules('title', phrase('channel_title'), 'trim|xss_clean|required|is_unique[tv.tvTitle.tvSlug.'.$this->uri->segment(4).']|max_length[255]');
			$this->form_validation->set_rules('content', phrase('channel_descriptions'), 'trim|xss_clean|required');
			$this->form_validation->set_rules('url', phrase('channel_url'), 'trim|xss_clean|required|valid_url|callback_real_url');
			if(!empty($_FILES['userfile']['name']))
			{
				$this->form_validation->set_rules('userfile', phrase('channel_logo'), 'callback_upload_checker');
			}
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				$fields = array(
					'tvTitle'			=> $this->input->post('title'),
					'tvContent'			=> $this->input->post('content'),
					'tvURL'				=> $this->input->post('url'),
					'tvSlug'			=> format_uri($this->input->post('title')),
					'language' 			=> $this->session->userdata('language'),
					'timestamp'			=> time(),
				);
				
				if(!empty($_FILES['userfile']['name']))
				{
					$fields['tvFile']	= $this->upload_data['userfile']['file_name'];
				}
				
				if($this->model->updatePost($fields, $this->uri->segment(4)))
				{
					$this->session->set_flashdata('success', phrase('channel_was_updated_successfully'));
					echo json_encode(array("status" => 200, "redirect" => base_url('user/tv')));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_update_channel')));
				}
			}
		}
		else
		{
			$data['post'] 			= $this->model->getPost($this->uri->segment(4));
			$data['meta']			= array(
				'title' 			=> phrase('edit_channel'),
				'descriptions'		=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'			=> 'post, dwitri, blogs, article, social, blogging',
				'image'				=> guessImage('tv'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				if(null != $this->input->post('modal'))
				{
					$data['modal']	= true;
					$this->load->view('tv/posts_edit', $data);
				}
				else
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('tv/posts_edit', $data, true)
							)
						)
					);
				}
			}
			else
			{
				$this->template->build('tv/posts_edit', $data);
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
	
	function upload_checker()
	{
		$config['upload_path'] 		= 'uploads/tv';
		$config['allowed_types'] 	= 'jpg|jpeg|gif|png';
		$config['max_size']      	= 1024*2; //2MB
		$config['encrypt_name']	 	= TRUE;
		
		$this->upload->initialize($config); 
		
		if(!$this->upload->do_upload())
		{
			$this->form_validation->set_message('upload_checker', str_replace(array('<p>', '</p>'),'', $this->upload->display_errors())); 
			return false;
		} 
		else
		{
			$this->upload_data['userfile'] = $this->upload->data();

			//upload successful generate a thumbnail
			$config['image_library'] 	= 'gd2';
			$config['source_image'] 	= 'uploads/tv/' . $this->upload_data['userfile']['file_name'];
			$config['create_thumb'] 	= FALSE;
			$config['maintain_ratio'] 	= TRUE;
			$config['width']     		= 260;
			$config['height']   		= 260;

			$this->load->library('image_lib', $config);
			$this->image_lib->initialize($config);

			if($this->image_lib->resize())
			{
				$this->image_lib->clear();
				generateThumbnail('tv', $this->upload_data['userfile']['file_name']);
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	function real_url($url)
	{
		if(@fsockopen($url, 80, $errno, $errstr, 30))
		{
			$this->form_validation->set_message('real_url', phrase('channel_url_error')); 
			return false;
		}
		else
		{
			return true;
		}
	} 
}