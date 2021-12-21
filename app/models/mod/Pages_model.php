<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages_model extends CI_Model {

	private $_table = 't_pages';
	private $_column_order = array(null, 'id', 'title', 'seotitle', null, null);
	private $_column_search = array('id', 'title', 'seotitle');

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
			$result =  $this->db->get()->num_rows();
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
			
			$result =  $query->result_array();
		}

		return $result;
	}


	private function _all_pages()
	{
		$this->db->select('id,title,seotitle,active');		
		$this->db->from($this->_table);

		$i = 0;
		foreach ( $this->_column_search as $item ) 
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

	
	
	public function insert_data($data)
	{
		$this->db->insert($this->_table,$data);
	}
	

	public function update_data($ID, array $data)
	{
		if ( $this->cek_id($ID) == 1 )
		{
			$this->db->where('id', $ID)->update($this->_table, $data);
			return TRUE;
		}

		return FALSE;
	}


	public function delete($ID = 0)
	{
		if ($ID > 1 && $this->cek_id($ID) == 1) 
		{
			$this->db->where('id', $ID)->delete($this->_table);
			return TRUE;
		}

		return FALSE;
	}


	public function get_pages($ID)
	{
		return $this->db->where('id', $ID)->get($this->_table)->row_array();
	}


	public function cek_id($ID = 0)
	{
		$int = 0;
		$query = $this->db->select('id')->where('id', $ID)->get($this->_table);
		$int = $query->num_rows();
		return $int;
	}


	public function cek_seotitle($seotitle = '')
	{
		$query = $this->db->where("BINARY seotitle = '$seotitle'", NULL, FALSE)->get($this->_table);
		$result = $query->num_rows();

		if ( $result == 0 ) return TRUE;
		return FALSE;
	}


	public function cek_seotitle2($ID, $seotitle)
	{
		$cek = $this->db->where("BINARY seotitle = '$seotitle'", NULL, FALSE)->get($this->_table);
		if (
			$cek->num_rows() == 1 && 
			$cek->row_array()['id'] == $ID || 
			$cek->num_rows() != 1
		   ) 
		{
			return TRUE;
		}

		return FALSE;
	}	
} // End class.