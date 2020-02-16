<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mail extends Backend_Controller {
	
	public $mod = 'mail';

	public function __construct() 
	{
		parent::__construct();
		$this->lang->load('mod/'.$this->mod);
		$this->load->model('mod/mail_model');
		$this->meta_title(lang_line('mod_title'));
	}


	public function index()
	{
		return $this->_inbox();
	}


	private function _inbox()
	{
		if ( $this->role->i('read') )
		{
			if ( $this->input->is_ajax_request() )
			{
				if ($this->input->post('act') == 'delete')
				{
					return $this->_delete();
				}
				else
				{				
					$data = array();

					foreach ($this->mail_model->datatable('_inbox', 'data') as $val) 
					{
						$row = [];
						$row[] = '<div class="text-center"><input type="checkbox" class="row_data" value="'. encrypt($val['id']) .'"></div>';

						$row[] = $val['id'];

						if ( $val['active'] == 'Y' )
						{
							$row[] = '<div id="mico-'.$val['id'].'" class="text-muted"><i class="fa fa-envelope-open-o"></i></div>';
						} 
						else 
						{
							$row[] = '<div id="mico-'.$val['id'].'" class="text-primary"><i class="fa fa-envelope"></i></div>';
						}

						$row[] = $val['name'].'<br><small class="text-muted">'. $val['email'] .'</small>';
						$row[] = '<a href="'. admin_url($this->mod.'/read/'.$val['id']) .'">'. $val['subject'] .' <small class="text-muted"> <br> '. cut($val['message'], 80, TRUE) .'</small></a>';
						$row[] = ci_date($val['date'], 'l, d M Y');
						$row[] = '<div class="text-center"><div class="btn-group">
								<a href="'. admin_url($this->mod.'/read/'.$val['id']) .'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_view') .'"><i class="cificon licon-eye"></i></a>
								<button type="button" class="btn btn-xs btn-white delete_single" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_delete').'" data-pk="'. encrypt($val['id']) .'"><i class="cificon licon-trash-2"></i></button>
								</div> </div>';

						$data[] = $row;
					}

					$this->json_output(['data' => $data, 'recordsFiltered' => $this->mail_model->datatable('_inbox', 'count')]);
				}
			}

			else
			{
				$this->vars['all_message'] = $this->mail_model->all_message();
				$this->render_view('view_inbox');
			}
		}

		else
		{
			$this->render_403();
		}
	}


	public function outbox()
	{
		if ( $this->role->i('read') )
		{
			if ( $this->input->is_ajax_request() )
			{
				if ($this->input->post('act') == 'delete')
				{
					return $this->_delete();
				}
				else
				{
					$data = array();

					foreach ($this->mail_model->datatable('_outbox', 'data') as $val) 
					{
						$row = [];
						$row[] = '<div class="text-center"><input type="checkbox" class="row_data" value="'. encrypt($val['id']) .'"></div>';

						$row[] = $val['id'];

						$row[] = $val['name'].'<br><small class="text-muted">'. $val['email'] .'</small>';

						$row[] = '<a href="'. admin_url($this->mod.'/read/'.$val['id']) .'">'. $val['subject'] .' <small class="text-muted"> <br> '. cut($val['message'], 80) .'...</small></a>';

						$row[] = ci_date($val['date'], 'l, d M Y');
						
						$row[] = '<div class="text-center"><div class="btn-group">
								<a href="'. admin_url($this->mod.'/read/'.$val['id']) .'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_view') .'"><i class="cificon licon-eye"></i></a>
								<button type="button" class="btn btn-xs btn-white delete_single" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_delete').'" data-pk="'. encrypt($val['id']) .'"><i class="cificon licon-trash-2"></i></button>
								</div> </div>';

						$data[] = $row;
					}

					$this->json_output(['data' => $data, 'recordsFiltered' => $this->mail_model->datatable('_outbox', 'count')]);
				}
			}

			else
			{
				$this->render_view('view_outbox');
			}
		}

		else
		{
			$this->render_403();
		}
	}


	public function read($paramId = 0)
	{
		if ($this->role->i('read'))
		{
			$id = xss_filter($paramId,'sql');

			if ( $this->mail_model->cek_id($id) == 1 )
			{
				$data = $this->db->where('id', $id)->get('t_mail')->row_array();
				$this->vars['res_mail'] = $this->db->where('id',$id)->get('t_mail')->row_array();
				$this->render_view('view_read', $this->vars);
				$this->mail_model->update($id, array('active' => 'Y'));
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


	public function write()
	{
		if ( $this->role->i('write') )
		{
			if ( $this->input->method() == 'post' )  // submit send
			{
				$this->form_validation->set_rules(array(
					array(
						'field' => 'mail',
						'label' => lang_line('_to'),
						'rules' => 'trim|required|valid_email',
					),
					array(
						'field' => 'subject',
						'label' => lang_line('_subject'),
						'rules' => 'trim|required',
					),
					array(
						'field' => 'message',
						'label' => lang_line('_message'),
						'rules' => 'required',
					),
				));

				if ( $this->form_validation->run() )
				{
					$mail    = $this->input->post('mail');
					$subject = $this->input->post('subject',TRUE);
					$message = $this->input->post('message');
					$name    = explode('@', $mail)[0];

					$this->mail_model->insert(array(
						'name'    => $name,
						'email'   => $mail,
						'subject' => $subject,
						'message' => htmlspecialchars($message),
						'active'  => 'Y',
						'box'     => 'out'
					));

					$this->load->library('email');
					$this->email->initialize(mail_config());
					$this->email->from(
					                   get_setting('web_email'),
					                   get_setting('web_name')
					                   );
					$this->email->to($mail);
					$this->email->subject($subject);
					$this->email->message('<html><body>'. $message .'</body></html>');
					$this->email->send();

					redirect(admin_url($this->mod.'/outbox'));
				} 
				else
				{
					$this->cifire_alert->set($this->mod,'danger', validation_errors());
					redirect(uri_string());
				}
			}

			else
			{
				$this->render_view('view_write');
			}
		}
		else
		{
			$this->render_403();
		}
	}


	public function reply($id = '')
	{
		if ( $this->role->i('read') && $this->role->i('write') )
		{
			$id = xss_filter($id, 'sql');

			if ( $this->mail_model->cek_id($id) == 1 )
			{
				$this->vars['res_mail'] = $this->mail_model->get_mail($id);

				if ( $this->input->method() == 'post' ) 
				{
					$this->form_validation->set_rules(array(
						array(
							'field' => 'subject',
							'label' => lang_line('_subject'),
							'rules' => 'trim|required',
						),
						array(
							'field' => 'message',
							'label' => lang_line('_message'),
							'rules' => 'trim|required',
						),
					));

					if ( $this->form_validation->run() )
					{
						$subject = $this->input->post('subject',TRUE);
						$message = $this->input->post('message');

						$this->mail_model->insert(array(
							'name'    => $this->vars['res_mail']['name'],
							'email'   => $this->vars['res_mail']['email'],
							'subject' => $subject,
							'message' => htmlspecialchars($message),
							'active'  => 'Y',
							'box'     => 'out'
						));

						$this->load->library('email');
						$this->email->initialize(mail_config());
						$this->email->from(get_setting('web_email'), get_setting('web_name'));
						$this->email->to($this->vars['res_mail']['email']);
						$this->email->subject($subject);
						$this->email->message('<html><body>'.$message.'</body></html>');
						$this->email->send();

						redirect(admin_url($this->mod));
					} 
					else
					{
						$this->cifire_alert->set($this->mod,'danger',validation_errors());
						redirect(uri_string());
					}
				}

				else
				{
					
					$this->render_view('view_reply');
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
				$this->mail_model->delete($pk);
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
} // End Class.