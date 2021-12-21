<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends Backend_Controller {

	public $mod = 'pages';
	public $pk;

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('mod/'.$this->mod);
		$this->load->model('mod/pages_model','_model');
	}


	public function index() 
	{
		$this->meta_title(lang_line('mod_title_all'));

		if ( $this->role->i('read') )
		{
			if ($this->input->is_ajax_request())
			{
				if ($this->input->post('act') == 'delete')
				{
					return $this->_delete();
				}

				else
				{
					$data = array();

					foreach ($this->_model->datatable('_all_pages', 'data') as $val) 
					{
						// row fortmat
						$row = [];

						// checkbox
						$row[] = '<div class="text-center"><input type="checkbox" class="row_data" value="'. encrypt($val['id']) .'"></div>';

						// ID
						$row[] = $val['id'];

						// Title
						$row[] = $val['title'];

						// Seotitle
						$row[] = $val['seotitle'];
						
						// Status
						$row[] = ($val['active'] == 'Y' ? '<span class="badge badge-outline-success">'. lang_line('ui_publish') .'</span>' : '<span class="badge badge-outline-default">'. lang_line('ui_draft') .'</span>');

						// Action
						$row[] = '<div class="text-center"><div class="btn-group">
								<a href=\''. admin_url($this->mod.'/preview/'. hashid_encode($val['id']) ) .'\'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_view') .'" target="_blank"><i class="cificon licon-eye"></i></a>

								<a href="'. admin_url($this->mod.'/edit/?id='.urlencode(encrypt($val['id'])) ) .'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_edit') .'"><i class="cificon licon-edit"></i></a>

								<button type="button" class="btn btn-xs btn-white delete_single" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_delete') .'" data-pk="'. encrypt($val['id']) .'"><i class="cificon licon-trash-2"></i></button>
								</div></div>';
						
						// generate rows data
						$data[] = $row;
					}

					$csrf_name = $this->security->get_csrf_token_name();
					$csrf_hash = $this->security->get_csrf_hash();  
					$response = array('data' => $data, 'recordsFiltered' => $this->_model->datatable('_all_pages', 'count'));
					$response['csrf_name'] = $csrf_name;
					$response['csrf_hash'] = $csrf_hash;

					return  $this->json_output($response);
				}
			}
			
			else
			{
				$this->render_view('view_index');
			}
		}

		else
		{
			$this->render_403();
		}
	}


	public function add() 
	{
		$this->meta_title(lang_line('mod_title_add'));

		if ( $this->role->i('write') ) 
		{
			if ( $this->input->is_ajax_request() )
			{
				$this->form_validation->set_rules(array(
					array(
						'field' => 'title',
						'label' => lang_line('_title'),
						'rules' => 'required|trim|min_length[3]|max_length[255]'
					),
					array(
						'field' => 'seotitle',
						'label' => lang_line('_seotitle'),
						'rules' => 'required|trim|min_length[3]|max_length[255]|callback__cek_add_seotitle'
					),
					array(
						'field' => 'content',
						'label' => lang_line('_content'),
						'rules' => 'required'
					),
					array(
						'field' => 'status',
						'label' => lang_line('_status'),
						'rules' => 'required|trim|max_length[1]'
					)
				));

				if ( $this->form_validation->run() ) 
				{
					$this->_model->insert_data(array(
						'title'    => xss_filter($this->input->post('title', true), 'xss'),
						'seotitle' => seotitle($this->input->post('seotitle', true)),
						'content'  => xss_filter($this->input->post('content'), 'xss'),
						'picture'  => xss_filter($this->input->post('picture', true)),
						'active'   => xss_filter($this->input->post('status'), 'xss')
					));
					$response['success'] = true;
					$this->json_output($response);
				}
				else
				{
					$response['success'] = false;
					$response['alert']['type'] = 'error';
					$response['alert']['content'] = validation_errors();
					$this->json_output($response);
				}
			}
			else
			{
				$this->render_view('view_add', $this->vars);
			}
		}
		else 
		{
			$this->render_403();
		}
	}


	public function edit() 
	{
		$this->meta_title(lang_line('mod_title_edit'));

		if ( $this->role->i('modify') )
		{
			$get_id = (!empty($this->input->get('id')) ? decrypt($this->input->get('id')) : 0);
			$ID = xss_filter($get_id,'sql');

			if ( $this->_model->cek_id($ID) == 1 ) 
			{
				if ( $this->input->is_ajax_request() )
				{
					$this->form_validation->set_rules(array(
						array(
							'field' => 'title',
							'label' => lang_line('_title'),
							'rules' => 'required|trim|min_length[3]|max_length[255]'
						),
						array(
							'field' => 'seotitle',
							'label' => lang_line('_seotitle'),
							'rules' => 'required|trim|min_length[3]|max_length[255]|callback__cek_edit_seotitle'
						),
						array(
							'field' => 'content',
							'label' => lang_line('_content'),
							'rules' => 'required'
						),
						array(
							'field' => 'status',
							'label' => lang_line('_status'),
							'rules' => 'required|trim|max_length[1]'
						)
					));

					if ( $this->form_validation->run() ) 
					{
						$this->_model->update_data($ID, array(
							'title'    => xss_filter($this->input->post('title', true), 'xss'),
							'seotitle' => seotitle($this->input->post('seotitle', true)),
							'content'  => xss_filter($this->input->post('content'), 'xss'),
							'picture'  => xss_filter($this->input->post('picture', true)),
							'active'   => xss_filter($this->input->post('status'), 'xss')
						));

						$response['success'] = true;
						$response['alert']['type'] = 'success';
						$response['alert']['content'] = lang_line('form_message_update_success');
					}
					else
					{
						$response['success'] = false;
						$response['alert']['type'] = 'error';
						$response['alert']['content'] = validation_errors();
					}

					$this->json_output($response);
				}
				else
				{
					$data = $this->_model->get_pages($ID);
					
					$this->vars['status'] = '<option value="'. $data['active'] .'" style="display:none;">'.($data['active'] == 'Y' ? lang_line('ui_publish') : lang_line('ui_draft') ).'</option>';

					$this->vars['res_pages']  = $data;
					$this->render_view('view_edit', $this->vars);
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


	private function _delete()
	{
		if ($this->role->i('delete'))
		{
			$data = $this->input->post('data');

			foreach ($data as $key)
			{
				$pk = xss_filter(decrypt($key),'sql');
				$this->_model->delete($pk);
			}

			$response['success'] = true;
			$this->json_output($response);
		} 
		else
		{
			$response['success'] = false;
			$this->json_output($response);
		}
	}


	public function preview($varID = '')
	{
		$this->meta_title(lang_line('mod_title_preview'));
		
		if ($this->role->i('read'))
		{
			$ID = xss_filter(hashid_decode($varID),'sql');

			if ($this->_model->cek_id($ID) == 1)
			{
				$this->vars['res'] = $this->_model->get_pages($ID);
				$this->render_view('view_preview', $this->vars);
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


	public function _cek_add_seotitle($seotitle = '') 
	{
		$cek = $this->_model->cek_seotitle(seotitle($seotitle));

		if ( $cek === false ) 
		{
			$this->form_validation->set_message('_cek_add_seotitle', lang_line('form_validation_already_exists'));
		}
		
		return $cek;
	}


	public function _cek_edit_seotitle($seotitle = '') 
	{
		$ID = ($this->input->get('id') ? decrypt($this->input->get('id')) : 0);
		$cek = $this->_model->cek_seotitle2($ID, seotitle($seotitle));
		
		if ( $cek === false ) 
		{
			$this->form_validation->set_message('_cek_edit_seotitle', lang_line('form_validation_already_exists'));
		} 

		return $cek;
	}
} // End class.