<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends CI_Controller
{
	public $settings = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Categories_model', 'model');
		
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
			'title' 		=> phrase('post_categories'),
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
						'html'		=> $this->load->view('categories/posts', $data, true)
					)
				)
			);
		}
		else
		{
			$this->template->set_partial('navigation', 'dashboard_navigation');
			$this->template->build('categories/posts', $data);
		}
	}
	
	function add()
	{
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		if($this->input->post('hash'))
		{
			$this->form_validation->set_rules('categoryTitle', phrase('category_title'), 'trim|xss_clean|required|is_unique[posts_category.categoryTitle]|max_length[22]');
			$this->form_validation->set_rules('categoryDescription', phrase('category_descriptions'), 'trim|xss_clean|required|min_length[30]');
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				$fields = array(
					'categoryTitle'			=> $this->input->post('categoryTitle'),
					'categorySlug'			=> format_uri($this->input->post('categoryTitle')),
					'categoryDescription'	=> $this->input->post('categoryDescription'),
					'language'				=> $this->session->userdata('language'),
					'timestamp'				=> time()
				);
				if($this->model->createCategory($fields))
				{
					$this->session->set_flashdata('success', phrase('category_was_successfully_submitted'));
					echo json_encode(array("status" => 200, "redirect" => base_url('user/categories')));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_submit_new_category')));
				}
			}
		}
		else
		{
			$data['meta']		= array(
				'title' 		=> phrase('add_category'),
				'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
				'image'			=> guessImage('posts'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				if(null != $this->input->post('modal'))
				{
					$data['modal']	= true;
					$this->load->view('categories/posts_add', $data);
				}
				else
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('categories/posts_add', $data, true)
							)
						)
					);
				}
			}
			else
			{
				$this->template->build('categories/posts_add', $data);
			}
	  	}
	}
	
	function edit()
	{
		if(!$this->session->userdata('loggedIn')) return error(403, ($this->input->is_ajax_request() ? 'ajax' : null));
		if($this->input->post('hash'))
		{
			$this->form_validation->set_rules('categoryTitle', phrase('category_title'), 'trim|xss_clean|required|is_unique[posts_category.categoryTitle.categorySlug.'.$this->uri->segment(4).']|max_length[22]');
			$this->form_validation->set_rules('categoryDescription', phrase('category_descriptions'), 'trim|xss_clean|required|min_length[30]');
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				$fields = array(
					'categoryTitle' => $this->input->post('categoryTitle'),
					'categorySlug' => format_uri($this->input->post('categoryTitle')),
					'categoryDescription' => $this->input->post('categoryDescription'),
					'language' => $this->session->userdata('language'),
					'timestamp' => time()
				);
				if($this->model->updateCategory($fields, $this->uri->segment(4)))
				{
					$this->session->set_flashdata('success', phrase('category_was_successfully_updated'));
					echo json_encode(array("status" => 200, "redirect" => base_url('user/categories')));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_update_category')));
				}
			}
		}
		else
		{
			$data['current'] 	= $this->uri->segment(2);
			$data['post'] 		= $this->model->getCategory($this->uri->segment(4));
			$data['meta']		= array(
				'title' 		=> phrase('edit_category'),
				'descriptions'	=> phrase('whatever_you_writing_for_is_a_reportations'),
				'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
				'image'			=> guessImage('posts'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				if(null != $this->input->post('modal'))
				{
					$data['modal']	= true;
					$this->load->view('categories/posts_edit', $data);
				}
				else
				{
					$this->output->set_content_type('application/json');
					$this->output->set_output(
						json_encode(
							array(
								'meta'		=> $data['meta'],
								'html'		=> $this->load->view('categories/posts_edit', $data, true)
							)
						)
					);
				}
			}
			else
			{
				$this->template->build('categories/posts_edit', $data);
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
			$remove	= $this->model->removeCategory($this->uri->segment(4));
			if($remove == 403)
			{
				echo json_encode(array('status' => 'not_null', 'messages' => phrase('there_are_some_posts_still_use_this_category')));
			}
			elseif($remove == 200)
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