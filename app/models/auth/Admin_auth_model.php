<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_auth_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }


    public function cek_user($input_username = '')
    {
        $username = decrypt($input_username);
        $query = $this->db->where("BINARY username = '".$username."'", NULL, FALSE);
        $query = $this->db->where('active','Y');
        $query = $this->db->get('t_user');
        return $query->num_rows();
    }


    public function cek_login($input)
    {
    	$query = $this->db->where("BINARY username = '".$input['username']."'", NULL, FALSE);
        $query = $this->db->where('active','Y');
        $query = $this->db->get('t_user');

        if ( $query->num_rows() == 1 )
        {
            $userdata = $query->row_array();

            if ( decrypt($userdata['password']) == decrypt($input['password']) )
                return TRUE;
            else
                return FALSE;
        }
        else
        {
            return FALSE;
        }
    }


    public function get_user($input)
    {
        $query = $this->db->where("BINARY username = '".$input['username']."'", NULL, FALSE);
        $query = $this->db->get('t_user');
        $query = $query->row_array();
        return $query;
    }
} // End class.