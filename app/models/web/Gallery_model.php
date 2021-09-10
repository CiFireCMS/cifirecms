<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery_model extends CI_Model {

	public $vars;
	
	public function __construct()
	{
		parent::__construct();
	}
	

	public function all_albums()
	{
		$query = $this->db->select();
		$query = $this->db->from('t_album');
		$query = $this->db->order_by('id','DESC');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	public function get_album($id='')
	{
		return $this->db->where('id', $id)->get('t_album')->row_array();
	}



	public function album_cover($id_album = '')
	{
		$result = '';
		if ( !empty($id_album) )
		{
			$query = $this->db->select('picture,title');
			$query = $this->db->from('t_gallery');
			$query = $this->db->where('id_album', $id_album);
			$query = $this->db->order_by('id','DESC');
			$query = $this->db->limit(1);
			$query = $this->db->get();
			$result = $query->row_array();
		}
		return $result;
	}


	public function get_gallery_images($id_album = '')
	{
		$result = [];
		if ( !empty($id_album) )
		{
			$query = $this->db->select('*');
			$query = $this->db->from('t_gallery');
			$query = $this->db->where('id_album', $id_album);
			$query = $this->db->order_by('id','DESC');
			$query = $this->db->get();
			$result = $query->result_array();
		}
		return $result;
	}

	public function all_galleri_images()
	{
		$query = $this->db->select('*');
		$query = $this->db->from('t_gallery');
		$query = $this->db->order_by('id','DESC');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

} // End class.