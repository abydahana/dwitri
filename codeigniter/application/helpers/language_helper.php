<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('phrase'))
{
	function phrase($phrase = '', $variable = array())
	{
		$CI	=&	get_instance();
		$CI->load->database();
		
		if(!$CI->session->userdata('language'))
		{
			// Checking browser language
			$user_lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
			if($user_lang == 'id')
			{
				$translation	= 'indonesia';
			}
			elseif($user_lang == 'en')
			{
				$translation	= 'english';
			}
			else
			{
				$translation	= 'english';
			}
			
			// Checking language exists
			if($CI->db->field_exists($translation, 'language'))
			{
				$language = $translation;
			}
			else
			{
				$language = $CI->db->where('siteID', 0)->get('settings')->row()->siteLang;
			}
			
			// Create language session
			$CI->session->set_userdata('language' , $language);
		}
		else
		{
			$language = $CI->session->userdata('language');
		}
		
		$query = $CI->db->get_where('language' , array('phrase' => $phrase));
		$row = $query->row();
		
		if(empty($row))
		{
			$CI->db->insert('language' , array('phrase' => strtolower(strip_tags(str_replace(' ','_', $phrase)))));
			return ucwords(strip_tags(str_replace('_', ' ', $phrase)));
		}
		else
		{
			if (isset($row->$language) && $row->$language != '')
			{
				if(sizeof($variable) > 0)
				{
					$value				= array();
					$replacement		= array();
					$n					= 1;
					foreach($variable as $key => $val)
					{
						$value[]		= '%' . $n;
						$replacement[]	= $val;
						$n++;
					}
					return str_replace(array('&lt;', '&gt;', '&quot;', 'base_url/'), array('<', '>', '"', base_url()), str_replace($value, $replacement, $row->$language));
				}
				else
				{
					return $row->$language;
				}
			}
			else
			{
				return ucwords(strip_tags(str_replace('_', ' ', $phrase)));
			}
		}
	}
}