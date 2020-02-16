<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Install_model extends CI_Model {

    public function __construct() 
    {
        parent::__construct();
    }

    public function last_id() 
    {
        return $this->db->insert_id();
    }

    public function import_tables($file) 
    {
        $this->db->trans_off();

        $this->db->trans_start(TRUE);
        $this->db->trans_begin();
        
        $sql = file_get_contents($file) ;
        $this->db->query($sql);

        if ($this->db->trans_status() == TRUE) 
        {
            $this->db->trans_commit();
            return true;
        }
        else 
        {
            $this->db->trans_rollback();
            return false;
        }
    }

    public function insert_user($data) 
    {
        return $this->db->insert('t_user', $data);
    }

    public function insert_setting($data) 
    {
        return $this->db->insert('t_setting', $data);
    }
}