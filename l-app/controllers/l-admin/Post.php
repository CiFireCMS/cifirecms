<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends Backend_Controller {

	public $mod = 'post';
	public $pk;

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('mod/'.$this->mod);
		$this->load->model('mod/post_model');
	}


	public function index() 
	{
		$this->meta_title(lang_line('mod_title_all'));

		if ( $this->role->i('read') )
		{
			if ($this->input->is_ajax_request())
			{
				if ($this->input->post('act') == 'delete')
				{
					return $this->_delete();
				}

				elseif ($this->input->post('act') == 'headline')
				{
					return $this->_headline();
				}

				else
				{
					$data = array();

					foreach ($this->post_model->datatable('_all_post', 'data') as $val) 
					{
						// row fortmat
						$row = [];

						// checkbox
						$row[] = '<div class="text-center"><input type="checkbox" class="row_data" value="'. encrypt($val['post_id']) .'"></div>';

						// ID
						$row[] = $val['post_id'];

						// Title
						$row[] = '<div>'. $val['post_title'] .'</div> <div><em><a href="'. post_url($val['post_seotitle']) .'" target="_blank" class="text-info">'. post_url($val['post_seotitle']) .'</a></em></div>
							<div class="badge badge-pill mt-2 pl-2 pr-2" style="background-color:#f1f1f1;font-size:12px;color:#777;"> 
							<span class="mr-2"><i class="cificon licon-user mr-1"></i>'. $val['user_name'] .'</span> 
							<span class="mr-2"><i class="cificon licon-calendar mr-1"></i>'. ci_date($val['post_datepost'] . $val['post_timepost'],'d M Y, h:i A') .'</span> 
							<span class="mr-2"><i class="cificon licon-eye mr-1"></i>'. $val['post_hits'] .'</span> 
							<span class="mr-2"><i class="cificon licon-message-square mr-1"></i>'. $val['comments'] .'</span> 
							'. ( $val['post_headline'] == 'Y' ? '<span class="h-'. $val['post_id'] .'"><i class="fa fa-star text-warning"></i> Headline</span>' : '<span class="h-'. $val['post_id'] .'"></span>' ) .'</div> ';

						// category
						$row[] = $val['category_title'];
						// datepost
						$row[] = ltrim(ci_date($val['post_datepost'],'d F Y'),'0').' <br/> <small>'.ltrim(ci_date($val['post_timepost'],'H:i'),'0').'</small>';

						// status
						$row[] = ($val['post_active'] == 'Y' ? '<span class="badge badge-outline-success">'. lang_line('ui_publish') .'</span>' : '<span class="badge badge-outline-default">'. lang_line('ui_draft') .'</span>');

						// Action
						$row[] = '<div class="text-center"><div class="btn-group">
								<a href=\''. admin_url($this->mod.'/preview/?id='. urlencode(encrypt($val['post_id'])) ) .'\'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_view') .'" target="_blank"><i class="cificon licon-eye"></i></a>

								<button type="button" class="btn btn-xs btn-white headline_toggle" data-toggle="tooltip" data-placement="top" data-title="Headline" data-pk="'. encrypt($val['post_id']) .'"><i class="cificon licon-star"></i></button>

								<a href="'. admin_url($this->mod.'/edit/?id='.urlencode(encrypt($val['post_id'])) ) .'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_edit') .'"><i class="cificon licon-edit"></i></a>

								<button type="button" class="btn btn-xs btn-white delete_single" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_delete') .'" data-pk="'. encrypt($val['post_id']) .'"><i class="cificon licon-trash-2"></i></button>
								</div></div>';
						
						// generate rows data
						$data[] = $row;
					}

					$csrf_name = $this->security->get_csrf_token_name();
					$csrf_hash = $this->security->get_csrf_hash();  
					$response = array('data' => $data, 'recordsFiltered' => $this->post_model->datatable('_all_post', 'count'));
					$response['csrf_name'] = $csrf_name;
					$response['csrf_hash'] = $csrf_hash;

					return  $this->json_output($response);
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
			if ( $this->input->is_ajax_request() ) // submit add new post.
			{
				$this->form_validation->set_rules(array(
					array(
						'field' => 'title',
						'label' => lang_line('_title'),
						'rules' => 'required|trim|min_length[6]|max_length[150]'
					),
					array(
						'field' => 'seotitle',
						'label' => lang_line('_seotitle'),
						'rules' => 'required|trim|min_length[6]|max_length[150]|callback__cek_add_seotitle'
					),
					array(
						'field' => 'category',
						'label' => lang_line('_category'),
						'rules' => 'required|trim'
					),
					array(
						'field' => 'content',
						'label' => lang_line('_content'),
						'rules' => 'required'
					),
					array(
						'field' => 'datepost',
						'label' => lang_line('_date'),
						'rules' => 'trim|required|max_length[10]',
					),
					array(
						'field' => 'timepost',
						'label' => lang_line('_time'),
						'rules' => 'trim|required|max_length[9]',
					),
					array(
						'field' => 'status',
						'label' => lang_line('_status'),
						'rules' => 'required|trim|max_length[1]'
					)
				));

				if ( $this->form_validation->run() ) 
				{
					$tags_input = $this->input->post('tags');
					$tags_input_s = explode(',', $tags_input);
					
					$tags = '';
					foreach ($tags_input_s as $tval) 
					{
						if ( $tval ) {
							$tag_title = clean_tag($tval);
							$tag_seotitle = seotitle($tval,'');

							$this->load->model('mod/tag_model'); // Load tag_model
							
							if ( $this->tag_model->cek_seotitle($tag_seotitle) == TRUE )
							{
								$getLastRowTag = $this->db->select('id')->order_by('id','DESC')->limit(1)->get('t_tag')->row_array();
								$lastIdTag = empty($getLastRowTag) ? 0 : $getLastRowTag['id'];
								$tID = (int)$lastIdTag + 1;
								$this->tag_model->insert(array(
									'id' => $tID,
									'title' => $tag_title,
									'seotitle' => $tag_seotitle
								));
							}

							$tags .= $tag_seotitle.',';	
						}
					}
					$tags = rtrim($tags, ',');
					
					$date_post = (empty($this->input->post('datepost')) ? date('Y-m-d') : date('Y-m-d', strtotime($this->input->post('datepost'))));
					$headline = ($this->input->post('headline') == 'Y' ? 'Y' : 'N');
					$comment  = ($this->input->post('comment') == 'Y' ? 'Y' : 'N');
					$active   = ($this->input->post('status') == 'Y' ? 'Y' : 'N');

					$getLastRow = $this->db->select('id')->order_by('id','DESC')->limit(1)->get('t_post')->row_array();
					$lastId = empty($getLastRow) ? 0 : $getLastRow['id'];
					$CID = (int)$lastId + 1;

					$data_post = array(
						'id'            => $CID,
						'title'         => xss_filter($this->input->post('title')),
						'seotitle'      => seotitle($this->input->post('seotitle', TRUE)),
						'content'       => xss_filter($this->input->post('content')),
						'id_category'   => xss_filter(decrypt($this->input->post('category')),'sql'),
						'tag'           => $tags,
						'picture'       => xss_filter($this->input->post('picture')),
						'image_caption' => xss_filter($this->input->post('image_caption')),
						'datepost'      => $date_post,
						'timepost'      => xss_filter($this->input->post('timepost').':'.date('s')),
						'id_user'       => decrypt(login_key()),
						'headline'      => $headline,
						'comment'       => $comment,
						'active'        => $active,
					);

					$this->post_model->insert_post($data_post);
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
				$this->vars['all_category'] = $this->post_model->get_all_category();
				$this->vars['all_tag'] = $this->post_model->get_all_tag();
				$this->vars['all_user'] = $this->post_model->get_all_user();
				$this->render_view('view_add', $this->vars);
			}
		}
		else 
		{
			$this->render_403();
		}
	}


	public function edit() 
	{
		$this->meta_title(lang_line('mod_title_edit'));

		if ( $this->role->i('modify') )
		{
			$get_id = (!empty($this->input->get('id')) ? decrypt($this->input->get('id')) : 0);
			$id_post = xss_filter($get_id,'sql');

			if ( $this->post_model->cek_id($id_post) == 1 ) 
			{
				if ( $this->input->is_ajax_request() )  // submit update.
				{
					$this->form_validation->set_rules(array(
						array(
							'field' => 'title',
							'label' => lang_line('_title'),
							'rules' => 'required|trim|min_length[6]|max_length[150]',
						),
						array(
							'field' => 'seotitle',
							'label' => lang_line('_seotitle'),
							'rules' => 'required|trim|min_length[6]|max_length[150]|callback__cek_edit_seotitle',
						),
						array(
							'field' => 'category',
							'label' => lang_line('_category'),
							'rules' => 'trim|required',
						),
						array(
							'field' => 'content',
							'label' => lang_line('_content'),
							'rules' => 'required',
						),
						array(
							'field' => 'datepost',
							'label' => lang_line('_date'),
							'rules' => 'trim|required|max_length[10]',
						),
						array(
							'field' => 'timepost',
							'label' => lang_line('_time'),
							'rules' => 'trim|required|max_length[9]',
						),
						array(
							'field' => 'status',
							'label' => lang_line('_status'),
							'rules' => 'required|trim|max_length[1]'
						)
					));

					if ( $this->form_validation->run() ) 
					{
						$tag_input = $this->input->post('tags');
						$tag_input_s = explode(',', $tag_input);
						$tags = '';
						foreach ( $tag_input_s as $tval ) 
						{
							if ( $tval )
							{		
								$tag_title = clean_tag($tval);
								$tag_seotitle = seotitle($tval,'');

								$this->load->model('mod/tag_model'); // Load tag_model
								
								if ( $this->tag_model->cek_seotitle($tag_seotitle) == TRUE )
								{
									$this->tag_model->insert(array(
										'title' => $tag_title,
										'seotitle' => $tag_seotitle
									));
								}

								$tags .= $tag_seotitle.',';	
							}
						}
						$tags = rtrim($tags, ',');
						
						$input_category = decrypt($this->input->post('category'));
						$id_category = (!empty($input_category) ? $input_category : '1');
						$headline = ($this->input->post('headline') == 'Y' ? 'Y' : 'N');
						$comment = ($this->input->post('comment') == 'Y' ? 'Y' : 'N');
						$active = ($this->input->post('status') == 'Y' ? 'Y' : 'N');
						$title = xss_filter($this->input->post('title'));
						$seotitle = seotitle($title);

						if ( group_active() == 'root' || group_active() == 'admin' )
						{
							$data = array(
								'title'        => xss_filter($this->input->post('title', TRUE)),
								'seotitle'     => seotitle($this->input->post('seotitle', TRUE)),
								'content'      => xss_filter($this->input->post('content')),
								'id_category'  => $id_category,
								'tag'          => $tags,
								'picture'      => xss_filter($this->input->post('picture')),
								'image_caption' => xss_filter($this->input->post('image_caption')),
								'datepost'     => date('Y-m-d', strtotime(xss_filter($this->input->post('datepost')))),
								'timepost'     => xss_filter($this->input->post('timepost')),
								'id_user'      => xss_filter($this->input->post('author'), 'sql'),
								'headline'     => $headline,
								'comment'      => $comment,
								'active'       => $active,
							);
						}
						else
						{
							$data = array(
								'title'         => $title,
								'seotitle'      => $seotitle,
								'content'       => xss_filter($this->input->post('content')),
								'id_category'   => $id_category,
								'tag'           => $tags,
								'picture'       => xss_filter($this->input->post('picture')),
								'image_caption' => xss_filter($this->input->post('image_caption')),
								'headline'      => $headline,
								'comment'       => $comment,
								'active'        => $active,
							);	
						}

						$this->post_model->update_post($id_post, $data);

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
					$data = $this->post_model->get_post($id_post);

					$this->vars['headline'] = '<option value="'. $data['headline'] .'" style="display:none;">'.($data['headline'] == 'Y' ? lang_line('ui_yes') : lang_line('ui_no') ).'</option>';

					$this->vars['comment'] = '<option value="'. $data['comment'] .'" style="display:none;">'.($data['comment'] == 'Y' ? lang_line('ui_active') : lang_line('ui_deactive') ).'</option>';
					
					$this->vars['status'] = '<option value="'. $data['post_active'] .'" style="display:none;">'.($data['post_active'] == 'Y' ? lang_line('ui_publish') : lang_line('ui_draft') ).'</option>';

					$this->vars['result_post']  = $data;
					$this->vars['all_category'] = $this->post_model->get_all_category();
					$this->vars['all_user']     = $this->post_model->get_all_user();
					$this->render_view('view_edit', $this->vars);
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


	private function _headline()
	{
		if ($this->role->i('modify'))
		{
			$this->pk = decrypt($this->input->post('pk'));
					
			$query_headline = $this->db
				->select('id,headline')
				->where('id', $this->pk)
				->get('t_post');

			if ( $query_headline->num_rows() == 1 )
			{
				$post = $query_headline->row_array();
				$headline = ( $post['headline'] == 'Y' ? 'N' : 'Y');
				$data = [
					'headline' => $headline
				];

				$this->post_model->update_post($this->pk, $data);

				$response['status'] = true;

				$response['index'] = 'h-'.$this->pk;
				$response['html'] = ( $headline == 'Y' ? '<i class="fa fa-star text-warning"></i> '.lang_line('_headline') : '' );
				$response['alert']['type'] = 'warning';
				$response['alert']['content'] = ( $headline == 'Y' ? lang_line('_headline_on') : lang_line('_headline_off') );

				$this->json_output($response);
			}
			else
			{
				$response['status'] = false;
				$response['alert']['type'] = 'error';
				$response['alert']['content'] = 'Error';
				$this->json_output($response);
			}
		} 
		else
		{
			$response['success'] = false;
			$response['alert']['type']    = 'error';
			$response['alert']['content'] = 'ERROR';
			$this->json_output($response);
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
				$this->post_model->delete($pk);
			}

			$response['success'] = true;
			$response['alert']['type']    = 'success';
			$response['alert']['content'] = lang_line('form_message_delete_success');
			$this->json_output($response);
		} 
		else
		{
			$response['success'] = false;
			$response['alert']['type']    = 'error';
			$response['alert']['content'] = 'ERROR';
			$this->json_output($response);
		}
	}


	public function preview()
	{
		$this->meta_title(lang_line('mod_title_preview'));
		
		if ($this->role->i('read'))
		{
			$getid = ($this->input->get('id') ? $this->input->get('id') : 0);
			$id_post = xss_filter(urldecode(decrypt($getid)),'sql');

			if ($this->post_model->cek_id($id_post) == 1)
			{
				$this->vars['res'] = $this->post_model->get_detail($id_post);
				$this->render_view('view_preview', $this->vars);
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


	public function ajax_tags()
	{
		if ($this->input->is_ajax_request())
		{
			$input = clean_tag($this->input->post('seotitle'));
			$output = $this->post_model->ajax_tags($input);
			$this->json_output($output);
		}
		else
		{
			show_403();
		}
	}


	public function _cek_add_seotitle($seotitle = '') 
	{
		$cek = $this->post_model->cek_seotitle(seotitle($seotitle));

		if ( $cek === FALSE ) 
		{
			$this->form_validation->set_message('_cek_add_seotitle', lang_line('form_validation_already_exists'));
		}
		
		return $cek;
	}


	public function _cek_edit_seotitle($seotitle = '') 
	{
		$id_post = ($this->input->get('id') ? decrypt($this->input->get('id')) : 0);
		$cek = $this->post_model->cek_seotitle2($id_post, seotitle($seotitle));
		
		if ( $cek === FALSE ) 
		{
			$this->form_validation->set_message('_cek_edit_seotitle', lang_line('form_validation_already_exists'));
		} 

		return $cek;
	}



} // End class.