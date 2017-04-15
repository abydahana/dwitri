<?php defined('BASEPATH') OR exit('No direct script access allowed');

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
	
	function index($slug = null, $limit = 10, $offset = 0)
	{
		$openletters				= $this->model->getOpenletter($slug);
		if($openletters)
		{
			$data['openletters']	= $openletters;
			$data['meta']			= array(
				'title' 			=> $this->model->getOpenletterTitle($slug),
				'descriptions'		=> $this->model->getOpenletterExcerpt($slug),
				'keywords'			=> $this->model->getOpenletterTags($slug),
				'image'				=> guessImage('openletters', $slug),
				'author'			=> $this->model->getOpenletterContributor($slug)
			);
			if($this->input->is_ajax_request())
			{
				$this->output->set_content_type('application/json');
				$this->output->set_output(
					json_encode(
						array(
							'meta'		=> $data['meta'],
							'html'		=> $this->load->view('details', $data, true)
						)
					)
				);
			}
			else
			{
				$this->template->build('details', $data);
			}
		}
		else
		{
			$data['meta']		= array(
				'title' 		=> phrase('open_letters'),
				'descriptions'	=> phrase('write_letter_for_justice'),
				'keywords'		=> 'openletter, open letter, dwitri, justice',
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
							'html'		=> $this->load->view('openletters', $data, true)
						)
					)
				);
			}
			else
			{
				$this->template->build('openletters', $data);
			}
		}
	}
}
