<?php defined('BASEPATH') OR exit('No direct script access allowed');

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
		$slug						= $this->uri->segment(2);
		$page						= $this->model->getPage($slug);
		if($page)
		{
			$data['page']			= $page;
			$data['navigations']	= $this->model->generateNavigation();
			$data['meta']			= array(
				'title' 			=> $this->model->getPageTitle($slug),
				'descriptions'		=> $this->model->getPageExcerpt($slug),
				'keywords'			=> format_tag($this->model->getPageExcerpt($slug)),
				'image'				=> guessImage('pages', $slug),
				'author'			=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				$this->output->set_content_type('application/json');
				$this->output->set_output(
					json_encode(
						array(
							'meta'		=> $data['meta'],
							'html'		=> $this->load->view('page', $data, true)
						)
					)
				);
			}
			else
			{
				$this->template->build('page', $data);
			}
		}
		else
		{
			return error(404, ($this->input->is_ajax_request() ? 'ajax' : null));
		}
	}
	
	function contact()
	{
		if(($this->input->is_ajax_request()) && ($this->input->post('hash') && sha1('c0NtacT') == $this->input->post('hash')))
		{
			$this->form_validation->set_rules('full_name', phrase('full_name'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', phrase('email'), 'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('phone', phrase('phone'), 'trim|xss_clean|numeric');
			$this->form_validation->set_rules('messages', phrase('messages'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('copy_email', phrase('copy_email'), 'trim|xss_clean|numeric');
			
			if($this->form_validation->run() == FALSE)
			{
				echo '{"status":0,"errors":' . json_encode(array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))) . '}';
			}
			else
			{
				if($this->model->submitContact())
				{
					$this->session->set_flashdata('success', phrase('your_messages_was_successfully_submitted'));
					echo json_encode(array("status" => TRUE, "redirect" => current_url()));
				}
				else
				{
					echo '{"status":0,"errors":"' . phrase('something_went_wrong_submitting_your_feedback') . '"}';
				}
			}
		}
		else
		{
			$data['meta']		= array(
				'title' 		=> phrase('contact_us'),
				'descriptions'	=> phrase('contact_us'),
				'keywords'		=> phrase('contact_us'),
				'image'			=> guessImage('pages', 'contact'),
				'author'		=> $this->settings['siteTitle']
			);
			if($this->input->is_ajax_request())
			{
				$this->output->set_content_type('application/json');
				$this->output->set_output(
					json_encode(
						array(
							'meta'		=> $data['meta'],
							'html'		=> $this->load->view('contact', $data, true)
						)
					)
				);
			}
			else
			{
				$this->template->build('contact', $data);
			}
		}
	}
}
