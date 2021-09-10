<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component extends Backend_Controller {
	
	public $mod = 'component';
	protected $_path = [];

	public function __construct() 
	{
		parent::__construct();
		
		$this->lang->load('mod/'.$this->mod);
		$this->load->model('mod/component_model');
		
		$this->_path = array(
			'controllers' => APPPATH."controllers/".FADMIN."/",
			'models'      => APPPATH."models/mod/",
			'views'       => APPPATH."views/mod/",
			'modjs'       => CONTENTPATH."modjs/",
			'temp'        => CONTENTPATH."temp/",
		);

		$this->meta_title(lang_line('mod_title'));
	}


	public function index()
	{
		if ( $this->role->i('read') )
		{
			if ( $this->input->is_ajax_request() )
			{
				if ($this->input->post('act')=='delete')
				{
					return $this->_delete();
				}
				else
				{
					$data = array();

					foreach ($this->component_model->datatable('_all_component', 'data') as $val) 
					{
						$row   = [];
						$row[] = $val['name'];
						$row[] = '<a href="'.admin_url(seotitle($val['class'])).'">'.seotitle($val['class']).'</a>';

						$row[] = '
								<div class="text-center">
									<div class="btn-group">
										<button id="backupComponent" class="btn btn-xs btn-white" onclick="location.href=\''.  admin_url($this->mod.'/backup/'.$val['id']) .'\'" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_backup') .'"><i class="cificon licon-download"></i></button>
										
										<button type="button" class="btn btn-xs btn-white delete_single" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_delete') .'" data-pk="'. encrypt($val['id']) .'"><i class="cificon licon-trash-2"></i></button>
									</div>
								</div>';

						$data[] = $row;
					}

					$this->json_output(['data' => $data, 'recordsFiltered' => $this->component_model->datatable('_all_component', 'count')]);
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


	private function _delete()
	{
		if ( $this->input->is_ajax_request() && $this->role->i('delete') )
		{
			$data = $this->input->post('data');
			$id_component = decrypt($data[0]);
			$component    = $this->component_model->get_modul($id_component);
			
			if ( $component != FALSE )
			{
				$controllers_file = $this->_path['controllers'].ucfirst($component['class']).".php";
				$models_file      = $this->_path['models'].ucfirst($component['class'])."_model.php";
				$views_dir        = $this->_path['views'].seotitle($component['class'],'-');
				$modjs            = $this->_path['modjs'].seotitle($component['class'],'-').".js";

				$modlist = seotitle($component['class'],'-');

				if ( $this->component_model->delete($id_component, $component['table_name']) )
				{
					// delete views views_dir
					if ( is_dir($views_dir) )
						delete_folder($views_dir);

					// delete views controllers_file
					if ( file_exists($controllers_file) )
						@unlink($controllers_file);

					// delete views models_file
					if ( file_exists($models_file) )
						@unlink($models_file);

					// delete views modjs
					if ( file_exists($modjs) )
						@unlink($modjs);

					// delete mod list.
					$this->db->where('mod',$modlist)->delete('t_mod');

					$response['success']          = true;
					$response['alert']['type']    = 'success';
					$response['alert']['content'] = lang_line('form_message_delete_success');
					$this->json_output($response);
				}

				else
				{
					$response['success']        = false;
					$response['alert_type']     = 'danger';
					$response['alert_messages'] = 'Unknown error';
					$this->json_output($response);
				}
			}

			else
			{
				$response['success']          = false;
				$response['alert']['type']    = 'danger';
				$response['alert']['content'] = lang_line('err_component_notfound');
				$this->json_output($response);
			}
		}
		else
		{
			$response = false;
			$this->json_output($response);
		}
	}


	public function installation()
	{
		$this->meta_title(lang_line('mod_title_installation'));

		if ( 
		    $this->role->i('read') && 
		    $this->role->i('write') && 
		    $this->role->i('modify') && 
		    $this->role->i('delete') 
		    )
		{
			if ( $this->input->method() == 'post' )
			{
				return $this->_install();
			}
			else
			{
				$this->render_view('view_installation');
			}
		}
		else
		{
			$this->render_403();
		}
	}


	private function _install()
	{
		if ( 
		    $this->role->i('read') && 
		    $this->role->i('write') && 
		    $this->role->i('modify') && 
		    $this->role->i('delete')
		    )
		{
			$sp = DIRECTORY_SEPARATOR;
			$rand = md5(date('YmdHis'));
			$package_zip = $rand.'.zip';

			// panggil library upload dan konfigurasi paket komponen (extensi *.zip).
			$this->load->library('upload', array(
				'upload_path'   => $this->_path['temp'],
				'allowed_types' => 'zip',
				'file_name'     => $package_zip,
				'overwrite'     => FALSE,
				'max_size'      => 1024 * 5 // 5Mb
			));

			// jalankan upload.
			if ( $this->upload->do_upload('file') )
			{
				$destinationdir_unzip = $this->_path['temp'].$rand;

				// panggil library unzip.
				$this->load->library('unzip', array($this->_path['temp'].$package_zip));
				$this->unzip->extract($destinationdir_unzip);
				
				// hapus file *.zip dari foder temp.
				if (file_exists($this->_path['temp'].$package_zip))
				{
					@unlink($this->_path['temp'].$package_zip);
				}

				// cek folder hasil unzip dan file config.php
				if ( 
					 file_exists($destinationdir_unzip) && 
					 file_exists($destinationdir_unzip.$sp.'config.php')
					)
				{
					$src_dir = $destinationdir_unzip;

					// include-kan file konfigurasi (config.php)
					require_once ($destinationdir_unzip.$sp.'config.php');

					// remaping configuration.
					$_config = array(
						'component_name'  => (!empty($_config['component_name'])?$_config['component_name']:''),
						'class_name'      => (!empty($_config['class_name'])?$_config['class_name']:''),
						'table_name'      => (!empty($_config['table_name'])?$_config['table_name']:''),
						'file_sql'        => (!empty($_config['file_sql'])?$_config['file_sql']:''),
						'file_controller' => (!empty($_config['file_controller'])?$_config['file_controller']:''),
						'file_model'      => (!empty($_config['file_model'])?$_config['file_model']:''),
						'dir_views'       => (!empty($_config['dir_views'])?$_config['dir_views']:''),
						'file_modjs'      => (!empty($_config['file_modjs'])?$_config['file_modjs']:''),
						'mod'             => (!empty($_config['mod'])?$_config['mod']:'')
					);

					// cek file dan folder komponen.
					$cek_controllers = file_exists($this->_path['controllers'].$_config['file_controller']);
					$cek_views = file_exists($this->_path['views'].$_config['dir_views']);

					// cek apakah komponen memiliki file model.
					if (!empty($_config['file_model'])) {
						$cek_models = file_exists($this->_path['models'].$_config['file_model']);
					} else {
						$cek_models = FALSE;
					}

					// cek apakah komponen memiliki file modjs.
					if (!empty($_config['file_modjs'])) {
						$cek_modjs = file_exists($this->_path['modjs'].$_config['file_modjs']);
					} else {
						$cek_modjs = FALSE;
					}

					// cek tabel & data t_component.
					if (!empty($_config['file_sql'])) {
						$cek_table  = $this->db->table_exists($_config['table_name']);
						$cek_mod_db = $this->db->where('class', $_config['class_name'])->get('t_component')->num_rows();
					} else {
						$cek_table  = FALSE;
						$cek_mod_db = FALSE;
					}

					
					// cek source di folder temp.
					$cek_src_controllers = file_exists($src_dir.$sp.'controllers'.$sp.$_config['file_controller']);
					$cek_src_views       = file_exists($src_dir.$sp.'views');

					if (!empty($_config['file_model'])) {
						$cek_src_models = file_exists($src_dir.$sp.'models'.$sp.$_config['file_model']);
					} else {
						$cek_src_models = TRUE;
					}

					if (!empty($_config['file_modjs'])) {
						$cek_src_modjs = file_exists($src_dir.$sp.'modjs'.$sp.$_config['file_modjs']);
					} else {
						$cek_src_modjs = TRUE;
					}
					
					if (!empty($_config['file_sql'])) {
						$cek_src_sql = file_exists($src_dir.$sp.'sql'.$sp.$_config['file_sql']);
					} else {
						$cek_src_sql = TRUE;
					}


					// jalankan cek.
					if (
						 // cek apakah komponen sudah ada di sistem.
						 $cek_controllers == FALSE && 
						 $cek_models      == FALSE && 
						 $cek_views       == FALSE && 
						 $cek_modjs       == FALSE && 
						 $cek_table       == FALSE && 
						 $cek_mod_db      == 0 &&

						 // cek apakah ada file komponen di folder temp.
						 $cek_src_controllers == TRUE && 
						 $cek_src_models      == TRUE  && 
						 $cek_src_views       == TRUE && 
						 $cek_src_modjs       == TRUE && 
						 $cek_src_sql         == TRUE
						)
					{
						// Copy controllers dari folder temp ke sistem.
						@copy_folder($src_dir.$sp.'controllers', $this->_path['controllers']);

						// Copy views dari folder temp ke sistem.
						@copy_folder($src_dir.$sp.'views', $this->_path['views'].$_config['dir_views']);

						// sebelum copy cek dulu apakah komponen memiliki file model.
						if (!empty($_config['file_model'])) {
							@copy_folder($src_dir.$sp.'models', $this->_path['models']);
						}
						
						// sebelum copy cek dulu apakah komponen memiliki file modjs.
						if (!empty($_config['file_modjs'])) {
							@copy_folder($src_dir.$sp.'modjs', $this->_path['modjs']);
						}
						

						// insert data komponen ke tabel t_component.
						$this->component_model->insert(array(
							'name'       => $_config['component_name'],
							'class'      => $_config['class_name'],
							'table_name' => $_config['table_name']
						));

						// cek mod untuk insert data list mod.
						if (!empty($_config['mod'])) {
							$this->db->insert('t_mod',['mod' => $_config['mod']]);
						}

						if (!empty($_config['file_sql']))
						{
							// import database komponen (import *.sql file).
							if ( $this->_import_sql($src_dir.$sp.'sql'.$sp.$_config['file_sql']) )
							{
								@delete_folder($destinationdir_unzip); // delete folder temp.
								$this->cifire_alert->set($this->mod, 'info', lang_line('form_message_add_success'));
								redirect(admin_url($this->mod));
							} 
							else 
							{
								@delete_folder($destinationdir_unzip); // delete folder temp.
								$this->cifire_alert->set($this->mod, 'danger', '<i class="cificon licon-alert-triangle mr-2"></i>SQL Error! Or access denied.! Check the sql file and import it again');
								redirect(admin_url($this->mod));
							}
						}
						else
						{
							@delete_folder($destinationdir_unzip); // delete folder temp.
							$this->cifire_alert->set($this->mod, 'warning', '<p class="tx-medium"><i class="cificon licon-alert-triangle mr-2"></i>WARNING.!</p>Some mod sources is corrupt.! Check your system or contact the administrator.');
							redirect(admin_url($this->mod));
						}
					}

					// Jika pengecekan gagal.
					// controllers, model, views dir, modjs, table, data t_component.
					else
					{
						@delete_folder($destinationdir_unzip); // delete folder temp.
						$this->cifire_alert->set($this->mod, 'danger', lang_line('err_install_package'));
						redirect(uri_string());
					}
				}

				// folder hasil unzip dan file config.php 'not found'
				else
				{
					@delete_folder($destinationdir_unzip); // delete folder temp.
					$this->cifire_alert->set($this->mod, 'danger', lang_line('err_config_notfound'));
					redirect(uri_string());
				}
			}

			// Jika error upload paket komponen.
			else
			{
				$error_content = $this->upload->display_errors();
				$this->cifire_alert->set($this->mod, 'danger', $error_content);
				redirect(uri_string());
			}
		}
		else
		{
			$this->render_403();
		}
	}


	private function _import_sql($file) 
	{
		$this->db->trans_off();
		$this->db->trans_start(TRUE);
		$this->db->trans_begin();
		$sql = file_get_contents($file);
		$this->db->query($sql);

		if ( $this->db->trans_status() == TRUE ) 
		{
			$this->db->trans_commit();
			return true;
		}
		else 
		{
			$this->db->trans_rollback();
			return false;
		}
	}


	public function backup($id = '')
	{
		if (
		    $this->role->i('read') && 
		    $this->role->i('write') && 
		    $this->role->i('modify') && 
		    $this->role->i('delete')
		    )
		{
			$id_component = xss_filter($id, 'sql');
			$query = $this->db->where('id', $id_component)->get('t_component');
			
			if ( $query->num_rows() == 1 )
			{
				$component = $query->row_array();
				$this->_runBackup($component);
				redirect(admin_url($this->mod));
			}
			else
			{
				$this->cifire_alert->set($this->mod, 'danger', lang_line('err_component_notfound'));
				redirect(admin_url($this->mod));
			}
		}
		else
		{
			$this->render_403();
		}
	}


	private function _runBackup($component)
	{
		$c_date       = date('Y-m-d | h:i A');
		$c_name       = $component['name'];
		$c_class      = $component['class'];
		$c_table      = $component['table_name'];
		$c_sql        = $component['table_name'].'.sql';
		$c_mod        = seotitle($c_class);
		$c_controller = ucfirst($c_class).'.php';
		$c_model      = ucfirst($c_class).'_model.php';
		$c_views      = seotitle($c_class);
		$c_modjs      = seotitle($c_class).'.js';

		$c_status = 'Y';

		$dir_name = "modz".md5($c_class);
		$path_temp_component = $this->_path['temp'].$dir_name.'/';

		// create temp folder and config file.
		if ( @mkdir($path_temp_component) ) 
		{
			@mkdir($path_temp_component . 'controllers');
			@mkdir($path_temp_component . 'models');
			@mkdir($path_temp_component . 'modjs');
			@mkdir($path_temp_component . 'views');
			@mkdir($path_temp_component . 'sql');

			// create file config.
			$config_content = "<?php\n/**\n * - Ini adalah file konfigurasi instalasi komponen CiFireCMS.\n * - Komponen   : {$c_name}\n * - Tanggal    : {$c_date}\n*/\n\n\$_config['component_name']  = '{$c_name}';\n\$_config['class_name']      = '{$c_class}';\n\$_config['table_name']      = '{$c_table}';\n\$_config['file_sql']        = '{$c_sql}';\n\$_config['file_controller'] = '{$c_controller}';\n\$_config['file_model']      = '{$c_model}';\n\$_config['dir_views']       = '{$c_views}';\n\$_config['file_modjs']      = '{$c_modjs}';\n\$_config['mod']             = '{$c_mod}';";

			// Create installation config.
			write_file($path_temp_component . 'config.php', $config_content);

			// Copy controllers.
			$path_app_controllers  = $this->_path['controllers'].$c_controller;
			$path_temp_controllers = $path_temp_component . "controllers/$c_controller";
			if ( file_exists($path_app_controllers) ) {
				r_copy($path_app_controllers, $path_temp_controllers);
			}

			// Copy models.
			$path_app_models  = $this->_path['models'].$c_model;
			$path_temp_models = $path_temp_component . "models/$c_model";
			if ( file_exists($path_app_models) ) {
				r_copy($path_app_models, $path_temp_models);
			}

			// Copy views.
			$path_app_views  = $this->_path['views'].$c_views;
			$path_temp_views = $path_temp_component.'views';
			if ( file_exists($path_app_views) ) {
				copy_folder($path_app_views, $path_temp_views);
			}

			// Copy modjs.
			$path_app_modjs   = $this->_path['modjs'].$c_modjs;
			$path_temp_modjs  = $path_temp_component."modjs/$c_modjs";
			if ( file_exists($path_app_modjs) )
				r_copy($path_app_modjs, $path_temp_modjs);

			// backup table database.
			$sql_name      = $c_table.'.sql';
			$path_temp_sql = $path_temp_component.'sql/'.$sql_name;

			$this->db = $this->load->database('mysqli', TRUE);
			$this->load->dbutil();

			$backup_database = $this->dbutil->backup(array(
				'tables'     => array($c_table), // Array of tables to backup.
				'ignore'     => array(),         // List of tables to omit from the backup
				'format'     => 'txt',           // gzip, zip, txt
				'add_drop'   => TRUE,            // Whether to add DROP TABLE statements to backup file
				'add_insert' => TRUE,            // Whether to add INSERT data to backup file
				'newline'    => "\n"             // Newline character used in backup file
			));

			write_file($path_temp_sql, $backup_database);
		}

		// archives component.
		$this->load->library('zip');
		$zip_name = $dir_name.'.zip';

		$this->zip->read_dir($path_temp_component . 'controllers', FALSE);
		$this->zip->read_dir($path_temp_component . 'models', FALSE);
		$this->zip->read_dir($path_temp_component . 'views', FALSE);
		$this->zip->read_dir($path_temp_component . 'modjs', FALSE);
		$this->zip->read_dir($path_temp_component . 'sql', FALSE);
		$this->zip->read_file($path_temp_component . 'config.php');
		$this->zip->compression_level = 9;
		$this->zip->archive($path_temp_component.$zip_name);
		$this->zip->clear_data();

		// copy backup zip to content/uploads/.
		r_copy($path_temp_component.$zip_name, CONTENTPATH.'uploads/'.$zip_name);

		// delete temp.
		@delete_folder($path_temp_component);

		// Download backup zip from content/uploads/.
		$this->load->helper('download');
		force_download(CONTENTPATH.'uploads/'.$zip_name, NULL);

		
	} 
} // End Class.