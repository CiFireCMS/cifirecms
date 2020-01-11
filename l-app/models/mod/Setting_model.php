<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model {

	private $_table = 't_setting';
	private $_column_order = array(null, 'id', 'groups', 'options', 'value');
	private $_column_search = array('id', 'groups', 'options', 'value');

	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Function datatable
	 *
	 * @param     string    $method (query method)
	 * @param     string    $mode ('data' or 'count')
	 * @return    void
	*/
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


	private function _table_settings()
	{
		$this->db->select('*');
		$this->db->from($this->_table);

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

				if ( count($this->_column_search)-1 == $i ) 
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
			$this->db->order_by('id','ASC');
		}
	}



	public function get_setting($parm_id = '')
	{
		$id = xss_filter($parm_id, 'sql');

		if ( $this->cek_id($id) == 1 )
		{
			$data = $this->db->where('id', $id)->get($this->_table)->row_array();
		}
		else
		{
			$data = NULL;
		}
		return $data;
	}


	public function insert(array $data)
	{
		return $this->db->insert($this->_table, $data);
	}


	public function update($options = '',$data)
	{
		return $this->db->where('options', $options)->update($this->_table, $data);
	}

	public function update_setting($pk, array $data)
	{
		$id = xss_filter($pk,'sql');
		if ($this->cek_id($id)==1)
		{
			$this->db->where('id', $id)->update($this->_table, $data);
			return TRUE;
		}
	}


	public function delete_list($pk = 0)
	{
		$id = xss_filter($pk, 'sql');

		if ($this->cek_id($id) == 1) 
		{
			$this->db->where('id', $id)->delete($this->_table);
			return TRUE;
		}

		else return FALSE;
	}



	public function cek_id($pk = 0)
	{
		$id = xss_filter($pk, 'sql');
		$return = 0;
		if ( $id != 0 && !empty($id) )
		{
			$query_count = $this->db
				->select('id')
				->where('id', $id)
				->get($this->_table)
				->num_rows();

			if ( $query_count == 1 )
				$return = 1;
			else
				$return = 0;
		}

		return $return;
	}


	public function valid_edit_options($pk='',$param_options='')
	{
		$id = xss_filter($pk,'sql');
		$options = xss_filter($param_options,'xss');

		$query = $this->db
			->select('id')
			->where('options',$options)
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