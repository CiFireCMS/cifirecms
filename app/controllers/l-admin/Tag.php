<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends Backend_Controller {

	public $mod = 'tag';

	public function __construct() 
	{
		parent::__construct();
		$this->lang->load('mod/'.$this->mod);
		$this->load->model('mod/tag_model');
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

					foreach ($this->tag_model->datatable('_all_tag', 'data') as $val) 
					{
						$row = [];

						$row[] = '<div class="text-center"><input type="checkbox" class="row_data" value="'. encrypt($val['id']) .'"></div>';
						$row[] = $val['id'];
						$row[] = $val['title'];
						$row[] = $val['tag_count'];
						$row[] = '<div class="text-center"><button type="button" class="btn btn-xs btn-white delete_single" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_delete') .'" data-pk="'. encrypt($val['id']) .'"><i class="cificon licon-trash-2"></i></button></div>';

						$data[] = $row;
					}

					$this->json_output(['data' => $data, 'recordsFiltered' => $this->tag_model->datatable('_all_tag', 'count')]);
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
				$data = xss_filter($this->input->post('data'), 'xss');
				$tags = explode(',', $data);
				
				if (!empty(clean_tag($data)))
				{
					foreach ($tags as $key)
					{
						$title = clean_tag($key);
						$seotitle = seotitle($key,'');
						if ( !empty($title) && $this->tag_model->cek_seotitle($seotitle) == TRUE )
						{
							$getLastRow = $this->db->select('id')->order_by('id','DESC')->limit(1)->get('t_tag')->row_array();
							$lastId = empty($getLastRow) ? 0 : $getLastRow['id'];
							$CID = (int)$lastId + 1;
							$this->tag_model->insert(array(
								'id' => $CID,
								'title' => $title,
								'seotitle' => $seotitle
							));
						}
					}

					$this->cifire_alert->set($this->mod, 'info', lang_line('form_message_add_success'));
					$response['success'] = true;
					$this->json_output($response);
				}
				else
				{
					$response['success'] = false;
					$response['alert']['type'] = 'error';
					$response['alert']['content'] = lang_line('form_message_submit_error');
					$this->json_output($response);
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


	private function _delete()
	{
		if ($this->role->i('delete'))
		{
			$data = $this->input->post('data');

			foreach ($data as $key)
			{
				$pk = xss_filter(decrypt($key),'sql');
				$this->tag_model->delete($pk);
			}

			$response['success'] = true;
			$response['alert']['type']    = 'success';
			$response['alert']['content'] = lang_line('form_message_delete_success');
			$this->json_output($response);
		} 
		else
		{
			$response['success'] = false;
			$this->json_output($response);
		}
	}
} // End class.