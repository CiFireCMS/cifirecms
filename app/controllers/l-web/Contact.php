<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends Web_controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('web/contact_model');
	}
	
	
	public function index()
	{
		if ( $this->input->method() == 'post' )
		{
			$this->_submit();
			redirect(selft_url());
		}
		else
		{
			$this->meta_title('Contact - '.get_setting('web_name'));
			$this->render_view('contact');
		}
	}


	private function _submit()
	{
		if ( get_setting('recaptcha') == 'Y' && googleCaptcha()->success == FALSE )
		{
			$this->cifire_alert->set('contact', 'danger', 'Please complete the captcha');
		}
		else
		{
			$this->form_validation->set_rules(array(
				array(
					'field' => 'name',
					'label' => 'Name',
					'rules' => 'required|trim|min_length[4]|max_length[150]|regex_match[/^[a-zA-Z0-9-._ ]+$/]',
				),
				array(
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'required|trim|max_length[60]|valid_email'
				),
				array(
					'field' => 'subject',
					'label' => 'Subject',
					'rules' => 'required|trim|min_length[4]|max_length[300]|regex_match[/^[a-zA-Z0-9-.,_ ]+$/]'
				),
				array(
				    'field' => 'message',
					'label' => 'Message',
					'rules' => 'required|trim'
				),
			));

			if ( $this->form_validation->run() ) 
			{
				$data_contact = array(
					'name'    => xss_filter($this->input->post('name',TRUE), 'xss'),
					'email'   => $this->input->post('email', TRUE),
					'subject' => xss_filter($this->input->post('subject',TRUE), 'xss'),
					'message' => xss_filter($this->input->post('message'), 'xss'),
					'ip'      => $this->CI->input->ip_address(),
					'box'     => 'in',
					'active'  => 'N'
				);

				$this->contact_model->insert($data_contact);

				$this->load->library('email');

				$website_name   = get_setting('web_name');
				$website_email  = get_setting('web_email');

				$this->email->initialize(mail_config());
				$this->email->from($website_email, $website_name);
				$this->email->to($data_contact['email']);
				$this->email->subject('Thanks to contact our website');
				$this->email->message('<html><body>
								Hi <b>'. $data_contact['name'] .'</b>,<br /><br />
								Thanks to contact our website.<br /><br />
								Warm regards,<br />
								<a href="'. site_url() .'" target="_blank" title="'. $website_name .'">'. $website_name .'</a>
							</body></html>');

				if ($this->email->send())
				{
					$this->cifire_alert->set('contact', 'success', 'Success');
				}
				else
				{
					$this->cifire_alert->set('contact', 'warning', 'Success');
				}
			}
			else
			{
				$this->cifire_alert->set('contact', 'danger', validation_errors());
			}
		}
	}
} // End class.