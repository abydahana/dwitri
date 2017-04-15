<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller
{
	public $settings = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Users_model', 'model');
		
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
			'title' 		=> phrase('users'),
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
						'html'		=> $this->load->view('users/users', $data, true)
					)
				)
			);
		}
		else
		{
			$this->template->set_partial('navigation', 'dashboard_navigation');
			$this->template->build('users/users', $data);
		}
	}
	
	function edit()
	{
		if(!$this->session->userdata('loggedIn') && $this->session->userdata('user_level') != 1) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		
		if(null != $this->input->post('hash'))
		{
			$this->form_validation->set_rules('full_name', phrase('full_name'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('gender', phrase('gender'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('age', phrase('age'), 'trim|required|xss_clean|numeric');
			$this->form_validation->set_rules('mobile', phrase('mobile'), 'trim|required|xss_clean|numeric');
			$this->form_validation->set_rules('address', phrase('address'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('language', phrase('language'), 'trim|xss_clean');
			$this->form_validation->set_rules('bio', phrase('address'), 'trim|xss_clean');
			$this->form_validation->set_rules('email', phrase('email_address'), 'trim|required|valid_email|is_unique[users.email.userID.'.$this->session->userdata('userID').']');
			$this->form_validation->set_rules('username', phrase('username'), 'trim|required|alpha_dash|is_unique[users.userName.userID.'.$this->session->userdata('userID').']');
			
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
					'userName'			=> $this->input->post('username'),
					'full_name'			=> $this->input->post('full_name'),
					'gender'			=> $this->input->post('gender'),
					'age'				=> $this->input->post('age'),
					'mobile'			=> $this->input->post('mobile'),
					'address'			=> $this->input->post('address'),
					'email'				=> $this->input->post('email'),
					'language'			=> $this->input->post('language'),
					'bio'				=> $this->input->post('bio')
				);
				if(null != $this->input->post('con_password'))
				{
					$fields['password']	= sha1($this->input->post('con_password') . SALT);
				}
		
				if($this->model->updateUser($fields, $this->uri->segment(4)))
				{
					$this->session->set_flashdata('success', phrase('user_profile_was_updated_successfully'));
					echo json_encode(array("status" => 200, "redirect" => base_url('user/users')));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_update_user_profile')));
				}
			}
		}
		else
		{
			$data['user']		= $this->model->getUser($this->uri->segment(4));
			$data['meta']		= array(
				'title' 		=> phrase('edit_user'),
				'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
				'image'			=> guessImage('users'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				if(null != $this->input->post('modal'))
				{
					$data['modal']	= true;
					$this->load->view('users/users_edit', $data);
				}
				else
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('users/users_edit', $data, true)
							)
						)
					);
				}
			}
			else
			{
				$this->template->build('users/users_edit', $data);
			}
		}
	}
	
	function remove()
	{
		if(!$this->input->is_ajax_request()) return error(404, ($this->input->is_ajax_request() ? 'ajax' : null));
		
		if(!$this->session->userdata('loggedIn') && $this->session->userdata('user_level') != 1)
		{
			echo json_encode(array('status' => 403));
		}
		else
		{
			if($this->model->removeUser($this->uri->segment(4)))
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