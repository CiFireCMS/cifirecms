<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages_model extends CI_Model {

	private $_table = 't_pages';
	private $_column_order = array(null, 'id', 'title','seotitle','active', null);
	private $_column_search = array('id', 'title', 'active');

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

	private function _all_pages()
	{
		$this->db->select('id,title,seotitle,active');
		$this->db->from($this->_table);

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
			$this->db->order_by('id', 'DESC');
		}
	}


	public function insert($data)
	{
		$query = $this->db->insert($this->_table, $data);
		return $query;
	}


	public function update($pk, $data)
	{
		$id = xss_filter(decrypt($pk),'sql');
		$query = $this->db->where('id', $id);
		$query = $this->db->update($this->_table, $data);
		return $query;
	}


	public function delete($id)
	{
		if ( $this->cek_id($id) > 0 ) 
		{
			$query = $this->db->where('id', $id);
			$query = $this->db->delete($this->_table);
			return TRUE;
		}
		else
			return FALSE;
	}


	public function get_pages($id) 
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get($this->_table);
		$query = $query->row_array();
		return $query;
	}


	public function cek_id($id=0)
	{
		$query = $this->db->select('id');
		$query = $this->db->where('id', $id);
		$query = $this->db->get($this->_table);
		$result = $query->num_rows();
		$int = (int)$result;
		return $int;
	}
	

	public function cek_seotitle($seotitle)
	{
		$query = $this->db
			     ->where('seotitle',$seotitle)
			     ->get($this->_table)
			     ->num_rows();
		return $query;
	}


	public function cek_seotitle2($id, $seotitle)
	{
		$query = $this->db
			     ->select('id,seotitle')
			     ->where('seotitle', $seotitle)
			     ->get($this->_table);

		if (
		    $query->num_rows() == '1' && 
		    $query->row_array()['id'] == $id || 
		    $query->num_rows() != '1'
		   ) 
		{
			return TRUE;
		}
		else 
			return FALSE;
	}
} // End class.