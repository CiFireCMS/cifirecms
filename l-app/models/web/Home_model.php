<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

	public $vars;
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get_headline()
	{
		$query = $this->db
			->select('
				t_post.title      AS post_title,
				t_post.seotitle   AS post_seotitle,
				t_post.picture,
				t_post.datepost,
				t_post.timepost,
				t_category.title  AS category_title
			')
			->join('t_category', 't_category.id = t_post.id_category', 'LEFT')
			->where('t_post.active', 'Y')
			->where('t_post.headline', 'Y')
			->order_by('RAND()')
			->get('t_post')
			->result_array();
		return $query;
	}
} // End class.