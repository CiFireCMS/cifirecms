<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}


	public function datatable($method, $mode = '', $param = '')
	{
		if ($mode == 'count')
		{
			$this->$method($param); // another method from method params.

			$result =  $this->db->get()->num_rows();
		}

		elseif (empty($mode) || $mode == 'data')
		{
			$this->$method($param); // another method from method params.

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


	private function _data_groups()
	{
		$_column_order = array(
			null,
			'id',
			'title',
			'group'
		);
		
		$_column_search = array(
			'id',
			'title',
			'group'
		);		
		
		$this->db->select('*');

		$this->db->from('t_user_group');

		$i = 0;	
		foreach ($_column_search as $item) 
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

				if ( count($_column_search) - 1 == $i ) 
				{
					$this->db->group_end(); 
				}
			}
			$i++;
		}
		
		if ( !empty($this->input->post('order')) ) 
		{
			$this->db->order_by(
				$_column_order[$this->input->post('order')['0']['column']], 
				$this->input->post('order')['0']['dir']
			);
		}
	}


	private function _data_roles()
	{
		$_column_order = array(
			null,
			'id',
			'group',
			'module',
			'read_access',
			'write_access',
			'modify_access',
			'delete_access'
		);
		
		$_column_search = array(
			'id',
			'group',
			'module'
		);		
		
		$this->db->select('*');

		$this->db->from('t_roles');

		$i = 0;	
		foreach ($_column_search as $item) 
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

				if ( count($_column_search) - 1 == $i ) 
				{
					$this->db->group_end(); 
				}
			}
			$i++;
		}
		
		if ( !empty($this->input->post('order')) ) 
		{
			$this->db->order_by(
				$_column_order[$this->input->post('order')['0']['column']], 
				$this->input->post('order')['0']['dir']
			);
		}
		else
		{
			$this->db->order_by('id','DESC');
		}
	}


	private function _group_role($group='')
	{
		$group = xss_filter($group,'xss');
		$_column_order = array(
			null,
			'id',
			'module',
			'read_access',
			'write_access',
			'modify_access',
			'delete_access'
		);
		
		$_column_search = array(
			'id',
			'module'
		);		
		
		$this->db->select('*');

		$this->db->from('t_roles');
		$this->db->where('group',$group);

		$i = 0;	
		foreach ($_column_search as $item) 
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

				if ( count($_column_search) - 1 == $i ) 
				{
					$this->db->group_end(); 
				}
			}
			$i++;
		}
		
		if ( !empty($this->input->post('order')) ) 
		{
			$this->db->order_by(
				$_column_order[$this->input->post('order')['0']['column']], 
				$this->input->post('order')['0']['dir']
			);
		}
		else
		{
			$this->db->order_by('id','DESC');
		}
	}


	public function get_group($pk = '')
	{
		$pk = xss_filter($pk,'xss');
		$query = $this->db->where('pk',$pk)->get('t_user_group');
		if ($this->cek_pk($pk)==1)
		{
			$result = $query->row_array();
		}
		else
		{
			$result = NULL;
		}
		return $result;
	}


	public function get_roles_by_group($group='')
	{
		$group = xss_filter($group);
		$query = $this->db->where('group',$group)->get('t_roles')->result_array();
		return $query;
	}


	public function insert_group($data)
	{
		$this->db->insert('t_user_group', $data);
	}


	public function update_group($pk,$data)
	{
		$pk = xss_filter($pk,'xss');
		
		if ($this->cek_pk($pk)==1)
		{
			$group_a = $this->get_group($pk); // get group before change

			if ($group_a['group'] != 'root')
			{
				$this->db->where('pk', $pk)->update('t_user_group', $data); // update group

				$group_b = $this->get_group($pk); // get group after change

				// update role with new group
				$this->db->where('group', $group_a['group'])->update('t_roles', ['group'=>$group_b['group']]);
			}
		}
	}


	public function delete_group($pk='')
	{
		$pk = xss_filter($pk,'xss');
		$group = $this->db->where('pk',$pk)->where_not_in('group','root')->get('t_user_group');
		if ($group->num_rows()==1)
		{
			$group = $group->row_array();
			$this->db->where('group', $group['group'])->delete('t_roles');
			$this->db->where('pk', $pk)->delete('t_user_group');
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	public function cek_pk($pk='')
	{
		$pk = xss_filter($pk,'xss');
		$query = $this->db->where('pk',$pk)->get('t_user_group')->num_rows();
		return $query;
	}


	public function get_role_by_id($id='')
	{
		$id = xss_filter($id,'xss');
		$query = $this->db->where('BINARY id="'.$id.'"',NULL,FALSE)->get('t_roles')->row_array();
		return $query;
	}


	public function insert_role($data)
	{
		$this->db->insert('t_roles', $data);
	}


	public function update_role_by_id($id,$data)
	{
		if ($this->db->where('id',$id)->get('t_roles')->num_rows()==1)
		{
			$this->db->where('id', $id)->update('t_roles', $data);
		}
	}


	public function delete_role($id='')
	{
		$this->db->where('id',$id)->delete('t_roles');
	}
} // End class.