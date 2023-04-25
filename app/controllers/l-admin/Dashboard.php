<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Backend_Controller {

	public $mod = 'dashboard';

	public function __construct()
	{
		parent::__construct();

		if ( !$this->role->i('read') )
		{
			redirect(admin_url('logout'));
		}

		$this->lang->load('mod/'.$this->mod);
		$this->load->model('mod/dashboard_model', 'Model');
		$this->meta_title(lang_line('mod_title'));
	}
	

	public function index()
	{
		if (ENVIRONMENT == 'development')
		{
			$this->cifire_alert->set('ENV', 'default', lang_line('ui_environment_development_info').'', false);
		}

		$this->vars['h_post']       = $this->Model->card('post');
		$this->vars['h_category']   = $this->Model->card('category');
		$this->vars['h_tags']       = $this->Model->card('tag');
		$this->vars['h_pages']      = $this->Model->card('pages');
		$this->vars['h_theme']      = $this->Model->card('theme');
		$this->vars['h_component']  = $this->Model->card('component');
		$this->vars['h_mail']       = $this->Model->card('mail');
		$this->vars['h_users']      = $this->Model->card('user');

		if (get_setting('web_analytics') == 'Y')
		{
			$range = 6;

			for ($i = $range; $i >= 0; $i--) 
			{
				$date = ($i == 0) ? date('Y-m-d') : date('Y-m-d', strtotime('-'.$i.' days'));
                
                $visitorstemp = $this->db
                    ->select('ip')
                    ->where('date', $date)
                    ->group_by('ip')
                    ->count_all_results('t_visitor');

                $hitstemp = $this->db
                    ->select('SUM(hits) as hitstoday')
                    ->where('date', $date)
                    ->group_by('date')
                    ->get('t_visitor')
                    ->row_array();

                $arrvisitor[$i] = $visitorstemp;
                $this->vars['arrhari'][$i] = '"'.ci_date(date('Y-m-d', strtotime('-'.$i.' days')), 'D, d M').'"';
                $arrhits[$i] = (empty($hitstemp['hitstoday']) ? '0' : $hitstemp['hitstoday']);
			}
			
			$this->vars['rvisitors']  = array_combine(array_keys($arrvisitor), array_reverse(array_values($arrvisitor)));
			$this->vars['rhits']      = array_combine(array_keys($arrhits), array_reverse(array_values($arrhits)));
			$this->vars['rrhari']     = implode(",",$this->vars['arrhari']);
			$this->vars['rrhits']     = implode(",",array_reverse($this->vars['rhits']));
			$this->vars['rrvisitors'] = implode(",",array_reverse($this->vars['rvisitors']));
		}

		$this->render_view('view_index');
	}
}// End Class.