<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends Backend_Controller {

	public $mod = 'permissions';

	public function __construct() 
	{
		parent::__construct();
		$this->lang->load('mod/'.$this->mod);
		$this->load->model('mod/permissions_model');
	}


	public function index()
	{
		if ($this->role->i('read'))
		{
			$this->list_group();
		}
		else
		{
			$this->render_403();
		}
	}


	private function list_group()
	{
		$this->meta_title(lang_line('_list_group'));
		
		if ($this->role->i('read'))
		{
			if ($this->input->is_ajax_request())
			{
				if ($this->input->post('act') == 'delete' && $this->role->i('delete'))
				{
					return $this->_delete_group();
				}

				elseif ($this->input->post('act') == 'dataTableGroups')
				{
					$data = array();

					foreach ($this->permissions_model->datatable('_data_groups', 'data') as $val) 
					{
						$row = [];
						$row[] = '<div class="text-center"><input type="checkbox" class="row_data" value="'. $val['pk'] .'"></div>';

						$row[] = $val['id'];
						$row[] = $val['title'];
						$row[] = $val['group'];

						$row[] = '<div class="btn-group">
								<a href="'.admin_url($this->mod.'/role/'.$val['pk']).'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('_role').'"><i class="cificon licon-shield"></i></a>
								<a href="'.admin_url($this->mod.'/group/'.$val['pk']).'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_view') .'"><i class="cificon licon-eye"></i></a>
								<a href="'.admin_url($this->mod.'/group/edit/'.$val['pk']).'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_edit').'"><i class="cificon licon-edit"></i></a>
								<button type="button" class="btn btn-xs btn-white delete_single" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_delete').'" data-pk="'.$val['pk'].'"><i class="cificon licon-trash-2"></i></button>
								</div>';

						$data[] = $row;
					}

					$this->json_output(['data' => $data, 'recordsFiltered' => $this->permissions_model->datatable('_data_groups', 'count')]);
				}

				else
				{
					$response = false;
					$this->json_output($response);
				}
			}
			
			else
			{
				$this->render_view('list_group');
			}
		}
		else
		{
			$this->render_403();
		}
	}


	public function detail_group($pk='')
	{
		$this->meta_title(lang_line('_view_group'));

		if ($this->role->i('read')) 
		{
			$pk  = xss_filter($pk,'xss');
			$cek = $this->permissions_model->cek_pk($pk);

			if ($cek==1)
			{
				$res_group = $this->permissions_model->get_group($pk);
				$this->vars['res_group'] = $res_group;
				$this->vars['res_roles'] = $this->permissions_model->get_roles_by_group($res_group['group']);
				$this->vars['keyGroup'] = $pk;
				$this->render_view('view_group');
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


	public function add_group()
	{
		$this->meta_title(lang_line('_add_group'));

		if ($this->role->i('write'))
		{
			if ($this->input->is_ajax_request())
			{
				if ($this->input->post('act')=='add-group')
				{
					$this->form_validation->set_rules(array(
						array(
							'field' => 'title',
							'label' => lang_line('_title'),
							'rules' => 'required|trim|min_length[2]|regex_match[/^[a-zA-Z0-9- ]+$/]'
						),
						array(
							'field' => 'group',
							'label' => lang_line('_group'),
							'rules' => 'required|trim|min_length[2]|regex_match[/^[a-z0-9-]+$/]|callback__cek_add_group'
						)
					));

					if ($this->form_validation->run())
					{
						$data = array(
							'pk'    => uniqid(),
							'title' => $this->input->post('title'),
							'group' => $this->input->post('group'),
						);

						$this->permissions_model->insert_group($data);

						$response['success'] = true;
						$response['url'] = admin_url($this->mod);
					}
					else
					{
						$response['success'] = false;
						$response['alert']['type'] = 'error';
						$response['alert']['content'] = validation_errors();
					}
				}
				else
				{
					$response['success'] = false;
				}

				$this->json_output($response);
			}

			else
			{
				$this->render_view('add_group');
			}
		}
		else
		{
			$this->render_403();
		}
	}


	public function _cek_add_group($group='')
	{
		if ($this->role->i('read') && $this->role->i('write'))
		{
			$cek = $this->db->select('group')->where('group',$group)->get('t_user_group')->num_rows();
			if ( $cek == 1 )
			{
				$this->form_validation->set_message('_cek_add_group', lang_line('form_validation_already_exists'));
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


	public function edit_group($pk='')
	{
		$this->meta_title(lang_line('_edit_group'));
		
		if ($this->role->i('modify') && $pk!='root') 
		{
			if ($this->permissions_model->cek_pk($pk)==1)
			{
				if ($this->input->is_ajax_request())
				{
					if ($this->input->post('act')=='edit-group')
					{
						$this->form_validation->set_rules(array(
							array(
								'field' => 'title',
								'label' => lang_line('_title'),
								'rules' => 'required|trim|min_length[2]|regex_match[/^[a-zA-Z0-9- ]+$/]'
							),
							array(
								'field' => 'group',
								'label' => lang_line('_group'),
								'rules' => 'required|trim|regex_match[/^[a-z0-9-]+$/]|min_length[2]|callback__cek_edit_group['.$pk.']'
							)
						));

						if ($this->form_validation->run())
						{
							$data = array(
								'title' => $this->input->post('title'),
								'group' => $this->input->post('group'),
							);

							$this->permissions_model->update_group($pk,$data);
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
					}

					else
					{
						$response = false;
					}

					$this->json_output($response);
				}
				else
				{
					$this->vars['res_group'] = $this->permissions_model->get_group($pk);
					$this->render_view('edit_group');
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


	public function _cek_edit_group($group='', $pk='')
	{
		if ($this->role->i('read') && $this->role->i('modify')) 
		{
			$query = $this->db->where('group',$group)->get('t_user_group');

			if (empty($group))
			{
				$this->form_validation->set_message('_cek_edit_group', lang_line('form_validation_required'));
				return FALSE;
			}
			elseif (
			    $query->num_rows() == 1 && 
			    $query->row_array()['pk'] == $pk || 
			    $query->num_rows() == 0
			   ) 
			{
				return TRUE;
			}
			else 
			{
				$this->form_validation->set_message('_cek_edit_group', lang_line('form_validation_already_exists'));
				return FALSE;
			}
		}
		else
		{
			$this->render_403();
		}
	}	


	private function _delete_group()
	{
		if ($this->role->i('delete'))
		{
			$data = $this->input->post('data');
			foreach ($data as $pk)
			{
				$this->permissions_model->delete_group($pk);
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


	public function role($pk='')
	{
		$this->meta_title(lang_line('_role'));

		if ($this->role->i('read') && $this->role->i('delete'))
		{
			$pk = xss_filter($pk,'xss');
			$cek = $this->permissions_model->cek_pk($pk);

			if ($pk == 'root')
			{
				$this->render_403();
			}
			elseif ($cek == 1)
			{
				$res_group = $this->permissions_model->get_group($pk);

				if ( $this->input->is_ajax_request() )
				{
					if ($this->input->post('act')=='delete' && $this->role->i('delete'))
					{
						return $this->_delete_role();
					}

					elseif ($this->input->post('act')=='tableGroupRole')
					{
						$groupPk = $res_group['pk'];
						$group = $res_group['group'];
						$data = array();

						foreach ($this->permissions_model->datatable('_group_role', 'data', $group) as $val) 
						{
							$row = [];
							$row[] = '<div class="text-center"><input type="checkbox" class="row_data" value="'. $val['id'] .'"></div>';

							$row[] = $val['id'];
							$row[] = $val['module'];
							$row[] = ($val['read_access']==1?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-times text-danger"></i>');
							$row[] = ($val['write_access']==1?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-times text-danger"></i>');
							$row[] = ($val['modify_access']==1?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-times text-danger"></i>');
							$row[] = ($val['delete_access']==1?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-times text-danger"></i>');

							$row[] = '<div class="btn-group">
									<a href="'.admin_url($this->mod.'/edit-role/'.$val['id'].'/'.$groupPk).'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_edit').'"><i class="cificon licon-edit"></i></a>
									<button type="button" class="btn btn-xs btn-white delete_single" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_delete').'" data-pk="'.$val['id'].'"><i class="cificon licon-trash-2"></i></button>
									</div>';

							$data[] = $row;
						}

						$this->json_output(['data' => $data, 'recordsFiltered' => $this->permissions_model->datatable('_group_role', 'count', $group)]);
					}
					else
					{
						$response = false;
						$this->json_output($response);
					}
				}
				else
				{
					$this->vars['res_group'] = $res_group;
					$this->vars['res_roles'] = $this->permissions_model->get_roles_by_group($res_group['group']);
					$this->render_view('role');
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


	public function list_role()
	{
		$this->meta_title(lang_line('_list_role'));

		if ($this->role->i('read'))
		{
			if ($this->input->is_ajax_request())
			{
				if ($this->input->post('act') == 'delete' && $this->role->i('delete'))
				{
					return $this->_delete_role();
				}
				elseif ($this->input->post('act') == 'dataTableRoles')
				{
					$data = array();

					foreach ($this->permissions_model->datatable('_data_roles', 'data') as $val) 
					{
						$row = [];
						$row[] = '<div class="text-center"><input type="checkbox" class="row_data" value="'. $val['id'] .'"></div>';

						$row[] = $val['id'];
						$row[] = $val['group'];
						$row[] = $val['module'];
						$row[] = ($val['read_access']==1?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-times text-danger"></i>');
						$row[] = ($val['write_access']==1?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-times text-danger"></i>');
						$row[] = ($val['modify_access']==1?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-times text-danger"></i>');
						$row[] = ($val['delete_access']==1?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-times text-danger"></i>');
						$row[] = '<div class="btn-group">
								<a href="'.admin_url($this->mod.'/edit-role/'.$val['id']).'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_edit').'"><i class="cificon licon-edit"></i></a>
								<button type="button" class="btn btn-xs btn-white delete_single" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_delete').'" data-pk="'.$val['id'].'"><i class="cificon licon-trash-2"></i></button>
								</div>';

						$data[] = $row;
					}

					$this->json_output(['data' => $data, 'recordsFiltered' => $this->permissions_model->datatable('_data_roles', 'count')]);
				}
				else
				{
					$response = false;
					$this->json_output($response);
				}
			}
			else
			{
				$this->render_view('list_role');
			}
		}
		else
		{
			$this->render_403();
		}
	}


	public function add_group_role($groupPk='')
	{
		$this->meta_title(lang_line('_add_role'));

		if ($this->role->i('write'))
		{
			$groupPk = xss_filter($groupPk,'xss');
			$cekGroup = $this->permissions_model->cek_pk($groupPk);

			if ( $cekGroup == 1)
			{
				$res_group = $this->permissions_model->get_group($groupPk);

				if ($this->input->is_ajax_request())
				{
					if ($this->input->post('act')=='add-group-role')
					{
						$this->form_validation->set_rules(array(
							array(
								'field' => 'module',
								'label' => lang_line('_module'),
								'rules' => 'required|trim|min_length[2]'
							)
						));

						if ($this->form_validation->run())
						{
							$read_access = !empty($this->input->post('read_access')) ? 1:0;
							$write_access = !empty($this->input->post('write_access')) ? 1:0;
							$modify_access = !empty($this->input->post('modify_access')) ? 1:0;
							$delete_access = !empty($this->input->post('delete_access')) ? 1:0;
							$data = array(
								'group' => $res_group['group'],
								'module' => seotitle($this->input->post('module')),
								'read_access' => $read_access,
								'write_access' => $write_access,
								'modify_access' => $modify_access,
								'delete_access' => $delete_access
							);

							$cek = $this->db
								->select('group')
								->where('group',$data['group'])
								->where('module',$data['module'])
								->get('t_roles')
								->num_rows();

							if ($cek==0 && $data['group']!='root')
							{
								$this->permissions_model->insert_role($data);
								$response['success'] = true;
								$response['url'] = admin_url($this->mod.'/role/'.$groupPk);
								$this->json_output($response);
							}
							elseif ($data['group']=='root')
							{
								$response['success'] = false;
								$response['alert']['type'] = 'error';
								$response['alert']['content'] = lang_line('_root_cannot_be_changed');
								$this->json_output($response);
							}
							else
							{
								$response['success'] = false;
								$response['alert']['type'] = 'error';
								$response['alert']['content'] = lang_line('_role_is_exist');
								$this->json_output($response);
							}
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
						$response['success'] = false;
						$this->json_output($response);
					}
				}

				else
				{
					$this->vars['res_group'] = $res_group;
					$this->render_view('add_group_role');
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


	public function edit_group_role($id='', $groupPk='')
	{
		$this->meta_title(lang_line('_edit_role'));

		if ($this->role->i('modify'))
		{
			$id = xss_filter($id,'sql');
			$groupPk = xss_filter($groupPk,'xss');
			$queryGroup = $this->db->where('pk',$groupPk)->get('t_user_group')->row_array();
			$queryRole = $this->db->where('id',$id)->where('group',$queryGroup['group'])->get('t_roles');

			if ( $queryRole->num_rows() == 1 )
			{
				$res_role = $queryRole->row_array();

				if ($this->input->is_ajax_request())
				{
					$postPk = decrypt($this->input->post('pk'));

					if ($postPk == $queryGroup['pk']) // check pk from post and uri
					{
						$read_access   = !empty($this->input->post('read_access')) ? 1:0;
						$write_access  = !empty($this->input->post('write_access')) ? 1:0;
						$modify_access = !empty($this->input->post('modify_access')) ? 1:0;
						$delete_access = !empty($this->input->post('delete_access')) ? 1:0;
						$data = array(
							'read_access'   => $read_access,
							'write_access'  => $write_access,
							'modify_access' => $modify_access,
							'delete_access' => $delete_access
						);

						// $this->db->where('id',$id)->update('t_roles', $data);
						$this->permissions_model->update_role_by_id($id,$data);
						$response['success'] = true;
						$response['alert']['type'] = 'success';
						$response['alert']['content'] = lang_line('form_message_update_success');
						$this->json_output($response);
					}
					else
					{
						$response['success'] = false;
						$this->json_output($response);
					}
				}

				else
				{

					$this->vars['res_role'] = $res_role;
					$this->vars['res_group'] = $queryGroup;

					$this->vars['read_access'] = ($res_role['read_access']==1?'checked':'');
					$this->vars['write_access'] = ($res_role['write_access']==1?'checked':'');
					$this->vars['modify_access'] = ($res_role['modify_access']==1?'checked':'');
					$this->vars['delete_access'] = ($res_role['delete_access']==1?'checked':'');

					$this->render_view('edit_group_role');
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


	public function add_role($groupPk='')
	{
		if (!empty($groupPk))
		{
			return $this->add_group_role($groupPk);
		}
		else
		{
			return $this->add_list_role();
		}
	}



	public function add_list_role()
	{
		$this->meta_title(lang_line('_add_role'));

		if ($this->role->i('write'))
		{
			if ($this->input->is_ajax_request())
			{
				if ($this->input->post('act')=='add-role')
				{
					$this->form_validation->set_rules(array(
						array(
							'field' => 'group',
							'label' => lang_line('_group'),
							'rules' => 'required|trim|min_length[2]'
						),
						array(
							'field' => 'module',
							'label' => lang_line('_module'),
							'rules' => 'required|trim|min_length[2]'
						)
					));

					if ($this->form_validation->run())
					{
						$read_access = !empty($this->input->post('read_access')) ? 1:0;
						$write_access = !empty($this->input->post('write_access')) ? 1:0;
						$modify_access = !empty($this->input->post('modify_access')) ? 1:0;
						$delete_access = !empty($this->input->post('delete_access')) ? 1:0;
						$data = array(
							'group' => seotitle($this->input->post('group')),
							'module' => seotitle($this->input->post('module')),
							'read_access' => $read_access,
							'write_access' => $write_access,
							'modify_access' => $modify_access,
							'delete_access' => $delete_access
						);

						$cek = $this->db->select('group')
							->where('group',$data['group'])
							->where('module',$data['module'])
							->get('t_roles')
							->num_rows();

						if ($cek==0 && $data['group'] != 'root')
						{
							$this->permissions_model->insert_role($data);
							$response['success'] = true;
							$response['url'] = admin_url($this->mod.'/list-roles');
						}
						elseif ($data['group']=='root')
						{
							$response['success'] = false;
							$response['alert']['type'] = 'error';
							$response['alert']['content'] = lang_line('_root_cannot_be_changed');
							$this->json_output($response);
						}
						else
						{
							$response['success'] = false;
							$response['alert']['type'] = 'error';
							$response['alert']['content'] = lang_line('_role_is_exist');
						}
					}
					else
					{
						$response['success'] = false;
						$response['alert']['type'] = 'error';
						$response['alert']['content'] = validation_errors();
					}
				}
				else
				{
					$response['success'] = false;
				}

				$this->json_output($response);
			}
			else
			{
				$this->render_view('add_list_role');
			}
		}
		else
		{
			$this->render_403();
		}
	}


	public function edit_list_role($id='')
	{
		$this->meta_title(lang_line('_edit_role'));

		if ($this->role->i('modify'))
		{
			$id = xss_filter($id,'xss');
			$getRole = $this->permissions_model->get_role_by_id($id);

			if ( !is_null($getRole) )
			{
				if ($this->input->is_ajax_request())
				{
					if ($this->input->post('act')=='edit-role')
					{
						$data = array(
							'read_access'   => !empty($this->input->post('read_access')) ? 1:0,
							'write_access'  => !empty($this->input->post('write_access')) ? 1:0,
							'modify_access' => !empty($this->input->post('modify_access')) ? 1:0,
							'delete_access' => !empty($this->input->post('delete_access')) ? 1:0
						);

						$this->permissions_model->update_role_by_id($id,$data);

						$response['success'] = true;
						$response['alert']['type'] = 'success';
						$response['alert']['content'] = lang_line('form_message_update_success');
						$this->json_output($response);
					}
					else
					{
						$response = false;
						$this->json_output($response);
					}
				}
				else
				{
					$this->vars['res_role'] = $getRole;
					$this->vars['read_access'] = ($getRole['read_access']==1?'checked':'');
					$this->vars['write_access'] = ($getRole['write_access']==1?'checked':'');
					$this->vars['modify_access'] = ($getRole['modify_access']==1?'checked':'');
					$this->vars['delete_access'] = ($getRole['delete_access']==1?'checked':'');
					$this->render_view('edit_list_role');
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


	private function _delete_role()
	{
		if ($this->role->i('delete'))
		{
			$data = $this->input->post('data');
			foreach ($data as $pk)
			{
				$this->permissions_model->delete_role($pk);

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

} // End Class.