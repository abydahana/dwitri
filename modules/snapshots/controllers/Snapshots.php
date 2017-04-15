<?php defined('BASEPATH') OR exit('No direct script access allowed');

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
	
	function index($slug = null)
	{
		$snapshots				= $this->model->getSnapshot($slug);
		if($snapshots)
		{
			$data['post']			= $snapshots;
			$data['meta']		= array(
				'title' 		=> $this->model->getSnapshotTitle($slug),
				'descriptions'	=> $this->model->getSnapshotTitle($slug),
				'keywords'		=> format_tag($this->model->getSnapshotTitle($slug)),
				'image'			=> guessImage('snapshots', $slug),
				'author'		=> $this->model->getSnapshotContributor($slug)
			);
			if($this->input->is_ajax_request())
			{
				if(null != $this->input->post('modal'))
				{
					$data['modal']	= TRUE;
					$this->load->view('details', $data);
				}
				else
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
			}
			else
			{
				$this->template->build('details', $data);
			}
		}
		else
		{
			$data['meta']		= array(
				'title' 		=> phrase('snapshot'),
				'descriptions'	=> phrase('share_everything_you_take_by_camera'),
				'keywords'		=> 'snapshot, shot, camera, dwitri, image',
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
							'html'		=> $this->load->view('snapshots', $data, true)
						)
					)
				);
			}
			else
			{
				$this->template->build('snapshots', $data);
			}
		}
	}
}
