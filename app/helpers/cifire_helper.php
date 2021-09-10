<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('check_internet_connection') )
{
	/**
	 * - Fungsi untuk cek koneksi internet.
	 *  
	 * @param   string $param
	 * @return  bool  
	*/
	function check_internet_connection($addr = 'www.google.com')
	{
		return (bool)  @fsockopen($addr, 80, $num, $error, 5);
	}
}


if ( ! function_exists('get_setting')) 
{
	/**
	 * - Fungsi untuk mengambil data settings.
	 * - Contoh : get_setting($param = 'web_name')
	 *  
	 * @param   string $param
	 * @return  string  
	*/
	function get_setting($param = '', $array = FALSE, $arr_param='value')
	{
		$CI =& get_instance();
		// $CI->load->database();

		$data = NULL;

		if ($array == FALSE)
		{
			$query = $CI->db->select('value')->where('options', $param)->get('t_setting');
			if ($query->num_rows()==1)
				$data = $query->row_array()['value'];
		}
		else
		{
			$query = $CI->db->where('options', $param)->get('t_setting')->row_array();
			if ($query->num_rows()==1) {
				$data = $query[$arr_param];
			}
		}

		return $data;
	}
}

if ( ! function_exists('lang_active')) 
{
	/**
	 * - Fungsi untuk memanggil bahasa yang aktif.
	 *  
	 * @return  string  
	*/
	function lang_active()
	{
		$CI =& get_instance();
		$lang = $CI->config->item('language');
		return $lang;
	}
}


if ( ! function_exists('lang_line'))
{
	/**
	 * - Fungsi untuk memanggil baris bahasa.
	 * - Contoh : lang_line('button_save')
	 *
	 * @param   string  $line
	 * @return  string  
	*/
	function lang_line($line = '')
	{
		$CI =& get_instance();

		$lang = 'Undefined';

		if ($CI->lang->line($line))
		{
			$lang = $CI->lang->line($line);
		}

		return $lang;
	}
}


if ( ! function_exists('mail_config')) 
{
	/**
	 * - Fungsi konfigurasi email.
	 *
	 * @return  array  
	*/
	function mail_config()
	{
		$protocol = get_setting('mail_protocol');
		switch ($protocol) {
			case 'smtp':
				$config = array(
					'useragent' => get_setting('web_name'),
					'protocol'  => get_setting('mail_protocol'),
					'smtp_host' => get_setting('mail_hostname'),
					'smtp_user' => get_setting('mail_username'),
					'smtp_pass' => decrypt(get_setting('mail_password')),
					'smtp_port' => get_setting('mail_port'),
					'validate'  => TRUE,
					// 'smtp_crypto' => 'tls',
					// 'smtp_timeout' => 10,

					'crlf'      => "\r\n",
					'newline'   => "\r\n",
					'mailtype'  => 'html',
					'charset'   => 'iso-8859-1', // iso-8859-1 or utf-8
					'wordwrap'  => TRUE
				);
			break;
			
			case 'sendmail':
				$config = array(
					'useragent' => get_setting('web_name'),
					'protocol'  => get_setting('mail_protocol'),
					'mailpath'  => '/usr/sbin/sendmail',
					'newline'   => "\r\n",
					'crlf'      => "\r\n",
					'mailtype'  => 'html',
					'charset'   => 'iso-8859-1', // iso-8859-1 or utf-8
					'wordwrap'  => TRUE
				);
			break;

			case 'mail':
			default:
				$config = array(
					'useragent' => get_setting('web_name'),
					'protocol'  => get_setting('mail_protocol'),
					'newline'   => "\r\n",
					'crlf'      => "\r\n",
					'mailtype'  => 'html',
					'charset'   => 'iso-8859-1', // iso-8859-1 or utf-8
					'wordwrap'  => TRUE
				);
			break;
		}

		return $config;
	}
}


