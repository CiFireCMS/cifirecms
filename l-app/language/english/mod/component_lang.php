<?php defined('BASEPATH') OR exit('No direct script access allowed');

$lang['mod_title'] = 'Component';
$lang['mod_title_all'] = 'All Component';
$lang['mod_title_installation'] = 'Component Installation';

$lang['_component'] = 'Component';
$lang['_mod'] = 'Mod';
$lang['_t_name'] = 'Table Name';
$lang['_status'] = 'Status';
$lang['_action'] = 'Action';

$lang['_component_package'] = 'Component Package';

$lang['_instructions'] = 'Instructions';

$lang['_instruction_content'] = '
		<li>Upload the .zip component package file which can be downloaded via <a href="#" target="_blank" class="text-primary">CiFireCMS official website</a> or from a trusted developer.</li>
		<li>The system will automatically install the component files that you need.</li>
		<li>If an error occurs, please repeat the steps from the beginning. If there are the same components, the system will not run the installation process.</li>
		<li>A standard component package contains files : controllers, models, views, modjs, sql and configuration file for installation.</li>
		<li>If after the component installation, please give permission to user group for access a component.</li>';

$lang['form_message_add_success'] = 'Component has been successfully installed';

$lang['err_install_package'] = 'ERROR..! Component package is corrupt or some files have been installed before';
$lang['err_component_notfound'] = 'ERROR..! Component not found';
$lang['err_config_notfound'] = 'ERROR..! Installation config not found';
