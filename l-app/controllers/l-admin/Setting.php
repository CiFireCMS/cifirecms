<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Backend_Controller {

	public $mod = 'setting';

	public function __construct() 
	{
		parent::__construct();
		$this->lang->load('mod/'.$this->mod);
		$this->meta_title(lang_line('mod_title'));
		$this->load->model('mod/setting_model');
	}


	public function index()
	{
		if ($this->role->i('read'))
		{
			if ( $this->input->is_ajax_request() ) 
			{
				if ($this->input->post('act')=='metasocial')
				{
					if ($this->role->i('write') && $this->role->i('modify'))
					{
						$path = VIEWPATH.'meta_social.php'; 
						$data_content = $_POST['meta_content'];
						fopen($path, "r") or die("Could not open file!");
						write_file($path, $data_content);

						$response['success'] = TRUE;
						$response['alert']['type'] = 'success';
						$response['alert']['content'] = lang_line('_metasocial_update_success');
						$this->json_output($response);
					}
					else
					{
						$response['success'] = FALSE;
						$response['alert']['type'] = 'error';
						$response['alert']['content'] = 'Access denied';
						$this->json_output($response);
					}
				}
				else
				{
					$response['success'] = FALSE;
					$response['alert']['type'] = 'error';
					$response['alert']['content'] = 'ERROR';
					$this->json_output($response);
				}
			}
			
			else
			{
				$this->vars['content_general'] = $this->_setting_content('general');
				$this->vars['content_image']   = $this->_setting_content('image');
				$this->vars['content_local']   = $this->_setting_content('local');
				$this->vars['content_mail']    = $this->_setting_content('mail');
				$this->vars['content_config']  = $this->_setting_content('config');
				$this->vars['content_other']   = $this->_setting_content('other');
				$this->render_view('view_index');
			}
		}
		else
		{
			$this->render_403();
		}
	}


	private function _setting_content($groups='')
	{
		$dataOuput = '';
		$groups = $this->db->where('groups',$groups)->get('t_setting')->result_array();
		foreach ($groups as $res)
		{
			if ($res['type']=='html') 
			{
				$value = html_entity_decode($res['value']);
			}
			elseif ($res['type']=='password') 
			{
				$value = '<em class="text-muted">Hidden</em>';
			}
			else
			{
				$value = $res['value'];
			}

			$dataOuput .= '<tr>
							<td width="150" class="tx-medium">'. $res['options'] .'</td>
							<td>'.$value.'</td>
							<td width="95">
								<div class="btn-group">
									<a href="'. admin_url($this->mod.'/edit/?id='.urlencode(encrypt($res['id']))) .'" class="btn btn-sm btn-primary"><i class="cificon licon-edit"></i> '. lang_line('button_edit') .'</a>
								</div>
							</td>
						</tr>';
		}
		return $dataOuput;
	}


	public function createSitemap()
	{
		if ($this->role->i('read') && $this->role->i('write'))
		{
			if ($this->input->method() == 'post' && $this->input->post('pk')=='sitemap')
			{
				$this->load->library('sitemap');

				$changefreq  = $this->input->post('changefreq');
				$priority    = $this->input->post('priority');
				$this->sitemap->setDomain('');
				$this->sitemap->setPath(FCPATH);

				$this->sitemap->addItem(site_url(), $priority, $changefreq, '');

				$pages = $this->db
					->select('seotitle')
					->where('active', 'Y')
					->order_by('id', 'DESC')
					->get('t_pages')
					->result_array();
					
				foreach ($pages as $res_pages)
				{
					$this->sitemap->addItem(site_url('pages/'.$res_pages['seotitle']), $priority, $changefreq, date('Y-m-d'));
				}

				$categorys = $this->db
					->select('seotitle')
					->where('active', 'Y')
					->order_by('seotitle','ASC')
					->get('t_category')
					->result_array();

				foreach ($categorys as $res_category)
				{
					$this->sitemap->addItem(site_url('category/'.$res_category['seotitle']), $priority, $changefreq, date('Y-m-d'));
				}

				$posts = $this->db
					->select('seotitle, datepost')
					->where('active','Y')
					->order_by('id','DESC')
					->get('t_post')
					->result_array();
					
				foreach ($posts as $res_post)
				{
					$this->sitemap->addItem(post_url($res_post['seotitle']), $priority, $changefreq, $res_post['datepost']);
				}

				$this->sitemap->createSitemapIndex(site_url(), 'Today');

				$this->cifire_alert->set($this->mod, 'info', lang_line('_sitemap_was_created'));
				redirect(admin_url($this->mod), 'location', 302);
			}
			else
			{
				show_404();
			}
		}
		else
		{
			$this->render_403();
		}
	}


	public function list()
	{
		$this->meta_title(lang_line('_list_settings'));

		if ($this->role->i('read'))
		{
			if ( $this->input->is_ajax_request() )
			{
				if ($this->input->post('act') == 'delete')
				{
					return $this->_delete_list();
				}
				else
				{
					$data_method = '_table_settings';
					$data = array();
					
					foreach ($this->setting_model->datatable($data_method, 'data') as $val)
					{
						// row fortmat
						$row = [];

						// checkbox
						$row[] = '<div class="text-center"><input type="checkbox" class="row_data" value="'. encrypt($val['id']) .'"></div>';
						
						$row[] = $val['id'];
						$row[] = humanize($val['groups']);
						$row[] = $val['options'];

						if ($val['type']=='html')
						{
							$row_value = '<span class="badge badge-outline-danger">HTML Script</span>';
						}
						elseif ($val['type']=='password')
						{
							$row_value = '<span class="badge badge-outline-default">Hidden</span>';
						}
						else
						{
							$row_value = $val['value'];
						}

						$row[] =  $row_value;
						$action_tool = '<div class="text-center"><div class="btn-group">
									<a href="'.admin_url($this->mod.'/detail/?id='.urlencode(encrypt($val['id']))).'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_view').'"><i class="cificon licon-eye"></i></a>';
						if (group_active()=='root')
						{
							$action_tool = '<a href="'.admin_url($this->mod.'/edit/?id='.urlencode(encrypt($val['id']))).'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_edit').'"><i class="cificon licon-edit"></i></a>
										<button type="button" class="btn btn-xs btn-white delete_single" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_delete').'" data-pk="'. encrypt($val['id']) .'"><i class="cificon licon-trash-2"></i></button>
										</div></div>';
						}
						$row[] =  $action_tool;
						// generate rows data
						$data[] = $row;
					}
					
					$this->json_output(['data' => $data, 'recordsFiltered' => $this->setting_model->datatable($data_method, 'count')]);
				}
			}

			else
			{
				$this->render_view('view_listable');
			}
		}
		else
		{
			$this->render_403();
		}
	}


	private function _delete_list()
	{
		if ($this->role->i('delete'))
		{
			$data = $this->input->post('data');

			foreach ($data as $key)
			{
				$pk = xss_filter(decrypt($key),'sql');
				$this->setting_model->delete_list($pk);
			}

			$response['success'] = true;
			$response['alert']['type']    = 'success';
			$response['alert']['content'] = lang_line('form_message_delete_success');
			$this->json_output($response);
		} 
		else
		{
			$response = false;
			$this->json_output($response);
		}
	}


	public function detail()
	{
		$this->meta_title(lang_line('_list_settings'));

		if ($this->role->i('read'))
		{
			$get_id = decrypt($this->input->get('id'));
			$id = xss_filter($get_id, 'sql');
			$data = $this->setting_model->get_setting($id);
			$this->vars['result'] = $data;
			$this->render_view('view_detail', $this->vars);
		}
		else
		{
			$this->render_403();
		}
	}


	public function add_list()
	{
		$this->meta_title(lang_line('_add_setting'));

		if ($this->role->i('read') && $this->role->i('write'))
		{
			if ($this->input->method() == 'post')
			{
				$this->form_validation->set_rules(array(
					array(
						'field' => 'groups',
						'label' => lang_line('_groups'),
						'rules' => 'required|trim|regex_match[/^[a-z]+$/]'
					),
					array(
						'field' => 'options',
						'label' => lang_line('_options'),
						'rules' => 'required|trim|max_length[20]|regex_match[/^[a-z_]+$/]|callback__valid_add_options'
					),
					array(
						'field' => 'value[]',
						'label' => lang_line('_value'),
						'rules' => 'trim'
					),
					array(
						'field' => 'type',
						'label' => lang_line('_type'),
						'rules' => 'trim'
					),
				));

				if ( $this->form_validation->run() ) 
				{
					
					$op_type = xss_filter($this->input->post('type'),'xss');
					$op_content = '';

					if ($op_type == 'select')
					{
						$_val = $this->input->post('value');
						$op_val = $this->input->post('value')[1];
						$op_val = xss_filter($op_val);

						$op_content = '';
						foreach ($_val as $keys => $vals)
						{
							$op_content .= xss_filter($vals).'|';
						}
						$op_content = rtrim($op_content,'|');
					}
					elseif ($op_type == 'html')
					{
						$op_val = htmlspecialchars($this->input->post('value'));
					}
					elseif ($op_type == 'password')
					{
						$op_val = encrypt($this->input->post('value'));
					}
					else
					{
						$op_val = xss_filter($this->input->post('value'));
					}

					$data_insert = array(
						'groups' => seotitle($this->input->post('groups')), 
						'options' => xss_filter($this->input->post('options'), 'xss'),
						'value' => $op_val,
						'type' => $op_type,
						'content' => $op_content
					);

					if ( $this->setting_model->insert($data_insert) ) // insert data
					{
						$this->cifire_alert->set($this->mod, 'info', lang_line('form_message_add_success'));
						redirect(admin_url($this->mod.'/list')); // redirect to list
					}
					else
					{
						die('error inset data');
					}
				}
				else
				{
					$this->cifire_alert->set($this->mod, 'danger', validation_errors());
					redirect(uri_string());
				}
			}
			else
			{
				$this->render_view('view_add');
			}
		}
		else
		{
			$this->render_403();
		}
	}


	public function edit()
	{
		$this->meta_title(lang_line('_edit_setting'));

		if ($this->role->i('read') && $this->role->i('write'))
		{
			$id = xss_filter(decrypt($this->input->get('id')), 'sql');
			$pk = xss_filter(decrypt($this->input->post('pk')), 'sql');
			
			if ( $id!=0 && $this->setting_model->cek_id($id)==1 )
			{
				if ($this->input->method() == 'post')
				{
					$this->form_validation->set_rules(array(
						array(
							'field' => 'groups',
							'label' => lang_line('_groups'),
							'rules' => 'required|trim|regex_match[/^[a-z]+$/]'
						),
						array(
							'field' => 'options',
							'label' => lang_line('_options'),
							'rules' => 'required|trim|max_length[20]|regex_match[/^[a-z_]+$/]|callback__valid_edit_options['.$pk.']'
						),
						array(
							'field' => 'value',
							'label' => lang_line('_value'),
							'rules' => 'trim'
						),
						array(
							'field' => 'type',
							'label' => lang_line('_value'),
							'rules' => 'trim'
						)
					));

					if ( $this->form_validation->run() ) 
					{
						$type = xss_filter($this->input->post('type'),'xss');
						
						if ($type == 'html') {
							$value = htmlspecialchars($this->input->post('value'));
						}
						elseif ($type == 'password')
						{
							$value = encrypt($this->input->post('value'));
						}

						else
						{
							$value = xss_filter($this->input->post('value'));
						}

						$data_insert = array(
							'groups' => seotitle($this->input->post('groups')), 
							'options' => seotitle($this->input->post('options'), '_'),
							'value' => $value,
							'type' => xss_filter($this->input->post('type'),'xss')
						);

						if ($this->setting_model->update_setting($id,$data_insert))
						{
							if ($type == 'slug') // rewrite slug routes
							{
								$this->_write_route(get_setting('slug_title'), get_setting('slug_url'));
							}

							$this->cifire_alert->set($this->mod, 'info', lang_line('form_message_update_success'));

							redirect(admin_url($this->mod.'/edit/?id='.urlencode(encrypt($id))), 'location', 302);
						}
					}
					else
					{
						$this->cifire_alert->set($this->mod, 'danger', validation_errors());
						redirect(admin_url($this->mod.'/edit/?id='.urlencode(encrypt($id))), 'location', 302);
					}
				}

				else
				{
					$data = $this->setting_model->get_setting($id);
					$this->vars['result'] = $data;
					
					$select_option = '';
					if ($data['content'])
					{
						$arrContents = explode('|', $data['content']);
						foreach ($arrContents as $val)
						{
							$select_option .= '<option value="'.$val.'">'.$val.'</option>';
						}
					}
					$this->vars['select_option'] = $select_option;

					$this->render_view('view_edit');
				}
			}
			else
			{
				$this->render_404();
			}
		}
		else
		{
			$this->render_403();
		}
	}


	public function jsonTimezone()
	{
		if ($this->role->i('read') && $this->input->is_ajax_request())
		{
			$search = $this->input->post('search');
			if (!empty($search)) {
				$data = $this->db->like('value',$search)->get('t_timezone')->result_array();
			}
			else
			{
				$data = $this->db->get('t_timezone')->result_array();
			}
			
			echo json_encode($data);
		}
		else
		{
			$this->render_403();
		}
	}


	private function _write_route($slug_title='', $slug_url='')
	{
		$slg_setting = $this->db->where('options', 'slug_url')->get('t_setting')->row_array()['value'];
		$slg_title   = $this->db->where('options', 'slug_title')->get('t_setting')->row_array()['value'];
		$slg_actives = $this->db->where('title', $slg_setting)->get('t_slug')->result_array();

		$data = [];
		$data[] = "<?php defined('BASEPATH') OR exit('No direct script access allowed');";

		foreach ($slg_actives as $key) 
		{
			if ( $slg_setting === 'slug/seotitle' )
			{
				$data[] = '$route[\'' . $slg_title . '/([a-z0-9-]+)\'] = FWEB.\'/post/index/$1\';';
			}

			if ( $slg_setting === 'yyyy/seotitle' )
			{
				$data[] = '$route[\'' . $key['slug'] . '\'] = FWEB.\'/post/index/$2\';';
			}

			if ( $slg_setting === 'yyyy/mm/seotitle' )
			{
				$data[] = '$route[\'' . $key['slug'] . '\'] = FWEB.\'/post/index/$3\';';
			}

			if ( $slg_setting === 'yyyy/mm/dd/seotitle' )
			{
				$data[] = '$route[\'' . $key['slug'] . '\'] = FWEB.\'/post/index/$4\';';
			}

			if ( $slg_setting === 'seotitle' )
			{
				$data[] = '$route[\'' . $key['slug'] . '\'] = FWEB.\'/post/index/$1\';';
			}
		}

		$output = implode("\n", $data);
		write_file(APPPATH . 'config/routes/slug_routes.php', $output);
	}


	public function _valid_add_options($parm_options='')
	{
		if ($this->role->i('read') && $this->role->i('write'))
		{
			$options = seotitle($parm_options, '_');
			$valid = $this->db->select('id')->where('options',$options)->get('t_setting')->num_rows();

			if (empty($options))
			{
				$this->form_validation->set_message('_valid_add_options', lang_line('form_validation_required'));
				return FALSE;
			}
			else if ( $valid == 1)
			{
				$this->form_validation->set_message('_valid_add_options', lang_line('form_validation_already_exists'));
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		else
		{
			$this->render_403();
		}
	}

	public function _valid_edit_options($parm_options='', $pk='')
	{
		if ( $this->role->i('read') && $this->role->i('write'))
		{
			$id = xss_filter($pk,'sql');
			$options = seotitle($parm_options,'_');
			$valid = $this->setting_model->valid_edit_options($id,$options);

			if (empty($options))
			{
				$this->form_validation->set_message('_valid_edit_options', lang_line('form_validation_required'));
				return FALSE;
			}
			elseif ($valid == FALSE)
			{
				$this->form_validation->set_message('_valid_edit_options', lang_line('form_validation_already_exists'));
				return  FALSE;
			}
			else
			{
				return $valid;
			}
		}
		else
		{
			$this->render_403();
		}
	}


	public function backup()
	{
		if (group_active()=='root')
		{
			if ($this->input->method() == 'post')
			{
				$bakName     = 'cifiremasterz'.md5(date('Ymd'));
				$backupWebz  = $this->input->post('web') == '1' ? TRUE : FALSE;
				$inputTablez = $this->input->post('table');	
				
				if ($backupWebz == TRUE)
				{
					$this->_dataWebz($bakName);
				}
				if ($inputTablez)
				{
					$this->_dataBasez($bakName, $inputTablez);
				}
				
				if (rename(FCPATH."$bakName.zip", CONTENTPATH."uploads/$bakName.zip"))
				{
					$sbak['filez'] = "$bakName.zip";
					$this->session->set_userdata('__BAKZ',$sbak);
					redirect(admin_url($this->mod."/downloadz/".$sbak['filez']));			
				}
			}
			else
			{
				$this->render_404();
			}
		}
		else
		{
			show_403();
		}
	}

	
	public function downloadz($filez='')
	{
		$ses = '__BAKZ';

		if (group_active()=='root')
		{
			$sbak = $this->session->userdata($ses);

			if (!empty($sbak) && $sbak['filez'] == $filez)
			{
				$this->session->unset_userdata($ses);
				force_download(CONTENTPATH."uploads/$filez", NULL);
			}
			else
			{
				$this->session->unset_userdata($ses);
				show_404();
			}
		}
		else
		{
			$this->session->unset_userdata($ses);
			show_403();
		}
	}


	private function _dataWebz($bakName)
	{
		$sp = DIRECTORY_SEPARATOR;
		$folder_web = rtrim(FCPATH,$sp);
		$this->load->library('myzip');
		$this->myzip->zip_start(FCPATH.$bakName.'.zip');
		$this->myzip->zip_add($folder_web);
		$this->myzip->zip_end();
	}


	private function _dataBasez($bakName,$inputTablez)
	{
		$this->load->library('myzip');
		$this->myzip->zip_start(FCPATH.$bakName.'.zip');

		$this->db = $this->load->database('mysqli', TRUE);
		$this->load->dbutil();
		$dbName = 'database';
		$dataBasez = $this->dbutil->backup(array(
			'filename'   => $dbName,
			'tables'     => $inputTablez,
			'ignore'     => array(),
			'format'     => 'txt',
			'add_drop'   => TRUE,
			'add_insert' => TRUE,
			'newline'    => "\n",
			'foreign_key_checks' => TRUE
		));
		
		write_file(FCPATH."$dbName.sql", $dataBasez);
		
		$this->myzip->zip_add(FCPATH."$dbName.sql");
		
		if ($this->myzip->zip_end()) {
			@unlink(FCPATH."$dbName.sql");
		}
	}
} // End Class.