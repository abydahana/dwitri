<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pages_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function getPage($slug = null)
	{
		$query = $this->db->where('pageSlug', $slug)->limit(1)->get('pages');
		if($query->num_rows() > 0)
		{
			$this->db->where('pageSlug', $slug);
			$this->db->limit(1);
			$this->db->set('visits_count', 'visits_count+1', FALSE);
			$this->db->update('pages');
			
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function getPageTitle($slug = '')
	{
		$this->db->select('pageTitle');
		$this->db->where('pageSlug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('pages');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return truncate($u['pageTitle'], 70);
			}
		}
		else
		{
			return false;
		}
	}
	
	function getPageExcerpt($slug = '')
	{
		$this->db->select('pageExcerpt');
		$this->db->where('pageSlug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('pages');
		if ($query->num_rows() > 0)
		{
            $results = $query->result_array();
        	foreach ($results as $u)
			{
				return truncate($u['pageExcerpt'], 10);
			}
		}
		else
		{
			return false;
		}
	}
	
	function generateNavigation()
	{
		$this->db->select('
			pageTitle,
			pageSlug
		');
		$this->db->where('language', $this->session->userdata('language'));
		$query = $this->db->get('pages');
		if ($query->num_rows() > 0)
		{
            return $query->result_array();
		}
		else
		{
			return false;
		}
	}
		
	function submitContact()
	{
		$data = array(
			'full_name' => $this->input->post('full_name'),
			'email' => $this->input->post('email'),
			'phone' => $this->input->post('phone'),
			'subject' => $this->input->post('subject'),
			'messages' => $this->input->post('messages'),
			'timestamp' => time()
		);
		
		$copy = $this->input->post('copy_email');
		
		if($copy == 1)
		{
			$config				= array();
			$config['protocol']	= 'sendmail';
			$config['mailpath']	= '/usr/sbin/sendmail';
			$config['charset']	= 'iso-8859-1';
			$config['wordwrap']	= TRUE;
			$config['newline']	= "\r\n";

			$this->email->initialize($config);
			
			$message_body		= '
				<div style="border:10px solid #888;padding:20px;font-size:16px;">
					<p>' . phrase('thank_you') . ', <b>' . $this->input->post('full_name') . '</b>. ' . phrase('your_messages_were_submitted') . '<br /><br /><b>' . phrase('here_is_your_message_copies') . ':</b></p>
					<br />
					<table width="100%">
						<tr>
							<td valign="top">
								' . phrase('full_name') . '
							</td>
							<td valign="top">
								:
							</td>
							<td valign="top">
								' . $data['full_name'] . '
							</td>
						</tr>
						<tr>
							<td valign="top">
								' . phrase('email') . '
							</td>
							<td valign="top">
								:
							</td>
							<td valign="top">
								' . $data['email'] . '
							</td>
						</tr>
						<tr>
							<td valign="top">
								' . phrase('phone') . '
							</td>
							<td valign="top">
								:
							</td>
							<td valign="top">
								' . $data['phone'] . '
							</td>
						</tr>
						<tr>
							<td valign="top">
								' . phrase('submitted_date') . '
							</td>
							<td valign="top">
								:
							</td>
							<td valign="top">
								' . date('F, d M Y - H:i') . '
							</td>
						</tr>
						<tr>
							<td valign="top">
								' . phrase('subject') . '
							</td>
							<td valign="top">
								:
							</td>
							<td valign="top">
								' . $data['subject'] . '
							</td>
						</tr>
						<tr>
							<td valign="top">
								' . phrase('messages') . '
							</td>
							<td valign="top">
								:
							</td>
							<td valign="top">
								' . $data['messages'] . '
							</td>
						</tr>
					</table>
					<br /><br />
					<b>' . phrase('thanks_for_your_attention') . '</b>
				</div>
			';
			
			$this->email->from('no-reply@' . $_SERVER['SERVER_NAME'], $this->settings['siteTitle']);
			$this->email->to($this->settings['siteEmail']);
			$this->email->cc($data['email']);
			//$this->email->bcc('info@' . $_SERVER['SERVER_NAME']);
			//$this->email->reply_to('no-reply@' . $_SERVER['SERVER_NAME'], $this->settings['siteTitle']);
			$this->email->subject($data['subject'] . ' - ' . $this->settings['siteTitle']);
			$this->email->message($message_body);
			$this->email->set_newline("\r\n");
			$this->email->set_crlf("\r\n");
			$this->email->send();
		}
		if($this->db->insert('contacts', $data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}