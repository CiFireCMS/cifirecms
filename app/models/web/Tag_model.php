<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag_model extends CI_Model {

	public $vars;

	public function __construct()
	{
		parent::__construct();
	}

	public function cek_tag($seotitle = NULL)
	{
		$query = $this->db->where('seotitle', $seotitle);
		$query = $this->db->get('t_tag');
		$num_rows = $query->num_rows();

		if ( $num_rows == 1 )
			return TRUE;
		else
			return FALSE;
	}

	public function get_tag($seotitle)
	{
		$query = $this->db->where('seotitle', $seotitle);
		$query = $this->db->get('t_tag');
		return $query->row_array();
	}


	public function get_post($tag, $batas, $posisi) 
	{
		$query = $this->db->select('
			t_post.id            AS  post_id,
			t_post.title         AS  post_title,
			t_post.seotitle      AS  post_seotitle,
			t_post.active        AS  post_active,
			t_post.content,
			t_post.picture,
			t_post.datepost,
			t_post.timepost,
			t_post.tag,
			t_post.hits,
			t_category.id        AS  category_id,
			t_category.title     AS  category_title,
			t_category.seotitle  AS  category_seotitle,
			t_user.name          AS  user_name
		');
		$query = $this->db->join('t_category', 't_category.id = t_post.id_category', 'LEFT');
		$query = $this->db->join('t_user', 't_user.id = t_post.id_user', 'LEFT');
		$query = $this->db->where('t_post.active', 'Y');
		$query = $this->db->like('t_post.tag', $tag);
		$query = $this->db->order_by('t_post.id', 'DESC');
		$query = $this->db->limit($batas, $posisi);
		$query = $this->db->get('t_post');
		$result = $query->result_array();

		return $result;
	}


	public function jml_data($seotitle)
	{
		$query = $this->db->select('id');
		$query = $this->db->where('active', 'Y');
		$query = $this->db->like('tag', $seotitle);
		$query = $this->db->get('t_post');
		$result = $query->num_rows();

		return $result;
	}
} // End class.