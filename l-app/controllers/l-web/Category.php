<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Web_controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('web/category_model');
	}
	
	
	public function index($get_seotitle = NULL, $get_page = 1)
	{
		$seotitle = xss_filter($get_seotitle ,'xss');
		$check_seotitle = $this->category_model->check_seotitle($seotitle);

		if ( !empty($seotitle) && $check_seotitle == TRUE ) 
		{
			$result_category = $this->category_model->get_data($seotitle);
			
			$get_page = xss_filter($get_page, 'sql');
			$page     = ($get_page==0 ? 1 : $get_page);
			$batas    = get_setting('page_item');
			$posisi   = ($page-1) * $batas;

			$config['base_url']     = site_url('category/'.$seotitle.'/');
			$config['index_page']   = $page;
			$config['total_rows']   = $this->category_model->total_category_post($result_category['id']);
			$this->cifire_pagination->initialize($config);
			
			$this->vars['page_link'] = $this->cifire_pagination->create_links();
			$this->vars['result_category'] = $result_category;
			$this->vars['category_post'] = $this->category_model->get_post($result_category['id'], $batas, $posisi);

			if ( $this->vars['category_post'] ) 
			{
				$this->meta_title($result_category['title'].' - '.get_setting('web_name'));
				$this->meta_keywords($result_category['title'].', '.get_setting('web_keyword'));
				$this->meta_description($result_category['description']);
				$this->meta_image(post_images($result_category['picture'],'medium',TRUE));
				$this->render_view('category');
			}
			else
			{
				$this->render_404();
			}
		}
		else
		{
			$this->render_404();
		}
	}
} // End class.