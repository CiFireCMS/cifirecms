<?php
require_once ('init.php');

if (session_id() == '') {
	session_start();
}

$version = "9.14.0";

if (!$_SESSION) {
	die("Access denied");
}
elseif (!$_SESSION['filemanager_access'] && !$_SESSION['FM_KEY']) {
	die("Access denied");
} 
elseif ($_SESSION['filemanager_access']['read_access'] == FALSE) {
	die("Access denied");
}

elseif ($_SESSION['filemanager_access']['read_access'] == TRUE) 
{
	$FMKEY = $_SESSION['FM_KEY'];

	$user_group    = $_SESSION['filemanager_access']['user_group'];
	$read_access   = $_SESSION['filemanager_access']['read_access'];
	$write_access  = $_SESSION['filemanager_access']['write_access'];
	$modify_access = $_SESSION['filemanager_access']['modify_access'];
	$delete_access = $_SESSION['filemanager_access']['delete_access'];

	mb_internal_encoding('UTF-8');
	mb_http_output('UTF-8');
	// mb_http_input('UTF-8');
	mb_language('uni');
	mb_regex_encoding('UTF-8');
	ob_start('mb_output_handler');
	date_default_timezone_set('Europe/Rome');
	setlocale(LC_CTYPE, 'en_US'); //correct transliteration

	$server_link = preg_replace("/\/$f_content\/plugins\/filemanager\/(dialog\.php$)/","",$_SERVER['PHP_SELF']);

	$medium_width = 640;
	$medium_height = 426;

	/*
	|--------------------------------------------------------------------------
	| Optional security
	|--------------------------------------------------------------------------
	|
	| if set to true only those will access RF whose url contains the access key(akey) like:
	| <input type="button" href="../filemanager/dialog.php?field_id=imgField&lang=en_EN&akey=myPrivateKey" value="Files">
	| in tinymce a new parameter added: filemanager_access_key:"myPrivateKey"
	| example tinymce config:
	|
	| tiny init ...
	| external_filemanager_path:"../filemanager/",
	| filemanager_title:"Filemanager" ,
	| filemanager_access_key:"myPrivateKey" ,
	| ...
	|
	*/

	define('USE_ACCESS_KEYS', TRUE); // TRUE or FALSE

	/*
	|--------------------------------------------------------------------------
	| DON'T COPY THIS VARIABLES IN FOLDERS config.php FILES
	|--------------------------------------------------------------------------
	*/

	define('DEBUG_ERROR_MESSAGE', false); // TRUE or FALSE


	/*
	|--------------------------------------------------------------------------
	| Path configuration
	|--------------------------------------------------------------------------
	| In this configuration the folder tree is
	| root
	|    |- source <- upload folder
	|    |- thumbs <- thumbnail folder [must have write permission (755)]
	|    |- filemanager
	|    |- js
	|    |   |- tinymce
	|    |   |   |- plugins
	|    |   |   |   |- responsivefilemanager
	|    |   |   |   |   |- plugin.min.js
	*/


	/*
	|--------------------------------------------------------------------------
	| DON'T TOUCH (base url (only domain) of site).
	|--------------------------------------------------------------------------
	|
	| without final / (DON'T TOUCH)
	|
	*/
	$config['base_url'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && ! in_array(strtolower($_SERVER['HTTPS']), array( 'off', 'no' ))) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

	/*
	|--------------------------------------------------------------------------
	| path from base_url to base of upload folder
	|--------------------------------------------------------------------------
	|
	| with start and final /
	|
	*/
	$config['upload_dir'] = $server_link.'/'.$f_content.'/uploads/';

	/*
	|--------------------------------------------------------------------------
	| relative path from filemanager folder to upload folder
	|--------------------------------------------------------------------------
	|
	| with final /
	|
	*/
	$config['current_path'] = '../../../'.$f_content.'/uploads/';

	/*
	|--------------------------------------------------------------------------
	| relative path from filemanager folder to thumbs folder
	|--------------------------------------------------------------------------
	|
	| with final /
	| DO NOT put inside upload folder
	|
	*/
	$config['thumbs_base_path'] = '../../../'.$f_content.'/thumbs/';

	/*
	|--------------------------------------------------------------------------
	| path from base_url to base of thumbs folder
	|--------------------------------------------------------------------------
	|
	| with final /
	| DO NOT put inside upload folder
	|
	*/
	$config['thumbs_upload_dir'] = '/'.$f_content.'/thumbs/';

	/*
	|--------------------------------------------------------------------------
	| mime file control to define files extensions
	|--------------------------------------------------------------------------
	|
	| If you want to be forced to assign the extension starting from the mime type
	|
	*/
	$config['mime_extension_rename'] = true;

	/*
	|--------------------------------------------------------------------------
	| FTP configuration BETA VERSION
	|--------------------------------------------------------------------------
	|
	| If you want enable ftp use write these parametres otherwise leave empty
	| Remember to set base_url properly to point in the ftp server domain and
	| upload dir will be ftp_base_folder + upload_dir so without final /
	|
	*/
	$config['ftp_host']         = false; //put the FTP host
	$config['ftp_user']         = "user";
	$config['ftp_pass']         = "pass";
	$config['ftp_base_folder']  = "base_folder";
	$config['ftp_base_url']     = "http://site to ftp root";

	// Directory where place files before to send to FTP with final /
	$config['ftp_temp_folder']  = "../temp/";

	/*
	|---------------------------------------------------------------------------
	| path from ftp_base_folder to base of thumbs folder with start and final /
	|---------------------------------------------------------------------------
	*/
	$config['ftp_thumbs_dir'] = '/thumbs/';
	$config['ftp_ssl']        = false;
	$config['ftp_port']       = 21;
	/* EXAMPLE
	'ftp_host'         => "host.com",
	'ftp_user'         => "test@host.com",
	'ftp_pass'         => "pass.1",
	'ftp_base_folder'  => "",
	'ftp_base_url'     => "http://host.com/testFTP",
	*/


	/*
	|--------------------------------------------------------------------------
	| Multiple files selection
	|--------------------------------------------------------------------------
	| The user can delete multiple files, select all files , deselect all files
	*/
	$config['multiple_selection'] = true;

	/*
	|
	| The user can have a select button that pass a json to external input or pass the first file selected to editor
	| If you use responsivefilemanager tinymce extension can copy into editor multiple object like images, videos, audios, links in the same time
	|
	 */
	$config['multiple_selection_action_button'] = true;

	/*
	|--------------------------------------------------------------------------
	| Access keys
	|--------------------------------------------------------------------------
	|
	| add access keys eg: array('myPrivateKey', 'someoneElseKey');
	| keys should only containt (a-z A-Z 0-9 \ . _ -) characters
	| if you are integrating lets say to a cms for admins, i recommend making keys randomized something like this:
	| $username = 'Admin';
	| $salt = 'dsflFWR9u2xQa' (a hard coded string)
	| $akey = md5($username.$salt);
	| DO NOT use 'key' as access key!
	| Keys are CASE SENSITIVE!
	|
	*/

	$config['access_keys'] = array($FMKEY);

	//--------------------------------------------------------------------------------------------------------
	// YOU CAN COPY AND CHANGE THESE VARIABLES INTO FOLDERS config.php FILES TO CUSTOMIZE EACH FOLDER OPTIONS
	//--------------------------------------------------------------------------------------------------------


	/*
	|--------------------------------------------------------------------------
	| Maximum size of all files in source folder
	|--------------------------------------------------------------------------
	|
	| in Megabytes
	|
	*/
	$config['MaxSizeTotal'] = false;

	/*
	|--------------------------------------------------------------------------
	| Maximum upload size
	|--------------------------------------------------------------------------
	|
	| in Megabytes
	|
	*/
	$config['MaxSizeUpload'] = 1024;

	/*
	|--------------------------------------------------------------------------
	| File and Folder permission
	|--------------------------------------------------------------------------
	|
	*/
	$config['filePermission'] = 0755;
	$config['folderPermission'] = 0777;

	/*
	|--------------------------------------------------------------------------
	| default language file name
	|--------------------------------------------------------------------------
	*/
	$config['default_language'] = 'en_EN';

	/*
	|--------------------------------------------------------------------------
	| Icon theme
	|--------------------------------------------------------------------------
	|
	| Default available: ico and ico_dark
	| Can be set to custom icon inside filemanager/img
	|
	*/
	$config['icon_theme'] = 'ico';


	//Show or not total size in filemanager (is possible to greatly increase the calculations)
	$config['show_total_size'] = false;
	//Show or not show folder size in list view feature in filemanager (is possible, if there is a large folder, to greatly increase the calculations)
	$config['show_folder_size'] = false;
	//Show or not show sorting feature in filemanager
	$config['show_sorting_bar'] = true;
	//Show or not show filters button in filemanager
	$config['show_filter_buttons'] = true;
	//Show or not language selection feature in filemanager
	$config['show_language_selection'] = false;
	//active or deactive the transliteration (mean convert all strange characters in A..Za..z0..9 characters)
	$config['transliteration'] = true;
	//convert all spaces on files name and folders name with $replace_with variable
	$config['convert_spaces'] = true;
	//convert all spaces on files name and folders name this value
	$config['replace_with'] = "_";
	//convert to lowercase the files and folders name
	$config['lower_case'] = false;

	//Add ?484899493349 (time value) to returned images to prevent cache
	$config['add_time_to_img'] = true;

	// -1: There is no lazy loading at all, 0: Always lazy-load images, 0+: The minimum number of the files in a directory
	// when lazy loading should be turned on.
	$config['lazy_loading_file_number_threshold']	= -1;

	/*
	|--------------------------------------------------------------------------
	| Load more
	|--------------------------------------------------------------------------
	*/
	// Enable "Load more" functionality
	$config['load_more'] = true;
	// Number of files/folders displayed at once
	// Should be greater than "file_number_limit_js"
	$config['load_more_limit'] = 21;
	// Enable auto-loading on scroll
	$config['load_more_auto'] = false;


	//*******************************************
	//Images limit and resizing configuration
	//*******************************************

	// set maximum pixel width and/or maximum pixel height for all images
	// If you set a maximum width or height, oversized images are converted to those limits. Images smaller than the limit(s) are unaffected
	// if you don't need a limit set both to 0
	$config['image_max_width'] = 0;
	$config['image_max_height'] = 0;
	$config['image_max_mode'] = 'auto';
	/*
	#  $option:  0 / exact = defined size;
	#            1 / portrait = keep aspect set height;
	#            2 / landscape = keep aspect set width;
	#            3 / auto = auto;
	#            4 / crop= resize and crop;
	*/

	//Automatic resizing //
	// If you set $image_resizing to TRUE the script converts all uploaded images exactly to image_resizing_width x image_resizing_height dimension
	// If you set width or height to 0 the script automatically calculates the other dimension
	// Is possible that if you upload very big images the script not work to overcome this increase the php configuration of memory and time limit
	$config['image_resizing']          = false;
	$config['image_resizing_width']    = 0;
	$config['image_resizing_height']   = 0;
	$config['image_resizing_mode']     = 'auto';// same as $image_max_mode
	$config['image_resizing_override'] = false;
	// If set to TRUE then you can specify bigger images than $image_max_width & height otherwise if image_resizing is
	// bigger than $image_max_width or height then it will be converted to those values



	//******************
	//
	// WATERMARK IMAGE
	//
	//Watermark path or false
	$config['image_watermark'] = false; //"../watermark.png",
	# Could be a pre-determined position such as:
	#           tl = top left,
	#           t  = top (middle),
	#           tr = top right,
	#           l  = left,
	#           m  = middle,
	#           r  = right,
	#           bl = bottom left,
	#           b  = bottom (middle),
	#           br = bottom right
	#           Or, it could be a co-ordinate position such as: 50x100
	$config['image_watermark_position'] = 'br';
	# padding: If using a pre-determined position you can
	#         adjust the padding from the edges by passing an amount
	#         in pixels. If using co-ordinates, this value is ignored.
	$config['image_watermark_padding'] = 10;


	//******************
	// Default layout setting
	//
	// 0 => boxes
	// 1 => detailed list (1 column)
	// 2 => columns list (multiple columns depending on the width of the page)
	// YOU CAN ALSO PASS THIS PARAMETERS USING SESSION VAR => $_SESSION['RF']["VIEW"]=
	//
	//******************
	$config['default_view'] = 0;

	//set if the filename is truncated when overflow first row
	$config['ellipsis_title_after_first_row'] = true;

	//*************************
	//Permissions configuration
	//******************
	$config['delete_files'] = $delete_access;
	$config['create_folders'] = $write_access;
	$config['delete_folders'] = $delete_access;
	$config['upload_files'] = $write_access;
	$config['rename_files'] = $modify_access;
	$config['rename_folders'] = $modify_access;
	$config['duplicate_files'] = false;
	$config['extract_files'] = false;
	$config['copy_cut_files'] = false; // for copy/cut files
	$config['copy_cut_dirs'] = false; // for copy/cut directories
	$config['chmod_files'] = false; // change file permissions
	$config['chmod_dirs'] = false; // change folder permissions
	$config['preview_text_files'] = true; // eg.: txt, log etc.
	$config['edit_text_files'] = true; // eg.: txt, log etc.
	$config['create_text_files'] = false; // only create files with exts. defined in $config['editable_text_file_exts']
	$config['download_files'] = $write_access; // allow download files or just preview

	// you can preview these type of files if $preview_text_files is true
	$config['previewable_text_file_exts'] = array('txt');

	// you can edit these type of files if $edit_text_files is true (only text based files)
	// you can create these type of files if $config['create_text_files'] is true (only text based files)
	// if you want you can add html,css etc.
	// but for security reasons it's NOT RECOMMENDED!
	$config['editable_text_file_exts'] = array( 'txt' );
	$config['jplayer_exts'] = array("mp4","flv","webmv","webma","webm","m4a","m4v","ogv","oga","mp3","midi","mid","ogg","wav");
	$config['cad_exts'] = array('dwg','dxf','hpgl','plt','spl','step','stp','iges','igs','sat','cgm','svg');

	// Preview with Google Documents
	$config['googledoc_enabled'] = false;
	$config['googledoc_file_exts'] = array('doc','docx','xls','xlsx','ppt','pptx' ,'pdf','odt','odp','ods');


	// defines size limit for paste in MB / operation
	// set 'FALSE' for no limit
	$config['copy_cut_max_size'] = 100;
	// defines file count limit for paste / operation
	// set 'FALSE' for no limit
	$config['copy_cut_max_count'] = 200;
	//IF any of these limits reached, operation won't start and generate warning

	//**********************
	// Allowed extensions (lowercase insert)
	//**********************
	$config['ext_img']   = array('jpg','jpeg','png','gif','bmp','svg','ico'); //Images
	$config['ext_file']  = array('txt','pdf','doc','docx','ppt','pptx','rtf','xls','xlsx','csv'); //Files
	$config['ext_video'] = array('mov','mpeg','m4v','mp4','avi','mpg','wma','flv','webm'); //Video
	$config['ext_music'] = array('mp3','mpga','m4a','ac3','aiff','mid','ogg','wav'); //Audio
	$config['ext_misc']  = array('zip','rar','gz','tar','iso','dmg'); //Archives


	//*********************
	//  If you insert an extensions blacklist array the filemanager don't check any extensions but simply block the extensions in the list
	//  otherwise check Allowed extensions configuration
	//*********************
	$config['ext_blacklist'] = false; //['exe','bat','jpg'],

	//Empty filename permits like .htaccess, .env, ...
	$config['empty_filename'] = false;

	/*
	|--------------------------------------------------------------------------
	| accept files without extension
	|--------------------------------------------------------------------------
	|
	| If you want to accept files without extension, remember to add '' extension on allowed extension
	|
	*/
	$config['files_without_extension'] = false;


	/******************
	* TUI Image Editor config
	*******************/
	// Add or modify the options below as needed - they will be json encoded when added to the configuration so arrays can be utilized as needed
	$config['tui_active'] = false;
	$config['tui_position'] = 'bottom';
	// 'common.bi.image'                      => "../assets/images/logo.png",
	// 'common.bisize.width'                  => '70px',
	// 'common.bisize.height'                 => '25px',
	$config['common.backgroundImage']               = 'none';
	$config['common.backgroundColor']               = '#ececec';
	$config['common.border']                        = '1px solid #E6E7E8';

	// header
	$config['header.backgroundImage']               = 'none';
	$config['header.backgroundColor']               = '#ececec';
	$config['header.border']                        = '0px';

	// main icons
	$config['menu.normalIcon.path']                 = 'svg/icon-d.svg';
	$config['menu.normalIcon.name']                 = 'icon-d';
	$config['menu.activeIcon.path']                 = 'svg/icon-b.svg';
	$config['menu.activeIcon.name']                 = 'icon-b';
	$config['menu.disabledIcon.path']               = 'svg/icon-a.svg';
	$config['menu.disabledIcon.name']               = 'icon-a';
	$config['menu.hoverIcon.path']                  = 'svg/icon-c.svg';
	$config['menu.hoverIcon.name']                  = 'icon-c';
	$config['menu.iconSize.width']                  = '24px';
	$config['menu.iconSize.height']                 = '24px';

	// submenu primary color
	$config['submenu.backgroundColor']              = '#ececec';
	$config['submenu.partition.color']              = '#000000';

	// submenu icons
	$config['submenu.normalIcon.path']              = 'svg/icon-d.svg';
	$config['submenu.normalIcon.name']              = 'icon-d';
	$config['submenu.activeIcon.path']              = 'svg/icon-b.svg';
	$config['submenu.activeIcon.name']              = 'icon-b';
	$config['submenu.iconSize.width']               = '32px';
	$config['submenu.iconSize.height']              = '32px';

	// submenu labels
	$config['submenu.normalLabel.color']            = '#000';
	$config['submenu.normalLabel.fontWeight']       = 'normal';
	$config['submenu.activeLabel.color']            = '#000';
	$config['submenu.activeLabel.fontWeight']       = 'normal';

	// checkbox style
	$config['checkbox.border']                      = '1px solid #E6E7E8';
	$config['checkbox.backgroundColor']             = '#000';

	// rango style
	$config['range.pointer.color']                  = '#333';
	$config['range.bar.color']                      = '#ccc';
	$config['range.subbar.color']                   = '#606060';

	$config['range.disabledPointer.color']          = '#d3d3d3';
	$config['range.disabledBar.color']              = 'rgba(85,85,85,0.06)';
	$config['range.disabledSubbar.color']           = 'rgba(51,51,51,0.2)';

	$config['range.value.color']                    = '#000';
	$config['range.value.fontWeight']               = 'normal';
	$config['range.value.fontSize']                 = '11px';
	$config['range.value.border']                   = '0';
	$config['range.value.backgroundColor']          = '#f5f5f5';
	$config['range.title.color']                    = '#000';
	$config['range.title.fontWeight']               = 'lighter';

	// colorpicker style
	$config['colorpicker.button.border']            = '0px';
	$config['colorpicker.title.color']              = '#000';


	//The filter and sorter are managed through both javascript and php scripts because if you have a lot of
	//file in a folder the javascript script can't sort all or filter all, so the filemanager switch to php script.
	//The plugin automatic swich javascript to php when the current folder exceeds the below limit of files number
	$config['file_number_limit_js'] = 12;

	//**********************
	// Hidden files and folders
	//**********************
	// set the names of any folders you want hidden (eg "hidden_folder1", "hidden_folder2" ) Remember all folders with these names will be hidden (you can set any exceptions in config.php files on folders)
	if ($user_group=='root') {
		$config['hidden_folders'] = array('medium');
	} else {
		$config['hidden_folders'] = array('medium','user','favicon');
	}
	// set the names of any files you want hidden. Remember these names will be hidden in all folders (eg "this_document.pdf", "that_image.jpg" )
	$config['hidden_files'] = array('config.php','index.html');

	/*******************
	* URL upload
	*******************/
	$config['url_upload'] = false;


	//************************************
	// Thumbnail for external use creation
	//************************************


	// New image resized creation with fixed path from filemanager folder after uploading (thumbnails in fixed mode)
	// If you want create images resized out of upload folder for use with external script you can choose this method,
	// You can create also more than one image at a time just simply add a value in the array
	// Remember than the image creation respect the folder hierarchy so if you are inside source/test/test1/ the new image will create at
	// path_from_filemanager/test/test1/
	// PS if there isn't write permission in your destination folder you must set it
	//
	$config['fixed_image_creation']                    = true; //activate or not the creation of one or more image resized with fixed path from filemanager folder
	$config['fixed_path_from_filemanager']             = array('../../../'.$f_content.'/uploads/medium/'); //fixed path of the image folder from the current position on upload folder
	$config['fixed_image_creation_name_to_prepend']    = array(''); //name to prepend on filename
	$config['fixed_image_creation_to_append']          = array(''); //name to appendon filename
	$config['fixed_image_creation_width']              = array($medium_width); //width of image
	$config['fixed_image_creation_height']             = array($medium_height); //height of image
	/*
	#             $option:     0 / exact = defined size;
	#                          1 / portrait = keep aspect set height;
	#                          2 / landscape = keep aspect set width;
	#                          3 / auto = auto;
	#                          4 / crop= resize and crop;
	*/
	$config['fixed_image_creation_option']             = array('crop','crop'); //set the type of the crop

	// New image resized creation with relative path inside to upload folder after uploading (thumbnails in relative mode)
	// With Responsive filemanager you can create automatically resized image inside the upload folder, also more than one at a time
	// just simply add a value in the array
	// The image creation path is always relative so if i'm inside source/test/test1 and I upload an image, the path start from here
	//
	$config['relative_image_creation']                 = false; //activate or not the creation of one or more image resized with relative path from upload folder
	$config['relative_path_from_current_pos']          = array('medium/'); //relative path of the image folder from the current position on upload folder
	$config['relative_image_creation_name_to_prepend'] = array(''); //name to prepend on filename
	$config['relative_image_creation_name_to_append']  = array(''); //name to append on filename
	$config['relative_image_creation_width']           = array($medium_width); //width of image
	$config['relative_image_creation_height']          = array($medium_height); //height of image

	/*
	 * $option:     0 / exact = defined size;
	 *              1 / portrait = keep aspect set height;
	 *              2 / landscape = keep aspect set width;
	 *              3 / auto = auto;
	 *              4 / crop= resize and crop;
	 */
	$config['relative_image_creation_option'] = array('crop','crop'); //set the type of the crop

	// Remember text filter after close filemanager for future session
	$config['remember_text_filter'] = false;


	return array_merge(
		$config,
		array(
			'ext' => array_merge(
				$config['ext_img'],
				$config['ext_file'],
				$config['ext_misc'],
				$config['ext_video'],
				$config['ext_music']
			),
			'tui_defaults_config' => array(
				//'common.bi.image'                   => $config['common.bi.image'],
				//'common.bisize.width'               => $config['common.bisize.width'],
				//'common.bisize.height'              => $config['common.bisize.height'], 
				'common.backgroundImage'            => $config['common.backgroundImage'],
				'common.backgroundColor'            => $config['common.backgroundColor'], 
				'common.border'                     => $config['common.border'],
				'header.backgroundImage'            => $config['header.backgroundImage'],
				'header.backgroundColor'            => $config['header.backgroundColor'],
				'header.border'                     => $config['header.border'],
				'menu.normalIcon.path'              => $config['menu.normalIcon.path'],
				'menu.normalIcon.name'              => $config['menu.normalIcon.name'],
				'menu.activeIcon.path'              => $config['menu.activeIcon.path'],
				'menu.activeIcon.name'              => $config['menu.activeIcon.name'],
				'menu.disabledIcon.path'            => $config['menu.disabledIcon.path'],
				'menu.disabledIcon.name'            => $config['menu.disabledIcon.name'],
				'menu.hoverIcon.path'               => $config['menu.hoverIcon.path'],
				'menu.hoverIcon.name'               => $config['menu.hoverIcon.name'],
				'menu.iconSize.width'               => $config['menu.iconSize.width'],
				'menu.iconSize.height'              => $config['menu.iconSize.height'],
				'submenu.backgroundColor'           => $config['submenu.backgroundColor'],
				'submenu.partition.color'           => $config['submenu.partition.color'],
				'submenu.normalIcon.path'           => $config['submenu.normalIcon.path'],
				'submenu.normalIcon.name'           => $config['submenu.normalIcon.name'],
				'submenu.activeIcon.path'           => $config['submenu.activeIcon.path'],
				'submenu.activeIcon.name'           => $config['submenu.activeIcon.name'],
				'submenu.iconSize.width'            => $config['submenu.iconSize.width'],
				'submenu.iconSize.height'           => $config['submenu.iconSize.height'],
				'submenu.normalLabel.color'         => $config['submenu.normalLabel.color'],
				'submenu.normalLabel.fontWeight'    => $config['submenu.normalLabel.fontWeight'],
				'submenu.activeLabel.color'         => $config['submenu.activeLabel.color'],
				//'submenu.activeLabel.fontWeight'    => $config['submenu.activeLabel.fontWeightcommon.bi.image'],
				'checkbox.border'                   => $config['checkbox.border'],
				'checkbox.backgroundColor'          => $config['checkbox.backgroundColor'],
				'range.pointer.color'               => $config['range.pointer.color'],
				'range.bar.color'                   => $config['range.bar.color'],
				'range.subbar.color'                => $config['range.subbar.color'],
				'range.disabledPointer.color'       => $config['range.disabledPointer.color'],
				'range.disabledBar.color'           => $config['range.disabledBar.color'],
				'range.disabledSubbar.color'        => $config['range.disabledSubbar.color'],
				'range.value.color'                 => $config['range.value.color'],
				'range.value.fontWeight'            => $config['range.value.fontWeight'],
				'range.value.fontSize'              => $config['range.value.fontSize'],
				'range.value.border'                => $config['range.value.border'],
				'range.value.backgroundColor'       => $config['range.value.backgroundColor'],
				'range.title.color'                 => $config['range.title.color'],
				'range.title.fontWeight'            => $config['range.title.fontWeight'],
				'colorpicker.button.border'         => $config['colorpicker.button.border'],
				'colorpicker.title.color'           => $config['colorpicker.title.color']
			),
		)
	);


} else {
	echo "Access denied";
	exit;
}