if ( ! function_exists('theme_active')) 
{
	/**
	 * - Fungsi untuk mengambil data tema yang aktif.
	 * - Contoh : theme_active('folder');
	 *  
	 * @param   string $param
	 * @return  string  
	*/
	function theme_active($param = 'folder')
	{
		$CI =& get_instance();
		$query = $CI->db->where('active','Y')->get('t_theme')->row_array();

		return $query[$param];
	}
}


if ( ! function_exists('url_origin'))
{
	/**
	 * - Fungsi untuk menampilkan url
	 * 
	 * @param   bool  $use_forwarded_host
	 * @return  string  
	*/
	function url_origin($use_forwarded_host = false)
	{
		$ssl      = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' );
		$sp       = strtolower( $_SERVER['SERVER_PROTOCOL'] );
		$protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
		$port     = $_SERVER['SERVER_PORT'];
		$port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
		$host     = ( $use_forwarded_host && isset( $_SERVER['HTTP_X_FORWARDED_HOST'] ) ) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : ( isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : null );
		$host     = isset( $host ) ? $host : $_SERVER['SERVER_NAME'] . $port;
		return $protocol . '://' . $host;
	}
}


if ( ! function_exists('selft_url'))
{
	/**
	 * - Fungsi untuk menampilkan url
	 *  Contoh : <?=selft_url()?>
	 * 
	 * @param   bool  $use_forwarded_host
	 * @return  void|string  
	*/
	function selft_url($use_forwarded_host = false )
	{
		return url_origin($use_forwarded_host ) . $_SERVER['REQUEST_URI'];
	}
}


if ( ! function_exists('admin_url'))
{
	/**
	 * - Fungsi untuk menampilkan url base halaman administrator
	 * 
	 *  Contoh : <?=admin_url('home')?>
	 *  Hasil  : http://your.domain/l-admin/home
	 * 
	 * @param   string  $param
	 * @param   string  $protocol
	 * @return  string  
	*/
	function admin_url($param = '', $protocol = NULL)
	{
		$CI =& get_instance();
		return $CI->config->base_url(FADMIN.'/'.$param, $protocol);
	}
}


if ( ! function_exists('content_url'))
{
	/**
	 * - Fungsi untuk menampilkan url folder content
	 * 
	 *  Contoh : <?=content_url('assets/foo/bar.css')?>
	 *  Hasil  : http://your.domain/content/assets/foo/bar.css
	 * 
	 * @param   string  $param
	 * @param   string	$protocol
	 * @return  string  
	*/
	function content_url($param = '', $protocol = NULL)
	{
		$CI =& get_instance();
		return $CI->config->base_url(FCONTENT.'/'.$param, $protocol);
	}
}


if ( ! function_exists('post_url'))
{
	/**
	 * - Fungsi untuk menampilkan url post secara dinamis
	 *   sesuai konfigurasi slug url pada tabel t_setting.
	 * 
	 *   Contoh : <?=post_url($seotitle)?>
	 *   Hasil  : http://your.dmoain/your-slug/seotitle
	 * 
	 * @param   string  $seotitle
	 * @return  string
	*/
	function post_url($seotitle = '')
	{
		$CI =& get_instance();
		$slug_url = get_setting('slug_url');
		
		switch ($slug_url)
		{	
			default:
				$url = '';
			break;

			case 'seotitle':
				$url = site_url($seotitle);
			break;

			case 'slug/seotitle':
				$slug_title = get_setting('slug_title');
				$url = site_url("$slug_title/$seotitle");
			break;
			
			case 'yyyy/seotitle':
				$post = $CI->db->select('datepost')->where('seotitle', $seotitle)->get('t_post')->row_array();
				$year = date('Y',strtotime($post['datepost']));
				$url = site_url("$year/$seotitle");
			break;
			
			case 'yyyy/mm/seotitle':
				$post = $CI->db->select('datepost')->where('seotitle', $seotitle)->get('t_post')->row_array();
				$moon = date('m',strtotime($post['datepost']));
				$year = date('Y',strtotime($post['datepost']));
				$url = site_url("$year/$moon/$seotitle");
			break;
			
			case 'yyyy/mm/dd/seotitle':
				$post = $CI->db->select('datepost')->where('seotitle', $seotitle)->get('t_post')->row_array();
				$day  = date('d',strtotime($post['datepost']));
				$moon = date('m',strtotime($post['datepost']));
				$year = date('Y',strtotime($post['datepost']));
				$url = site_url("$year/$moon/$day/$seotitle");
			break;
		}

		return $url;
	}
}


