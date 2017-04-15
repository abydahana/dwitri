<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller
{
	public $settings = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Settings_model', 'model');
		
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
		if($this->input->post('hash'))
		{
			$this->form_validation->set_rules('siteTitle', phrase('siteTitle'), 'trim|xss_clean|required|max_length[260]');
			$this->form_validation->set_rules('siteDescription', phrase('siteDescription'), 'trim|xss_clean|required|max_length[260]');
			$this->form_validation->set_rules('siteTheme', phrase('siteTheme'), 'trim|xss_clean|required|max_length[260]');
			$this->form_validation->set_rules('siteLang', phrase('siteLang'), 'trim|xss_clean|required|max_length[260]');
			$this->form_validation->set_rules('sitePhone', phrase('sitePhone'), 'trim|xss_clean|required|max_length[260]');
			$this->form_validation->set_rules('sitePhone', phrase('sitePhone'), 'trim|xss_clean|required|max_length[260]');
			
			if($this->form_validation->run() == FALSE)
			{
				echo json_encode(array('status' => 204, 'messages' => array(validation_errors('<span><i class="fa fa-ban"></i> &nbsp; ', '</span><br />'))));
			}
			else
			{
				$fields = array(
					'siteTitle'			=> $this->input->post('siteTitle'),
					'siteTheme'			=> $this->input->post('siteTheme'),
					'siteLang'			=> $this->input->post('siteLang'),
					'siteDescription'	=> $this->input->post('siteDescription'),
					'siteFooter'		=> $this->input->post('siteFooter'),
					'siteAddress'		=> $this->input->post('siteAddress'),
					'sitePhone'			=> $this->input->post('sitePhone'),
					'siteFax'			=> $this->input->post('siteFax'),
					'siteEmail'			=> $this->input->post('siteEmail'),
					'siteYM'			=> json_encode(array(array('YM' => $this->input->post('siteYM')))),
					'siteBBM'			=> json_encode(array(array('BBM' => $this->input->post('siteBBM'))))
				);
				if($this->model->updateSettings($fields))
				{
					$this->session->set_flashdata('success', phrase('configuration_saved'));
					echo json_encode(array("status" => 200, "redirect" => current_url()));
				}
				else
				{
					echo json_encode(array('status' => 500, 'messages' => phrase('unable_to_update_configuration')));
				}
			}
		}
		else
		{
			$data['themesdir'] 	= directory_map('themes/', 1);
			$data['settings'] 	= $this->model->getSettings();
			$data['meta']		= array(
				'title' 		=> phrase('global_settings'),
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
							'html'		=> $this->load->view('settings/settings', $data, true)
						)
					)
				);
			}
			else
			{
				$this->template->set_partial('navigation', 'dashboard_navigation');
				$this->template->build('settings/settings', $data);
			}
		}
	}
}