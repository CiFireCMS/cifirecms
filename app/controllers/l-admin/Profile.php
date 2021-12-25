<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends Backend_Controller {
	
	public $mod = 'profile';
	public $dirout = 'mod/profile/';
	public $path_photo = PUBLICPATH.'uploads/user/';

	public function __construct() 
	{
		parent::__construct();
		
		$this->lang->load('mod/'.$this->mod);
		$this->load->model('mod/profile_model');
		$this->meta_title(lang_line('mod_title'));
		$this->data = $this->profile_model->get_data();
	}


	public function index()
	{
		if ($this->role->i('read'))
		{
			$this->vars['gender'] = ($this->data['user_gender']=='M' ? lang_line('_male') : lang_line('_female'));

			$this->render_view('view_index');
		}
		else
		{
			$this->render_403();
		}
	}


	public function edit()
	{
		if ($this->role->i('modify'))
		{	
			$data = $this->profile_model->get_data();
			$ID = $data['user_id'];

			if ( $this->input->is_ajax_request() )
			{
				$this->form_validation->set_rules(array(
					array(
						'field' => 'name',
						'label' => lang_line('_name'),
						'rules' => 'required|trim|min_length[4]|max_length[20]|alpha_numeric_spaces',
					),
					array(
						'field' => 'email',
						'label' => lang_line('_email'),
						'rules' => 'required|trim|min_length[4]|max_length[80]|valid_email|callback__cek_email['.$ID.']',
					),
					array(
						'field' => 'input_password',
						'label' => lang_line('_password'),
						'rules' => 'min_length[6]',
					),
					array(
						'field' => 'birthday',
						'label' => lang_line('_birthday'),
						'rules' => 'required|trim',
					),
					array(
						'field' => 'tlpn',
						'label' => lang_line('_tlpn'),
						'rules' => 'max_length[20]|regex_match[/^[0-9-+ ]+$/]',
					),
					array(
						'field' => 'address',
						'label' => lang_line('_address'),
						'rules' => 'trim|max_length[600]',
					),
					array(
						'field' => 'about',
						'label' => lang_line('_about'),
						'rules' => 'trim|max_length[600]',
					)
				));

				if ( $this->form_validation->run() ) 
				{
					$dataUpdate = array(
						'password' => ( !empty($this->input->post('input_password')) ? encrypt($this->input->post('input_password')) : $data['user_password'] ),
						'email'    => xss_filter($this->input->post('email', true), 'xss'),
						'name'     => xss_filter($this->input->post('name'), 'xss'),
						'gender'   => xss_filter($this->input->post('gender'), 'xss'),
						'birthday' => date('Y-m-d',strtotime($this->input->post('birthday'))),
						'about'    => xss_filter($this->input->post('about'), 'xss'),
						'address'  => xss_filter($this->input->post('address'),'xss'),
						'tlpn'     => xss_filter($this->input->post('tlpn'), 'xss')
					);

					if ( empty($_FILES['fupload']['tmp_name']) )
					{
						$rmPhoto = $this->input->post('rmphoto');

						if ($rmPhoto == 'on')
						{
							$newPhoto = random_string('numeric', 16).'.jpg';
							$dataUpdate2 = array(
								'photo' => $newPhoto
							);

							$dataUpdate2 = array_merge($dataUpdate, $dataUpdate2);

							if (file_exists($this->path_photo.$data['user_photo']))
							{
								@unlink($this->path_photo.$data['user_photo']);
							}

							if (file_exists(PUBLICPATH.'thumbs/user/'.$data['user_photo']))
							{
								@unlink(PUBLICPATH.'thumbs/user/'.$data['user_photo']);
							}

							$this->profile_model->update($dataUpdate2);
						}
						else
						{
							$this->profile_model->update($dataUpdate);
						}
						
						$response['success'] = true;
						$response['alert']['type'] = 'success';
						$response['alert']['content'] = lang_line('form_message_update_success');

						$this->json_output($response);
					}
					else
					{
						$new_photo = $data['user_photo'];

						$this->load->library('upload', array(
							'upload_path'   => $this->path_photo,
							'allowed_types' => "jpg|png|jpeg",
							'file_name'     => $new_photo,
							'max_size'      => 1024 * 10,
							'overwrite'     => true
						));

						if ($this->upload->do_upload('fupload')) 
						{
							$this->profile_model->update($dataUpdate);

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
				$this->vars['res'] = $data;
				$this->render_view('view_edit');
			}
		}
		else
		{
			$this->render_403();
		}
	}


	public function _cek_email($email,$id) 
	{
		$cek = $this->profile_model->cek_email($id, $email);

		if ($cek == false) 
		{
			$this->form_validation->set_message('_cek_email', lang_line('form_validation_already_exists'));
			return false;
		}
		else 
		{
			return true;
		}
	}
} // End Calss.