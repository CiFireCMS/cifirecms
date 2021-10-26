<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mail_model extends CI_Model {

	private $_table = 't_mail';

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


	protected function _inbox()
	{
		$column_search = array('id', 'name', 'email', 'subject ', 'message');
		$column_order = array(null, 'id', 'id', 'name', 'subject', 'date', 'active', null);

		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('box','in');

		$i = 0;	
		foreach ($column_search as $item) 
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
			$field = xss_filter($column_order[$this->input->post('order')['0']['column']],'xss');
			$value = xss_filter($this->input->post('order')['0']['dir'],'xss');
			$this->db->order_by($field,$value);
		}
		else 
		{
			$this->db->order_by('id', 'DESC');
		}
	}


	protected function _outbox()
	{
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('box','out');

		$i = 0;	
		$column_search = array('id', 'name', 'email', 'subject ', 'message');
		foreach ($column_search as $item) // loop column 
		{
			if ( $this->input->post('search')['value'] ) // if datatable send POST for search
			{
				if ( $i === 0 ) // first loop
				{
					$this->db->group_start();
					$this->db->like($item, $this->input->post('search')['value']);
				}
				else
				{
					$this->db->or_like($item, $this->input->post('search')['value']);
				}

				// last loop
				if ( count($this->column_search_out) - 1 == $i )
				{
					$this->db->group_end(); //close bracket
				}
			}

			$i++;
		}
		
		if ( !empty($this->input->post('order')) ) 
		{
			$column_order = array(null,'id', 'id', 'name', 'subject', 'date', 'active');
			$field = xss_filter($column_order[$this->input->post('order')['0']['column']],'xss');
			$value = xss_filter($this->input->post('order')['0']['dir'],'xss');
			$this->db->order_by($field,$value);
		}
		else 
		{
			$this->db->order_by('id', 'DESC');
		}
	}


	public function insert(array $data)
	{
		$this->db->insert($this->_table, $data);
	}


	public function update($id = 0, array $data)
	{
		if ( $this->cek_id($id) == 1 ) 
		{
			$this->db->where('id',$id);
			$this->db->update($this->_table, $data);
			return TRUE;
		}
		else
			return FALSE;
	}


	public function delete($id = 0)
	{
		if ( $this->cek_id($id) == 1 ) 
		{
			$this->db->where('id', $id);
			$this->db->delete($this->_table);
			$respon = TRUE;
		}
		else
		{
			$respon = FALSE;
		}

		return $respon;
	}


	public function all_message() 
	{
		$query = $this->db->order_by('id','DESC')->get($this->_table)->result_array();
		return $query;
	}


	public function get_message($id = 0) 
	{
		if ($this->cek_id($id) == 1 )
		{
			$num_row = $this->db->where('id',$id)->get($this->_table)->row_array();
			return $num_row;
		}
		else
		{
			show_404();
		}
	}


	public function get_mail($id = 0)
	{
		if ( $this->cek_id($id) == 1 )
			return $this->db->where('id',$id)->get($this->_table)->row_array();
		else
			return NULL;
	}


	public function cek_id($id = 0)
	{
		if ( empty($id) || $id == 0 )
			return 0;
		
		else
		{		
			$query = $this->db
				->select('id')
				->where('id',$id)
				->get($this->_table)
				->num_rows();

			if ( $query == 1 )
				return $query;
			else
				return 0;
		}
	}
} // End class.