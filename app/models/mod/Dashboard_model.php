<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}


	public function card($param)
	{
		$result = '';
		if ($param == 'post') {$result = $this->db->select('id')->get('t_post')->num_rows();}
		if ($param == 'category') {$result = $this->db->select('id')->get('t_category')->num_rows();}
		if ($param == 'tag') {$result = $this->db->select('id')->get('t_tag')->num_rows();}
		if ($param == 'pages') {$result = $this->db->select('id')->get('t_pages')->num_rows();}
		if ($param == 'theme') {$result = $this->db->select('id')->get('t_theme')->num_rows();}
		if ($param == 'component') {$result = $this->db->select('id')->get('t_component')->num_rows();}
		if ($param == 'mail') {$result = $this->db->select('id')->get('t_mail')->num_rows();}
		if ($param == 'user') {$result = $this->db->select('id')->get('t_user')->num_rows();}
		return $result;
	}

	public function popular_post($interval = 'year', $limit = '5')
	{
		$query = $this->db->select('
					 t_post.id            AS  post_id,
					 t_post.title         AS  post_title,
					 t_post.seotitle      AS  post_seotitle,
					 t_post.active        AS  post_active,
					 t_post.content       AS  post_content,
					 t_post.picture       AS  post_picture,
			         t_post.datepost      AS  post_datepost,
			         t_post.timepost      AS  post_timepost,
			         t_post.tag           AS  post_tag,
			         t_post.hits          AS  post_hits,

					 t_category.id        AS  category_id,
					 t_category.title     AS  category_title,
					 t_category.seotitle  AS  category_seotitle,

					 t_user.id            AS  user_id,
					 t_user.name          AS  user_name,
					');
			$query = $this->db->join('t_category', 't_category.id = t_post.id_category', 'left');
			$query = $this->db->join('t_user', 't_user.id = t_post.id_user', 'left');
			$query = $this->db->where('t_post.active', 'Y');

			if ($interval == 'week')
			{
				$query = $this->db->where('date(t_post.datepost) > DATE_SUB(NOW(), INTERVAL 1 WEEK)', NULL, FALSE);
			} 
			if ($interval == 'month')
			{
				$query = $this->db->where('MONTH(t_post.datepost)', date('m'));
			}
			if ($interval == 'year')
			{
				$query = $this->db->where('YEAR(t_post.datepost)', date('Y'));
			}

			$query = $this->db->order_by('t_post.hits','DESC');
			$query = $this->db->limit($limit);
			$query = $this->db->get('t_post');

		return $query->result_array();
	}


} // End class.