if ( ! function_exists('favicon'))
{
	/**
	 * - Fungsi untuk menampilkan url favicon dan url logo.
	 * - Contoh penggunaan : <link rel="shortcut icon" href="<?=favicon()?>">
	 * 
	 * @param   string  $param
	 * @return  string  
	*/
	function favicon($param = '') 
	{
		if ($param == 'logo')
		{
			$result = content_url('uploads/'.get_setting('web_logo'));
		}
		elseif ($param == 'web_image')
		{
			$result = content_url('uploads/'.get_setting('web_image'));
		}
		else
		{
			$result = content_url('uploads/'.get_setting('favicon'));
		}
		
		return $result;
	}
}


if ( ! function_exists('post_images'))
{
	/**
	 * - Fungsi untuk menampilkan url gambar.
	 * - Mode : NULL, medium, thumb
	 * - noimage : bool
	 * 
	 *   Contoh : post_images($filename, 'medium', TRUE)
	 *   Hasil  : http://your.dmoain/content/uploads/medium/filename.jpg
	 * 
	 * @param   string  $filename
	 * @param   string  $mode
	 * @param   bool    $noimage
	 * @return  string
	*/
	function post_images($filename = null, $mode = NULL, $noimage = FALSE)
	{
		$image_url = '';
		$dt = "?".strtotime(date('YmdHis'));

		switch ($noimage) 
		{
			case TRUE:
				if ( !empty($filename) )
				{
					if ( $mode == 'thumb' ) 
					{
						if (file_exists(CONTENTPATH."thumbs/$filename")) 
							$image_url = content_url("thumbs/$filename").$dt;
						else 
							$image_url = content_url("images/thumb_noimage.jpg").$dt;
					}
					elseif ( $mode == 'medium' ) 
					{
						if (file_exists(CONTENTPATH."uploads/medium/$filename")) 
							$image_url = content_url("uploads/medium/$filename").$dt;
						else
							$image_url = content_url("images/medium_noimage.jpg").$dt;
					}
					else
					{
						if ( file_exists(CONTENTPATH."uploads/$filename") ) 
							$image_url = content_url("uploads/$filename").$dt;
						else
							$image_url = content_url("images/noimage.jpg").$dt;
					}
				}

				else
				{
					if ( $mode == 'thumb' )
						$image_url = content_url("images/thumb_noimage.jpg").$dt;
					elseif ($mode == 'medium')
						$image_url = content_url("images/medium_noimage.jpg").$dt;
					else
						$image_url = content_url("images/noimage.jpg").$dt;
				}			
			break;
			
			default:
			case FALSE:
				if ( !empty($filename) && file_exists(CONTENTPATH."uploads/$filename") && $mode == '' ) 
					$image_url = content_url("uploads/$filename").$dt;
				elseif ( !empty($filename) && file_exists(CONTENTPATH."uploads/medium/$filename") && $mode == 'medium' ) 
					$image_url = content_url("uploads/medium/$filename").$dt;
				elseif ( !empty($filename) && file_exists(CONTENTPATH."thumbs/$filename") && $mode == 'thumb' ) 
					$image_url = content_url("thumbs/$filename").$dt;
				else
					$image_url = '';
			break;
		}

		return $image_url;
	}
}


