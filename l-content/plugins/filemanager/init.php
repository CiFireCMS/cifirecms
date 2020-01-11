<?php
$f_application = 'l-app';
$f_content     = 'l-content';
$f_system      = 'l-system';

$d_s = DIRECTORY_SEPARATOR;
define('ENVIRONMENT', 'production');
define('BASEPATH', dirname(dirname(dirname(dirname(__FILE__)))));
define('APPPATH', BASEPATH . $d_s . $f_application . $d_s);
define('LIBPATH', BASEPATH . "{$d_s}{$f_system}{$d_s}libraries{$d_s}Session{$d_s}");
define('CONTENTPATH', BASEPATH . $d_s . $f_content . $d_s);

require_once LIBPATH . 'Session_driver.php';
require_once LIBPATH . "drivers{$d_s}Session_files_driver.php";
require_once BASEPATH . "{$d_s}{$f_system}{$d_s}core{$d_s}Common.php";

$config = get_config();

if (empty($config['sess_save_path'])) 
{
    $config['sess_save_path'] = rtrim(ini_get('session.save_path'), '/\\');
}

$config = array(
    'cookie_lifetime' => $config['sess_expiration'],
    'cookie_name'     => $config['sess_cookie_name'],
    'cookie_path'     => $config['cookie_path'],
    'cookie_domain'   => $config['cookie_domain'],
    'cookie_secure'   => $config['cookie_secure'],
    'expiration'      => $config['sess_expiration'],
    'match_ip'        => $config['sess_match_ip'],
    'save_path'       => $config['sess_save_path'],
    '_sid_regexp'     => '[0-9a-v]{32}',
);

$class = new CI_Session_files_driver($config);

if (is_php('5.4')) 
{
    session_set_save_handler($class, TRUE);
} 
else 
{
    session_set_save_handler(
        array($class, 'open'),
        array($class, 'close'),
        array($class, 'read'),
        array($class, 'write'),
        array($class, 'destroy'),
        array($class, 'gc')
    );
    register_shutdown_function('session_write_close');
}

session_name($config['cookie_name']);