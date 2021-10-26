<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {
	
	public $mod = 'login';

	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->lang->load('general/auth_lang');
		$this->load->model('auth/admin_auth_model', 'auth_model');
		$this->form_validation->set_error_delimiters('<div class="mg-b-0 text-left">', '</div>');
		$this->vars['input_uname'] = encrypt('username');
		$this->vars['input_pwd']   = encrypt('password');
	}


	public function index()
	{
		$this->meta_title(lang_line('signin_title'));

		if ( login_status() == TRUE )
		{
			redirect(admin_url('dashboard'),'location',302);
		}
		else
		{
			if ( $this->input->is_ajax_request() )
			{
				$this->form_validation->set_rules(array(
					array(
						'field' => 'data',
						'label' => lang_line('username'),
						'rules' => 'required|trim|min_length[4]|max_length[20]|regex_match[/^[a-z0-9_.]+$/]'
					)
				));

				if ( $this->form_validation->run() )
				{
					$username = xss_filter($this->input->post('data'), 'xss');
					$response['status'] =  $this->auth_model->cek_user(encrypt($username));
					$response['html'] =  '<div class="form-group">
												<label>'. lang_line('password') .'</label>
												<input type="password" name="'. $this->vars['input_pwd'] .'" id="password" class="form-control" required autofocus/>
											</div>
											<button type="submit" class="btn btn-brand btn-block mg-t-20">'. lang_line('button_login') .'</button>
											<div class="text-center mg-t-20">
												<a href="'. admin_url('forgot') .'">'. lang_line('forgot_password') .'?</a>
											</div>';
				}
				else
				{
					$response = 0;
				}
				$this->json_output($response);
			}

			if ( $this->input->method() == 'post' )
			{
				return $this->_submit_login();
			}

			else
			{
				$this->vars['script'] = '<script type="text/javascript">$("input:not(textarea)").keydown(function(t){if(13==(t.witch||t.keyCode))return t.preventDefault(),!1}),$(".input-username").on("input",function(){var t=$(this),n=$(this).val();n.length>=20&&t.val(function(){return console.log(n.substr(0,20)),n.substr(0,20)}),$.ajax({type:"POST",data:{data:n,csrf_name:csrfToken},dataType:"json",cache:!1,success:function(t){"1"==t.status?($(".input-password").html(t.html),$("#password").focus()):$(".input-password").html("")}})});</script>';

				$this->load->view('backend/auth_header', $this->vars);
				$this->load->view('backend/auth_login', $this->vars);
				$this->load->view('backend/auth_footer', $this->vars);
			}
		}
	}


	private function _submit_login($name = NULL, $value = NULL)
	{
		foreach ($this->input->post() as $key => $val)
		{
			$name .= $key.',';
			$value .= $val.',';
		}

		$input_name = explode(',', $name);
		$input_value = explode(',', $value);

		if (
		    decrypt($input_name[0]) == decrypt($this->vars['input_uname']) && 
		    decrypt($input_name[1]) == decrypt($this->vars['input_pwd'])
		    )
		{
			$this->form_validation->set_rules(array(
				array(
					'field' => $input_name[0],
					'label' => lang_line('username'),
					'rules' => 'required|trim|min_length[4]|max_length[20]|regex_match[/^[a-z0-9_.]+$/]'
				),
				array(
					'field' => $input_name[1],
					'label' => lang_line('password'),
					'rules' => 'required|min_length[6]|max_length[20]'
				)
			));

			if ( $this->form_validation->run() ) 
			{
				$data_input = array(
					'username' => $this->input->post($input_name[0]),
					'password' => encrypt($this->input->post($input_name[1]))
				);

				$cek_data_input = $this->auth_model->cek_login($data_input);

				if ( $cek_data_input == TRUE )
				{
					$get_user = $this->auth_model->get_user($data_input);

					$this->session->set_userdata('_CiFireLogin', TRUE);
					$this->session->set_userdata('key_id', encrypt($get_user['id']));
					$this->session->set_userdata('key_group', encrypt($get_user['key_group']));
					$this->session->set_userdata('filemanager_access', array(
						'user_group' => $get_user['key_group'],
						'read_access' => $this->role->access('filemanager','read_access'),
						'write_access' => $this->role->access('filemanager','write_access'),
						'modify_access' => $this->role->access('filemanager','modify_access'),
						'delete_access' => $this->role->access('filemanager','wdeleteaccess')
					));
					if ($this->role->access('filemanager','read_access')==true)
					{
						$this->session->set_userdata('FM_KEY', md5($get_user['key_group'].date('Ymdhis')));
					}

					redirect(admin_url('dashboard'),'location',302);
				}

				else
				{
					$this->cifire_alert->set('login', 'warning', lang_line('message_login_error'));
					redirect(uri_string(),'location',302);
				}
			}
			
			else
			{
				$this->cifire_alert->set('login', 'warning', validation_errors());
				redirect(uri_string(),'location',302);
			}
		}
		
		else
		{
			show_400();
		}
	}


	private function _cek_username($username = '') 
	{
		$cek_username = $this->auth_model->cek_username($username);

		if ($cek_username == '0') 
		{
			$this->form_validation->set_message('_cek_username', '{field} error.');
			return FALSE;
		}
		if ($cek_username == '1')
		{
			return TRUE;
		}
	}


	public function forgot()
	{
		$this->meta_title(lang_line('forgot_title'));

		if ( login_status() == TRUE )
		{
			redirect(admin_url('dashboard'),'location',302);
		}
		else
		{		
			if ( $this->input->method() == 'post' )
			{
				$this->form_validation->set_rules(array(
					array(
					'field' => 'email',
					'label' => lang_line('login_email'),
					'rules' => 'required|trim|min_length[4]|max_length[80]|valid_email',
					)
				));

				if ( $this->form_validation->run() )
				{
					$user_email = $this->input->post('email', TRUE);
					$query = $this->db
						->select('name,email,password')
						->where("BINARY email='$user_email'", NULL, FALSE)
						->where('active', 'Y')
						->get('t_user');

					if ( $query->num_rows() == 1 )
					{
						$data = $query->row_array();

						$password      = decrypt($data['password']);
						$full_name     = $data['name'];
						$website_name  = get_setting('web_name');
						$website_email = get_setting('web_email');

						// Send activation link to email.
						$this->load->library('email');
						$this->email->initialize(mail_config());
						$this->email->from($website_email, $website_name);
						$this->email->to($user_email);
						$this->email->subject('Forgot Password');
						// $this->email->set_newline("\r\n");

						$this->email->message('<html><body>
								Hi <b>'. $full_name .'</b>,<br /><br />
								If you have never requested message information about forgotten password in <a href="'. site_url() .'" target="_blank" title="'. $website_name .'">'. $website_name .'</a>, please to ignore this email.<br /><br />
								But if you really are asking for messages of this information, please to log in with the password and then change the default password to a more secure password.<br /><br />
								-------------------------------------------------------<br />
								Your password : '.$password.'<br />
								-------------------------------------------------------<br /><br />
								Warm regards,<br />
								<a href="'. site_url() .'" target="_blank" title="'. $website_name .'">'. $website_name .'</a>
							</body></html>');

						$this->email->send();

						// set allert and redirect;
						$this->cifire_alert->set('forgot', 'info', lang_line('message_forgot_success'));
						// redirect(uri_string());
					} 
					else
					{
						$this->cifire_alert->set('forgot', 'warning', lang_line('message_forgot_error'));
					}
					
					redirect(uri_string(),'location',302);
				} 
				else
				{
					$error_content = validation_errors();
					$this->cifire_alert->set('forgot', 'danger', $error_content);
					redirect(uri_string(),'location',302);
				}
			} 
			else 
			{
				$this->load->view('backend/auth_header', $this->vars);
				$this->load->view('backend/auth_forgot', $this->vars);
				$this->load->view('backend/auth_footer', $this->vars);
			}
		}
	}


	public function logout()
	{
		$this->session->sess_destroy();
		// $this->session->unset_userdata('log_admin');
		// $this->session->unset_userdata('filemanager');
		redirect(admin_url(),'location',302);
	}
} // End Class.