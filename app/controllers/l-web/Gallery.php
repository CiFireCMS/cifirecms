<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends Web_controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('web/gallery_model');
	}
	
	public function index()
	{
		$this->vars['all_gallery_image'] = $this->gallery_model->all_galleri_images();
		$this->meta_title('Gallery - '.get_setting('web_name'));
		$this->render_view('gallery');
	}
} // End class.