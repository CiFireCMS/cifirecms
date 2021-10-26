<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery_model extends CI_Model {

	public $vars;
	private $_table = 't_gallery';

	public function __construct()
	{
		parent::__construct();
	}


	public function insert($table,$data)
	{
		return $this->db->insert($table, $data);
	}


	public function update($id, $data)
	{
		return $this->db->where('id', $id)->update($this->_table, $data);
	}


	public function delete($id = '')
	{
		$this->db->where('id', $id)->delete('t_gallery');
	}


	public function delete_album($id = '')
	{
		$this->db->where('id', $id)->delete('t_album');
		$this->db->where('id_album', $id)->delete('t_gallery');
	}


	public function all_album()
	{
		$query = $this->db
			->where('active', 'Y')
			->order_by('id', 'DESC')
			->get('t_album')
			->result_array();
		return $query;
	}


	public function get_gallerys($id_album = '')
	{
		$query = $this->db
			->where('id_album', $id_album)
			->order_by('id', 'DESC')
			->get('t_gallery')
			->result_array();
		return $query; 
	}


	public function get_album($id_album = '')
	{
		$query = $this->db
			->where('id', $id_album)
			->get('t_album')
			->row_array();

		return $query; 
	}


	public function cek_id_album($id_album = '')
	{
		$query = $this->db
			->where('id', $id_album)
			->get('t_album')
			->num_rows();

		return $query; 
	}


	public function cek_id($id = '')
	{
		$num_rows = 0;
		
		if ( !empty($id) )
		{
			$query = $this->db->select('id');
			$query = $this->db->where('id', $id);
			$query = $this->db->get($this->_table);
			$num_rows = $query->num_rows();

			if ( $num_rows < 1 ) 
				$num_rows = 0;
		}
		
		return $num_rows;
	}


	public function cek_seotitle($seotitle)
	{
		$query = $this->db->select('id,seotitle');
		$query = $this->db->where("BINARY seotitle = '$seotitle'", NULL, FALSE);
		$query = $this->db->get($this->_table);
		$num_rows = $query->num_rows();

		if ( $num_rows == 0 ) 
			return TRUE;
		else
			return FALSE;
	}


	public function cek_seotitle2($id, $seotitle)
	{
		$query = $this->db->select('id,seotitle');
		$query = $this->db->where("BINARY seotitle = '$seotitle'", NULL, FALSE);
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