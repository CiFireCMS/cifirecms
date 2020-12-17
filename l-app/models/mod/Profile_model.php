<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends CI_Model {

	private $_table = 't_user';
	private $_id;

	public function __construct()
	{
		parent::__construct();
		$this->_id = decrypt(login_key());
	}


	public function update(array $data)
	{
		if ( $this->cek_id($this->_id) > 0 )
			$this->db->where('id', $this->_id)->update($this->_table, $data);
	}



	public function get_data() 
	{
		$query = $this->db
			->select('
			         t_user.id          AS  user_id,
				     t_user.username    AS  user_username,
				     t_user.password    AS  user_password,
				     t_user.email       AS  user_email,
				     t_user.name        AS  user_name,
				     t_user.gender      AS  user_gender,
				     t_user.birthday    AS  user_birthday,
				     t_user.about       AS  user_about,
				     t_user.address     AS  user_address,
				     t_user.tlpn        AS  user_tlpn,
				     t_user.photo       AS  user_photo
				    ');
		$query = $this->db->where('t_user.id', $this->_id);
		$query = $this->db->get($this->_table);
		$result = $query->row_array();

		if ( $query->num_rows() == 1 )
			return $result;
		else
			show_404();
	}


	public function get_photo2($id) 
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get($this->_table);
		$result = $query->row_array();
		$photo = $result['photo'];
		return $photo;
	}


	public function get_photo()
	{
		if ( $this->cek_id($this->_id) > 0 )
		{
			$query = $this->db->where('id', $this->_id);
			$query = $this->db->get('t_user');
			$result = $query->row_array();
			$photo = $result['photo'];
			return $photo;
		}
		else 
		{
			return NULL;
		}
	}


	public function cek_id($id)
	{
		$query = $this->db->select('id');
		$query = $this->db->where('id', $id);
		$query = $this->db->get($this->_table);
		$result = $query->num_rows();

		if ( $result < 1 )
			$result = 0;
		
		return $result;
	}


	public function cek_email($id, $email)
	{
		$query = $this->db
			->select('id')
			->where("BINARY email = '$email'", NULL, FALSE)
			->get($this->_table);

		if (
		    $query->num_rows() == 1 && 
		    $query->row_array()['id'] == $id || 
		    $query->num_rows() == 0
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