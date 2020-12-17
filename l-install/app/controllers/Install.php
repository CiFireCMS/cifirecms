<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Install extends CI_Controller {

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
			if ($_POST['act'] === 'start') 
			{
				$this->_view('form1');
			}

			// Step 1 
			if ($_POST['act'] === 'step1') 
			{
				$dbconfig = $this->_dbconfig($_POST);
				$db_obj = $this->load->database($dbconfig, TRUE);
				
				if (! $db_obj->conn_id)
				{
				    show_error('Unable to connect to your database server using the provided settings.',500,'A Database Error Occurred');
				}
				else
				{
					$this->load->database();
				}

				// import database.sql.
				if ($this->install_model->import_tables(FCPATH."l-install/sql/database.sql") == TRUE)
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
			if ($_POST['act'] === 'step2') 
			{
				$dbconfig = $this->_dbconfig($_POST);
				$db_obj = $this->load->database($dbconfig, TRUE);
				
				if (!$db_obj->conn_id)
				{
				    return show_error('Unable to connect to your database server using the provided settings.',500,'A Database Error Occurred');
				}
				else
				{
					$this->load->database();
					$this->db->reconnect();	
				}
				
				$key = $this->key;
				$encrypted_password = $this->encryption->encrypt($_POST['adm_pass']);
				
				$this->db->trans_off();
				$this->db->trans_begin();

				// Insert User
				$this->install_model->insert_user(array(
					'id'        => 1,
					'username'  => $_POST['adm_user'],
					'password'  => $encrypted_password,
					'email'     => $_POST['adm_email'],
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
					'value'   => $_POST['site_name'],
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'web_url',
					'value'   => $_POST['site_url'],
					'type'    => 'text',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'web_slogan',
					'value'   => $_POST['site_slogan'],
					'type'    => 'otehr',
					'content' => ''
				));
				$this->install_model->insert_setting(array(
					'groups'  => 'general',
					'options' => 'web_description',
					'value'   => $_POST['site_desc'],
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
					'value'   => $_POST['site_email'],
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
					'value'   => $_POST['timezone'],
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
				
				
				if ( ! $this->db->trans_status() ) 
				{
					$this->db->trans_rollback();
				}
				else
				{
					$this->db->trans_commit();

					$this->_create_file_config(array(
						'key'      => $this->key,
						'site_url' => $_POST['site_url'],
						'db_host'  => $_POST['db_host'],
						'db_name'  => $_POST['db_name'],
						'db_user'  => $_POST['db_user'],
						'db_pass'  => $_POST['db_pass']
					));

					$this->_create_file_dbconfig(array(
						'db_port' => $_POST['db_port'],
						'db_host' => $_POST['db_host'],
						'db_name' => $_POST['db_name'],
						'db_user' => $_POST['db_user'],
						'db_pass' => $_POST['db_pass']
					));
					
					$this->db->close();
					$this->_view('finish');
					@unlink(FCPATH."index.php");
					$this->rebuild_index();
					@delete_folder(FCPATH.'l-install');
				}
			}
		}
		else
		{
			$this->_view('welcome');
		}
	}


	public function _view($var = '')
	{
		$this->load->view('inc_head', $this->vars);
		$this->load->view($var, $this->vars);
		$this->load->view('inc_footer', $this->vars);
	}


	protected function _dbconfig($data)
	{
		define('DB_HOST', $data['db_host']);
		define('DB_NAME', $data['db_name']);
		define('DB_USER', $data['db_user']);
		define('DB_PASS', $data['db_pass']);
		define('DB_PORT', $data['db_port']);

		$config = array(
			'dsn'	   => 'mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset=utf8;',
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


	protected function _create_file_config($configs) 
	{
		$content = cfile($configs);
		$file = FCPATH."l-app/config/config.php";
		write_file($file, $content);
	}


	protected function _create_file_dbconfig($configs) 
	{
		$content = cdb($configs);
		$file = FCPATH."l-app/config/database.php";
		write_file($file, $content);
	}


	protected function rebuild_index()
	{
		$content = cindex();
		$file = FCPATH."index.php";
		write_file(FCPATH."index.php", $content);
	}
}