if ( ! function_exists('post_file'))
{
	/**
	 * - Fungsi untuk menampilkan url file.
	 *   Contoh : post_file('dokumen.xlsx', 'file')
	 *   Hasil  : http://your.dmoain/content/uploads/file/dokumen.xlsx
	 * 
	 * @param   string  $filename
	 * @param   string  $mode
	 * @param   bool    $noimage
	 * @return  string
	*/
	function post_file($filename = '', $type = 'file')
	{
		$url_file = '';
		if ( file_exists(CONTENTPATH . "uploads/$type/$filename") )
			$url_file = content_url("uploads/$type/$filename");

		return $url_file;
	}
}


if ( ! function_exists('user_photo'))
{
	/**
	 * - Fungsi untuk menampilkan url foto user.
	 *   Contoh : user_photo(user.jpg)
	 *   Hasil  : http://your.dmoain/content/uploads/user/user.jpg
	 * 
	 * @param   string  $photo
	 * @return  string
	*/
	function user_photo($photo = '')
	{
		if ( !empty($photo) && file_exists(CONTENTPATH."uploads/user/$photo") ) 
			$user_photo = content_url("uploads/user/$photo");
		else
			$user_photo = content_url('images/avatar.jpg');

		$photo = $user_photo."?".strtotime(date('YmdHis'));
		return $photo;
	}
}


if ( ! function_exists('base64_image'))
{
	/**
	 * - Fungsi untuk menampilkan base64 image.
	 *   Contoh : base64_image('http://your.dmoain/content/uploads/user/user.jpg')
	 * 
	 * @param   string  $img_url
	 * @return  string
	*/
	function base64_image($img_url = '')
	{
		$src = "data:image/png;base64," . base64_encode(file_get_contents($img_url));
		return $src;
	}
}


if ( ! function_exists('cut'))
{
	/**
	 * - Fungsi untuk menentukn panjang karakter.
	 *   Contoh : cut('foo bar bass pass', 2)
	 * 
	 * @param   string         $data
	 * @param   string|int     $long
	 * @param   bool           $option
	 * @return  string
	*/
	function cut($data = '', $long = '', $option = FALSE)
	{
		$str = $data;

		if ( isset($data) && isset($long))
		{
			if ($option == FALSE)
			{
				$str = html_entity_decode($str);
				$str = strip_tags($str);
				$str = mb_substr($str, 0, $long);
				$str = mb_substr($str, 0, strrpos($str," "));
			} 
			else
			{
				$str = mb_substr($str,0,$long);
			}
		}

		return $str;
	}
}


if ( ! function_exists('text_highlight'))
{
	/**
	 * - Fungsi untuk menyoroti kata dalam kalimat.
	 *   Contoh : text_highlight('foo bar bass pass', 'foo', 'color:red;')
	 * 
	 * @param 	string 	$words
	 * @param 	string 	$text
	 * @param 	string 	$style
	 * @return 	string 	
	*/
	function text_highlight($words = '', $text = '', $style = '')
	{
		$font_style = (!empty($params) ? $params : 'color:#FDFF2B;');
		
		preg_match_all('~[A-Za-z0-9_äöüÄÖÜ]+~', $words, $m);
		
		if ( !$m )
		{
			$highlight = $text;
		}

		$re = '~(' . implode('|', $m[0]) . ')~i';
		$highlight = preg_replace($re, '<font style="'. $font_style .'">$0</font>', $text);

		return $highlight;
	}
}


if ( ! function_exists('arrays_to_string'))
{
	/**
	 * - Fungsi untuk konversi array ke string.
	 *   Contoh : arrays_to_string(['foo','bar','bass'], ',')
	 * 
	 * @param 	array 	$ar
	 * @param 	string 	$sep
	 * @return 	string 	
	*/
	function arrays_to_string(array $ar, $sep = ',') 
	{
		$str = '';

		foreach ($ar as $val) 
		{
			$str .= implode($sep, $val);
			$str .= $sep; // add separator between sub-arrays
		}
		
		$str = rtrim($str, $sep); // remove last separator
		
		return $str;
	}
}


