<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Backend_Controller {

	public $mod = 'user';
	protected $dirout = 'mod/user/';
	protected $path_photo = CONTENTPATH.'uploads/user/';

	public function __construct() 
	{
		parent::__construct();
		$this->lang->load('mod/'.$this->mod);
		$this->load->model('mod/user_model');
	}


	public function index()
	{
		$this->meta_title(lang_line('mod_title_all'));

		if ($this->role->i('read'))
		{
			if ( $this->input->is_ajax_request() )
			{
				if ($this->input->post('act')=='delete')
				{
					return $this->_delete();
				}
				
				else
				{
					$data = [];

					foreach ($this->user_model->datatable('_all_user', 'data') as $val) 
					{					
						$row = [];

						$row[] = '<div class="text-center"><input type="checkbox" class="row_data" value="'. encrypt($val['user_id']) .'"></div>';

						$row[] = $val['user_id'];

						$row[] = '<div class="text-center"><a href="'.user_photo($val['user_photo']).'"><img src="'.user_photo($val['user_photo']).'" class="rounded-circle wd-30"></a></div>';

						$row[] = $val['user_username'];
						$row[] = $val['user_name'];
						$row[] = $val['group_title'];
						
						// status
						$row[] = ($val['user_active'] == 'Y' ? '<span class="badge badge-outline-success">'. lang_line('ui_active') .'</span>' : '<span class="badge badge-outline-danger">'. lang_line('ui_deactive') .'</span>');

						// action
						$row[] = '<div class="text-center"><div class="btn-group">
								<a href="'. admin_url($this->mod.'/edit/?id='.urlencode(encrypt($val['user_username']))) .'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_edit') .'"><i class="cificon licon-edit"></i></a>
								<button type="button" class="btn btn-xs btn-white delete_single" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_delete') .'" data-pk="'. encrypt($val['user_id']) .'"><i class="cificon licon-trash-2"></i></button>
								</div></div>';
						
						$data[] = $row;
					}

					$this->json_output(['data' => $data, 'recordsFiltered' => $this->user_model->datatable('_all_user', 'count')]);
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
			if ($this->input->is_ajax_request())
			{
				return $this->_submit('add');
			}
			elseif ( $this->input->method() == 'post' ) // when submit without ajax.
			{
				show_404();
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
		$this->meta_title(lang_line('mod_title_edit'));

		if ($this->role->i('modify'))
		{
			$key_edit = xss_filter(decrypt($this->input->get('id')),'xss');
			$data_user = $this->user_model->get_user_edit($key_edit);

			if (!empty($data_user))
			{
				if ($this->input->is_ajax_request()) // ajax submit form.
				{
					return $this->_submit('update',$data_user);
				}

				elseif ( $this->input->method() == 'post' ) // when submit form without ajax.
				{
					show_404();
				}

				else // default view.
				{
					$this->vars['res_user'] = $data_user;
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


	private function _delete()
	{
		if ( $this->input->is_ajax_request() && $this->role->i('delete') )
		{
			$data_pk = $this->input->post('data');

			foreach ($data_pk as $key)
			{
				$pk = xss_filter(decrypt($key),'sql');
				$photo = $this->user_model->get_photo($pk);
				$this->user_model->delete($pk);
				
				// delete user photo.
				if ( !empty($photo) && file_exists($this->path_photo.$photo) )
				{
					@unlink($this->path_photo.$photo);
				}
			}

			$response['success'] = true;
			$response['alert']['type'] = 'success';
			$response['alert']['content'] = lang_line('form_message_delete_success');
			$this->json_output($response);
		}
		else
		{
			$response['success'] = false;
			$this->json_output($response);
		}
	}


	private function _submit($param = '', $datas='')
	{
		if ($param == 'add') 
		{
			if ( $this->input->is_ajax_request() && $this->role->i('write'))
			{
				$this->form_validation->set_rules(array(
					array(
						'field' => 'group',
						'label' => lang_line('_group'),
						'rules' => 'required|trim|regex_match[/^[a-z0-9]+$/]'
					),
					array(
						'field' => 'username',
						'label' => lang_line('_username'),
						'rules' => 'required|trim|min_length[4]|max_length[20]|regex_match[/^[a-z0-9._]+$/]|callback__cek_addusername',
					),
					array(
						'field' => 'email',
						'label' => lang_line('_email'),
						'rules' => 'required|trim|min_length[10]|max_length[60]|valid_email|callback__cek_addemail',
					),
					array(
						'field' => 'input_password',
						'label' => lang_line('_password'),
						'rules' => 'required|min_length[6]|max_length[20]',
					),
					array(
						'field' => 'name',
						'label' => lang_line('_name'),
						'rules' => 'required|trim|min_length[4]|max_length[20]|alpha_numeric_spaces',
					),
					array(
						'field' => 'birthday',
						'label' => lang_line('_birthday'),
						'rules' => 'required',
					),
					array(
						'field' => 'gender',
						'label' => lang_line('_gender'),
						'rules' => 'required|trim|min_length[1]',
					),
					array(
						'field' => 'tlpn',
						'label' => lang_line('_tlpn'),
						'rules' => 'trim|max_length[20]',
					),
					array(
						'field' => 'active',
						'label' => lang_line('_status'),
						'rules' => 'required|trim|max_length[1]',
					)
				));

				if ( $this->form_validation->run() ) 
				{
					$photo_name = 'user-'.random_string('numeric', 20) .".jpg";

					$data = array(
						'key_group'    => xss_filter($this->input->post('group'), 'xss'),
						'username' => xss_filter($this->input->post('username')),
						'email'    => $this->input->post('email', TRUE),
						'password' => encrypt($this->input->post('input_password')),
						'name'     => xss_filter($this->input->post('name'), 'xss'),
						'gender'   => xss_filter($this->input->post('gender'), 'xss'),
						'tlpn'     => xss_filter($this->input->post('tlpn'), 'xss'),
						'address'  => xss_filter($this->input->post('address')),
						'about'    => xss_filter($this->input->post('about'), 'xss'),
						'active'   => xss_filter($this->input->post('active'), 'xss'),
						'photo'    => $photo_name,
					);

					// Submit with photo
					if ( empty($_FILES['fupload']['tmp_name']) )
					{
						$this->user_model->insert_user($data);
						$response['success'] = true;
						$this->json_output($response);
					}

					// Submit without photo
					else
					{
						$this->load->library('upload', array(
							'upload_path'   => $this->path_photo,
							'allowed_types' => "jpg|png|jpeg",
							'file_name'     => $photo_name,
							'max_size'      => 1024 * 10,
							'overwrite'     => TRUE
						));

						if ($this->upload->do_upload('fupload')) 
						{
							$this->user_model->insert_user($data);

							// crop image.
							$this->load->library('simple_image');
							$this->simple_image
							     ->fromFile($this->path_photo.$photo_name)
							     ->thumbnail(200, 200, 'center')
							     ->toFile($this->path_photo.$photo_name);

							$response['success'] = true;
							$this->json_output($response);
						}

						else
						{
							$response['success'] = false;
							$response['alert']['type'] = 'error';
							$response['alert']['content'] = $this->upload->display_errors();
							$this->json_output($response);
						}
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
				show_403();
			}
		}

		if ($param == 'update')
		{
			if ( $this->input->is_ajax_request() && $this->role->i('modify') )
			{
				$pk = decrypt($this->input->post('pk'));
				$id = xss_filter($pk, 'sql');

				if ($id == $datas['u_id'])
				{
					$this->form_validation->set_rules(array(
						array(
							'field' => 'group',
							'label' => lang_line('_group'),
							'rules' => 'required|trim|regex_match[/^[a-z0-9]+$/]'
						),
						array(
							'field' => 'email',
							'label' => lang_line('_email'),
							'rules' => 'required|trim|min_length[10]|max_length[60]|valid_email|callback__cek_editemail['.$pk.']',
						),
						array(
							'field' => 'input_password',
							'label' => lang_line('_password'),
							'rules' => 'min_length[6]|max_length[20]',
						),
						array(
							'field' => 'name',
							'label' => lang_line('_name'),
							'rules' => 'required|trim|min_length[4]|max_length[20]|alpha_numeric_spaces',
						),
						array(
							'field' => 'birthday',
							'label' => lang_line('_birthday'),
							'rules' => 'required|trim',
						),
						array(
							'field' => 'tlpn',
							'label' => lang_line('_tlpn'),
							'rules' => 'trim|max_length[20]',
						)
					));

					if ( $this->form_validation->run() ) 
					{
						$in_pass1 = $this->input->post('input_password');
						$in_pass2 = $datas['password'];
						$password = empty($in_pass1) ? $in_pass2 : encrypt($in_pass1);
						
						$dataUpdate = array(
							'key_group' => xss_filter($this->input->post('group'), 'xss'),
							'password' => $password,
							'email'    => $this->input->post('email', TRUE),
							'name'     => xss_filter($this->input->post('name'), 'xss'),
							'gender'   => $this->input->post('gender',TRUE),
							'birthday' => date('Y-m-d',strtotime($this->input->post('birthday'))),
							'address'  => xss_filter($this->input->post('address')),
							'about'    => xss_filter($this->input->post('about'), 'xss'),
							'tlpn'     => xss_filter($this->input->post('tlpn'), 'xss'),
							'active'   => xss_filter($this->input->post('active'), 'xss')
						);

						if ( empty($_FILES['fupload']['tmp_name']) ) // update without photo
						{
							$this->user_model->update($id, $dataUpdate);

							$response['success'] = true;
							$response['alert']['type'] = 'success';
							$response['alert']['content'] = lang_line('form_message_update_success');
							$this->json_output($response);
						}

						else // update with photo
						{

							$new_photo = $this->user_model->get_photo($id);

							$this->load->library('upload', array(
								'upload_path'   => $this->path_photo,
								'allowed_types' => "jpg|png|jpeg",
								'file_name'     => $new_photo,
								'max_size'      => 1024 * 10,
								'overwrite'     => TRUE
							));

							if ($this->upload->do_upload('fupload')) 
							{
								$this->user_model->update($id, $dataUpdate); // Update

								// crop image.
								$this->load->library('simple_image');
								$this->simple_image
								     ->fromFile($this->path_photo.$new_photo)
								     ->thumbnail(200, 200, 'center')
								     ->toFile($this->path_photo.$new_photo);

								$response['success'] = true;
								$response['alert']['type'] = 'success';
								$response['alert']['content'] = lang_line('form_message_update_success');
								$this->json_output($response);
							}

							else
							{
								$response['success'] = false;
								$response['alert']['type'] = 'error';
								$response['alert']['content'] = $this->upload->display_errors();
								$this->json_output($response);
							}
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
					show_404();
				}
			}

			else
			{
				show_403();
			}
		}
	}


	public function _cek_addusername($username = '') 
	{
		$cek = $this->user_model->cek_username($username);

		if ($cek == FALSE) 
		{
			$this->form_validation->set_message('_cek_addusername', lang_line('form_validation_already_exists'));
			return FALSE;
		}

		return $cek;
	}


	public function _cek_addemail($email = '') 
	{
		$cek = $this->user_model->cek_email($email);

		if ($cek == FALSE) 
		{
			$this->form_validation->set_message('_cek_addemail', lang_line('form_validation_already_exists'));
			return FALSE;
		}

		return $cek;
	}


	public function _cek_editemail($email,$id) 
	{
		$cek = $this->user_model->cek_email2($id, $email);

		if ($cek == FALSE) 
		{
			$this->form_validation->set_message('_cek_editemail', lang_line('form_validation_already_exists'));
			return FALSE;
		}
		else 
		{
			return TRUE;
		}
	}
} // End Class.