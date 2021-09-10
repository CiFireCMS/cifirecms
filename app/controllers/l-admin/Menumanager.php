<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menumanager extends Backend_Controller {
	
	public $mod = 'menumanager';
	public $dirout = 'mod/menumanager/';
	public $act;

	public function __construct() 
	{
		parent::__construct();
		$this->lang->load('mod/'.$this->mod);
		$this->meta_title(lang_line('mod_title'));
	}

	public function index() 
	{
		if ( 
		    $this->role->i('read') || 
		    $this->role->i('write') ||
		    $this->role->i('modify') ||
		    $this->role->i('delete')
		    )
		{
			$grids = xss_filter($this->input->get('group_id'),'sql');

			if ( !empty($grids) )
				$_get_group_id = $grids;
			else 
				$_get_group_id = 1;

			$group_id = 1;

			if (isset($_get_group_id)) 
			{
				$this->vars['group_id'] = (int)$_get_group_id;
				$$group_id = (int)$_get_group_id;
			}

			$cari_id = 1;

			$grids = xss_filter($this->input->get('group_id'), 'sql');

			if ( !empty($grids) )
				$group_ids = $grids;
			else 
				$group_ids = 1;

			$menu = $this->db
				->where('group_id',$group_ids)
				->order_by('position','ASC')
				->get('t_menu')
				->result_array();
			
			$this->vars['menu_ul'] = '<ul id="easymm"></ul>';

			foreach ($menu as $row) 
			{
				$this->_add_row(
					$row['id'],
					$row['parent_id'],
					' id="menu-'.$row['id'].'" class="sortable"',
					$this->_get_label($row)
				);
			}

			$this->vars['menu_ul']     = $this->_generate_list('id="easymm"');
			$this->vars['group_id']    = $group_id;
			$this->vars['group_title'] = $this->_get_menu_group_title($group_id);
			$this->vars['menu_groups'] = $this->_get_menu_groups($group_id);
			
			$this->render_view('view_menumanager');
		}
		else
		{
			$this->render_403();
		}
	}


	private function _add_row($id, $parent, $li_attr, $label) 
	{
		$this->vars[$parent][] = array('id' => $id, 'li_attr' => $li_attr, 'label' => $label);
	}


	private function _generate_list($attr = '', $attrss = '') 
	{
		return $this->_ul(0, $attr, $attrss);
	}
	

	private function _ul($parent = 0, $attr = '', $attrss = '') 
	{
		static $i = 1;

		$indent = str_repeat("\t\t", $i);

		if (isset($this->vars[$parent])) 
		{
			if ( $attr )
				$attr = ' ' . $attr;

			if ( $attrss )
				$attrss = ' ' . $attrss;

			$html = "\n$indent";
			$html .= "<ul $attr>";
			$i++;

			foreach ($this->vars[$parent] as $row) 
			{
				$child = $this->_ul($row['id'], $attrss);
				$html .= "\n\t$indent";
				$html .= '<li'. $row['li_attr'] . '>';
				$html .= $row['label'];

				if ( $child )
				{
					$i--;
					$html .= $child;
					$html .= "\n\t$indent";
				}

				$html .= '</li>';
			}
			$html .= "\n$indent</ul>";

			return $html;
		} 
		else 
		{
			return FALSE;
		}
	}


	public function add_menu_group()
	{
		if ($this->input->is_ajax_request() && $this->input->post('act') == 'add')
		{
			if ($this->role->i('write'))
			{
				$post_title = trim(xss_filter($this->input->post('title',TRUE),'xss'));
				if (!empty($post_title)) 
				{
					if ($this->db->insert('t_menu_group', array('title' => $post_title))) 
					{
						$id_self = $this->db->insert_id();
						
						$this->db->insert('t_menu', array(
							'title'    => 'Menu Title',
							'url'      => '#',
							'class'    => '',
							'group_id' => $id_self,
							'position' => '1',
							'active'   => 'N',
						));
						
						$response['status'] = 1;
						$response['id'] = $id_self;
					} 
					else 
					{
						$response['status'] = 2;
						$response['msg'] = 'Add menu group error.';
					}
				} 
				else 
				{
					$response['status'] = 3;
				}

				$this->json_output($response);
			}
			else
			{
				$response = 'Acces denied';
				$this->json_output($response, 403);
			}
		}
		else
		{
			if ($this->role->i('write'))
			{
				$this->load->view($this->dirout.'templates/menu_group_add',$this->vars);
			}
			else
			{
				$response = 'Acces denied';
				$this->json_output($response, 403);
			}
		}
	}


	public function edit_menu_group() 
	{
		if ( $this->input->is_ajax_request() )
		{
			if ($this->role->i('modify'))
			{
				$input_title = trim($this->input->post('title',TRUE));
				$post_title = xss_filter($input_title,'xss');

				if ( !empty($post_title) )
				{
					$dataTitle = trim($post_title);
					$idGroup = xss_filter($this->input->post('id'),'sql');
					$cekIdGroup = $this->db->where('id',$idGroup)->get('t_menu_group')->num_rows();
					
					if ($cekIdGroup == 1)
					{
						$this->db->where('id',$idGroup)
						         ->update('t_menu_group', array('title' => $dataTitle));
						$response['success'] = true;
						$this->json_output($response);
					}
					else
					{
						$response['success'] = false;
						$this->json_output($response, 404);
					}
				}
				else
				{
					$response['success'] = false;
					$this->json_output($response, 404);
				}
			}
			else
			{
				$response['success'] = false;
				$this->json_output($response, 403);
			}
		}
		else
		{
			show_403();
		}
	}


	public function delete_menu_group() 
	{
		if ($this->input->is_ajax_request())
		{
			if ($this->role->i('delete'))
			{
				$ID = xss_filter($this->input->post('id'),'sql');
				$cek = $this->db->select('id')->where('id',$ID)->get('t_menu_group')->num_rows();

				if ( !empty($ID) && $cek==1 && $ID>1 ) 
				{
					$id = $ID;
					
					if ( $ID == 1 ) 
					{
						$response['success'] = false;
						$response['msg'] = 'Cannot delete Group ID = 1';
						$this->json_output($response);
					} 
					else 
					{
						$del_group = $this->db->where('id', $ID)->delete('t_menu_group');
						
						if ( $del_group ) 
						{
							$this->db->where('group_id', $ID)->delete('t_menu');
						}

						$response['success'] = true;
						$this->json_output($response);
					}
				}
				else
				{
					$response['success'] = false;
					$response['msg'] = 'Group menu not found';
					$this->json_output($response);
				}
			}
			else
			{
				$response['success'] = false;
				$response['msg'] = 'Access denied';
				$this->json_output($response,403);
			}
		}
		else
		{
			show_403();
		}
	}


	public function add_single_menu() 
	{
		if ( $this->input->is_ajax_request() )
		{
			if ($this->role->i('write'))
			{
				$title = xss_filter($this->input->post('title'),'xss');
				$title = trim($title);
				
				if (!empty($title))
				{
					$gid = xss_filter($this->input->post('gid',TRUE),'sql');
					$query_lp = $this->db
								->select_max('position')
								->where('group_id', 0)
								->get('t_menu')
								->row_array();

					$postition = empty($query_lp['position']) ? 1 : $query_lp['position'] + 1;
					$data_menu = array(
						'title'    => $title,
						'url'      => xss_filter($this->input->post('url')),
						'class'    => stripcslashes(htmlspecialchars($this->input->post('class') ,ENT_QUOTES)),
						'active'   => ($this->input->post('active')=='Y'?'Y':'N'),
						'group_id' => $gid,
						'position' => $postition
					);
					$this->db->insert('t_menu', $data_menu);

					$li_id = 'menu-'.$this->db->insert_id();
					
					$data['id']     = $this->db->insert_id();
					$data['title']  = $data_menu['title'];
					$data['url']    = $data_menu['url'];
					$data['class']  = $data_menu['class'];
					$data['active'] = $data_menu['active'];

					$response['li']     = '<li id="'.$li_id.'" class="sortable">'.$this->_get_label($data).'</li>';
					$response['li_id']  = $li_id;
					$response['gid']    = $gid;

					$response['status'] = 1;
					$this->json_output($response);
				}
				else
				{
					$response['status'] = 2;
					$response['msg']    = 'Title is required';
					$this->json_output($response);
				}
			}
			else
			{
				$response['status'] = 2;
				$response['msg']    = 'Acces denied';
				$this->json_output($response);
			}
		}
		else
		{
			show_403();
		}
	}


	function edit_single_menu($paramID=0) 
	{
		if ($this->input->is_ajax_request())
		{
			if ($this->role->i('modify'))
			{
				if ( $this->input->post('acc') == 'editsinglemenu' ) 
				{
					$menuID = xss_filter($this->input->post('menu_id'),'sql');
					$cekID = $this->db->select('id')->where('id',$menuID)->get('t_menu')->num_rows();
					
					if ($cekID==1)
					{
						$data = array(
							'title'  => xss_filter($this->input->post('title'),'xss'),
							'url'    => xss_filter($this->input->post('url')),
							'class'  => stripcslashes(htmlspecialchars($this->input->post('class') ,ENT_QUOTES)),
							'active' => ($this->input->post('active')=='Y'?'Y':'N')
						);
						
						$this->db->where('id', $menuID)->update('t_menu', $data); // update

						$response['menu'] = $data;
						$response['status'] = 1;
						$response['gid'] = $this->input->post('g_id');
						
						$this->output
							->set_content_type('application/json', 'utf-8')
							->set_output(json_encode($response, JSON_PRETTY_PRINT))
							->_display();
						exit;
					}
					else
					{
						$response = "ERROR";
						$this->json_output($response,404);
					}
				}

				else
				{
					$idMenu = xss_filter($paramID,'sql');
					$cekID = $this->db->select('id')->where('id',$paramID)->get('t_menu')->num_rows();
					if ($cekID==1)
					{
						$data_menu= $this->db
							->where('id',$idMenu)
							->get('t_menu')
							->row();

						$response['status'] = true;

						$this->json_output($this->_formMenuEdit($data_menu));
					}
					else
					{
						$response['status'] = false;
						$response['msg'] = 'Menu not found';
						$this->json_output($response);
					}
				}
			}
			else
			{

			}
		}
		else
		{
			show_404();
		}
	}
	

	private function _formMenuEdit($data)
	{
		$html = '<h2>Edit Menu</h2>';
		$html .= form_open(admin_url('menumanager/editsinglemenu'), 'id="formeditmenu" autocomplete="off"');
		$html .= '<input type="hidden" name="acc" value="editsinglemenu" />
				<input type="hidden" name="menu_id" value="'.$data->id.'" />
				<input type="hidden" name="g_id" value="'.$data->group_id.'" />
				<div class="mb-3">
					<label>Title</label>
					<input type="text" name="title" value="'.$data->title.'" class="form-control"/>
				</div>
				<div class="mb-3">
					<label>URL</label>
					<input type="text" name="url" value="'.$data->url.'" class="form-control"/>
				</div>
				<div class="mb-3">
					<label>Class</label>
					<input type="text" name="class" value="'.$data->class.'" class="form-control"/>
				</div>
				<div class="mb-1">
					<label>Active</label>
					<select name="active" class="form-control" style="width:50px;">
						<option value="'.$data->active.'" style="display: none;">'.$data->active.'</option>
						<option value="Y">Y</option>
						<option value="N">N</option>
					</select>
				</div>';
		$html .= form_close();

		return $html;
	}


	public function delete_single_menu() 
	{
		if ( $this->input->is_ajax_request() )
		{
			if ($this->role->i('delete'))
			{
				$id = xss_filter($this->input->post('id'),'sql');
				$del_menu = $this->db->where('id', $id)->delete('t_menu');
				
				$response['success'] = false;

				if ( $del_menu ) 
				{
					$this->_del_childs($id);
					$response['success'] = true;
				}
				
				$this->json_output($response);
			}
			else
			{
				$response['success'] = false;
				$this->json_output($response);
			}
		}
		else
		{
			show_403();
		}
	}
	

	private function _del_childs($id)
	{
		$ids = $this->db->where('parent_id', $id)->get('t_menu')->result_array();
		foreach ($ids as $key) 
		{
			$this->db->where('id', $key['id'])->delete('t_menu');
			$this->_del_childs($key['id']);
		}
	}


	public function savemenuposition() 
	{
		if ($this->input->is_ajax_request())
		{
			if ($this->role->i('write') && $this->role->i('modify'))
			{
				$easymm = $this->input->post('easymm');
				$this->_update_menu_position(0, $easymm);
				$response['status'] = 1;
				$response['msg'] = '<h2>Success</h2>Menu position has been saved';
				$this->json_output($response);
			}
			else
			{
				$response['status'] = 0;
				$response['msg'] = '<h2>Error</h2>Access denied';
				$this->json_output($response);
			}
		}
		else
		{
			show_403();
		}
	}


	private function _update_menu_position($parent, $children) 
	{
		$i = 1;

		foreach ($children as $k => $v) 
		{
			$id = (int)$children[$k]['id'];
			$data = array(
				'parent_id' => $parent,
				'position' => $i
			);
			
			$this->db->where('id',$id)->update('t_menu',$data); // update

			if (isset($children[$k]['children'][0])) 
			{
				$this->_update_menu_position($id, $children[$k]['children']);
			}
			
			$i++;
		}
	}


	private function _get_label($row) 
	{
		$img_edit  = content_url('images/menu/edit.png');
		$img_cross = content_url('images/menu/cross.png');
		$label =
			'<div class="ns-row">' .
				'<div class="ns-title">'.$row['title'].'</div>' .
				'<div class="ns-url">'.$row['url'].'</div>' .
				'<div class="ns-class">'.$row['class'].'</div>' .
				'<div class="ns-active">'.$row['active'].'</div>' .
				'<div class="ns-actions">' .
					'<a href="#" class="edit-menu" title="Edit Menu">' .
						'<img src="'.$img_edit.'" alt="Edit">' .
					'</a>' .
					'<a href="#" class="delete-menu" data-token="'.$this->security->get_csrf_hash().'">' .
						'<img src="'.$img_cross.'" alt="Delete">' .
					'</a>' .
					'<input type="hidden" name="menu_id" value="'.$row['id'].'">' .
					'<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">' .
				'</div>' .
			'</div>';
			
		return $label;
	}


	private function _get_menu_group_title($group_id) 
	{
		$result = $this->db->where('id', $group_id)->get('t_menu_group')->row_array();
		return $result;
	}
	

	private function _get_menu_groups($group_id) 
	{	
		$result = $this->db->where('id',$group_id)->get('t_menu_group')->row_array();
		return $result;
	}
} // End Class.