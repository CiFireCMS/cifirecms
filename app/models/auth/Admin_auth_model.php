<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_auth_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function cek_user($input_username = '')
    {
        $username = decrypt($input_username);
        $query = $this->db->where("BINARY username = '".$username."'", null, false);
        $query = $this->db->where('active', 'Y');
        $query = $this->db->get('t_user');
        return $query->num_rows();
    }


    public function cek_login($input)
    {
        $query = $this->db->where("BINARY username = '".$input['username']."'", null, false);
        $query = $this->db->where('active', 'Y');
        $query = $this->db->get('t_user');

        if ($query->num_rows() == 1) {
            $userdata = $query->row_array();

            if (decrypt($userdata['password']) == decrypt($input['password'])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function get_user($input)
    {
        $query = $this->db->where("BINARY username = '".$input['username']."'", null, false);
        $query = $this->db->get('t_user');
        $query = $query->row_array();
        return $query;
    }
} // End class.
