<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends Backend_Controller {

	public $mod = 'pages';
	public $pk;

	public function __construct() 
	{
		parent::__construct();
		$this->lang->load('mod/'.$this->mod);
		$this->load->model('mod/pages_model');
	}


	public function index()
	{
		$this->meta_title(lang_line('mod_title_all'));

		if ( $this->role->i('read') )
		{
			if ( $this->input->is_ajax_request() ) 
			{
				if ($this->input->post('act') == 'delete') // submit delete
				{
					$this->_delete();
				}

				else
				{
					$data = array();

					foreach ($this->pages_model->datatable('_all_pages', 'data') as $val) 
					{
						$row = [];
						$row[] = '<div class="text-center"><input type="checkbox" class="row_data" value="'. encrypt($val['id']) .'"></div>';

						$row[] = $val['id'];
						
						$row[] = $val['title'];

						$row[] = '<a href="'. site_url('pages/'.$val['seotitle']) .'" target="_blank" class="text-default">'. $val['seotitle'] .'</a>';

						$row[] = ($val['active'] == 'Y' ? '<span class="badge badge-outline-success">'. lang_line('ui_publish') .'</span>' : '<span class="badge badge-outline-default">'. lang_line('ui_draft') .'</span>');

						$row[] = '<div class="btn-group">
								<a href="'. admin_url($this->mod.'/preview/?id='. urlencode(encrypt($val['id'])) ) .'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_view').'" target="_blank"><i class="cificon licon-eye"></i></a>

								<a href="'. admin_url($this->mod.'/edit/?id='.urlencode(encrypt($val['id'])) ) .'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_edit').'"><i class="cificon licon-edit"></i></a>

								<button type="button" class="btn btn-xs btn-white delete_single" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_delete').'" data-pk="'. encrypt($val['id']) .'"><i class="cificon licon-trash-2"></i></button>
								</div>';

						$data[] = $row;
					}

					$this->json_output(['data' => $data, 'recordsFiltered' => $this->pages_model->datatable('_all_pages', 'count')]);
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


	public function preview()
	{
		$this->meta_title(lang_line('mod_title_preview'));

		if ($this->role->i('read'))
		{
			$getid = ($this->input->get('id') ? $this->input->get('id') : '0');

			$id = xss_filter(urldecode(decrypt($getid)),'sql');

			if ( $this->pages_model->cek_id($id) == 1 )
			{
				$this->vars['res'] = $this->pages_model->get_pages($id);
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
						'rules' => 'required|trim|min_length[2]|max_length[200]'
					),
					array(
						'field' => 'seotitle',
						'label' => lang_line('_seotitle'),
						'rules' => 'required|trim|min_length[2]|max_length[200]|callback__cek_add_seotitle'
					),
					array(
						'field' => 'status',
						'label' => lang_line('_status'),
						'rules' => 'required|trim|max_length[1]'
					)
				));
				
				if ( $this->form_validation->run() ) 
				{
					$data_form = array(
						'title'    => xss_filter($this->input->post('title')),
						'seotitle' => seotitle($this->input->post('seotitle')),
						'content'  => xss_filter($this->input->post('content')),
						'picture'  => xss_filter($this->input->post('picture')),
						'active'   => $this->input->post('status')
					);

					$this->pages_model->insert($data_form);
					$response['success'] = true;
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
				$this->render_view('view_add');
			}
		}

		else
		{
			$this->render_403();
		} 
	}


	public function _cek_add_seotitle($seotitle = '') 
	{
		$seotitle = seotitle($seotitle);
		$cek = $this->pages_model->cek_seotitle($seotitle);
		
		if ( $cek >= 1 )
		{
			$this->form_validation->set_message('_cek_add_seotitle', lang_line('form_validation_already_exists'));
			return FALSE;
		}
		else
			return TRUE;
	}


	public function edit()
	{
		$this->meta_title(lang_line('mod_title_edit'));

		if ( $this->role->i('modify') )
		{
			$get_id = (!empty($this->input->get('id')) ? decrypt($this->input->get('id')) : 0);
			$id_page = xss_filter($get_id ,'sql');
			
			if ( $this->pages_model->cek_id($id_page) == 1 )
			{
				if ( $this->input->is_ajax_request() ) 
				{
					$pk = encrypt($id_page);
					$rules = array(
						array(
							'field' => 'title',
							'label' => lang_line('_title'),
							'rules' => 'required|trim|min_length[2]|max_length[100]'
						),
						array(
							'field' => 'seotitle',
							'label' => lang_line('_seotitle'),
							'rules' => 'required|trim|min_length[2]|max_length[150]|callback__cek_edit_seotitle'
						),
						array(
							'field' => 'status',
							'label' => lang_line('_status'),
							'rules' => 'required|trim|max_length[1]'
						)
					);

					$this->form_validation->set_rules($rules);

					if ( $this->form_validation->run() ) 
					{
						$data = array(
							'title'    => xss_filter($this->input->post('title')),
							'seotitle' => seotitle($this->input->post('seotitle')),
							'content'  => xss_filter($this->input->post('content')),
							'picture'  => xss_filter($this->input->post('picture')),
							'active'   => $this->input->post('status')
						);

						if ( $this->pages_model->update($pk, $data) ) 
						{
							$response['success'] = true;
							$response['alert']['type'] = 'success';
							$response['alert']['content'] = lang_line('form_message_update_success');
						}
						else
						{
							$response['success'] = false;
							$response['alert']['type'] = 'error';
							$response['alert']['content'] = 'Error';
						}
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
					$data = $this->pages_model->get_pages($id_page);
					$this->vars['res_pages'] = $data;
					$this->vars['status1'] = ($data['active'] == 'Y' ? 'selected' :'');
					$this->vars['status0'] = ($data['active'] == 'N' ? 'selected' :'');

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


	public function _cek_edit_seotitle($seotitle = '') 
	{
		$seotitle = seotitle($seotitle);
		$id = ($this->input->get('id') ? decrypt($this->input->get('id')) : 0);
		$cek = $this->pages_model->cek_seotitle2($id, $seotitle);
		
		if ( $cek == FALSE ) 
		{
			$this->form_validation->set_message('_cek_edit_seotitle', lang_line('form_validation_already_exists'));
			return FALSE;
		} 
		else
			return TRUE;
	}


	private function _delete()
	{
		if ($this->role->i('delete'))
		{
			$data = $this->input->post('data');

			foreach ($data as $key)
			{
				$pk = xss_filter(decrypt($key),'sql');
				$this->pages_model->delete($pk);
			}

			$response['success'] = true;
			$response['alert']['type']    = 'success';
			$response['alert']['content'] = lang_line('form_message_delete_success');
			$this->json_output($response);
		} 
		else
		{
			$response['success'] = false;
			$this->json_output($response);
		}
	}
} // End Class.