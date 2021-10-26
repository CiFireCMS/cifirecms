<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_model extends CI_Model {

	public $vars;
	private $_table = 't_component';
	private $_column_order = array('name', 'class', null);
	private $_column_search = array('id', 'name');

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



	private function _all_component()
	{
		$this->db->select('id,name,class');
		$this->db->from($this->_table);

		$i = 0;	
		foreach ($this->_column_search as $item) 
		{
			if ( $this->input->post('search')['value'] )
			{
				$search_key = xss_filter($this->input->post('search')['value'], 'xss');
				$search_key = trim($search_key);
				if ( $i === 0 )
				{
					$this->db->group_start();
					$this->db->like($item, $search_key);
				}
				else
				{
					$this->db->like($item, $search_key);
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
			$field = xss_filter($this->_column_order[$this->input->post('order')['0']['column']],'xss');
			$value = xss_filter($this->input->post('order')['0']['dir'],'xss');
			$this->db->order_by($field,$value);
		}
		else
		{
			$this->db->order_by('id','DESC');
		}
	}



	public function get_modul($id = 0)
	{
		if ( $this->cek_id($id) == 1 )
		{
			$query = $this->db->where('id',$id)->get($this->_table)->row_array();
			return $query;
		}
		else
		{
			return FALSE;
		}
	}


	public function insert($data)
	{
		$query = $this->db->insert($this->_table, $data);
		
		if ( $query == FALSE )
		{
			return FALSE;
		}
		else 
		{
			return TRUE;
		}
	}


	public function delete($id = 0, $table_name = '')
	{
		if ( !empty($table_name) && $this->cek_id($id) == 1 ) 
		{
			$this->load->dbforge();
			
			if ( $this->dbforge->drop_table($table_name, TRUE) )
			{
				$this->db->where('id', $id)->delete($this->_table);
				return TRUE;
			}
			else 
			{
				return FALSE;
			}
			
		}
		else 
		{
			return FALSE;
		}
	}


	public function cek_id($id = 0)
	{
		$query = $this->db->select('id');
		$query = $this->db->where('id', $id);
		$query = $this->db->get($this->_table);
		$query = $query->num_rows();
		$int = (int)$query;
		return $int;
	}
} // End Class.