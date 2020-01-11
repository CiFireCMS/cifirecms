<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

	public $vars;
	private $table = 't_category';

	public function __construct()
	{
		parent::__construct();
	}


	public function check_seotitle($seotitle)
	{
		$result = FALSE;

		$query = $this->db
			->select('seotitle')
			->where("BINARY seotitle = '$seotitle'", NULL, FALSE)
			->where('active','Y')
			->get($this->table);

		$result = $query->num_rows();

		if ( $result >= 1 )
		{
			$result = TRUE;
		}
		
		return $result;
	}	


	public function get_data($seotitle) 
	{
		$query = $this->db
			->where("BINARY seotitle = '$seotitle'", NULL, FALSE)
			->where('active', 'Y')
			->get($this->table);

		return $query->row_array();
	}


	public function get_post($id, $batas, $posisi)
	{
		$id_category = $this->db
			->select('id')
			->where('id', $id)
			->or_where('id_parent', $id)
			->get($this->table)
			->result_array();

		// arrays_to_string() is function from helper.
		$range_id = explode(',', arrays_to_string($id_category, ','));

		$query = $this->db
			->select('
					 t_post.id           AS  post_id,
					 t_post.title        AS  post_title,
					 t_post.seotitle     AS  post_seotitle,
					 t_post.active       AS  post_active,
					 t_post.content,
					 t_post.picture,
			         t_post.datepost,
			         t_post.timepost,
			         t_post.tag,
			         t_post.hits,

					 t_category.id        AS  category_id,
					 t_category.title     AS  category_title,
					 t_category.seotitle  AS  category_seotitle,
					 t_user.id            AS  user_id,
					 t_user.name          AS  user_name
					')
			->from('t_post')
			->join('t_category', 't_category.id = t_post.id_category', 'left')
			->join('t_user', 't_user.id = t_post.id_user', 'left')
			->where('t_post.active', 'Y')
			->where_in('t_post.id_category', $range_id)
			->order_by('t_post.id', 'DESC')
			->limit($batas, $posisi)
			->get()
			->result_array();

		return $query;
	}


	public function total_category_post($id_category)
	{
		$query = $this->db
			->select('id')
			->where('active', 'Y')
			->where('id_category', $id_category)
			->get('t_post')
			->num_rows();

		return $query;
	}
} // End class.