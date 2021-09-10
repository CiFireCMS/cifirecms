<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Install extends CI_Controller
{
	public $vars;

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->CI =& get_instance();

		$this->key = md5(date('dmYhis').random_string(16));
		$this->encryption->initialize(array(
			'key' => hex2bin($this->key)
		));
	}


	public function index()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			// start
			if ($this->input->post('act') === 'start') 
			{
				$this->_view('form1');
			}

			// Step 1
			if ($this->input->post('act') === 'step1')
			{
				$dbconfig = $this->_dbconfig($this->input->post());
				$db_obj = $this->load->database($dbconfig, true);
				
				if (! $db_obj->conn_id)
				{
					show_error('Unable to connect to your database server using the provided settings.', 500, 'A Database Error Occurred');
				}
				else
				{
					$this->load->database();
				}

				// import database.sql.
				if ($this->install_model->import_tables(FCPATH."vendor/cifirecms/installer/sql/database.sql") == true)
				{
					$this->_view('form2');
				}
				else
				{
					$this->_view('form1');
				}
				
				$this->db->close(); // close connection
			}

			// Step 2
			if ($this->input->post('act') === 'step2')
			{
				$dbconfig = $this->_dbconfig($this->input->post());
				$db_obj = $this->load->database($dbconfig, true);
				
				if (! $db_obj->conn_id)
				{
					return show_error('Unable to connect to your database server using the provided settings.', 500, 'A Database Error Occurred');
				}
				else 
				{
					$this->load->database();
					$this->db->reconnect();
				}
				
				$key = $this->key;
				$encrypted_password = $this->encryption->encrypt($this->input->post('adm_pass'));
				
				$this->db->trans_off();
				$this->db->trans_begin();

				// Insert User
				$this->install_model->insert_user(array(
					'id'        => 1,
					'username'  => $this->input->post('adm_user'),
					'password'  => $encrypted_password,
					'email'     => $this->input->post('adm_email'),
					'name'      => 'Super Admin',
					'key_group' => 'root',
					'tlpn'      => '08123456789',
					'gender'    => 'M',
					'birthday'  => date('Y-m-d'),
					'photo'     => md5(date('Ymdhis')).'.jpg',
					'about'     => 'Lorem ipsum dolor sit amet consectetur adipiscing elit fusce eget turpis pulvinar interdum tellus blandit imperdiet velit.',
					'address'   => 'Lorem ipsum dolor sit amet consectetur adipiscing elit fusce eget turpis pulvinar interdum tellus blandit imperdiet velit.',
					'active'    => 'Y'
				));
				
				// Insert Setting

				// general
				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'web_name',
					'value'   => $this->input->post('site_name'),
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'web_url',
					'value'   => $this->input->post('site_url'),
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'web_slogan',
					'value'   => $this->input->post('site_slogan'),
					'type'    => 'otehr',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'web_description',
					'value'   => $this->input->post('site_desc'),
					'type'    => 'other',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'web_keyword',
					'value'   => 'CifireCMS, CMS Codeigniter, CMS Indonesia, CMS Open Source',
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'web_author',
					'value'   => 'CiFireCMS',
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'web_email',
					'value'   => $this->input->post('site_email'),
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'telephone',
					'value'   => '0123456789',
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'fax',
					'value'   => '000-0000-0000',
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'address',
					'value'   => 'Jakarta Indonesia',
					'type'    => 'other',
					'content' => ''
				));

				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'map_latitude',
					'value'   => '123456789',
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'map_longitude',
					'value'   => '123456789',
					'type'    => 'text',
					'content' => ''
				));

				// image
				$this->install_model->insert_setting(array(
					'groups'  => 'image',
					'options' => 'favicon',
					'value'   => 'favicon.png',
					'type'    => 'file',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'image',
					'options' => 'web_logo',
					'value'   => 'logo.png',
					'type'    => 'file',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'image',
					'options' => 'web_image',
					'value'   => 'web-image.png',
					'type'    => 'file',
					'content' => ''
				));

				// local
				$this->install_model->insert_setting(array(
					'groups'  => 'local',
					'options' => 'timezone',
					'value'   => $this->input->post('timezone'),
					'type'    => 'timezone',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'local',
					'options' => 'country',
					'value'   => 'Indonesia',
					'type'    => 'text',
					'content' => ''
				));
				
				// mail
				$this->install_model->insert_setting(array(
					'groups'  => 'mail',
					'options' => 'mail_protocol',
					'value'   => 'smtp',
					'type'    => 'select',
					'content' => 'smtp|sendmail|mail'
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'mail',
					'options' => 'mail_hostname',
					'value'   => '',
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'mail',
					'options' => 'mail_username',
					'value'   => '',
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'mail',
					'options' => 'mail_password',
					'value'   => '',
					'type'   => 'password',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'mail',
					'options' => 'mail_port',
					'value'   => '465',
					'type'    => 'text',
					'content' => ''
				));
				
				// config
				$this->install_model->insert_setting(array(
					'groups'  => 'config',
					'options' => 'maintenance_mode',
					'value'   => 'N',
					'type'    => 'select',
					'content' => 'N|Y'
				));
				// $this->install_model->insert_setting(array(
				// 	'groups'  => 'config',
				// 	'options' => 'member_registration',
				// 	'value'   => 'Y',
				// 	'type'    => 'select',
				// 	'content' => 'N|Y'
				// ));
				$this->install_model->insert_setting(array(
					'groups'  => 'config',
					'options' => 'page_item',
					'value'   => '5',
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'config',
					'options' => 'slug_url',
					'value'   => 'slug/seotitle',
					'type'    => 'slug',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'config',
					'options' => 'slug_title',
					'value'   => 'detailpost',
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'config',
					'options' => 'post_comment',
					'value'   => 'Y',
					'type'    => 'select',
					'content' => 'Y|N'
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'config',
					'options' => 'recaptcha',
					'value'   => 'N',
					'type'    => 'select',
					'content' => 'Y|N'
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'config',
					'options' => 'recaptcha_site_key',
					'value'   => '6LfJzIoUAAAAAN1-sOfEpehjAE5YAwGiWXT0ydh-',
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'config',
					'options' => 'recaptcha_secret_key',
					'value'   => '6LfJzIoUAAAAAA6eXmTd7oINHnPjOQok-cIQ0rQ-',
					'type'    => 'text',
					'content' => ''
				));


				// Other
				$this->install_model->insert_setting(array(
					'groups'  => 'other',
					'options' => 'sitemap',
					'value'   => setting_value_sitemap(),
					'type'    => 'html',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'other',
					'options' => 'web_analytics',
					'value'   => 'N',
					'type'    => 'select',
					'content' => 'Y|N'
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'other',
					'options' => 'google_analytics',
					'value'   => 'your google analytics code',
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'other',
					'options' => 'web_cache',
					'value'   => 'N',
					'type'    => 'select',
					'content' => 'N|Y'
				));
				
				
				if (! $this->db->trans_status())
				{
					$this->db->trans_rollback();
				}
				else
				{
					$this->db->trans_commit();

					$this->_create_file_config(array(
						'key' => $this->key,
						'site_url' => $this->input->post('site_url'),
					));

					$this->_create_file_env(array(
						'ci_env'   => 'production',
						'site_url' => $this->input->post('site_url'),
						'db_host'  => $this->input->post('db_host'),
						'db_port'  => $this->input->post('db_port'),
						'db_name'  => $this->input->post('db_name'),
						'db_user'  => $this->input->post('db_user'),
						'db_pass'  => $this->input->post('db_pass'),
					));
					
					$this->db->close();
					$this->_view('finish');
				}
			}
		}

		else {
			$this->_view('welcome');
		}
	}


	public function _view($var = '')
	{
		$this->load->view('inc_head', $this->vars);
		$this->load->view($var, $this->vars);
		$this->load->view('inc_footer', $this->vars);
	}


	protected function _create_file_config($configs) 
	{
		$content = cfile($configs);
		$file = FCPATH.'app/config/config.php';
		write_file($file, $content);
	}


	protected function _create_file_env($configs)
	{
		$content = env($configs);
		$file = FCPATH . ".env";
		write_file($file, $content);
	}

	
	protected function _dbconfig($data)
	{
		define('DB_HOST', $data['db_host']);
		define('DB_NAME', $data['db_name']);
		define('DB_USER', $data['db_user']);
		define('DB_PASS', $data['db_pass']);
		define('DB_PORT', $data['db_port']);

		$config = array(
			'dsn'      => 'mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset=utf8;',
			'hostname' => DB_HOST,
			'username' => DB_USER,
			'password' => DB_PASS,
			'database' => DB_NAME,
			'dbdriver' => 'pdo',
			'dbprefix' => '',
			'pconnect' => FALSE,
			'db_debug' => (ENVIRONMENT !== 'development'),
			'cache_on' => FALSE,
			'cachedir' => '',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'encrypt'  => FALSE,
			'compress' => FALSE,
			'stricton' => FALSE,
			'failover' => array(),
			'save_queries' => FALSE
		);

		return $config;
	}
} // End Class.
