<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 |------------------------------------------------------------------
 | Prety Date
 | author : faytranevozter
 | github : https://gist.github.com/faytranevozter/c697f38f1e497554b453d330c78a2056
 |------------------------------------------------------------------
 | Converting date format using language in codeigniter
 | 
 | Add code below to language directory in codeigniter
 | application/language/indonesia/date_lang.php : 
 | 
 | $lang['month_name'] = array(1=>'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',  'September', 'Oktober', 'November', 'Desember');
 | $lang['day_name'] = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
 | 
*/
 
if ( ! function_exists('ci_date'))
{
	function ci_date($date, $new_format)
	{
		$CI =& get_instance();
		$lang_active = $CI->config->item('language');
		$CI->lang->load('date', $lang_active);

		// D	=	Mon through Sun
		// l	=	Sunday through Saturday
		// N	=	1 (for Monday) through 7 (for Sunday)
		// w	=	0 (for Sunday) through 6 (for Saturday)
		// F	=	January through December
		// M	=	Jan through Dec
		
		$timestamp = strtotime($date);
		
		$prety_date = date($new_format, $timestamp);

		if ( strpos($new_format, 'F') !== FALSE )
		{
			$month_name     = $CI->lang->line('month_name');
			$month_global   = date('F', $timestamp);
			$month_global_n = date('n', $timestamp);
			$month_id       = $month_name[$month_global_n];
			$prety_date     = str_replace($month_global, $month_id, $prety_date);
		}
		if (strpos($new_format, 'M') !== FALSE) {
			$month_name = $CI->lang->line('month_name');
			$month_global2 = date('M', $timestamp);
			$month_global_n2 = date('n', $timestamp);
			$month_id2 = $month_name[$month_global_n2];
			$prety_date = str_replace($month_global2, substr($month_id2, 0, 3), $prety_date);
		}
		if (strpos($new_format, 'l') !== FALSE) {
			$day_name = $CI->lang->line('day_name');
			$day_global = date('l', $timestamp);
			$day_global_n = date('w', $timestamp);
			$day_id = $day_name[$day_global_n];
			$prety_date = str_replace($day_global, $day_id, $prety_date);
		}
		if (strpos($new_format, 'D') !== FALSE) {
			$day_name = $CI->lang->line('day_name');
			$day_global2 = date('D', $timestamp);
			$day_global_n2 = date('w', $timestamp);
			$day_id2 = $day_name[$day_global_n2];
			$prety_date = str_replace($day_global2, substr($day_id2, 0, 3), $prety_date);
		}
		return $prety_date;
	}
}

if ( ! function_exists('ci_timeago'))
{
	function ci_timeago($datetime)
	{
		$CI =& get_instance();
		$lang_active = $CI->config->item('language');
		$CI->lang->load('date', $lang_active);

		$today = time();
	    $createdday = strtotime($datetime);
	    $datediff = abs($today - $createdday);
	    $difftext = "";
	    $years = floor($datediff / (365 * 60 * 60 * 24));
	    $months = floor(($datediff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	    $days = floor(($datediff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
	    $hours = floor($datediff / 3600);
	    $minutes = floor($datediff / 60);
	    $seconds = floor($datediff);

	    //year checker
	    if ( $difftext == "" )
	    {
	        if ($years > 1)
	            $difftext = $years . " ".lang_line('timeago_years');
	        elseif ($years == 1)
	            $difftext = $years . " ".lang_line('timeago_year');
	    }
	    //month checker
	    if ($difftext == "") {
	        if ($months > 1)
	            $difftext = $months . " ".lang_line('timeago_months');
	        elseif ($months == 1)
	            $difftext = $months . " ".lang_line('timeago_month');
	    }
	    //month checker
	    if ($difftext == "") {
	        if ($days > 1)
	            $difftext = $days . " ".lang_line('timeago_days');
	        elseif ($days == 1)
	            $difftext = $days . " ".lang_line('timeago_day');
	    }
	    //hour checker
	    if ($difftext == "") {
	        if ($hours > 1)
	            $difftext = $hours . " ".lang_line('timeago_hours');
	        elseif ($hours == 1)
	            $difftext = $hours . " ".lang_line('timeago_hour');
	    }
	    //minutes checker
	    if ($difftext == "") {
	        if ($minutes > 1)
	            $difftext = $minutes . " " .lang_line('timeago_minutes');
	        elseif ($minutes == 1)
	            $difftext = $minutes . " ".lang_line('timeago_minute');
	    }
	    //seconds checker
	    if ($difftext == "") {
	        if ($seconds > 1)
	            $difftext = $seconds . " ".lang_line('timeago_seconds');
	        elseif ($seconds == 1)
	            $difftext = $seconds . " ".lang_line('timeago_second');
	    }
	    return $difftext;
	}
}