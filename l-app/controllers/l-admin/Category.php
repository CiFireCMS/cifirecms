<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Backend_Controller {
	
	public $mod = 'category';

	public function __construct() 
	{
		parent::__construct();
		$this->lang->load('mod/'.$this->mod);
		$this->load->model('mod/category_model');
	}


	public function index()
	{
		$this->meta_title(lang_line('mod_title_all'));

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

					foreach ($this->category_model->datatable('_all_category', 'data') as $val) 
					{
						// row fortmat
						$row = [];

						// checkbox
						$row[] = '<div class="text-center"><input type="checkbox" class="row_data" value="'. encrypt($val['id']) .'"></div>';
						
						// id
						$row[] = $val['id'];
						
						// title
						$row[] = $val['title'];
						
						// setitle
						$row[] = '<a href="'. site_url('category/'.$val['seotitle']) .'" target="_blank" class="text-default">'. $val['seotitle'] .'</a>';
						
						// parent
						$row[] = $this->category_model->get_parent_title($val['id_parent']);
						
						// status
						$row[] = ($val['active'] == 'Y' ? '<span class="badge badge-outline-success">'. lang_line('ui_publish') .'</span>' : '<span class="badge badge-outline-default">'. lang_line('ui_draft') .'</span>');
						
						// action
						$row[] = '<div class="text-center"><div class="btn-group">
								<a href="'.admin_url($this->mod.'/edit/'.$val['id']).'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_edit').'"><i class="cificon licon-edit"></i></a>
								<button type="button" class="btn btn-xs btn-white delete_single" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_delete').'" data-pk="'. encrypt($val['id']) .'"><i class="cificon licon-trash-2"></i></button>
								</div></div>';

						// generate rows data
						$data[] = $row;
					}

					$this->json_output(['data' => $data, 'recordsFiltered' => $this->category_model->datatable('_all_category', 'count')]);
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


	public function add()
	{
		$this->meta_title(lang_line('mod_title_add'));

		if ( $this->role->i('write') )
		{
			if ( $this->input->is_ajax_request() )
			{
				$this->form_validation->set_rules(array(
					array(
						'field' => 'title',
						'label' => lang_line('_title'),
						'rules' => 'required|trim|min_length[2]|max_length[50]'
					),
					array(
						'field' => 'seotitle',
						'label' => lang_line('_seotitle'),
						'rules' => 'required|trim|min_length[2]|max_length[60]|callback__cek_add_seotitle'
					),
					array(
						'field' => 'parent',
						'label' => lang_line('_parent'),
						'rules' => 'trim|required'
					),
					array(
						'field' => 'status',
						'label' => lang_line('_status'),
						'rules' => 'trim|required|max_length[1]'
					)
				));

				if ( $this->form_validation->run() ) 
				{
					$in_parent = $this->input->post('parent');
					$id_parent = ( $in_parent == '0' ? '0' : decrypt($in_parent) );

					$lastRow = $this->db->select('id')->order_by('id','DESC')->limit(1)->get('t_category')->row_array();
					$lastId = empty($lastRow) ? 0 : $lastRow['id'];
					$CID = (int)$lastId + 1;

					$data_form = array(
						'id'          => $CID,
						'id_parent'   => $id_parent,
						'title'       => xss_filter($this->input->post('title'), 'xss'),
						'seotitle'    => seotitle($this->input->post('seotitle')),
						'description' => xss_filter($this->input->post('description'), 'xss'),
						'picture'      => $this->input->post('picture',TRUE),
						'active'      => $this->input->post('status')
					);

					$this->category_model->insert($data_form);
					$response['success'] = true;
					$this->json_output($response);
				}
				else 
				{
					$response['success'] = false;
					$response['alert']['type'] = 'error';
					$response['alert']['content'] = validation_errors();
					$this->json_output($response);
				}
			}

			else
			{
				$this->vars['parents'] = $this->category_model->get_parent(1);
				$this->render_view('view_add');
			} 
		}

		else
		{
			$this->render_403();
		}
	}

	public function _cek_add_seotitle($seotitle = '') 
	{
		$seotitle = seotitle($seotitle);
		$cek      = $this->category_model->cek_seotitle($seotitle);

		if ( $cek === FALSE ) 
		{
			$this->form_validation->set_message('_cek_add_seotitle', lang_line('form_validation_already_exists'));
		}
		
		return $cek;
	}


	public function edit($id = 0)
	{
		$this->meta_title(lang_line('mod_title_edit'));

		if ( $this->role->i('modify') && $id > 1)
		{
			$id_category = xss_filter($id);

			if ( !empty($id_category) && $this->category_model->cek_id($id_category) == 1 ) 
			{
				if ( $this->input->is_ajax_request() ) 
				{
					$this->form_validation->set_rules(array(
						array(
							'field' => 'title',
							'label' => lang_line('_title'),
							'rules' => 'required|trim|min_length[2]|max_length[50]|callback__cek_edit_seotitle'
						),
						array(
								'field' => 'seotitle',
								'label' => lang_line('_seotitle'),
								'rules' => 'required|trim|min_length[2]|max_length[60]|callback__cek_edit_seotitle'
							),
						array(
							'field' => 'parent',
							'label' => lang_line('_parent'),
							'rules' => 'required|trim'
						),
						array(
							'field' => 'status',
							'label' => lang_line('_status'),
							'rules' => 'trim|required|max_length[1]'
						)
					));

					if ( $this->form_validation->run() ) 
					{
						$in_parent = $this->input->post('parent');
						$id_parent = ( $in_parent == '0' ? '0' : decrypt($in_parent) );

						$data = array(
							'id_parent'   => $id_parent,
							'title'       => xss_filter($this->input->post('title'), 'xss'),
							'seotitle'    => seotitle($this->input->post('seotitle')),
							'description' => xss_filter($this->input->post('description'), 'xss'),
							'picture'      => $this->input->post('picture',TRUE),
							'active'      => $this->input->post('status')
						);

						$this->category_model->update($id_category, $data);

						$response['success'] = true;
						$response['alert']['type'] = 'success';
						$response['alert']['content'] = lang_line('form_message_update_success');
					}
					else 
					{
						$response['success'] = false;
						$response['alert']['type'] = 'error';
						$response['alert']['content'] = validation_errors();
					}

					$this->json_output($response);
				}

				else
				{
					$data = $this->category_model->get_category($id_category);
					$this->vars['res_category'] = $data;
					$this->vars['parents'] = $this->category_model->get_parent($data['id']);
					$this->render_view('view_edit');
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

	public function _cek_edit_seotitle($seotitle = '') 
	{
		$seotitle = seotitle($seotitle);
		$idEdit   = $this->uri->segment(4);
		$cek      = $this->category_model->cek_seotitle2($idEdit, $seotitle);
		
		if ( $cek === FALSE ) 
		{
			$this->form_validation->set_message('_cek_edit_seotitle', lang_line('form_validation_already_exists'));
		} 

		return $cek;
	}


	private function _delete()
	{
		if ($this->role->i('delete'))
		{
			$data = $this->input->post('data');

			foreach ($data as $key)
			{
				$pk = xss_filter(decrypt($key),'sql');
				$this->category_model->delete($pk);
			}

			$response['success'] = true;
			$this->json_output($response);
		} 
		else
		{
			$response['success'] = false;
			$this->json_output($response);
		}
	}
} // End Class.