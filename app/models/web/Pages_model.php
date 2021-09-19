<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_model extends CI_Model
{
    public $vars;

    public function __construct()
    {
        parent::__construct();
    }


    public function check_seotitle($seotitle = '')
    {
        $result = false;

        $query = $this->db
            ->select('id')
            ->where("BINARY seotitle = '$seotitle'", null, false)
            ->where('active', 'Y')
            ->get('t_pages')
            ->num_rows();

        if ($query == 1) {
            $result = true;
        }
        
        return $result;
    }


    public function get_data($seotitle = '')
    {
        $query = $this->db
            ->select('title,content,picture')
            ->where("BINARY seotitle = '$seotitle'", null, false)
            ->where('active', 'Y')
            ->get('t_pages')
            ->row_array();

        return $query;
    }
} // End Class
