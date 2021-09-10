<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Web_Controller {

	public $mod = 'home';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('web/home_model');
	}
	

	public function index()
	{
		$this->meta_title(get_setting('web_name').' - '.get_setting('web_slogan'));
		$this->render_view('home');
	}
} // End Class