if ( ! function_exists('json_to_array'))
{
	/**
	 * - Fungsi untuk konversi data json ke array asosiatif.
	 *   Contoh : json_to_array($data_json)
	 * 
	 * @param 	string|jon 	$data
	 * @return 	array 	
	*/
	function json_to_array($data)
	{
		if (is_object($data))
		{
			$data = get_object_vars($data);
		}

		$jdata = is_array($data) ? array_map(__FUNCTION__, $data) : $data;
		return json_decode($jdata);
	}
}


if ( ! function_exists('seotitle'))
{
	/**
	 * - Fungsi untuk memfilter string manjadi string seo.
	 *   Contoh : seotitle("foo bar bass")
	 *   Hasil  : foo-bar-bass
	 * 
	 * @param 	string 	$str
	 * @param 	string 	$sp
	 * @return 	string 	
	*/
	function seotitle($str = '', $sp = '-')
	{
		$seotitle = '';

		if ( !empty($str) )
		{	
			$q_separator = preg_quote($sp, '#');

			$trans = array(
				'_' => $sp,
				'&.+?;' => '',
				'[^\w\d -]' => '',
				'\s+' => $sp,
				'('.$q_separator.')+' => $sp
			);

			$str = strip_tags($str);
			
			foreach ($trans as $key => $val)
			{
				$str = preg_replace('#'.$key.'#i'.(UTF8_ENABLED ? 'u' : ''), $val, $str);
			}
			
			$str = strtolower($str);
			$seotitle = trim(trim($str, $sp));
		}

		return $seotitle;
	}
}


if ( ! function_exists('xss_filter'))
{
	/**
	 * - Fungsi untuk memfilter string dari karakter berbahaya.
	 *   Contoh : xss_filter("foo bar bass", 'xss')
	 * 
	 * @param 	string 	$str
	 * @param 	string 	$type  xss|sql
	 * @return 	string 	
	*/
	function xss_filter($str, $type = '')
	{
		switch($type)
		{
			default:
				$str = stripcslashes(htmlspecialchars($str, ENT_QUOTES));
				return $str;
			break;

			case 'sql':
				$x = array('-','/','\\',',','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','%','$','^','&','*','=','?','+');
				$str = str_replace($x, '', $str);
				$str = stripcslashes($str);	
				$str = htmlspecialchars($str);				
				$str = preg_replace('/[^A-Za-z0-9]/','',$str);				
				return intval($str);
			break;

			case 'xss':
				$x = array ('\\','#',';','\'','"','[',']','{','}',')','(','|','`','~','!','%','$','^','*','=','?','+');
				$str = str_replace($x, '', $str);
				$str = stripcslashes($str);	
				$str = htmlspecialchars($str);
				return $str;
			break;
		}
	}
}


if ( ! function_exists('clean_space'))
{
	/**
	 * - Fungsi untuk mengurangi spasi ganda.
	 *   Contoh : clean_space("foo bar   bass")
	 * 
	 * @param 	string 	$data
	 * @return 	string 	
	*/
	function clean_space($data = '')
	{
		$str = '';
		if ( !empty($data) )
		{
			$patterns = array("/\s+/", "/\s([?.!])/");
			$replacer = array(" ","$1");
			$str = preg_replace( $patterns, $replacer, $data );
		}
		return $str;
	}
}


if ( ! function_exists('clean_tag'))
{
	/**
	 * - Fungsi untuk memfilter string dari karakter berbahaya untuk kebutuhan tag.
	 *   Contoh : clean_tag("foo bar bass")
	 *   Hasil  : foobarbass
	 * 
	 * @param 	string 	$str
	 * @return 	string 	
	*/
	function clean_tag($str = '')
	{
		if ( isset($str) )
		{
			$d = array ('\\','#',';','\'','"','[',']','{','}',')','(','|','`','~','!','%','$','^','*','=','?','+','<','>','.','@',':','/','&');
			$str = str_replace($d, '', $str);
			$str = stripcslashes($str);	
		}
		
		return $str;
	}
}


