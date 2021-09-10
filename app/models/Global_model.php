<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Global_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}


	public function insert($table, array $data)
	{
		$this->db->insert($this->_table, $data);
	}


	public function delete_by_id($table = '', $id = '')
	{
		$cek_id = $this->cek_id($table, $id);
		
		if ( $cek_id == 1) 
		{
			$this->db->where('id', $id)->get($table);
			return TRUE;
		}
		else
			return FALSE;
	}


	public function cek_id($table = '', $id = 0)
	{
		$int = 0;
		if ( $id!=0 && !empty($id) && !empty($table) )
		{
			$query = $this->db->where('id', $id)->get($table)->num_rows();
			
			if ( $query == 1 )
				$int = 1;
		}

		return $int;
	}


	public function get_theme_active()
	{
	   $query = $this->db->where('active','Y')->get('t_theme')->row_array();
	   return $query;
	}


	public function get_setting()
	{
		$query = $this->db->get('t_setting')->row_array();
		return $query;
	}
} // End class