<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $vars;
	public $mod;
	public $meta_url;
	public $meta_site_name;
	public $meta_title;
	public $meta_keywords;
	public $meta_description;
	public $meta_image;
	public $CI;
	public $CiFire;
	public $VERSION = '2.0.1';

	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->load->library('Cifire_Role', ['modz'=>$this->mod], 'role');
		$this->_default_timezone();
		$this->set_meta();
	}


	private function _default_timezone() 
	{
		$default_timezone = (! empty(config_item('timezone'))) ? config_item('timezone') : get_setting('timezone');
		date_default_timezone_set($default_timezone);
	}


	private function set_meta()
	{
		$this->meta_title();
		$this->meta_keywords();
		$this->meta_description();
		$this->meta_image();
	}


	public function meta_title($param = NULL)
	{
		$this->meta_title = !empty($param) ? $param : get_setting('web_name');
		unset($param);
		return $this;
	}


	public function meta_keywords($param = NULL)
	{
		$this->meta_keywords = !empty($param) ? $param : get_setting('web_keyword');
		unset($param);
		return $this;
	}


	public function meta_description($param = NULL)
	{
		$this->meta_description = !empty($param) ? $param : get_setting('web_description');
		unset($param);
		return $this;
	}


	public function meta_image($param = NULL)
	{
		$this->meta_image = !empty($param) ? $param : favicon('web_image');
		return $this;
	}


	public function captcha()
	{
		if (get_setting('recaptcha') == 'Y')
			return TRUE;
		else
			return FALSE;
	}


	public function json_output($parm, $header=200)
	{
		$this->output
			 ->set_status_header($header)
			 ->set_content_type('application/json', 'utf-8')
			 ->set_output(json_encode($parm, JSON_HEX_APOS | JSON_HEX_QUOT))
			 ->_display();
		exit();
	}


	public function web_analytics() 
	{
		if ( get_setting('web_analytics') === 'Y' )
		{
			$this->load->library('user_agent');
			
			$ipinfo        = json_decode(@file_get_contents("https://ipinfo.io/"));
			$ipvi          = (!empty($ipinfo->ip) ? $ipinfo->ip : $this->input->ip_address());
			$country       = (!empty($ipinfo->country) ? $ipinfo->country : "Others");
			$city          = (!empty($ipinfo->city) ? $ipinfo->city : "Others");
			$os_stat       = $this->input->user_agent();
			$platform_stat = $this->agent->platform();
			$browser_stat  = $this->agent->browser();
			$datestat      = date("Y-m-d");
			$timestat      = time();
			$url           = $_SERVER['REQUEST_URI'];

			$totalvi = $this->db
				->where('ip', $ipvi)
				->where('date', $datestat)
				->where('url', $url)
				->get('t_visitor')
				->num_rows();

			if ( $totalvi < 1 ) 
			{
				$this->db->insert('t_visitor', array(
					'ip'       => $ipvi,
					'platform' => $platform_stat,
					'os'       => $os_stat,
					'browser'  => $browser_stat,
					'country'  => $country,
					'city'     => $city,
					'date'     => $datestat,
					'hits'     => 1,
					'url'      => $url 
				));
			}
			else 
			{
				$statpro = $this->db
					->where('ip', $ipvi)
					->where('date', $datestat)
					->where('url', $url)
					->get('t_visitor')
					->row_array();

				$hitspro = $statpro['hits'] + 1;

				$data_update = array(
					'platform' => $platform_stat,
					'os'       => $os_stat,
					'browser'  => $browser_stat,
					'country'  => $country,
					'city'     => $city,
					'hits'     => $hitspro,
					'online'   => $timestat,
					'url'      => $url
				);

				$this->db->where('ip', $ipvi)
						 ->where('date', $datestat)
						 ->where('url', $url)
						 ->update('t_visitor', $data_update);
			}
		}
	}


	public function m_filter($str, $segment = 3) 
	{
		if ($this->uri->segment($segment) === $str) show_404();
	}
} // End class.

require_once 'Web_Controller.php';
require_once 'Backend_Controller.php';