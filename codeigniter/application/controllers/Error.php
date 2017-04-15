<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends CI_Controller
{
	public $settings = array();
	
	function __construct()
	{
		parent::__construct();
		
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
	
	public function index()
	{
		$data['meta']		= array(
			'title' 		=> phrase('page_not_found'),
			'descriptions'	=> phrase('the_page_was_not_found'),
			'keywords'		=> 'post, dwitri, blogs, article, social, blogging',
			'image'			=> guessImage()
		);
		$this->template->build('error', $data);
	}
}