if ( ! function_exists('pecah_kata'))
{
	/**
	 * - Fungsi untuk memisahkan kata dalam kalimat.
	 *   Contoh : 
	 *      $kata = "foo bar bass";
	 *      pecah_kata(NULL, $kata, FALSE, '#', ',')
	 * 
	 * @param 	string 	$str
	 * @return 	string 	
	*/
	function pecah_kata($delimiter = NULL, $kata, $link = FALSE, $href = '#', $separator = '') 
	{
		$_rez = '';
		
		if (empty($delimiter))
			$delimiter = ' ';

		$pecah = explode($delimiter,$kata);
		$arr_katas = (integer)count($pecah) - 1;						
		
		for ( $i = 0; $i <= $arr_katas ; $i++ )
		{
			if ( $i == $arr_katas )
				$separator = "";
			
			switch ($link)
			{
				default:
					$_rez .= $pecah[$i].$separator;
				break;
				case TRUE:
					$lstrlink = $href.$pecah[$i];
					$_rez .= '<a href="'.$lstrlink.'">'.$pecah[$i].'</a>'.$separator;
				break;				
			}			
		}

		return rtrim($_rez,$separator);
	}
}


if ( ! function_exists('r_copy'))
{
	/**
	 * - Fungsi untuk menyalin (copy) folder beserta isinya.
	 * 
	 *   Contoh : r_copy(foo/bar/, foo/bass)
	 * 
	 * @param 	string 	$src
	 * @param 	string 	$dst
	 * @return 	void 	
	*/
	function r_copy($src, $dst)
	{
		if (is_dir($src)) // copy folder
		{
			if (!file_exists($dst))
				@mkdir($dst);
			
			$files = scandir($src);

			foreach ($files as $file)
			{
				if ($file != "." && $file != "..")
					r_copy("$src/.$file", "$dst/$file");
			}
		} 
		elseif (file_exists($src)) // copy file
		{
			copy($src, $dst);
		}
	}
}


if ( ! function_exists('copy_folder'))
{
	/**
	 * - Fungsi untuk menyalin (copy) folder.
	 * 
	 *   Contoh : copy_folder(foo/, bar/, 0755, FALSE)
	 * 
	 * @param 	string 	$source
	 * @param 	string 	$destination
	 * @param 	int 	$permissions
	 * @param 	bool 	$delete_source
	 * @return 	bool 	
	*/
	function copy_folder($source, $destination, $permissions = 0755, $delete_source = FALSE)
	{
		if (file_exists($source))
		{
			// Check for symlinks
			if (is_link($source)) 
			{
				return symlink(readlink($source), $destination);
			}

			// Simple copy for a file
			if (is_file($source)) 
			{
				return copy($source, $destination);
			}

			// Make destination directory
			if (!file_exists($destination)) 
			{
				@mkdir($destination, $permissions, TRUE);
			}

			// Loop through the folder
			$dir = dir($source);
			
			while ( FALSE !== $entry = $dir->read() ) 
			{
				// Skip pointers
				if ($entry == '.' || $entry == '..') 
					continue;

				// Deep copy directories
				copy_folder("$source".DIRECTORY_SEPARATOR."$entry", "$destination".DIRECTORY_SEPARATOR."$entry", $permissions);
			}

			// Clean up
			$dir->close();

			if ($delete_source == TRUE) 
			{
				delete_folder($source);
			}

			return TRUE;
		}

		return FALSE;
	}
}


