<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag_model extends CI_Model {

	private $_table = 't_tag';
	private $_column_order = array(null, 'id', 'title', 'tag_count', null);
	private $_column_search = array('title');

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



	private function _all_tag()
	{
		$this->db->select('
		                  t_tag.*, 
		                  COUNT(t_post.id) AS tag_count
		                 ');
		
		$this->db->from($this->_table);
		$this->db->join('t_post', "t_post.tag LIKE CONCAT('%',t_tag.seotitle,'%')", 'LEFT');

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
			$this->db->order_by('t_tag.id', 'DESC');
		}

		$this->db->group_by('t_tag.id');
	}


	
	public function insert(array $data)
	{
		$this->db->insert($this->_table, $data);
	}


	public function update($id, array $data)
	{
		$this->db->where('id', $id)->update($this->_table, $data);
	}


	public function delete($id = 0)
	{
		if ($this->cek_id($id) == 1) 
		{
			$this->db->where('id', $id)->delete($this->_table);
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	public function get_tags($id) 
	{
		$query = $this->db->where('id',$id);
		$query = $this->db->get($this->_table);
		$result = $query->row_array();
		return $result;
	}


	public function cek_seotitle($seotitle)
	{
		$query = $this->db->where('seotitle', $seotitle);
		$query = $this->db->get($this->_table);
		$result = $query->num_rows();

		if ( $result == 0 )
			return TRUE;
		else 
			return FALSE;
	}


	public function cek_seotitle2($id, $seotitle)
	{
		$query = $this->db->where('seotitle', $seotitle);
		$query = $this->db->get($this->_table);
		
		if ( $query->num_rows() == 1 && $query->row_array()['id'] == $id || $query->num_rows() != 1 ) 
			return TRUE;
		else 
			return FALSE;
	}


	public function cek_id($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get($this->_table);
		$result = $query->num_rows();

		if ( $result < 1 )
			$result = 0;
		
		return $result;
	}
} // End class.