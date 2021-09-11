<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH.'controllers/'.FWEB.'/Home.php');

class HomeIndex extends Home
{
    public function __construct()
    {
        parent::__construct();
    }
} // End Class
