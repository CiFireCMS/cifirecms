<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends Web_controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('web/pages_model');
	}
	
	
	public function index($get_seotitle = '')
	{
		$seotitle = xss_filter($get_seotitle ,'xss');
		$cek_seotitle = $this->pages_model->check_seotitle($seotitle);

		if ( !empty($get_seotitle) && $cek_seotitle == TRUE ) 
		{
			$data = $this->pages_model->get_data($seotitle);
			$this->vars['result_pages'] = $data;
			
			$this->meta_title($data['title'].' - '.get_setting('web_name'));
			$this->meta_description(cut($data['content'], 150));
			$this->meta_image(post_images($data['picture'],'medium'));
			$this->render_view('pages');
		}
		else
		{
			$this->render_404();
		}
	}
} // End class.