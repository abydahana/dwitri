<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Snapshots extends CI_Controller
{
	public $settings = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Snapshots_model', 'model');
		
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
			'title' 		=> phrase('snapshots'),
			'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
			'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
			'image'			=> guessImage('snapshots'),
			'author'		=> $this->settings['siteTitle']
		);
		if($this->input->is_ajax_request())
		{
			$this->output->set_content_type('application/json');
			$this->output->set_output(
				json_encode(
					array(
						'meta'		=> $data['meta'],
						'html'		=> $this->load->view('snapshots/posts', $data, true)
					)
				)
			);
		}
		else
		{
			$this->template->set_partial('navigation', 'dashboard_navigation');
			$this->template->build('snapshots/posts', $data);
		}
	}
	
	function add()
	{
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		if($this->input->post('hash'))
		{
			$this->form_validation->set_rules('content', phrase('content'), 'trim|xss_clean|required|max_length[260]');
			$this->form_validation->set_rules('userfile', phrase('image'), 'callback_upload_checker');
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				$time	= time();
				$fields = array(
					'snapshotContent'	=> $this->input->post('content'),
					'snapshotFile'		=> $this->upload_data['userfile']['file_name'],
					'contributor'		=> $this->session->userdata('userID'),
					'snapshotSlug'		=> md5($time . $this->session->userdata('userID') . $this->input->post('content')),
					'snapshotCredits'	=> $this->input->post('credits'),
					'language'			=> $this->session->userdata('language'),
					'timestamp'			=> $time
				);
				if($this->model->createPost($fields))
				{
					$this->session->set_flashdata('success', phrase('snapshot_was_successfully_submitted'));
					echo json_encode(array("status" => 200, "redirect" => base_url('snapshots/' . $this->uri->segment(4))));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_submit_snapshot')));
				}
			}
		}
		else
		{
			$data['meta']			= array(
				'title' 			=> phrase('update_snapshot'),
				'descriptions'		=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'			=> 'post, dwitri, blogs, article, social, blogging',
				'image'				=> guessImage('snapshots'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				if(null != $this->input->post('modal'))
				{
					$data['modal']	= true;
					$this->load->view('snapshots/posts_add', $data);
				}
				else
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('snapshots/posts_add', $data, true)
							)
						)
					);
				}
			}
			else
			{
				$this->template->build('snapshots/posts_add', $data);
			}
	  	}
	}
	
	function edit()
	{
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		if($this->input->post('hash'))
		{
			$this->form_validation->set_rules('content', phrase('content'), 'trim|xss_clean|required|max_length[260]');
			if(!empty($_FILES['userfile']['name']))
			{
				$this->form_validation->set_rules('userfile', phrase('image'), 'callback_upload_checker');
			}
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				$fields = array(
					'snapshotContent'		=> $this->input->post('content'),
					'snapshotCredits'		=> $this->input->post('credits'),
					'language'				=> $this->session->userdata('language'),
					'timestamp'				=> time()
				);
				if(!empty($_FILES['userfile']['name']))
				{
					$fields['snapshotFile'] = $this->upload_data['userfile']['file_name'];
				}
				
				if($this->model->updatePost($fields, $this->uri->segment(4)))
				{
					$this->session->set_flashdata('success', phrase('snapshot_was_updated_successfully'));
					echo json_encode(array("status" => 200, "redirect" => base_url('user/snapshots')));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_update_snapshot')));
				}
			}
		}
		else
		{
			$data['post'] 			= $this->model->getPost($this->uri->segment(4));
			$data['meta']			= array(
				'title' 			=> phrase('update_snapshot'),
				'descriptions'		=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'			=> 'post, dwitri, blogs, article, social, blogging',
				'image'				=> guessImage('snapshots'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				if(null != $this->input->post('modal'))
				{
					$data['modal']	= true;
					$this->load->view('snapshots/posts_edit', $data);
				}
				else
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('snapshots/posts_edit', $data, true)
							)
						)
					);
				}
			}
			else
			{
				$this->template->build('snapshots/posts_edit', $data);
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
		$config['upload_path'] 		= 'uploads/snapshots';
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
			
			if($this->upload_data['userfile']['image_type'] != 'gif' && $this->upload_data['userfile']['image_width'] >= 800)
			{
				//upload successful generate a thumbnail
				$config['image_library'] 	= 'gd2';
				$config['source_image'] 	= 'uploads/snapshots/' . $this->upload_data['userfile']['file_name'];
				$config['create_thumb'] 	= FALSE;
				$config['width']     		= 800;

				$this->load->library('image_lib', $config);
				$this->image_lib->initialize($config);

				if($this->image_lib->resize())
				{
					$this->image_lib->clear();
					generateThumbnail('snapshots', $this->upload_data['userfile']['file_name']);
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				generateThumbnail('snapshots', $this->upload_data['userfile']['file_name']);
				return true;
			}
		}
	}
}