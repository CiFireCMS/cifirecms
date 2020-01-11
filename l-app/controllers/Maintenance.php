<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Maintenance extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		if ( get_setting('maintenance_mode') == 'N' )
		{
			redirect(site_url());
		} 
	}

	public function index()
	{
		$this->load->view('maintenance');
	}
} // End class.