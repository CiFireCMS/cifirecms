<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_model extends CI_Model {

	public $vars;

	public function __construct()
	{
		parent::__construct();
	}


	public function SearchORI($kata, $sort, $batas, $posisi)
	{
		$pisah_kata = explode(" ",$kata);
		$jml_kata = (integer)count($pisah_kata)-1;

		$query = $this->db->select('*');
		$query = $this->db->from('t_post');

		for ($i=0; $i<=$jml_kata; $i++)
		{
			$query = $this->db->or_group_start();
			  $query = $this->db->like('title', $pisah_kata[$i]);
			  $query = $this->db->or_like('content', $pisah_kata[$i]);
			  $query = $this->db->or_like('tag', $pisah_kata[$i]);
			$query = $this->db->group_end();
			
			$query = $this->db->where('active', 'Y');
		}

		$query = $this->db->order_by('id', 'DESC');
		$query = $this->db->limit($batas,$posisi);
		$query = $this->db->get();

		$result = $query->result_array();

		return $result;
	}


	public function Search($kata, $sort, $batas, $posisi)
	{
		$pisah_kata = explode(" ",$kata);
		$jml_kata = (integer)count($pisah_kata)-1;

		$query = $this->db->select('
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
		                           ');
		$query = $this->db->from('t_post');
		$query = $this->db->join('t_category', 't_category.id = t_post.id_category', 'left');
		$query = $this->db->join('t_user', 't_user.id = t_post.id_user', 'left');

		for ($i=0; $i<=$jml_kata; $i++)
		{
			$query = $this->db->or_group_start();
			  $query = $this->db->like('t_post.title', $pisah_kata[$i]);
			  $query = $this->db->or_like('t_post.content', $pisah_kata[$i]);
			  $query = $this->db->or_like('t_post.tag', $pisah_kata[$i]);
			$query = $this->db->group_end();
			
			$query = $this->db->where('t_post.active', 'Y');
		}

		$query = $this->db->order_by('t_post.id', 'DESC');
		$query = $this->db->limit($batas, $posisi);
		$query = $this->db->get();

		$result = $query->result_array();
		return $result;
	}



	public function jml_data($kata)
	{
		$pisah_kata = explode(" ",$kata);
		$jml_kata = (integer)count($pisah_kata)-1;

		$query = $this->db->select('id');
		$query = $this->db->from('t_post');

		for ($i=0; $i<=$jml_kata; $i++)
		{
			$query = $this->db->or_group_start();
			  $query = $this->db->like('title', $pisah_kata[$i]);
			  $query = $this->db->or_like('content', $pisah_kata[$i]);
			  $query = $this->db->or_like('tag', $pisah_kata[$i]);
			$query = $this->db->group_end();
			
			$query = $this->db->where('active', 'Y');
		}

		$query = $this->db->get();

		$result = $query->num_rows();
		
		return $result;
	}
} // End Class