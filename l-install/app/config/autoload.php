<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload['packages']  = array();
$autoload['libraries'] = array('encryption', 'session');
$autoload['drivers']   = array();
$autoload['helper']    = array('url', 'file', 'string', 'text', 'html', 'form', 'master_helper',);
$autoload['config']    = array();
$autoload['language']  = array();
$autoload['model']     = array('install_model');