<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function build_version($ver = '2.0.1')
{
	return $ver;
}


function delete_folder($path) {
	if (!file_exists($path)) {
		return false;
	}

	if (is_file($path) || is_link($path)) {
		return unlink($path);
	}

	$stack = array($path);

	while ($entry = array_pop($stack)) {
		if (is_link($entry)) {
			unlink($entry);
			continue;
		}

		if (@rmdir($entry)) {
			continue;
		}

		$stack[] = $entry;
		$dh = opendir($entry);

		while(false !== $child = readdir($dh)) {
			if ($child === '.' || $child === '..') {
				continue;
			}

			$child = $entry . DIRECTORY_SEPARATOR . $child;
			
			if (is_dir($child) && !is_link($child)){
				$stack[] = $child;
			} else {
				unlink($child);
			}
		}

		closedir($dh);
	}

	return true;
}


function cdb($conf) {
	$db_host = $conf['db_host'];
	$db_user = $conf['db_user'];
	$db_pass = $conf['db_pass'];
	$db_name = $conf['db_name'];
	$db_port = $conf['db_port'];

	$date = date('d-M-Y h:i:s');
	$build_version = build_version();

$content = <<<EOS
<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------
  | DATABASE CONNECTIVITY SETTINGS
  | -------------------------------------------------------------------
  | This file will contain the settings needed to access your database.
  |
  | For complete instructions please consult the 'Database Connection'
  | page of the User Guide.
  |
  | -------------------------------------------------------------------
  | EXPLANATION OF VARIABLES
  | -------------------------------------------------------------------
  |
  |	['dsn']      The full DSN string describe a connection to the database.
  |	['hostname'] The hostname of your database server.
  |	['username'] The username used to connect to the database
  |	['password'] The password used to connect to the database
  |	['database'] The name of the database you want to connect to
  |	['dbdriver'] The database driver. e.g.: mysqli.
  |			     Currently supported:
  |				 cubrid, ibase, mssql, mysql, mysqli, oci8,
  |				 odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
  |	['dbprefix'] You can add an optional prefix, which will be added
  |				 to the table name when using the  Query Builder class
  |	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
  |	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
  |	['cache_on'] TRUE/FALSE - Enables/disables query caching
  |	['cachedir'] The path to the folder where cache files should be stored
  |	['char_set'] The character set used in communicating with the database
  |	['dbcollat'] The character collation used in communicating with the database
  |				 NOTE: For MySQL and MySQLi databases, this setting is only used
  | 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
  |				 (and in table creation queries made with DB Forge).
  | 				 There is an incompatibility in PHP with mysql_real_escape_string() which
  | 				 can make your site vulnerable to SQL injection if you are using a
  | 				 multi-byte character set and are running versions lower than these.
  | 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
  |	['swap_pre'] A default table prefix that should be swapped with the dbprefix
  |	['autoinit'] Whether or not to automatically initialize the database.
  |	['encrypt']  Whether or not to use an encrypted connection.
  |	['compress'] Whether or not to use client compression (MySQL only)
  |	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
  |							- good for ensuring strict SQL while developing
  |	['failover'] array - A array with 0 or more data for connections if the main should fail.
  |	['save_queries'] TRUE/FALSE - Whether to "save" all executed queries.
  | 				NOTE: Disabling this will also effectively disable both
  | 				\$this->db->last_query() and profiling of DB queries.
  | 				When you run a query, with this setting set to TRUE (default),
  | 				CodeIgniter will store the SQL statement for debugging purposes.
  | 				However, this may cause high memory usage, especially if you run
  | 				a lot of SQL queries ... disable this to avoid that problem.
  |
  | The \$active_group variable lets you choose which connection group to
  | make active.  By default there is only one group (the 'default' group).
  |
  | The \$query_builder variables lets you determine whether or not to load
  | the query builder class.
  |
  | Install date      : {$date}
  | CiFireCMS Version : {$build_version}
  | @link https://github.com/CiFireCMS/cifirecms
 */

\$active_group  = 'default';
\$query_builder = TRUE;

\$_dbhost = '{$db_host}';
\$_dbuser = '{$db_user}';
\$_dbpass = '{$db_pass}';
\$_dbname = '{$db_name}';
\$_dbport = '{$db_port}';

\$db['default'] = array(
	'dsn'	   => "mysql:host=\$_dbhost;port=\$_dbport;dbname=\$_dbname;charset=utf8;",
	'hostname' => \$_dbhost,
	'username' => \$_dbuser,
	'password' => \$_dbpass,
	'database' => \$_dbname,
	'dbdriver' => 'pdo',
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

\$db['mysqli'] = array(
	'port'     => \$_dbport,
	'hostname' => \$_dbhost,
	'username' => \$_dbuser,
	'password' => \$_dbpass,
	'database' => \$_dbname,
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
EOS;
return $content;
}


function cfile($data) {
	$site_url = $data['site_url'];
	$en_key   = $data['key'];
	$db_host  = $data['db_host'];
	$db_name  = $data['db_name'];
	$db_user  = $data['db_user'];
	$db_pass  = $data['db_pass'];

	$date = date('d-M-Y h:i:s');
	$build_version = build_version();
$content = <<<EOS
<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
| Install date      : {$date}
| CiFireCMS Version : {$build_version}
*/

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| WARNING: You MUST set this value!
|
| If it is not set, then CodeIgniter will try guess the protocol and path
| your installation, but due to security concerns the hostname will be set
| to \$_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
| The auto-detection mechanism exists only for convenience during
| development and MUST NOT be used in production!
|
| If you need to allow multiple domains, remember that this file is still
| a PHP script and you can easily do that on your own.
|
*/
\$config['base_url']  = '{$site_url}';
//@\$config['base_url']  = ((isset(\$_SERVER['HTTPS']) && \$_SERVER['HTTPS'] == "on") ? "https" : "http");
//@\$config['base_url'] .= "://" . \$_SERVER['HTTP_HOST'];
//@\$config['base_url'] .= str_replace(basename(\$_SERVER['SCRIPT_NAME']), "", \$_SERVER['SCRIPT_NAME']);

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
\$config['index_page'] = '';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'REQUEST_URI' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'REQUEST_URI'    Uses \$_SERVER['REQUEST_URI']
| 'QUERY_STRING'   Uses \$_SERVER['QUERY_STRING']
| 'PATH_INFO'      Uses \$_SERVER['PATH_INFO']
|
| WARNING: If you set this to 'PATH_INFO', URIs will always be URL-decoded!
*/
\$config['uri_protocol'] = 'REQUEST_URI';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| https://codeigniter.com/user_guide/general/urls.html
*/
\$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
\$config['language'] = 'english';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
| See http://php.net/htmlspecialchars for a list of supported charsets.
|
*/
\$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
\$config['enable_hooks'] = FALSE;

/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| https://codeigniter.com/user_guide/general/core_classes.html
| https://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
\$config['subclass_prefix'] = 'MY_';

/*
|--------------------------------------------------------------------------
| Composer auto-loading
|--------------------------------------------------------------------------
|
| Enabling this setting will tell CodeIgniter to look for a Composer
| package auto-loader script in application/vendor/autoload.php.
|
|	\$config['composer_autoload'] = TRUE;
|
| Or if you have your vendor/ directory located somewhere else, you
| can opt to set a specific path as well:
|
|	\$config['composer_autoload'] = '/path/to/vendor/autoload.php';
|
| For more information about Composer, please visit http://getcomposer.org/
|
| Note: This will NOT disable or override the CodeIgniter-specific
|	autoloading (application/config/autoload.php)
*/
\$config['composer_autoload'] = FALSE;

/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify which characters are permitted within your URLs.
| When someone tries to submit a URL with disallowed characters they will
| get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| The configured value is actually a regular expression character group
| and it will be executed as: ! preg_match('/^[<permitted_uri_chars>]+$/i
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
\$config['permitted_uri_chars'] = 'a-z 0-9~%.:_-';

/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
\$config['enable_query_strings'] = FALSE;
\$config['controller_trigger']   = 'c';
\$config['function_trigger']     = 'm';
\$config['directory_trigger']    = 'd';

/*
|--------------------------------------------------------------------------
| Allow \$_GET array
|--------------------------------------------------------------------------
|
| By default CodeIgniter enables access to the \$_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
\$config['allow_get_array'] = TRUE;

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| You can also pass an array with threshold levels to show individual error types
|
| 	array(2) = Debug Messages, without Error Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
\$config['log_threshold'] = 1;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ directory. Use a full server path with trailing slash.
|
*/
\$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Log File Extension
|--------------------------------------------------------------------------
|
| The default filename extension for log files. The default 'php' allows for
| protecting the log files via basic scripting, when they are to be stored
| under a publicly accessible directory.
|
| Note: Leaving it blank will default to 'php'.
|
*/
\$config['log_file_extension'] = '';

/*
|--------------------------------------------------------------------------
| Log File Permissions
|--------------------------------------------------------------------------
|
| The file system permissions to be applied on newly created log files.
|
| IMPORTANT: This MUST be an integer (no quotes) and you MUST use octal
|            integer notation (i.e. 0700, 0644, etc.)
*/
\$config['log_file_permissions'] = 0644;

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
\$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Error Views Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/views/errors/ directory.  Use a full server path with trailing slash.
|
*/
\$config['error_views_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/cache/ directory.  Use a full server path with trailing slash.
|
*/
\$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Include Query String
|--------------------------------------------------------------------------
|
| Whether to take the URL query string into consideration when generating
| output cache files. Valid options are:
|
|	FALSE      = Disabled
|	TRUE       = Enabled, take all query parameters into account.
|	             Please be aware that this may result in numerous cache
|	             files generated for the same page over and over again.
|	array('q') = Enabled, but only take into account the specified list
|	             of query parameters.
|
*/
\$config['cache_query_string'] = FALSE;

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class, you must set an encryption key.
| See the user guide for more info.
|
| https://codeigniter.com/user_guide/libraries/encryption.html
|
*/
\$config['encryption_key'] = hex2bin('{$en_key}');

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_driver'
|
|	The storage driver to use: files, database, redis, memcached
|
| 'sess_cookie_name'
|
|	The session cookie name, must contain only [0-9a-z_-] characters
|
| 'sess_expiration'
|
|	The number of SECONDS you want the session to last.
|	Setting to 0 (zero) means expire when the browser is closed.
|
| 'sess_save_path'
|
|	The location to save sessions to, driver dependent.
|
|	For the 'files' driver, it's a path to a writable directory.
|	WARNING: Only absolute paths are supported!
|
|	For the 'database' driver, it's a table name.
|	Please read up the manual for the format with other session drivers.
|
|	IMPORTANT: You are REQUIRED to set a valid save path!
|
| 'sess_match_ip'
|
|	Whether to match the user's IP address when reading the session data.
|
|	WARNING: If you're using the database driver, don't forget to update
|	         your session table's PRIMARY KEY when changing this setting.
|
| 'sess_time_to_update'
|
|	How many seconds between CI regenerating the session ID.
|
| 'sess_regenerate_destroy'
|
|	Whether to destroy session data associated with the old session ID
|	when auto-regenerating the session ID. When set to FALSE, the data
|	will be later deleted by the garbage collector.
|
| Other session cookie settings are shared with the rest of the application,
| except for 'cookie_prefix' and 'cookie_httponly', which are ignored here.
|
*/
\$config['sess_driver']             = '';
\$config['sess_cookie_name']        = md5('{$en_key}');
\$config['sess_expiration']         = 7200;
\$config['sess_save_path']          = NULL;
\$config['sess_match_ip']           = FALSE;
\$config['sess_time_to_update']     = 300;
\$config['sess_regenerate_destroy'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix'   = Set a cookie name prefix if you need to avoid collisions
| 'cookie_domain'   = Set to .your-domain.com for site-wide cookies
| 'cookie_path'     = Typically will be a forward slash
| 'cookie_secure'   = Cookie will only be set if a secure HTTPS connection exists.
| 'cookie_httponly' = Cookie will only be accessible via HTTP(S) (no javascript)
|
| Note: These settings (with the exception of 'cookie_prefix' and
|       'cookie_httponly') will also affect sessions.
|
*/
\$config['cookie_prefix']   = '';
\$config['cookie_domain']   = '';
\$config['cookie_path']     = '/';
\$config['cookie_secure']   = FALSE;
\$config['cookie_httponly'] = FALSE;

/*
|--------------------------------------------------------------------------
| Standardize newlines
|--------------------------------------------------------------------------
|
| Determines whether to standardize newline characters in input data,
| meaning to replace \\r\\n, \\r, \\n occurrences with the PHP_EOL value.
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
\$config['standardize_newlines'] = FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
\$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
| 'csrf_regenerate' = Regenerate token on every submission
| 'csrf_exclude_uris' = Array of URIs which ignore CSRF checks
*/
\$config['csrf_protection']   = TRUE;
\$config['csrf_token_name']   = 'csrf_name';
\$config['csrf_cookie_name']  = 'csrf_cookie_name';
\$config['csrf_expire']       = 7200;
\$config['csrf_regenerate']   = FALSE;
\$config['csrf_exclude_uris'] = array();

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| Only used if zlib.output_compression is turned off in your php.ini.
| Please do not use it together with httpd-level output compression.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
\$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or any PHP supported timezone. This preference tells
| the system whether to use your server's local time as the master 'now'
| reference, or convert it to the configured one timezone. See the 'date
| helper' page of the user guide for information regarding date handling.
|
*/
\$config['time_reference'] = 'local';

/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
| Note: You need to have eval() enabled for this to work.
|
*/
\$config['rewrite_short_tags'] = FALSE;

/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy
| IP addresses from which CodeIgniter should trust headers such as
| HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP in order to properly identify
| the visitor's IP address.
|
| You can use both an array or a comma-separated list of proxy addresses,
| as well as specifying whole subnets. Here are a few examples:
|
| Comma-separated:	'10.0.1.200,192.168.5.0/24'
| Array:			array('10.0.1.200', '192.168.5.0/24')
*/
\$config['proxy_ips'] = '';
EOS;
return $content;
}


function cindex() {
	$date = date('Y');
	$build_version = build_version();
$content = <<< EOS
<?php
/**
 * MIT License
 * 
 * Copyright (c) 2019 - {$date} CiFireCMS
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * 
 * @package	CiFireCMS
 * @author	Adiman
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://github.com/CiFireCMS/cifirecms
 * @since	Version {$build_version}
 * @filesource
*/

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
*/
define('ENVIRONMENT', isset(\$_SERVER['CI_ENV']) ? \$_SERVER['CI_ENV'] : 'development');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
*/
switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(-1);
		error_reporting(E_ALL ^ E_DEPRECATED);
		ini_set('display_errors', 1);
	break;

	case 'testing':
	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>='))
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}
		else
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
	break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1); // EXIT_ERROR
}

/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" directory.
 * Set the path if it is not in the same directory as this file.
*/
\$system_path = 'l-system';

/*
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * directory than the default one you can set its name here. The directory
 * can also be renamed or relocated anywhere on your server. If you do,
 * use an absolute (full) server path.
 * For more info please see the user guide:
 *
 * https://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
*/
\$application_folder = 'l-app';

/*
 *---------------------------------------------------------------
 * VIEW DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want to move the view directory out of the application
 * directory, set the path to it here. The directory can be renamed
 * and relocated anywhere on your server. If blank, it will default
 * to the standard location inside your application directory.
 * If you do move this, use an absolute (full) server path.
 *
 * NO TRAILING SLASH!
*/
\$view_folder = '';

/*
 *---------------------------------------------------------------
 * ADMINISTRATOR DIRECTORY NAME
 *---------------------------------------------------------------
*/
\$admin_folder = 'l-admin';

/*
 *---------------------------------------------------------------
 * WEB DIRECTORY NAME
 *---------------------------------------------------------------
*/
\$web_folder = 'l-web';

/*
 *---------------------------------------------------------------
 * CONTENT DIRECTORY NAME
 *---------------------------------------------------------------
*/
\$content_folder = 'l-content';

/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here. For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT: If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller. Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the \$routing array below to use this feature
*/
	// The directory name, relative to the "controllers" directory.  Leave blank
	// if your controller is not in a sub-directory within the "controllers" one
	// \$routing['directory'] = '';

	// The controller class file name.  Example:  mycontroller
	// \$routing['controller'] = '';

	// The controller function you wish to be called.
	// \$routing['function']	= '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The \$assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the \$assign_to_config array below to use this feature
*/
	// \$assign_to_config['name_of_config_item'] = 'value of config item';



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
*/

// Set the current directory correctly for CLI requests.
if (defined('STDIN'))
{
	chdir(dirname(__FILE__));
}

if ((\$_temp = realpath(\$system_path)) !== FALSE)
{
	\$system_path = \$_temp.DIRECTORY_SEPARATOR;
}
else
{
	// Ensure there's a trailing slash.
	\$system_path = strtr(
		rtrim(\$system_path, '/\\\'),
		'/\\\',
		DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
	).DIRECTORY_SEPARATOR;
}

// Is the system path correct?
if ( ! is_dir(\$system_path))
{
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);
	exit(3); // EXIT_CONFIG
}

// The name of THIS file.
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// Path to the system directory.
define('BASEPATH', \$system_path);

// Path to the front controller (this file) directory.
define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

// Name of the "system" directory.
define('SYSDIR', basename(BASEPATH));

// The path to the "application" directory.
if (is_dir(\$application_folder))
{
	if ((\$_temp = realpath(\$application_folder)) !== FALSE)
	{
		\$application_folder = \$_temp;
	}
	else
	{
		\$application_folder = strtr(
			rtrim(\$application_folder, '/\\\'),
			'/\\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		);
	}
}
elseif (is_dir(BASEPATH.\$application_folder.DIRECTORY_SEPARATOR))
{
	\$application_folder = BASEPATH.strtr(
		trim(\$application_folder, '/\\\'),
		'/\\\',
		DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
	);
}
else
{
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
	exit(3); // EXIT_CONFIG
}

define('APPPATH', \$application_folder.DIRECTORY_SEPARATOR);

// The path to the "views" directory.
if ( ! isset(\$view_folder[0]) && is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR))
{
	\$view_folder = APPPATH.'views';
}
elseif (is_dir(\$view_folder))
{
	if ((\$_temp = realpath(\$view_folder)) !== FALSE)
	{
		\$view_folder = \$_temp;
	}
	else
	{
		\$view_folder = strtr(
			rtrim(\$view_folder, '/\\\'),
			'/\\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		);
	}
}
elseif (is_dir(APPPATH.\$view_folder.DIRECTORY_SEPARATOR))
{
	\$view_folder = APPPATH.strtr(
		trim(\$view_folder, '/\\\'),
		'/\\\',
		DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
	);
}
else
{
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
	exit(3); // EXIT_CONFIG
}

define('VIEWPATH', \$view_folder.DIRECTORY_SEPARATOR);

// Custom path.
define('CONTENTPATH', \$content_folder.DIRECTORY_SEPARATOR);
define('FCONTENT', \$content_folder);
define('FADMIN', \$admin_folder);
define('FWEB', \$web_folder);

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
*/
require_once BASEPATH.'core/CodeIgniter.php';
EOS;
return $content;
}


function setting_value_sitemap(){
	$str = htmlspecialchars('<form id="_formSiteMapz" method="POST" action="" class="form-inline">
  <input id="_csrf" type="hidden" name="csrf_name"/>
  <div class="input-group">
    <div class="input-group-prepend">
      <span class="input-group-text">Frequency</span>
    </div>
    <select name="changefreq" class="form-control">
      <option value="" selected>None</option>
      <option value="always">Always</option>
      <option value="hourly">Hourly</option>
      <option value="daily">Daily</option>
      <option value="weekly">Weekly</option>
      <option value="monthly">Monthly</option>
      <option value="yearly">Yearly</option>
      <option value="never">Never</option>
    </select>
    <div class="input-group-prepend">
      <span class="input-group-text">Priority</span>
    </div>
    <select name="priority" class="form-control">
      <option value="0.1" selected>0.1</option>
      <option value="0.2">0.2</option>
      <option value="0.3">0.3</option>
      <option value="0.4">0.4</option>
      <option value="0.5">0.5</option>
      <option value="0.6">0.6</option>
      <option value="0.7">0.7</option>
      <option value="0.8">0.8</option>
      <option value="0.9">0.9</option>
      <option value="1.0">1.0</option>
    </select>
    <div class="mg-l-5">
      <button type="submit" name="pk" value="sitemap" class="btn btn-success">Create Site Map</button>
    </div>
  </div>
</form>

<script>
  $(document).ready(function(){
    var _formSiteMapAction = admin_url + a_mod + \'/createsitemap\';
    $(\'#_formSiteMapz\').attr(\'action\', _formSiteMapAction);
    $(\'#_csrf\').val(csrfToken);
  });
</script>');
	return $str;
}


function license()
{
$license = '
MIT License

Copyright (c) 2019 - '.date("Y").' CiFireCMS

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
';
return $license;
}