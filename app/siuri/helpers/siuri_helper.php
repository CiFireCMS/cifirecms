<?php

function dbconnect()
{
	$CI =& get_instance();

	$config = array(
		'port'     => $_SERVER['DB_PORT'],
		'hostname' => $_SERVER['DB_HOST'],
		'username' => $_SERVER['DB_USER'],
		'password' => $_SERVER['DB_PASS'],
		'database' => $_SERVER['DB_NAME'],
		'dbdriver' => 'mysqli',
		'dbprefix' => '',
		'pconnect' => FALSE,
		'db_debug' => (ENVIRONMENT !== 'production'),
		'cache_on' => FALSE,
		'cachedir' => '',
		'char_set' => 'utf8',
		'dbcollat' => 'utf8_general_ci',
		'swap_pre' => '',
		'encrypt'  => FALSE,
		'compress' => FALSE,
		'stricton' => FALSE,
		'failover' => array(),
		'save_queries' => TRUE
	);

	return $CI->load->database($config, true);
}