if ( ! function_exists('delete_folder'))
{
	/**
	 * - Fungsi untuk menghapus folder.
	 *   Contoh : delete_folder('foo/bar')
	 * 
	 * @param 	string 	$path
	 * @return 	bool|void	
	*/
	function delete_folder($path = '')
	{
		if ( !file_exists($path) )
		{
			return FALSE;
		}

		if ( is_file($path) || is_link($path) )
		{
			return unlink($path);
		}

		$stack = array($path);

		while ( $entry = array_pop($stack) )
		{
			if (is_link($entry)) 
			{
				unlink($entry);
				continue;
			}

			if (@rmdir($entry))
			{
				continue;
			}

			$stack[] = $entry;
			$dh = opendir($entry);

			while( FALSE !== $child = readdir($dh) )
			{
				if ( $child === '.' || $child === '..' )
				{
					continue;
				}

				$child = $entry . DIRECTORY_SEPARATOR . $child;
				
				if (is_dir($child) && !is_link($child))
				{
					$stack[] = $child;
				}
				else
				{
					unlink($child);
				}

			}

			closedir($dh);
		}

		return true;
	}
}


if ( ! function_exists('url_decode'))
{
	/**
	 * - Fungsi untuk decode url.
	 *   Contoh : url_decode('foo%barr')
	 * 
	 * @param 	string 	$param
	 * @return 	void|string	
	*/
	function url_decode($param = 'nourl')
	{
		return urldecode(rawurldecode($param));
	}
}


if ( ! function_exists('url_encode'))
{
	/**
	 * - Fungsi untuk encode url.
	 * 
	 *   Contoh : url_decode('foo-barr')
	 * 
	 * @param 	string 	$param
	 * @return 	void|string	
	*/
	function url_encode($param = 'nourl')
	{ 
		return urlencode(rawurlencode($param));
	}
}


if ( ! function_exists('encrypt'))
{
	/**
	 * - Fungsi untuk encrypt string.
	 * 
	 * @param 	string 	$str
	 * @return 	string	
	*/
	function encrypt($str = '')
	{
		$CI =& get_instance();
		$CI->load->library('encryption');
		return $CI->encryption->encrypt($str);
	}
}


if ( ! function_exists('decrypt'))
{
	/**
	 * - Fungsi untuk decrypt string.
	 * 
	 * @param 	string 	$str
	 * @return 	string	
	*/
	function decrypt($str = '')
	{
		$CI =& get_instance();
		$CI->load->library('encryption');
		$result = $CI->encryption->decrypt($str);
		return $result;
	}
}



if ( ! function_exists('hashid_encode'))
{
	/**
	 * - Fungsi untuk encrypt id.
	 * 
	 * @param 	int|string 	$val
	 * @return 	int|string	
	*/
	function hashid_encode($val = '')
	{
		$CI =& get_instance();
		$CI->load->library('Cifire_Hashids');
		$result = $CI->cifire_hashids->encode($val);
		return $result;
	}
}

if ( ! function_exists('hashid_decode'))
{
	/**
	 * - Fungsi untuk encrypt id.
	 * 
	 * @param 	int|string 	$val
	 * @return 	int|string	
	*/
	function hashid_decode($val = '')
	{
		$CI =& get_instance();
		$CI->load->library('Cifire_Hashids');
		$result = $CI->cifire_hashids->decode($val);

		if (count($result) > 0)
			return $result[0];
		else
			return NULL;
	}
}


if ( ! function_exists('fmkey'))
{
	/**
	 * Fungsi untuk key filemanager
	 *
	 * @return  string
	*/
	function fmkey() 
	{
		$key = !empty($_SESSION['FM_KEY']) ? $_SESSION['FM_KEY'] : NULL;	
		return $key;
	}
}



if ( ! function_exists('group_active'))
{
	function group_active($param='group')
	{
		$CI =& get_instance();
		$user_id = decrypt(login_key());
		$getUser = $CI->db->where('id',$user_id)->get('t_user')->row_array();
		$getGroup = $CI->db->where('pk',$getUser['key_group'])->get('t_user_group')->row_array();
		$result = $getGroup[$param];
		return $result;
	}
}


