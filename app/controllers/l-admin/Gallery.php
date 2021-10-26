<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends Backend_Controller {
	
	public $mod = 'gallery';

	public function __construct() 
	{
		parent::__construct();
		
		$this->lang->load('mod/'.$this->mod);
		$this->load->model('mod/gallery_model');
		$this->meta_title(lang_line('mod_title'));
	}


	public function index()
	{
		if ( $this->role->i('read') )
		{
			if ($this->input->method() == 'post')
			{
				if ( $this->role->i('write') )
				{
					$this->form_validation->set_rules(array(
						array(
							'field' => 'title',
							'label' => lang_line('_title'),
							'rules' => 'trim|min_length[4]|max_length[150]'
						)
					));
					
					if ( $this->form_validation->run() ) 
					{
						$title = !empty($this->input->post('title')) ? $this->input->post('title') : mdate(date('Y-m-d'), 2);

						$data_insert = array(
							'title'    => $title,
							'seotitle' => seotitle(date('Ymdhis').random_string('numeric', 12)),
							'active'   => 'Y'
						);

						$this->gallery_model->insert('t_album', $data_insert);
					}
					else 
					{
						$this->cifire_alert->set($this->mod, 'danger', validation_errors());
					}

					redirect(uri_string());
				}
				else
				{
					$this->render_403();
				}
			}
			else
			{
				$this->vars['albums'] = $this->gallery_model->all_album();
				$this->render_view('view_index');
			}
		}

		else
		{
			$this->render_403();
		}
	}


	public function album()
	{
		if ( $this->role->i('read') )
		{
			$getid = ($this->input->get('id') ? $this->input->get('id') : '0');
			$id_album = xss_filter(urldecode(decrypt($getid)),'sql');

			if ($this->input->method() == 'post')
			{
				if ( $this->role->i('write') )
				{
					$this->form_validation->set_rules(array(
						array(
							'field' => 'title',
							'label' => lang_line('_title'),
							'rules' => 'trim|min_length[2]|max_length[200]',
						),
						array(
							'field' => 'picture',
							'label' => lang_line('_picture'),
							'rules' => 'required',
						)
					));

					if ( $this->form_validation->run() ) 
					{
						$title = ( !empty($this->input->post('title')) ? $this->input->post('title') : random_string('numeric', 8).'-'.date('dmY') );
						
						$pictures = json_to_array($this->input->post('picture'));
						
						if ( count($pictures) == 0 ) // if insert single data
						{
							$data = array(
								'id_album' => $id_album,
								'title'    => $title,
								'seotitle' => seotitle($title."-".random_string('numeric', 10)),
								'picture'  => $this->input->post('picture')
							);

							$this->gallery_model->insert('t_gallery', $data);
						}

						else // if insert multiple data
						{
							$i = 1;
							foreach ($pictures as $val)
							{
								$i ++;
								$datas = array(
									'id_album' => $id_album,
									'title'    => $title.$i,
									'seotitle' => seotitle($title.$i."-".random_string('numeric', 10)),
									'picture'  => $val
								);
								$this->gallery_model->insert('t_gallery', $datas);
							}
						}
					}

					else 
					{
						$this->cifire_alert->set($this->mod, 'danger', validation_errors());
					}

					redirect(admin_url($this->mod.'/album/?id='.urlencode(encrypt($id_album))));
				}
				else
				{
					$this->render_403();
				}
			}

			else
			{
				$this->vars['id_album'] = urlencode(encrypt($id_album));
				$this->vars['res_album'] = $this->gallery_model->get_album($id_album);
				$this->vars['gallerys'] = $this->gallery_model->get_gallerys($id_album);
				
				$this->render_view('view_album');
			}
		}
		else
		{
			$this->render_403();
		}
	}


	public function delete($param = '')
	{
		if ( $this->input->is_ajax_request() )
		{
			if ( $this->role->i('delete') )
			{
				$data_pk = $this->input->post('data');

				foreach ($data_pk as $key)
				{
					$pk = xss_filter(decrypt($key),'sql');
					if ( $param == 'album' ) 
					{
						$this->gallery_model->delete_album($pk);
					}
					if ( $param == 'image' ) 
					{
						$this->gallery_model->delete($pk);
					}
				}

				$response['success'] = true;
				$response['dataDelete'] = decrypt($data_pk[0]);
				$response['alert']['type'] = 'success';
				$response['alert']['content'] = lang_line('form_message_delete_success');

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
			$this->render_404();
		}
	}


} // End Class.