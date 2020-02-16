<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Index_post extends Web_controller {
	
	var $mod = 'index-post';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('web/index_model');
	}

	public function index($get_page = 1)
	{
		$get_page = xss_filter($get_page, 'sql');
		$page   = ($get_page==0 ? 1 : $get_page);
		$batas  = get_setting('page_item');
		$posisi = ($page-1) * $batas;

		$config['base_url']   = site_url($this->mod.'/');
		$config['index_page'] = $page;
		$config['total_rows'] = $this->index_model->total_post();
		$this->cifire_pagination->initialize($config);
		$this->vars['page_link'] = $this->cifire_pagination->create_links();

		$data_post = $this->index_model->index_post($batas, $posisi);

		if ($data_post)
		{
			$this->vars['data_post'] = $data_post;

			$this->meta_title('Index Post - '. get_setting('web_name'));
			$this->render_view('index_post');
		}
		else
		{
			$this->render_404();
		}
	}
} // End class
