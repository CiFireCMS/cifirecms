<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cifire_Web {
	
	public function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * @var  $interval  string   (all, week, moth, year)
	 * @var  $limit     int
	*/
	public function popular_post($interval = 'all', $limit = 5) 
	{
		$result = $this->CI->index_model->popular_post($interval,$limit);
		return $result;
	}

	/**
	 * @var  $limit   int
	*/
	public function latest_post($limit = 5) 
	{
		$result = $this->CI->index_model->latest_post($limit);
		return $result;
	}
} // End Class