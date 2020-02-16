<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Theme extends Backend_Controller {

	public $mod = 'theme';

	public function __construct() 
	{
		parent::__construct();
		
		$this->lang->load('mod/'.$this->mod);
		$this->meta_title(lang_line('mod_title'));
		$this->load->model('mod/theme_model');
	}


	public function index()
	{
		if ( $this->role->i('read') ) 
		{
			if ($this->input->is_ajax_request())
			{
				if ( $this->_act == 'active-theme')
				{
					if ($this->role->i('modify'))
					{
						$id_theme = $this->input->post('pk');
						$cek = $this->theme_model->cek_id($id_theme);
						
						if ($cek==1)
						{
							$this->theme_model->active_theme($id_theme);
							$response['success'] = true;
						} 
						else
						{
							$response['success'] = false;
						}

						$this->json_output($response);
					}
					else
					{
						$response['success'] = false;
						$this->json_output($response);
					}
				}
			}
			else
			{
				if ( $this->_act == 'blank_theme')
				{
					return $this->_create_blank_theme();
				}
				
				else
				{
					$this->vars['all_themes'] = $this->theme_model->all_themes();
					$this->render_view('view_index');
				}
			}
		}

		else
		{
			$this->render_403();
		}
	}


	private function _create_blank_theme()
	{
		if (
		     $this->role->i('read') && 
		     $this->role->i('write') &&
		     $this->role->i('modify') &&
		     $this->role->i('delete')
		    )
		{
			$this->form_validation->set_rules(array(
				array(
					'field' => 'title',
					'label' => lang_line('form_label_title'),
					'rules' => 'required|trim|min_length[3]|max_length[50]'
				)
			));

			if ( $this->form_validation->run() )
			{
				$title = $this->input->post('title');
				$folder = seotitle($title).'-'.md5(encrypt(1));
				$zip_path = VIEWPATH."mod/$this->mod/blanktheme.zip";
				$destination_path = VIEWPATH."themes/$folder";

				$this->load->library('unzip', array($zip_path));
				$this->unzip->extract($destination_path);

				// create theme foler asset.
				@mkdir(CONTENTPATH.'themes/'.$folder, 0777, true);
				@fopen(CONTENTPATH."themes/$folder/index.html", "w");
				
				$this->theme_model->insert(array(
					'title'  => $title,
					'folder' => $folder
				));

				$this->cifire_alert->set($this->mod, 'info', lang_line('message_add_success'));
				redirect(uri_string());
			}
			else
			{
				$this->cifire_alert->set($this->mod, 'danger', validation_errors());
				redirect(uri_string());
			}
		}
		else
		{
			$this->render_403();
		}
	}


	public function delete_theme()
	{
		if ( $this->input->is_ajax_request() && $this->role->i('delete') ) 
		{
			$data = $this->input->post('data');
			$idTheme = xss_filter(decrypt($data['id']),'sql');
			$folderTheme = xss_filter(decrypt($data['folder']), 'xss');
			
			if ( $this->theme_model->delete($idTheme) )
			{
				@delete_folder(VIEWPATH.'themes/'.$folderTheme); // delete theme views dir.
			    @delete_folder(CONTENTPATH.'themes/'.$folderTheme); // delete theme asset dir.
				
				$response['success'] = true;
				$response['dataDelete'] = $idTheme;
				$response['alert']['type'] = 'success';
				$response['alert']['content'] = lang_line('message_delete_success');
			}
			else
			{
				$response['success'] = false;
				$response['dataDelete'] = '0';
				$response['alert']['type'] = 'error';
				$response['alert']['content'] = 'ERROR';
			}

			$this->json_output($response);
		}
		else
		{
			$response['success'] = false;
			$this->json_output($response);
		}
	}




	public function add()
	{
		if ( $this->role->i('write') )
		{
			if ( $this->input->method() == 'post' )
			{
				$theme_title = xss_filter($this->input->post('title'), 'xss');
				$theme_folder = seotitle($theme_title)."-".md5(date('YmdHis'));
				$cek_theme_folder = $this->theme_model->cek_theme_folder($theme_folder);

				if ( $cek_theme_folder == FALSE )
				{
					echo "Oups..! Themes is exist.";
				}

				else
				{
					$this->theme_model->insert([
 						'title'  => $theme_title,
 						'folder' => $theme_folder,
 						'active' => 'N'
					]);

					$zip_name = md5(date('Ymdhis'));

					$this->load->library('upload', array(
						'upload_path'   => CONTENTPATH."temp/",
						'allowed_types' => "zip",
						'file_name'     => $zip_name.".zip",
						'max_size'      => 1024 * 30, // 30mb
						// 'overwrite'  => TRUE
					));

					if ($this->upload->do_upload('fupload')) // upload
					{
						// Extract zip file.
						$unzip_path = CONTENTPATH."temp/$zip_name";
						$zip_file   = CONTENTPATH."temp/$zip_name.zip";

						$this->load->library('unzip', array($zip_file));
						$this->unzip->extract($unzip_path); // run extract.
						
						// Delete zip file.
						@unlink($zip_file);

						// Create views dir
						$view_destination_path = VIEWPATH."themes/$theme_folder";
						@mkdir($view_destination_path);
						
						// copy views temp to application views.
						$view_path = CONTENTPATH."temp/$zip_name/views/";
						@copy_folder($view_path,  $view_destination_path);

						// Create assets dir
						$assets_destination_path = CONTENTPATH."themes/$theme_folder";
						@mkdir($assets_destination_path);
						
						// copy assets temp to content themes.
						$assets_path = CONTENTPATH."temp/$zip_name/assets/";
						@copy_folder($assets_path,  $assets_destination_path);

						// Delete temp
						@delete_folder($unzip_path);

						$this->cifire_alert->set($this->mod,'info',lang_line('message_add_success'));
						redirect(admin_url($this->mod));
					}
					else
					{
						$error_content = $this->upload->display_errors();
						$this->cifire_alert->set($this->mod,'danger', $error_content);
						redirect(uri_string());
					}
				}
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


	public function edit($id = 0, $file = 'home')
	{
		if ( $this->role->i('modify') )
		{
			$this->vars['res_theme'] = $this->theme_model->get_theme((int)$id);

			if ( !empty($this->vars['res_theme']) ) 
			{
				$folder_edit = $this->vars['res_theme']['folder'];
				if ($this->_act == 'edit') 
				{
					$theme = $this->theme_model->get_theme($id);
					$code_content = $this->input->post('code_content');
					$data_content = str_replace("textarea_CI", "textarea", $code_content);
					$path = VIEWPATH.'themes/'.$theme['folder'].'/'.$file.'.php';

					write_file($path, $data_content);

					// alert
					$this->cifire_alert->set($this->mod,'info', "File <code>".$file.".php</code> ".lang_line('form_message_update_success'));

					redirect(uri_string());
				}

				elseif ( $this->_act == 'create_file' )
				{
					$file_name = seotitle($this->input->post('filename'));
					$path = VIEWPATH."themes/$folder_edit/$file_name.php";
					
					if (! file_exists($path)) 
					{
						write_file($path, '');
						redirect(uri_string());
					}
					else
					{
						$this->cifire_alert->set($this->mod,'danger', lang_line('file_exist'));
						redirect(uri_string());
					}
				}

				elseif ( $this->_act == 'upload_theme_assets' )
				{
					$zip_name = md5("theme-assets-".date('Ymdhis'));

					$this->load->library('upload', array(
						'upload_path'   => CONTENTPATH."temp/",
						'allowed_types' => "zip",
						'file_name'     => $zip_name,
						'max_size'      => 1024 * 20, // 20Mb
						'overwrite'     => FALSE
					));

					if ( $this->upload->do_upload('fupload') )
					{
						$zip_path = CONTENTPATH."temp/$zip_name.zip";
						$destination_path = CONTENTPATH."themes/$folder_edit/";

						$this->load->library('unzip', array($zip_path));

						if ( $this->unzip->extract($destination_path) )
						{
							@delete_folder($zip_path);

							redirect(uri_string());
						}
					}
					else
					{
						$this->upload->display_errors();
					}
				}

				else
				{
					$this->vars['file_layout'] = $file;
					$this->vars['res_theme'] = $this->theme_model->get_theme((int)$id);
					
					if (! file_exists(VIEWPATH.'themes/'.$this->vars['res_theme']['folder']."/$file.php"))
					{
						$this->render_404();
					}
					else
					{
						// load view
						$this->render_view('view_edit');	
					}
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


	public function backup()
	{
		if (
		    $this->role->i('read') && 
		    $this->role->i('write') && 
		    $this->role->i('modify') && 
		    $this->role->i('delete')
		    )
		{
			$id = $this->input->get('id',true);
			$idTheme = xss_filter(decrypt($id), 'sql');
			$query = $this->db->where('id', $idTheme)->get('t_theme');
			
			if ( $query->num_rows() == 1 )
			{
				$data = $query->row_array();
				$this->_runBackup($data);
			}
			else
			{
				$this->cifire_alert->set($this->mod, 'danger', lang_line('theme_notfound'));
				redirect(admin_url($this->mod),'location',302);
			}
		}
		else
		{
			$this->render_403();
		}
	}


	private function _runBackup($theme)
	{
		$path_views  = VIEWPATH.'themes/'.$theme['folder'];
		$path_assets = CONTENTPATH.'themes/'.$theme['folder'];
		$dirname     = 'themez'.md5($theme['folder']);
		$zip_name    = $dirname.'.zip';
		$dir_temp    = CONTENTPATH.'temp/'.$dirname;

		$this->load->library('zip');
		$this->load->helper('download');

		if (mkdir($dir_temp))
		{
			copy_folder($path_views, $dir_temp.'/views');
			copy_folder($path_assets, $dir_temp.'/assets');

			$this->zip->read_dir($dir_temp.'/views', FALSE);
			$this->zip->read_dir($dir_temp.'/assets', FALSE);
			$this->zip->compression_level = 2;
			$this->zip->archive(CONTENTPATH.'temp/'.$zip_name);
			$this->zip->clear_data();

			if (rename(CONTENTPATH.'temp/'.$zip_name, CONTENTPATH.'uploads/'.$zip_name))
			{
				@delete_folder($dir_temp);
				$session['filez'] = $zip_name;
				$this->session->set_flashdata($session);
				redirect(admin_url($this->mod.'/downloadz/?filez='.urlencode(encrypt($zip_name))),'location', 302);
			}
		}
		else
		{
			$this->cifire_alert->set($this->mod, 'danger', lang_line('clear_temp_notice'));
			redirect(admin_url($this->mod),'location',302);
		}
	}


	public function downloadz()
	{
		if (
		    $this->role->i('read') && 
		    $this->role->i('write') && 
		    $this->role->i('modify') && 
		    $this->role->i('delete')
		    )
		{
			$getFilez = $this->input->get('filez',true);
			$file = xss_filter(urldecode(decrypt($getFilez)),'xss');
			if (!empty($file) && $file == $this->CI->session->flashdata('filez'))
			{
				force_download(CONTENTPATH."uploads/$file", NULL);
			}
			else
			{
				show_error("Requested file was not found", 404, 'Not Found');
			}
		}
		else
		{
			$this->render_403();
		}
	}
} // End Class.