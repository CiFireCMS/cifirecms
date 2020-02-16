<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends Web_controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('web/post_model');
	}


	public function index()
	{
		$getSegments = $this->uri->segment(count($this->uri->segments));
		$getSeotitle = seotitle($getSegments);

		if ( !empty($getSeotitle) && $this->post_model->cek_post($getSeotitle) == TRUE ) 
		{
			$id_post = $this->post_model->id_post($getSeotitle);
			$data_post = $this->post_model->get_post($getSeotitle);
			
			if ( $this->input->method() == 'post' )  // Submit Komentar.
			{
				if (get_setting('post_comment')=='Y' && $data_post['post_comment']=='Y')
				{
					$this->_submit_comment($id_post);
					redirect(selft_url());
				}
				else
				{
					show_404();
				}
			}
			else
			{
				$this->vars['result_post']  = $data_post;
				$this->vars['result_post']['content'] = $this->_content($data_post); // set content
				$this->vars['content_paging'] = $this->_content($data_post,TRUE); // set content paging
				
				// link prev post & next post.
				$this->vars['prev_post'] = $this->_prev_post($id_post); 
				$this->vars['next_post'] = $this->_next_post($id_post);

				// related post
				$this->vars['related_post'] = $this->post_model->related_post($data_post['tag'], $data_post['post_id'], 4);

				$this->meta_title($data_post['post_title'].' - '.get_setting('web_name'));
				$this->meta_keywords($data_post['tag'].', '.get_setting('web_keyword'));
				$this->meta_description(cut($data_post['content'], 150));
				$this->meta_image(post_images($data_post['picture'], 'medium', TRUE));
				
				$this->render_view('post');

				// set new hit.
				$hit = $data_post['hits']+1;
				$this->post_model->hits($id_post, $hit);
			}
		}
		else
		{
			$this->render_404();
		}
	}


	private function _content($data = '', $pagination = FALSE)
	{
		$pagebreak  = explode('&lt;!-- pagebreak --&gt;', $data['content']);
		$_countPage = count($pagebreak);
		$_urlPost   = post_url($data['post_seotitle'])."?";
		$_getPage   = xss_filter($this->input->get('page'),'sql');
		$_index     = $_getPage > 0 ? $_getPage - 1 : $_getPage;
		
		$result = '';
		switch ($pagination)
		{
			case TRUE:
				$config['base_url']   = post_url($data['post_seotitle'])."?page=";
				$config['index_page'] = $_index+1;   // halaman aktif
				$config['total_rows'] = $_countPage; // total data
				$config['total_link'] = $_countPage; // total page link number
				$config['per_page']   = 1; // data per halaman (default get_setting('page_item'))
				$config['limit_item'] = 5; // int of FALSE (limit page link number) 
				$config['prev_link']  = '<i class="fa fa-angle-left"></i>'; // string or FALSE
				$config['next_link']  = '<i class="fa fa-angle-right"></i>'; // string or FALSE
				$this->cifire_pagination->initialize($config);

				if ($_countPage > 1)
				{
					$result = $this->cifire_pagination->create_links();
				}
			break;
			
			default:
				$result = html_entity_decode($pagebreak[$_index]);
			break;
		}

		return $result;
	}


	private function _submit_comment($id_post = 0)
	{
		if ( get_setting('recaptcha') == 'Y' && googleCaptcha()->success == FALSE )
		{
			$this->cifire_alert->set('alert_comment', 'danger', 'Please complete the captcha');
		}

		else
		{
			$this->form_validation->set_rules(array(
				array(
					'field' => 'name',
					'label' => 'Name',
					'rules' => 'required|trim|min_length[4]|max_length[150]|regex_match[/^[a-zA-Z0-9-._ ]+$/]'
				),
				array(
				    'field' => 'email',
					'label' => 'Email',
					'rules' => 'required|trim|max_length[60]|valid_email'
				),
				array(
				    'field' => 'comment',
					'label' => 'Comment',
					'rules' => 'required|trim|min_length[6]|max_length[5000]'
				)
			));

			if ( $this->form_validation->run() ) 
			{
				$inputParent = xss_filter(decrypt($this->input->post('parent')), 'sql');
				$parent  = ( !empty($inputParent) ? $inputParent : 0 );

				$data_comment = array(
					'id_post' => xss_filter($id_post, 'sql'),
					'parent'  => $parent,
					'name'    => xss_filter($this->input->post('name', TRUE), 'xss'),
					'email'   => $this->input->post('email', TRUE),
					'comment' => xss_filter($this->input->post('comment')),
					'ip'      => $this->input->ip_address(),
					'active'  => 'N'
				);

				$this->post_model->insert_comment($data_comment);
				$this->cifire_alert->set('alert_comment', 'success', 'Succes');
			}
			else 
			{
				$this->cifire_alert->set('alert_comment', 'danger', validation_errors());
			}
		}
	}


	private function _prev_post($id = 0) 
	{
		$data = $this->post_model->prev_post($id);

		if ( $data == FALSE )
		{
			return NULL;
		}

		else
		{
			$result = array(
				'title' => $data['title'], 
				'url'   => post_url($data['seotitle'])
			);

			return $result;
		}
	}


	private function _next_post($id = 0) 
	{
		$data = $this->post_model->next_post($id);
		
		if ( $data == FALSE )
		{
			return NULL;
		}
		else
		{	
			$result = array(
				'title' => $data['title'], 
				'url'   => post_url($data['seotitle'])
			);
			return $result;
		}
	}

} // End class.