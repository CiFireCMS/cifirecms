<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

	private $_table = 't_category';
	private $_column_order = array(null, 'id', 'title', 'seotitle', 'id_parent', 'active', null);
	private $_column_search = array('id', 'title', 'seotitle', 'id_parent');

	public function __construct()
	{
		parent::__construct();
	}


	public function datatable($method, $type = 'data')
	{
		$result = NULL;

		if ($type === 'count')
		{
			$this->$method();
			$result = $this->db->get()->num_rows();
		}

		if ($type === 'data')
		{
			$this->$method();
			if ($this->input->post('length') != -1) 
			{
				$length = xss_filter($this->input->post('length'), 'xss');
				$start = xss_filter($this->input->post('start'), 'xss');
				$this->db->limit($length, $start);
				$query = $this->db->get();
			}
			else
			{
				$query = $this->db->get();
			}
			
			$result = $query->result_array();
		}

		return $result;
	}


	private function _all_category()
	{
		$this->db->select('id,id_parent,title,seotitle,active');
		$this->db->from($this->_table);
		// $this->db->where('id >',1);

		$i = 0;	
		foreach ($this->_column_search as $item) 
		{
			if ( $this->input->post('search')['value'] )
			{
				$search_key = xss_filter($this->input->post('search')['value'], 'xss');
				$search_key = trim($search_key);
				if ( $i == 0 )
				{
					$this->db->group_start();
					$this->db->like($item, $search_key);
				}
				else
				{
					$this->db->or_like($item, $search_key);
				}

				if ( count($this->_column_search)-1 == $i ) 
				{
					$this->db->group_end(); 
				}
			}
			$i++;
		}
		
		if ( !empty($this->input->post('order')) ) 
		{
			$field = xss_filter($this->_column_order[$this->input->post('order')['0']['column']],'xss');
			$value = xss_filter($this->input->post('order')['0']['dir'],'xss');
			$this->db->order_by($field,$value);
		}
		else
		{
			$this->db->order_by('id','DESC');
		}
	}


	public function insert(array $data)
	{
		$this->db->insert($this->_table, $data);
	}


	public function update($id = 0, array $data)
	{
		if ($id > 1 && $this->cek_id($id) == 1) 
		{
			$this->db->where("BINARY id='$id'", NULL, FALSE)->update($this->_table, $data);
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	public function delete($id = 0)
	{
		if ($id > 1 && $this->cek_id($id) == 1) 
		{
			$this->db->where('id', $id)->delete($this->_table);
			$scp = $this->db->where('id_parent', $id)->get($this->_table)->row_array();
			$this->db->where('id_parent', $scp['id_parent'])->update($this->_table, array('id_parent'=>'0'));

			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	public function get_category($id=0) 
	{
		$result = NULL;

		if ($this->cek_id($id)==1)
		{
			$query = $this->db->where('id', $id);
			$query = $this->db->get($this->_table);
			$result = $query->row_array();
		}

		return $result;
	}


	public function get_parent($id = 0)
	{
		$query = $this->db->select('id, title');
		$query = $this->db->where_not_in('id', ['1', $id]);
		$query = $this->db->order_by('id', 'DESC');
		$query = $this->db->get('t_category');
		$result = $query->result_array();
		return $result;
	}


	public function get_parent_title($id = 0)
	{
		if ($id > 1 && $this->cek_id($id) == 1) 
		{
			$query = $this->db->select('title');
			$query = $this->db->where('id', $id);
			$query = $this->db->get($this->_table);
			$result = $query->row_array();
			$parent_title = $result['title'];
		}
		else
		{
			$parent_title = '-';
		}

		return $parent_title;
	}


	public function cek_id($id = 0)
	{
		$id = xss_filter($id,'xss');

		$query = $this->db->select('id');
		$query = $this->db->where("BINARY id='$id'", NULL, FALSE);
		$query = $this->db->get($this->_table);
		$result = $query->num_rows();

		return $result;
	}


	public function cek_seotitle($seotitle)
	{
		$query = $this->db->select('id,seotitle');
		$query = $this->db->where("BINARY seotitle='$seotitle'", NULL, FALSE);
		$query = $this->db->get($this->_table);
		$query = $query->num_rows();

		if ($query == 0)
			return TRUE;
		else
			return FALSE;
	}

	
	public function cek_seotitle2($id, $seotitle)
	{
		$query = $this->db->select('id,seotitle');
		$query = $this->db->where("BINARY seotitle='$seotitle'", NULL, FALSE);
		$query = $this->db->get($this->_table);

		if (
		    $query->num_rows() == '1' && 
		    $query->row_array()['id'] == $id || 
		    $query->num_rows() != '1'
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