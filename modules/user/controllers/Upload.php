<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller
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
	
	public function images($type = null)
	{
		//if(!$this->input->is_ajax_request()) return error(404);
		if(!$this->session->userdata('loggedIn') || $type == null)
		{
			$array 	= array(
				'error' => phrase('please_login_to_upload')
			);
			echo stripslashes(json_encode($array));
		}
		else
		{
			$config['upload_path'] 		= 'uploads/' . $type;
			$config['allowed_types'] 	= 'gif|jpg|png';
			$config['encrypt_name'] 	= TRUE;

			$this->upload->initialize($config);

			if (!$this->upload->do_upload('file'))
			{
				$array 	= array(
					'error' => phrase('please_login_to_upload')
				);
				echo stripslashes(json_encode($array));
			}
			else
			{
				$data = $this->upload->data();
			
				if($data['image_type'] != 'gif')
				{
					//upload successful generate a thumbnail
					$config['image_library'] 	= 'gd2';
					$config['source_image'] 	= 'uploads/' . $type . '/' . $data['file_name'];
					$config['create_thumb'] 	= FALSE;
					$config['maintain_ratio'] 	= TRUE;
					$config['width']     		= 600;
					$config['height']   		= 600;

					$this->load->library('image_lib', $config);
					$this->image_lib->initialize($config);

					if($this->image_lib->resize())
					{
						$this->image_lib->clear();
						generateThumbnail($type, $data['file_name']);
					}
				}
				else
				{
					generateThumbnail($type, $data['file_name']);
				}
				
				$array 	= array(
					'filelink' => base_url('uploads/' . $type . '/' . $data['file_name']),
					'filename' => $data['file_name']
				);
				echo stripslashes(json_encode($array));
			}
		}
	}

	public function files()
	{
		//if(!$this->input->is_ajax_request()) return error(404);
		if(!$this->session->userdata('loggedIn'))
		{
			$array 	= array(
				'error' => phrase('please_login_to_upload')
			);
			echo stripslashes(json_encode($array));
		}
		else
		{
			$config['upload_path'] 		= 'uploads/files';
			$config['allowed_types'] 	= '*';
			$config['encrypt_name'] 	= TRUE;

			$this->upload->initialize($config);

			if(!$this->upload->do_upload('file'))
			{
				$array 	= array(
					'error' => $this->upload->display_errors()
				);
				echo stripslashes(json_encode($array));
			}
			else
			{
				$data 	= $this->upload->data();
				$array 	= array(
					'filelink' => base_url('uploads/files/' . $data['file_name']),
					'filename' => $data['file_name']
				);
				echo stripslashes(json_encode($array));
			}
		}
	}

	public function choose($type = null)
	{
		if(!$this->input->is_ajax_request()) return error(404);
		if(!$this->session->userdata('loggedIn') || $type == null)
		{
			$array 	= array(
				'error' => phrase('please_login_to_upload')
			);
			echo stripslashes(json_encode($array));
		}
		else
		{
			$extensions = array('jpg', 'jpeg', 'png', 'gif');
			$files = get_filenames_by_extension('uploads/' . $type . '/thumbs', $extensions);

			$return = array();
			foreach($files as $file)
			{
				$return[] = array('thumb'=>base_url('uploads/' . $type . '/thumbs/' . $file), 'image' => base_url('uploads/' . $type . '/' . $file));
			}
			echo stripslashes(json_encode($return));
		}
	}
}