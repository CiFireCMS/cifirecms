<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Rss_model extends CI_Model {

	public $vars;

	public function __construct()
	{
		parent::__construct();
	}

	public function rss_lists()
	{
		$query = $this->db->select('title,seotitle');
		$query = $this->db->where_not_in('id',1);
		$query = $this->db->order_by('title','ASC');
		$query = $this->db->get('t_category');
		$result = $query->result_array();
		return $result;
	}

	public function get_category($seotitle)
	{
		return $this->db->where('seotitle', $seotitle)->get('t_category')->row_array();
	}


	public function category($param='')
	{
		$query = $this->db->select('
					 t_post.id              AS  post_id,
					 t_post.title           AS  post_title,
					 t_post.seotitle        AS  post_seotitle,
					 t_post.active          AS  post_active,
					 t_post.content         AS  post_content,
					 t_post.picture         AS  post_picture,
			         t_post.datepost,
			         t_post.timepost,
			         t_post.tag,
			         t_post.hits,

					 t_category.id           AS  category_id,
					 t_category.title        AS  category_title,
					 t_category.seotitle     AS  category_seotitle,
					 t_category.description     AS  category_description,
					 
					 t_user.id               AS  user_id,
					 t_user.name             AS  user_name
					');
		$query = $this->db->from('t_post');
		$query = $this->db->join('t_category', 't_category.id = t_post.id_category', 'left');
		$query = $this->db->join('t_user', 't_user.id = t_post.id_user', 'left');
		$query = $this->db->where('t_post.active', 'Y');
		$query = $this->db->where('t_category.seotitle', $param);
		$query = $this->db->order_by('t_post.id', 'DESC');
		$query = $this->db->get();
		$result = $query->result_array();

		return $result;
	}

	
	public function all_posts()
	{
		$query = $this->db->select('
					 t_post.id                 AS  post_id,
					 t_post.title              AS  post_title,
					 t_post.seotitle           AS  post_seotitle,
					 t_post.active             AS  post_active,
					 t_post.content            AS  post_content,
					 t_post.picture            AS  post_picture,
			         t_post.datepost,
			         t_post.timepost,
			         t_post.tag,
			         t_post.hits,

					 t_category.id              AS  category_id,
					 t_category.title           AS  category_title,
					 t_category.seotitle        AS  category_seotitle,
					 t_category.description     AS  category_description,
					 
					 t_user.id                  AS  user_id,
					 t_user.name                AS  user_name
					');
		$query = $this->db->from('t_post');
		$query = $this->db->join('t_category', 't_category.id = t_post.id_category', 'left');
		$query = $this->db->join('t_user', 't_user.id = t_post.id_user', 'left');
		$query = $this->db->where('t_post.active', 'Y');
		$query = $this->db->order_by('post_id', 'DESC');
		$query = $this->db->get();
		$result = $query->result_array();

		return $result;
	}
} // End class.