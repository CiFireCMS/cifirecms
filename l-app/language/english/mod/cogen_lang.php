<?php defined('BASEPATH') OR exit('No direct script access allowed');

$lang['mod_title'] = 'CoGen';
$lang['mod_setp1'] = 'Component';
$lang['mod_setp2'] = 'Database';
$lang['mod_setp3'] = 'Configuration';
$lang['mod_setp4'] = 'Finish';
$lang['button_generate'] = 'Generate Component';
$lang['mod_setp4_1'] = 'By clicking <span class="badge badge-primary badge-pill">'. $lang['button_generate'] .'</span> you agree to our';
$lang['mod_setp4_2'] = 'terms of use.';
$lang['mod_success1'] = 'Component has been successfully created';
$lang['label_component_name'] = 'Component Name';
$lang['label_component_type'] = 'Component Type';
$lang['label_table_name'] = 'Table Name';
$lang['label_table_filed'] = 'Table Field';
$lang['label_field'] = 'Field';
$lang['label_field_name'] = 'Field Name';
$lang['label_field_type'] = 'Field Type';
$lang['label_field_Length_values'] = 'Field Length / Values';
$lang['label_field_default_values'] = 'Field Default Values';
$lang['label_conf_action'] = 'Action Elements';
$lang['label_conf_read'] = 'Read';
$lang['label_conf_add'] = 'Add';
$lang['label_conf_edit'] = 'Edit';
$lang['label_conf_delete'] = 'Delete';
$lang['label_conf_multiple_delete'] = 'Multiple Delete';
$lang['label_conf_Browse'] = 'Field For Browse File';
$lang['label_conf_tinymce'] = 'Field For Content TinyMCE';
$lang['label_conf_select'] = 'Field For Select Input';
$lang['label_conf_option'] = 'Option';
$lang['label_conf_datatable'] = 'Datatable';
$lang['label_conf_frontend'] = 'Front End';
$lang['label_conf_column'] = 'Column';
$lang['label_conf_column_name'] = 'Column Name';
$lang['label_conf_field_data'] = 'Field Data';
$lang['delete_field'] = 'Delte field';
$lang['button_add_field'] = 'Add field';
$lang['button_add_option'] = 'Add option';
$lang['button_add_column'] = 'Add column';
$lang['button_next'] = 'Next';
$lang['button_prev'] = 'Previous';
$lang['button_goto_component'] = 'Go To Component';
$lang['mod_db_error1'] = 'Database Error.! <br> Field must be more than 3 items';
$lang['mod_db_error2'] = 'Database Error.! <br>
						  <li>If you continue, the table will be removed.</li>
						  <li>Please check the database configuration.</li>
						  <li>To Be carefully !</li>';
$lang['mod_tos'] = '
<li><p>Make sure all web files and databases have been backed up first. Because we are not responsible when an error occurs in the generator process and the web does not work properly.</p></li>
<li><p>Component modules generated from CoGen are components for ordinary data CRUD (Create Read Update Delete) processes. For further additional features, we submit it to the developer.</p></li>
<li><p>CoGen will automatically create a new component module file and create a new table in the database.</p></li>';