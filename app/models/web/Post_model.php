<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

	public $vars;

	public function __construct()
	{
		parent::__construct();
	}


	public function cek_post($seotitle)
	{
		$segments = count($this->uri->segments);
		$seotitle = xss_filter($this->uri->segment($segments), 'xss');
		
		$thn = $this->uri->segment(1);
		$bln = $this->uri->segment(2);
		$hri = $this->uri->segment(3);

		if ( $segments == 4 )
		{
			$cdate = "$thn-$bln-$hri";
			$cek = $this->db
				->where('active', 'Y')
				->where('seotitle', $seotitle)
				->where('datepost', $cdate)
				->get('t_post')
				->num_rows();
		}
		elseif ( $segments == 3 )
		{
			$cdate = "$thn-$bln";
			$cek = $this->db
				->where('active', 'Y')
				->where('seotitle', $seotitle)
				->like('datepost', $cdate)
				->get('t_post')
				->num_rows();
		}
		elseif ($segments == 2)
		{
			if ($this->uri->segment(1) == get_setting('slug_title')) 
			{
				$cek = $this->db
					->where('active', 'Y')
					->where('seotitle', $seotitle)
					->get('t_post')
					->num_rows();
			}
			else
			{
				$cek = $this->db
					->where('active', 'Y')
					->where('seotitle', $seotitle)
					->like('datepost', $thn)
					->get('t_post')
					->num_rows();
			}
		}
		elseif ($segments == 1)
		{
			$cek = $this->db
				->where('active', 'Y')
				->where('seotitle', $seotitle)
				->get('t_post')
				->num_rows();
		}

		if ($cek == 1)
			return TRUE;
		else
			return FALSE;
	}


	public function id_post($seotitle) 
	{
		$query = $this->db
			->select('id')
			->from('t_post')
			->where("BINARY seotitle='$seotitle'", NULL, FALSE)
			->where('active', 'Y')
			->get();

		$id_post = $query->row_array()['id']; 
		
		return $id_post;
	}


	public function get_post($seotitle) 
	{
		$id_post = $this->id_post($seotitle);

		$query_post = $this->db
			->select('
						t_post.id             AS  post_id,
						t_post.title          AS  post_title,
						t_post.seotitle       AS  post_seotitle,
						t_post.active         AS  post_active,
						t_post.content,
						t_post.picture,
						t_post.image_caption,
						t_post.datepost,
						t_post.timepost,
						t_post.tag,
						t_post.hits,
						t_post.comment        AS  post_comment,
						t_category.id         AS  category_id,
						t_category.title      AS  category_title,
						t_category.seotitle   AS  category_seotitle,
						t_user.name           AS  author_name,
						t_user.photo          AS  author_photo,
						t_user.about          AS  author_about
					')
			->join('t_category', 't_category.id = t_post.id_category', 'left')
			->join('t_user', 't_user.id = t_post.id_user', 'left')
			->where('t_post.id', $id_post)
			->get('t_post');
		
		$post = $query_post->row_array();

		$query_comment = $this->db
			->where('id_post', $id_post)
			->where("active != 'N'")
			->get('t_comment')
			->num_rows();

		$qount_comment = array('comment' => $query_comment);
		$result = array_merge($post, $qount_comment);
		
		return $result;
	}


	public function prev_post($id = 0) 
	{
		$query = $this->db
			->select('id, title, seotitle')
			->where('id <',$id)
			->where('active','Y')
			->order_by('id','DESC')
			->limit(1)
			->get('t_post');

		if ( $query->num_rows() > 0 ) 
		{
			$result = $query->row_array();
		}
		else 
		{
			$result = FALSE;
		}

		return $result;
	}


	public function next_post($id = 0) 
	{
		$query = $this->db
			->select('id, title, seotitle')
			->where('id >',$id)
			->where('active','Y')
			->order_by('id','ASC')
			->limit(1)
			->get('t_post');

		if ( $query->num_rows() > 0 )
		{
			$result = $query->row_array();
		}
		else 
		{
			$result = FALSE;
		}

		return $result;
	}


	public function related_post($post_tags = '', $post_id = '', $limit = '')
	{
		$tags = (!empty($post_tags) ? $post_tags : 'no-tag');
		$pecah_tags  = explode(",",$tags);
		$count_tags = (int)count($pecah_tags)-1; 

		$query = $this->db->select('*');
		$query = $this->db->from('t_post');
		$query = $this->db->where('active', 'Y');
		$query = $this->db->where_not_in('id', $post_id);

		$query = $this->db->group_start();
		for ( $i=0; $i<=$count_tags; $i++ )
		{
			if ( $i > $count_tags )
				$query = $this->db->like('tag', $pecah_tags[$i]);
			else
				$query = $this->db->or_like('tag', $pecah_tags[$i]);
		}
		$query = $this->db->group_end();

		$query = $this->db->order_by('RAND()');
		$query = $this->db->limit($limit);
		$query = $this->db->get();

		if ( $query->num_rows() >= 2 )
		{
			$result = $query->result_array();
		}
		else
		{
			$query2 = $this->db->select('*');
			$query2 = $this->db->from('t_post');
			$query2 = $this->db->where('active', 'Y');
			$query2 = $this->db->where('id !=', $post_id);
			$query2 = $this->db->order_by('id', 'RANDOM');
			$query2 = $this->db->limit($limit);
			$query2 = $this->db->get();
			$result = $query2->result_array();
		}
		
		return $result;
	}


	public function insert_comment(array $data)
	{
		$this->db->insert('t_comment', $data);
	}


	public function hits($id_post, $hits)
	{
		$data = array('hits' => $hits);
		$this->db->where('id', $id_post)->update('t_post', $data);
	}
} // End class.