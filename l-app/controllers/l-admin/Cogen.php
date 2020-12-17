<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cogen extends Backend_Controller {
	
	public $mod = 'cogen';
	protected $_path = [];

	public function __construct() 
	{
		parent::__construct();

		$this->lang->load('mod/'.$this->mod);
		$this->load->helper('cogen');

		$this->_path = array(
			'controllers' => APPPATH."controllers/".FADMIN."/",
			'models'      => APPPATH."models/mod/",
			'views'       => VIEWPATH."mod/",
			'modjs'       => CONTENTPATH."modjs/",
			'temp'        => CONTENTPATH."temp/"
		);

		$this->meta_title('CoGen');
	}

	
	public function index()
	{
		if ( 
		    $this->role->i('read') && 
		    $this->role->i('write') && 
		    $this->role->i('modify') && 
		    $this->role->i('delete')
		    )
		{
			if ($this->input->is_ajax_request()) 
			{
				$this->_submit();
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


	private function _submit()
	{
		// component
		$component_name = $_POST['general']['component_name'];
		// $component_type = $_POST['general']['component_type'];
		$cname          = trim($_POST['general']['class_name'],'_');
		$class_name     = ucfirst($cname);
		// $c_mod          = $_POST['general']['class_name'];

		// File controller
		$file_controller = "$class_name.php";
		
		// File Model
		$file_model = $class_name."_model.php";

		// views
		$view_dirname = seotitle($cname);
		$file_index   = "view_index.php";
		$file_add     = "view_add.php";
		$file_edit    = "view_edit.php";
		$file_html    = "index.html";
		$file_modjs   = seotitle($cname).".js";

		// Temp path
		$path_temp = $this->_path['temp'].$class_name.'/';
		
		// dir path
		$path_controllers = $this->_path['controllers'];
		$path_models      = $this->_path['models'];
		$path_views       = $this->_path['views'].$view_dirname;
		$path_modjs       = $this->_path['modjs'];

		// db table
		$table_name = $_POST['table_name'];

		// cek application
		$cek_controller_file = file_exists($path_controllers . $file_controller);
		$cek_model_file      = file_exists($path_models . $file_model);
		$cek_views_dir       = file_exists($path_views);
		$cek_modjs_file      = file_exists($path_modjs . $file_modjs);

		// cek component db
		$cek_mod_db   = $this->db->where('class', $class_name)->get('t_component')->num_rows();
		$cek_db_table = $this->db->table_exists($table_name);
		$count_field  = ( !empty($_POST['field']) ? count($_POST['field']) : 0 ) ;

		// cek fields.
		if ( $count_field < 3 )
		{
			$response['success'] = false;
			$response['alert']['type']    = 'error';
			$response['alert']['content'] = lang_line('mod_db_error1');
			$this->json_output($response);
		}

		// component not exists (OK)
		elseif (
		         $cek_mod_db == 0 && 
		    	 $cek_controller_file == FALSE && 
		    	 $cek_model_file == FALSE && 
		    	 $cek_views_dir == FALSE &&
		    	 $cek_modjs_file == FALSE &&
		    	 $cek_db_table == FALSE
		       )
		{
			// Load dbforge.
			$this->load->dbforge();

			// create temp folder.
			@mkdir($path_temp);
			// Config Table.

			// Field 1 (auto_increment) primary key.
			$field_1_name   = $_POST['com_filed_name_1'];
			$field_1_lenght = $_POST['com_filed_lenght_1'];

			// add field primary key.
			$this->dbforge->add_field(array(
				$field_1_name => array(
					'type'           => 'INT',
					'constraint'     => $field_1_lenght,
					'unsigned'       => TRUE,
					'auto_increment' => TRUE
				)
			));

			// add another fields.
			if ( !empty($_POST['field']) ) 
			{
				foreach ( $_POST['field'] as $key => $value )
				{
					// ENUM
					if ($value['com_filed_type'] == "ENUM")
					{
						$pecah = explode(',', $value['com_filed_lenght']);
						$fl = "ENUM(";
						$mv = '';
						foreach ($pecah as $pk => $pv) 
						{
							$mv .= "'$pv',";
						}
						$fl .= rtrim($mv,',');
						$fl .= ")";
						
						$type_val = $fl;

						$this->dbforge->add_field(array(
							$value['com_filed_name'] => array(
								'type'    => $type_val,
								'default' => $value['com_filed_default'],
								// 'null' => TRUE,
						)));
					}

					// DATETIME, DATE, TIME
					elseif (
					         $value['com_filed_type'] == "DATETIME" ||
					         $value['com_filed_type'] == "DATE" || 
					         $value['com_filed_type'] == "TIME"
					        )
					{
						$this->dbforge->add_field(array(
							$value['com_filed_name'] => array(
								'type' => $value['com_filed_type']
							)
						));
					}

					// TEXT
					elseif ( $value['com_filed_type'] == "TEXT" )
					{

						$this->dbforge->add_field(array(
							$value['com_filed_name'] => array(
								'type' => $value['com_filed_type']
							)
						));
					}

					elseif ( $value['com_filed_type'] == "INT" )
					{
						if ( !empty($value['com_filed_default']) ) 
						{
							$this->dbforge->add_field(array(
								$value['com_filed_name'] => array(
									'type'       => 'INT',
									'constraint' => $value['com_filed_lenght'],
									'default'    => $value['com_filed_default'],
									'null'       => TRUE
								)
							));
						}
						else
						{
							$this->dbforge->add_field(array(
								$value['com_filed_name'] => array(
									'type'       => 'INT',
									'constraint' => $value['com_filed_lenght'],
									'null'       => TRUE
								)
							));
						}
					}

					// Dfault ( VARCHAR )
					else
					{	
						$this->dbforge->add_field(array(
							$value['com_filed_name'] => array(
								'type'       => $value['com_filed_type'],
								'constraint' => $value['com_filed_lenght'],
								'default'    => $value['com_filed_default'],
								'null'       => TRUE
							)
						));			
					}
				} // end foreach.
			} // end if.
			
			// set field_1 as primary key.
			$this->dbforge->add_key($field_1_name, TRUE);
			// table ENGINE type.
			$table_attr = array('ENGINE' => 'InnoDB');
			$this->db->db_debug = false;
			
			// Create the table.
			if ( $this->dbforge->create_table($table_name, TRUE, $table_attr) )
			{
				// insert new data to table t_component.
				$this->db->insert("t_component", array(
					'name'       => $component_name,
					'class'      => $cname,
					'table_name' => $table_name,
					'status'     => 'Y'
				));

				// create file_controller.
				write_file($path_temp . $file_controller, dump_file_controller($_POST));
				// create file_model.
				write_file($path_temp . $file_model, dump_file_model($_POST));
				// create file_index.
				write_file($path_temp . $file_index, dump_view_index($_POST));
				// create file_modjs.
				write_file($path_temp . $file_modjs, dump_file_javascript($_POST));
				// create index.html.
				write_file($path_temp . "index.html", dump_file_html());

				// cek fitur action add
				if ( !empty($_POST['conf']['action']['add']) )
				{
					// create file view_add.php
					write_file($path_temp . $file_add, dump_view_add($_POST));
				}

				// cek fitur action edit.
				if ( !empty($_POST['conf']['action']['edit']) )
				{
					write_file($path_temp . $file_edit, dump_view_edit($_POST));
				}

				// Copy file_controller from temp to application.
				r_copy($path_temp . $file_controller, $path_controllers.$file_controller);
				// Copy file_model from temp to application.
				r_copy($path_temp . $file_model, $path_models.$file_model);
				// Copy file_modjs from temp to content/modjs.
				r_copy($path_temp . $file_modjs, $path_modjs.$file_modjs);

				// crete views folder.
				@mkdir($path_views);

				// copy file_index from temp to application.
				r_copy($path_temp . $file_index, $path_views.'/'.$file_index);
				// copy file_add from temp to application.
				r_copy($path_temp . $file_add, $path_views.'/'.$file_add);
				// copy file_edit from temp to application.
				r_copy($path_temp . $file_edit, $path_views.'/'.$file_edit);
				// copy file_html from temp to application.
				r_copy($path_temp . $file_html, $path_views.'/'.$file_html);

				// delete temp data.
				delete_folder($path_temp);

				// set session citem for finish status.
				$this->session->set_flashdata('citem', seotitle($class_name));

				// insert component to mod list
				$this->db->insert('t_mod', ['mod'=>seotitle($cname)]);

				// finish.
				$response['success'] = true;
				$response['class']   = seotitle($class_name);
				$this->json_output($response);
			} // end if create_table.

			// if error create_table.
			else
			{
				$errMsg = 'Database Error .! <br>'. $this->db->error()['message'];

				$response['success'] = false;
				$response['alert']['type'] = 'error';
				$response['alert']['content'] = $errMsg;
				$this->json_output($response);
			}
		}

		else // Error. Component is exists.
		{
			$response['success'] = false;
			$response['alert']['type'] = 'error';
			$response['alert']['content'] = 'Component already exists';
			$this->json_output($response);
		}
	}



	public function finish($val = '')
	{
		if ( 
		    $this->role->i('read') && 
		    $this->role->i('write') && 
		    $this->role->i('modify') && 
		    $this->role->i('delete') 
		    )
		{
			if ( $this->session->flashdata('citem') == $val ) 
			{
				$this->vars['fitur'] = (!empty($_SESSION['_frontend']) ? $_SESSION['_frontend'] : NULL);
				$this->vars['c_link'] = $val;
				$this->render_view('success');

				$this->session->unset_userdata('citem');
				$this->session->unset_userdata('_frontend');
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



	public function add_field()
	{
		if ($this->input->is_ajax_request()) 
		{
			if (
			    $this->role->i('read') && 
		    	$this->role->i('write') && 
		    	$this->role->i('modify') && 
		    	$this->role->i('delete') 
		    )
		    {
				$id = $_POST['id'];
				echo '<tr id="def-field-'. $id .'">
						<td>
							<span id="'. $id .'" class="text-danger cursor-hand rmfield pull-right btn btn-xs"><i class="fa fa-times"></i> '. lang_line('button_delete') .'</span>
							<p class="text-success"><i class="fa fa-caret-right"></i> &nbsp; <b><small>'. lang_line('label_field') .' '. $id .'</small></b></p>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label>'. lang_line('label_field_name') .' <span class="text-danger">*</span></label>
										<input type="text" name="field['. $id .'][com_filed_name]" id="field['. $id .']" class="form-control" minlength="3" maxlength="20" required/>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>'. lang_line('label_field_type') .'</label>
										<select name="field['. $id .'][com_filed_type]" class="form-control">
											<option value="INT">INTEGER</option>
											<option value="VARCHAR">VARCHAR</option>
											<option value="TEXT">TEXT</option>
											<option value="DATE">DATE</option>
											<option value="TIME">TIME</option>
											<option value="DATETIME">DATETIME</option>
											<option value="ENUM">ENUM</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>'. lang_line('label_field_Length_values') .'</label>
										<input type="text" name="field['. $id .'][com_filed_lenght]" value="100" class="form-control"  required/>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>'. lang_line('label_field_default_values') .'</label>
										<input type="text" name="field['. $id .'][com_filed_default]" class="form-control">
									</div>
								</div>
							</div>
						</td>
					</tr>';
			}
		}
		else
		{
			show_403();
		}
	}


	public function add_option()
	{
		if ($this->input->is_ajax_request()) 
		{
			if (
			    $this->role->i('read') && 
				$this->role->i('write') && 
				$this->role->i('modify') && 
				$this->role->i('delete')
				) 
			{
				$id = $_POST['id'];
				echo '<div id="def-option'. $id .'">
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<span class="input-group-text">'. lang_line('label_conf_option') .'</span>
							</div>
							<input type="text" name="conf[field_select_option]['. $id .']" class="form-control" placeholder="Option '. $id .'">
							<div class="input-group-append">
								<span id="'. $id .'" class="btn btn-default rmoption"><i class="icon-cross3"></i></span>
							</div>
						</div>
					</div>';
			}
		}

		else
		{
			show_403();
		}
	}	


	public function add_column()
	{
		if ($this->input->is_ajax_request()) 
		{
			if (
			    $this->role->i('read') && 
		    	$this->role->i('write') && 
		    	$this->role->i('modify') && 
		    	$this->role->i('delete') 
		    	)
			{
				$id = $_POST['id'];
				echo '<div id="def-column-'. $id .'">
						<span id="'. $id .'" class="text-danger cursor-hand pull-right btn btn-xs rmcol"><i class="fa fa-times"></i> '. lang_line('button_delete') .'</span>
						<p class="text-success"><i class="fa fa-caret-right"></i> &nbsp; <span class="text-b text-sm">'. lang_line('label_conf_column') .' '. $id .'</span></p>
						<div class="bordered">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>'. lang_line('label_conf_column_name') .'</label>
										<input type="text" name="col['. $id .'][col_name]" class="form-control" minlength="2" required />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>'. lang_line('label_conf_field_data') .'</label>
										<input type="text" name="col['. $id .'][col_field]" class="form-control" minlength="2" required />
									</div>
								</div>
							</div>
						</div>
					</div>';
			}
		}

		else
		{
			$this->render_403();
		}
	}
} // End Class.