<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Backend_Controller extends MY_Controller {

	public $vars;
	public $mod;
	public $mod_view;
	public $_language;
	protected $_mod_view;

	public function __construct()
	{
		parent::__construct();

		if ( ! login_status() ) // Login FALSE
		{
			redirect(admin_url('login'));
		}
		
		else // login TRUE
		{
			$this->lang->load('general/menu_lang'); // load menu lang

			$this->form_validation->set_error_delimiters('<div>*&nbsp;', '</div>');
			$this->_act = !empty($this->input->post('act')) ? $this->input->post('act') : NULL;

			$this->vars['a_fmkey'] = fmkey();
			$this->vars['a_site_url']  = site_url();
			$this->vars['a_admin_url'] = site_url(FADMIN.'/');
			$this->vars['a_content_url']  = content_url();
			$this->vars['a_fcontent']  = CONTENTPATH;
			$this->vars['a_mod'] = $this->mod;
			$this->vars['a_act'] = (!empty($this->uri->segment(3)) ? "/".$this->uri->segment(3) : "");
			$this->vars['a_datatable_lang'] = content_url('plugins/datatable/lang/'.lang_active().'.json');
			$this->vars['a_system_lang']    = content_url('plugins/json/lang/'.lang_active().'.json');
		}
	}



	public function render_view($view = '')
	{
		if (file_exists(VIEWPATH."mod/$this->mod/$view.php"))
		{
			$this->vars['__modulez'] = "mod/$this->mod/$view";
			$this->load->view('backend/dashboard', $this->vars);
		}
		else
		{
			// show_error("Unable to load the requested file: mod/$this->mod/$view.php");
			$this->load->view("mod/$this->mod/$view", $this->vars);
		}
	}


	public function render_403()
	{
		$this->meta_title = "403 Access Denied";
		$this->vars['__modulez'] = "backend/error_403";
		$this->load->view('backend/dashboard', $this->vars);
	}


	public function render_404()
	{
		$this->meta_title = "404 Not Found";
		$this->vars['__modulez'] = "backend/error_404";
		$this->load->view('backend/dashboard', $this->vars);
	}


	public function ds_notif($type = '', $data = FALSE)
	{
		$result = '';

		if ($type == 'mail')
		{
			if ($data == TRUE) 
			{
				$result = $this->db
					->where('box', 'in')
					->order_by('id', 'DESC')
					->limit(5)
					->get('t_mail')
					->result_array();
			}
			else 
			{
				$result = $this->db->where('active', 'N')->where('box', 'in')->get('t_mail')->num_rows();
			}
		}
		
		if ($type == 'comment')
		{
			$result = $this->db->where('active', 'N')->get('t_comment')->num_rows();
		}

		return $result;
	}
} // End class.