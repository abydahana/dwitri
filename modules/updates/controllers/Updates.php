<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Updates extends CI_Controller
{
	public $settings = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Updates_model', 'model');
		
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
		$updateID					= $this->uri->segment(2);
		$updates					= $this->model->getUpdates($updateID);
		if($updates)
		{
			$data['updates']		= $updates;
			$data['meta']			= array(
				'title' 			=> $this->model->getUpdatesTitle($updateID),
				'descriptions'		=> $this->model->getUpdatesExcerpt($updateID),
				'keywords'			=> $this->model->getUpdatesTags($updateID),
				'image'				=> guessImage('updates', $updateID),
				'author'			=> $this->model->getUpdatesContributor($updateID)
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
			if($this->input->is_ajax_request())
			{
				//echo json_encode(array("status" => 200, "redirect" => base_url()));
			}
			else
			{
				//redirect();
			}
		}
	}
}
