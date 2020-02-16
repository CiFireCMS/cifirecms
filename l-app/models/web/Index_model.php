<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Index_model extends CI_Model {

	public $vars;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * get headline posts
	 * 
	 * @param    $limit      string
	 * @param    $order_by   string
	 * @return   array  
	 * 
	 */
	public function get_headline($limit = '5', $order_by = 'post_id,DESC')
	{
		$query = $this->db->select('
									t_post.id         AS post_id,
									t_post.title      AS post_title,
									t_post.seotitle   AS post_seotitle,
									t_post.content    AS post_content,
									t_post.picture,
									t_post.datepost,
									t_post.timepost,
									t_category.id     AS category_id,
									t_category.title  AS category_title
									');
		$query = $this->db->join('t_category', 't_category.id = t_post.id_category', 'LEFT');
		$query = $this->db->where('t_post.active', 'Y');
		$query = $this->db->where('t_post.headline', 'Y');

		$exLimit = explode(',', $limit);

		if ( count($exLimit) > 1 )
		{
			$query = $this->db->limit((int)$exLimit[0], (int)$exLimit[1]);
		}
		else
		{
			$query = $this->db->limit($limit);
		}

		if ( $order_by != 'RAND()' )
		{
			$xo = explode(',', $order_by);
			$query = $this->db->order_by($xo[0], $xo[1]);
		}
		else
		{
			$query = $this->db->order_by($order_by);
		}
		
		$query = $this->db->get('t_post');
		$result = $query->result_array();

		return $result;
	}


	public function popular_post($interval = NULL, $limit = '5', $order_by='post_hits,DESC')
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

		if ($interval === 'week')
		{
			$query = $this->db->where('date(t_post.datepost) > DATE_SUB(NOW(), INTERVAL 1 WEEK)', NULL, FALSE);
		} 

		if ($interval === 'month')
		{
			$query = $this->db->where('MONTH(t_post.datepost)', date('m'));
		}

		if ($interval === 'year')
		{
			$query = $this->db->where('YEAR(t_post.datepost)', date('Y'));
		}

		$exLimit = explode(',', $limit);
		
		if ( count($exLimit) > 1 )
		{
			$query = $this->db->limit((int)$exLimit[0], (int)$exLimit[1]);
		}
		else
		{
			$query = $this->db->limit($limit);
		}

		if ( $order_by != 'RAND()' )
		{
			$xo = explode(',', $order_by);
			$query = $this->db->order_by($xo[0], $xo[1]);
		}
		else
		{
			$query = $this->db->order_by($order_by);
		}

		$query = $this->db->get('t_post');
		$result = $query->result_array();

		return $result;
	}


	public function latest_post($limit = '5', $order_by = 'post_id,DESC')
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

		if ( $order_by != 'RAND()' )
		{
			$xo = explode(',', $order_by);
			$query = $this->db->order_by($xo[0], $xo[1]);
		}
		else
		{
			$query = $this->db->order_by($order_by);
		}

		$exLimit = explode(',', $limit);
		
		if ( count($exLimit) > 1 )
		{
			$query = $this->db->limit((int)$exLimit[0], (int)$exLimit[1]);
		}
		else
		{
			$query = $this->db->limit($limit);
		}
		
		$query = $this->db->get('t_post');
		$result = $query->result_array();

		return $result;
	}


	public function index_post($batas, $posisi)
	{
		$query = $this->db
			->select('
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
					 t_user.id            AS  user_id,
					 t_user.name          AS  user_name,
					')
			->join('t_category', 't_category.id = t_post.id_category', 'left')
			->join('t_user', 't_user.id = t_post.id_user', 'left')
			->where('t_post.active', 'Y')
			->order_by('t_post.datepost','DESC')
			->order_by('t_post.timepost','DESC')
			->limit($batas, $posisi)
			->get('t_post');

		return $query->result_array();
	}


	public function total_post()
	{
		$query = $this->db->select('id');
		$query = $this->db->where('active', 'Y');
		$query = $this->db->get('t_post');
		return $query->num_rows();
	}


	public function get_post_lmit_by_category($id_category = '', array $limit)
	{
		$query = $this->db
			->select('
			         t_post.title         AS  post_title,
			         t_post.seotitle      AS  post_seotitle,
			         t_post.picture,
			         t_post.datepost,
			         t_post.timepost,
			         t_post.content,
			         t_category.id        AS  category_id,
			         t_category.title     AS  category_title,
			         t_category.seotitle  AS  category_seotitle
			         ')
			->from('t_post')
			->join('t_category', 't_category.id=t_post.id_category', 'LEFT')
			->where('t_post.active', 'Y')
			->where('t_post.id_category', $id_category)
			->or_where('t_category.id_parent', $id_category)
			->order_by('t_post.id','DESC');

		if ( count($limit) == 1 )
		{
			$query = $this->db->limit($limit[0]);
		}
		else 
		{
			$query = $this->db->limit($limit[0], $limit[1]);
		}

		$result = $this->db->get();
		return $result;
	}


	public function get_category_by($col = 'id', $val = '1', $param = 'row')
	{
		$query = $this->db->where($col, $val);
		$query = $this->db->order_by('id', 'DESC');
		$query = $this->db->get('t_category');

		if ( $param == 'result' )
		{
			$result = $query->result_array();
		}
		
		if ( $param == 'row' )
		{
			$result = $query->row_array();
		}

		return $result;
	}


	public function get_comments($limit=4)
	{
		$query = $this->db
			->where('active','Y')
			->order_by('id','DESC')
			->get('t_comment')
			->result_array();
		return $query;
	}
} // End class.