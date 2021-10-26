<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload['packages']  = array();

$autoload['libraries'] = array(
                                'database',
								'session', 
								'form_validation',
								'user_agent',
								'Cifire_Alert',
								'Cifire_Pagination',
								'Cifire_Menu'
							  );

$autoload['drivers']   = array();
$autoload['helper']    = array(
								'url', 
								'language', 
								'inflector',
								'form',
								'html',
								'security',
								'string',
								'file',
								'directory',
								'download',
								'text',
								'date', 
								'cifire_date', 
								'cifire'
							  );

$autoload['config']    = array();
$autoload['language']  = array(
                               'general/button_lang',
                               'general/ui_lang',
                               );
$autoload['model']     = array('global_model');