if ( ! function_exists('login_status'))
{
	/**
	 * - Fungsi untuk pengecekan status login.
	 * 
	 * @return 	bool
	*/
	function login_status()
	{
		$CI =& get_instance();
		$session_login = $CI->session->userdata('_CiFireLogin');
		if (!empty($session_login) && $session_login==TRUE)
			return TRUE;
		else
			return FALSE;
	}
}


if ( ! function_exists('login_key'))
{
	function login_key() 
	{
		$key = !empty($_SESSION['key_id']) ? $_SESSION['key_id'] : '';	
		return $key;
	}
}


if ( ! function_exists('data_login'))
{
	/**
	 * - Fungsi untuk menampilkan data login.
	 *   Contoh : data_login('member', 'name')
	 * 
	 * @param 	string 	$mode
	 * @param 	string 	$field
	 * @return 	string	
	*/
	function data_login($field)
	{
		$CI =& get_instance();
		$id = decrypt(login_key());
		$query  = $CI->db->where('id', $id)->get('t_user')->row_array();
		$result = $query[$field];
		return $result;
	}
}


if ( ! function_exists('googleCaptcha'))
{
	function googleCaptcha() 
	{
		$sock =  @fsockopen('www.google.com', 80);
		if ( $sock )
		{
			$CI =& get_instance();
			$key = get_setting('recaptcha_secret_key');
			$get = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$key.'&response='.$_POST['g-recaptcha-response'].'&remoteip='.$_SERVER['REMOTE_ADDR']);
			if ($get) {
				return json_decode($get);
			}
			else {
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
}


if ( ! function_exists('google_analytics'))
{
	function google_analytics()
	{
		$code = get_setting('google_analytics');
		$script = "
			<script>
				window.dataLayer = window.dataLayer || [];
				function gtag(){dataLayer.push(arguments);}
				gtag('js', new Date());
				gtag('config', '$code');
			</script>
		";
		return $script;
	}
}


if ( ! function_exists('show_400'))
{
	/**
	 * Fungsi untuk menampilkan konten halaman 400	
	 * @return 	void
	*/
	function show_400($page = '', $log_error = FALSE)
	{
		if (is_cli())
		{
			$heading = '400 Bad Request';
			$message = 'Server cannot or will not process the request due to something that is perceived to be a client error.';
		}
		else
		{
			$heading = '400 Bad Request';
			$message = 'Server cannot or will not process the request due to something that is perceived to be a client error.';
		}

		// By default we log this, but allow a dev to skip it
		if ($log_error)
		{
			$CI =& get_instance();
			$page = base_url().$CI->uri->uri_string();
			log_message('error', $heading.': '.$page);
		}

		$_error =& load_class('Exceptions', 'core');
		echo $_error->show_error($heading, $message, 'error_400', 400);
		exit(4); // EXIT_UNKNOWN_FILE
	}
}


if ( ! function_exists('show_403'))
{
	/**
	 * Fungsi untuk menampilkan konten halaman 403	
	 * @return 	void
	*/
	function show_403($page = '', $log_error = FALSE)
	{
		if ( is_cli() )
		{
			$heading = '403 Access Denied';
			$message = 'You don\'t have permission to access.';
		}
		else
		{
			$heading = '403 Access Denied';
			$message = 'You don\'t have permission to access this page.';
		}

		if ( $log_error )
		{
			$CI =& get_instance();
			$page = base_url().$CI->uri->uri_string();
			log_message('error', $heading.': '.$page);
		}

		$_error =& load_class('Exceptions', 'core');
		echo $_error->show_error($heading, $message, 'error_403', 403);
		exit(4); // EXIT_UNKNOWN_FILE
	}
}

function copyright()
{
	$cp = 'Copyright &copy; 2019 - '.date('Y').' '.get_setting('web_name'). ' All rights reserved.';
	return $cp;
}

// New function here ...