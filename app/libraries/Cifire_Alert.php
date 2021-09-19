<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cifire_Alert
{
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('session');
    }


    public function set($alert_name = '', $alert_type = '', $alert_content = '', $timeout=true)
    {
        $session_alert = 'alert_'.$alert_name;
        $this->CI->session->set_flashdata($session_alert, array($alert_type,$alert_content,$timeout));
    }


    public function show($alert_name = '', $type = '', $content = '', $timeout=true, $static = false)
    {
        $sesname = 'alert_'.$alert_name;

        $alert = '';

        if (!empty($this->CI->session->flashdata($sesname))) {
            $ses = $this->CI->session->flashdata($sesname);
            $time = isset($ses[2])?$ses[2]:$timeout;
            $alert = $this->alert($ses[0], $ses[1], $time);
        }

        if ($static==true) {
            $alert = $this->alert($type, $content, $timeout);
        }
        
        echo $alert;
    }


    private function alert($type = '', $content = '', $tmieout=true)
    {
        $scriptTmieout = $tmieout==true?'<script>$("#alert-timeout").delay(10000).slideUp(100, function() {$(this).alert("close");$(this).remove();});</script>':'';
        $alert = '<div id="alert-timeout" class="alert alert-'.$type.' alert-styled-left alert-arrow-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button>'.$content.$scriptTmieout.'</div>';
        return $alert;
    }
} // End class
