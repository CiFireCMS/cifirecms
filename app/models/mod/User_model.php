<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	private $_table = 't_user';
	private $_column_order = array(null, 't_user.id', null, 't_user.username', 't_user.name', 't_user_group.title', 't_user.active', null);
	private $_column_search = array('t_user.id', 't_user.name', 't_user.username', 't_user_group.title');


	public function __construct()
	{
		parent::__construct();

		$this->_group_active = group_active('group');
		$this->_login_key = login_key();
	}


	public function datatable($method, $mode = '')
	{
		if ($mode == 'count')
		{
			$this->$method();
			
			$result =  $this->db->get()->num_rows();
		}

		elseif (empty($mode) || $mode == 'data')
		{
			$this->$method();
			if ($this->input->post('length') != -1) 
			{
				$this->db->limit($this->input->post('length'), $this->input->post('start'));
				$query = $this->db->get();
			}
			else
			{
				$query = $this->db->get();
			}
			
			$result =  $query->result_array();
		}

		return $result;
	}


	private function _all_user()
	{
		$this->db->select('
				         t_user.id           AS  user_id,
					     t_user.key_group    AS  user_key_group,
					     t_user.name         AS  user_name,
					     t_user.username     AS  user_username,
					     t_user.photo        AS  user_photo,
					     t_user.active       AS  user_active,

					     t_user_group.id     AS  group_id,
					     t_user_group.pk     AS  group_pk,
					     t_user_group.title  AS  group_title,
					     t_user_group.group  AS  group_name
					    ');

		$this->db->from($this->_table);

		if ( $this->_group_active != 'root' )
		{
			$this->db->where_not_in('t_user_group.group', 'root');
		}

		$this->db->where_not_in('t_user.id', decrypt(login_key())); // without user active login

		$this->db->join('t_user_group', 't_user_group.pk = t_user.key_group', 'left');

		$i = 0;	
		foreach ($this->_column_search as $item) 
		{
			if ( $this->input->post('search')['value'] )
			{
				if ( $i == 0 )
				{
					$this->db->group_start();
					$this->db->like($item, $this->input->post('search')['value']);
				}
				else
				{
					$this->db->or_like($item, $this->input->post('search')['value']);
				}

				if ( count($this->_column_search) - 1 == $i ) 
				{
					$this->db->group_end(); 
				}
			}
			$i++;
		}
		
		if ( !empty($this->input->post('order')) ) 
		{
			$this->db->order_by(
				$this->_column_order[$this->input->post('order')['0']['column']], 
				$this->input->post('order')['0']['dir']
			);
		}
		else
		{
			$this->db->order_by('t_user.id', 'DESC');
		}
	}


	public function insert_user($data)
	{
		$this->db->insert('t_user', $data);
	}


	public function update($id, array $data)
	{
		$this->db->where('id',$id)->update($this->_table, $data);
	}


	public function delete($id)
	{
		$this->db->where('id', $id)->delete($this->_table);
	}




	public function get_user($id) 
	{
		$session_level = login_level('admin');

		$query = $this->db
			->select('
			         t_user.id           AS  user_id,
				     t_user.level        AS  user_level,
				     t_user.username     AS  user_username,
				     t_user.password     AS  user_password,
				     t_user.email        AS  user_email,
				     t_user.name         AS  user_name,
				     t_user.gender       AS  user_gender,
				     t_user.birthday     AS  user_birthday,
				     t_user.about        AS  user_about,
				     t_user.address      AS  user_address,
				     t_user.tlpn         AS  user_tlpn,
				     t_user.photo        AS  user_photo,
				     t_user.active       AS  user_active,
				     t_user_level.id     AS  level_id,
				     t_user_level.title  AS  level_title
				    ');

		$query = $this->db->join('t_user_level', 't_user_level.id = t_user.level', 'left');
		
		if ( $session_level == 1 )
		{
			$query = $this->db->where('t_user.id', $id);
		}
		else
		{
			$query = $this->db->where('t_user.id', $id);
			$query = $this->db->where("t_user.level !='1'", NULL, FALSE);
		}

		$query = $this->db->get($this->_table);
		$result = $query->row_array();

		if ( $query->num_rows() == 1 )
			return $result;
		else
			show_404();
	}


	public function get_id($username='')
	{
		$query = $this->db->select('id');
		$query = $this->db->where("BINARY username = '$username'", NULL, FALSE);
		$query = $this->db->get($this->_table);
		$result = $query->row_array()['id'];
		return $result;
	}


	public function get_user_by_username($user_name='')
	{
		$username = xss_filter($user_name, 'xss');

		$query = $this->db
			->select('
			         t_user.id           AS  user_id,
				     t_user.level        AS  user_level,
				     t_user.username     AS  user_username,
				     t_user.password     AS  user_password,
				     t_user.email        AS  user_email,
				     t_user.name         AS  user_name,
				     t_user.gender       AS  user_gender,
				     t_user.birthday     AS  user_birthday,
				     t_user.about        AS  user_about,
				     t_user.address      AS  user_address,
				     t_user.tlpn         AS  user_tlpn,
				     t_user.photo        AS  user_photo,
				     t_user.active       AS  user_active,

				     t_user_group.pk     AS  group_pk
				    ');

		$query = $this->db->join('t_user_group', 't_user_group.pk = t_user.key_group', 'left');
		$query = $this->db->where('t_user.username', $username);
		$query = $this->db->get($this->_table);

		$result = $query->row_array();

		return $result;
	}


	public function get_user_edit($uname='')
	{
		$query = $this->db->select('*,

		                            t_user.id           AS  u_id,
		                            t_user_group.id     AS  g_id,
		                            t_user_group.pk     AS  g_pk,
		                            t_user_group.title  AS  g_title,
		                            t_user_group.group  AS  g_group,
		                         ');

		$query = $this->db->from('t_user');
		

		if ($this->_group_active == 'root')
		{
			$query = $this->db->where('t_user.username',$uname);
		}
		elseif ($this->_group_active == 'admin')
		{
			$query = $this->db->where_not_in('t_user_group.group','root');
			$query = $this->db->where('t_user.username',$uname);
		}
		else
		{
			$query = $this->db->where_not_in('t_user_group.group','root,admin');
			$query = $this->db->where('t_user.username',$uname);
		}

		$query = $this->db->join('t_user_group','t_user_group.pk = t_user.key_group','left');

		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}


	public function get_photo2($id) 
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get($this->_table);
		$result = $query->row_array();
		$photo = $result['photo'];
		return $photo;
	}


	public function get_photo($id)
	{
		if ( $this->cek_id($id) > 0 )
		{
			$query = $this->db->where('id', $id);
			$query = $this->db->get('t_user');
			$result = $query->row_array();
			$photo = $result['photo'];
			return $photo;
		}
		else 
		{
			return NULL;
		}
	}


	public function data_groups() 
	{
		if ( $this->_group_active == 'root' )
		{
			$query = $this->db
				->order_by('id','ASC')
				->get('t_user_group')
				->result_array();
		}
		elseif ( $this->_group_active == 'admin' ) {
			$query = $this->db
				->where_not_in('group','root')
				// ->where_not_in('group','admin')
				->order_by('title','ASC')
				->get('t_user_group')
				->result_array();
		}
		else
		{
			$query = $this->db
				->where_not_in('group','root')
				->where_not_in('group','admin')
				->order_by('title','ASC')
				->get('t_user_group')
				->result_array();
		}

		return $query;
	}


	public function cek_id($id)
	{
		$query = $this->db->select('id');
		$query = $this->db->where('id', $id);
		$query = $this->db->get($this->_table);
		$result = $query->num_rows();

		if ( $result < 1 )
			$result = 0;
		
		return $result;
	}


	public function cek_username($username)
	{
		$query = $this->db->where("BINARY username = '$username'", NULL, FALSE);
		$query = $this->db->get($this->_table);
		$result = $query->num_rows();

		if ( $result == 0 )
			return TRUE;
		else 
			return FALSE;
	}


	public function cek_username2($id, $username)
	{
		$query = $this->db->select('id,username');
		$query = $this->db->where("BINARY username = '$username'", NULL, FALSE);
		$query = $this->db->get($this->_table);

		if (
		    $query->num_rows() == 1 && 
		    $query->row_array()['id'] == $id || 
		    $query->num_rows() != 1
		   ) 
		{
			return TRUE;
		}
		else 
		{
			return FALSE;
		}
	}

	
	public function cek_email($email)
	{
		$query = $this->db->where("BINARY email = '$email'", NULL, FALSE);
		$query = $this->db->get($this->_table);
		$result = $query->num_rows();

		if ( $result == 0 ) 
			return TRUE;
		else 
			return FALSE;
	}

	public function cek_email2($id, $email)
	{
		$query = $this->db
			->select('id')
			->where("BINARY email = '$email'", NULL, FALSE)
			->get($this->_table);

		if (
		    $query->num_rows() == 1 && 
		    $query->row_array()['id'] == $id || 
		    $query->num_rows() == 0
		   ) 
		{
			return TRUE;
		}
		else 
		{
			return FALSE;
		}
	}
} // End class.