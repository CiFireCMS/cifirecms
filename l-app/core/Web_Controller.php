<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Web_Controller extends MY_Controller {

	public $__content_view;
	public $_theme_folder;

	public function __construct() 
	{
		parent::__construct();
		
		if ( get_setting('maintenance_mode') == 'Y' ) 
		{
			redirect('maintenance');
		}
		else 
		{
			$this->load->model('web/index_model');
			$this->_theme_folder = "themes/".theme_active('folder');

			$this->form_validation->set_error_delimiters('<div>*&nbsp;','</div>');
		}

		// web analytics.
		$this->web_analytics();
		// cache.
		$this->set_cache();
	}
	

	public function _layout($view='')
	{
		$this->load->view("$this->_theme_folder/$view", $this->vars);
	}


	public function render_view($view = '')
	{
		if (file_exists(VIEWPATH."$this->_theme_folder/$view.php"))
		{
			$this->__content_view = $view;
			$this->load->view("$this->_theme_folder/inc/template", $this->vars);
		} 
		else
		{
			show_error("Unable to load the requested file: $this->_theme_folder/$view.php");
		}
	}


	public function render_404()
	{
		$this->meta_title('404 Page Not Found');
		$this->meta_description('The page you requested was not found.');
		
		$this->render_view('404');
	}


	public function theme_asset($asset = '')
	{
		return content_url('themes/'.theme_active('folder').'/'.$asset);
	}


	public function load_menu($menu_group = '', $ul = '', $ul_li = '', $ul_li_a ='', $ul_li_a_ul = '')
	{
		echo $this->cifire_menu->front_menu($menu_group, $ul, $ul_li, $ul_li_a, $ul_li_a_ul);
		$this->cifire_menu->clear();
	}


	public function set_cache()
	{
		if ( get_setting('web_cache') === 'Y' )
		{
			$this->output->cache(get_setting('cache_time'));
		}
	}
} // End class.