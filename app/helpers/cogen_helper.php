<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('dump_file_html')) {
/**
 * - File index.html
 * - Ini adalah fungsi untuk membuat konten file index.html.
 * 
 * @return void|string
*/

function dump_file_html() {
$write = <<<EOS
<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>
EOS;
return $write;
} //----------------------> End funcion. File index.html
}




if ( ! function_exists('dump_view_index')) {
/**
 *-------------------------------------------------------------
 *  VIEW INDEX.
 *-------------------------------------------------------------
*/

/**
 * - View index.
 * - Ini adalah fungsi untuk membuat konten view view_index.php
 * 
 * @param 	string|array 	$data
 * @return 	void|string
*/
function dump_view_index($data='') {
$data_general    = $data['general'];
$data_config     = $data['conf'];
$data_col_name_1 = $data['conf_column_name_1'];
$data_field_1    = $data['com_filed_name_1'];
$component_name  = trim($data_general['component_name']);

if (!empty($data['field'])) {
	$data_fields = $data['field'];
}

	$colspan = '';
if (!empty($data['col'])) {
	$data_cols = $data['col'];

	$colspan = count($data_cols)+3;
}

$write = <<<EOS
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item">{$component_name}</a>
					</div>
					<h4 class="pd-0 mg-0 tx-20">{$component_name}</h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">\n
EOS;



//----------> Tambahkan tombol Add New.
if (!empty($data_config['action']['add'])) {
$write .= <<<EOS
			<button type="button" class="btn btn-md pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url(\$this->mod.'/add');?>'"><i data-feather="plus" class="mr-2"></i><?=lang_line('button_add');?></button>\n
EOS;
} //-------> End if.


$write .= <<<EOS
		</div>
	</div>

	<div>
		<?=\$this->cifire_alert->show(\$this->mod);?>
		<div class="ajax_alert" style="display:none;"></div>
	</div>
	
	<div class="card">
		<div class="table-responsive">
			<div class="card-body">
				<table id="DataTable" class="table table-striped table-bordered table-datatable">
					<thead>
						<tr>\n
EOS;

//----------> Tambahkan fitur "checkbox select all" delete_multiple.
if (!empty($data_config['action']['delete_multiple'])) {
$write .= <<<EOS
							<th class="no-sort text-center"><input type="checkbox" class="select_all" data-toggle="tooltip" data-placement="top" data-title="<?=lang_line('ui_select_all');?>"/></th>\n
EOS;
} //-----> End if. checkbox select all.

//----------> Tabel Kolom 1.
$write .= <<<EOS
							<th>{$data_col_name_1}</th>\n
EOS;

//----------> Tabel Kolom lainya.
if (!empty($data['col'])) {
foreach ($data_cols as $key_col => $row_col) {
$th_name = humanize($row_col['col_name']);
$write .= <<<EOS
							<th>{$th_name}</th>\n
EOS;
} //--------> End foreach.
}

$write .= <<<EOS
							<th class="th-action text-center">Action</th>
						</tr>
					</thead>
					<tbody></tbody>\n
EOS;

//----------> Tambahkan tombol "Delete Selected Item" delete_multiple.
if (!empty($data_config['action']['delete_multiple'])) {
$write .= <<<EOS
					<tfoot>
						<tr>
							<td colspan="{$colspan}">
								<button type="button" class="btn btn-sm btn-danger delete_multi"><?=lang_line('button_delete_selected_item');?></button>
							</td>
						</tr>
					</tfoot>\n
EOS;
} //--------> End if.



$write .= <<<EOS
				</table>
			</div>
		</div>
	</div>
</div>
EOS;

return $write;
} //----------------------------> End function.  Dump View Index.
} // endif





















if ( ! function_exists('dump_view_add')) {
/**
 * ---------------------------------------------------------
 *  VIEW ADD
 * ---------------------------------------------------------
*/

/**
 * - View add.
 * - Ini adalah fungsi untuk membuat konten view view_add.php
 * 
 * @param 	string|array 	$data
 * @return 	void|string
*/
function dump_view_add($data = '') {
$write = '';
$data_general = $data['general'];
$component_name = trim($data_general['component_name']);
$data_field_1 = $data['com_filed_name_1'];

if (!empty($data['field'])) {
	$data_fields = $data['field'];
}

$data_col_name_1 = $data['conf_column_name_1'];

if (!empty($data['col'])) {
	$data_cols = $data['col'];
}

$data_config = $data['conf'];

$write .= <<<EOS
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_component');?></a>
						<a href="#" class="breadcrumb-item">{$component_name}</a>
						<a href="#" class="breadcrumb-item">Add Data</a>
					</div>
					<h4 class="pd-0 mg-0 tx-20">{$component_name}</h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-md pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url(\$this->mod);?>'"><i data-feather="arrow-left" class="mr-2"></i><?=lang_line('button_back');?></button>
		</div>
	</div>

	<div>
		<?=\$this->cifire_alert->show(\$this->mod);?>
		<div class="ajax_alert" style="display:none;"></div>
	</div>

	<div class="card">
		<div class="card-header">
			<h6 class="lh-5 mg-b-0">Add Data</h6>
		</div>
		<?php 
			echo form_open('','autocomplete="off" class="form-bordered"');
			echo form_hidden('act', 'add');
		?>
		<div class="card-body">\n
EOS;


//--- data_fields ------------------------------------------------//
if (!empty($data['field'])) {
	
foreach ($data_fields as $key_field => $val_field) {

$field_name    = $val_field['com_filed_name'];
$field_type    = $val_field['com_filed_type'];
$input_label   = ucfirst($field_name);
$input_name    = $field_name;
$filed_lenght  = $val_field['com_filed_lenght'];
$filed_default = $val_field['com_filed_default'];

//-----------> Start. FIELD TYPE VARCHAR.
if ($field_type == "VARCHAR") {

//-----------> Input browse filemanager (VARCHAR).
if (!empty($data_config['field_browse']) && $data_config['field_browse'] == $field_name) {
$write_input_filemanager_varchar = write_input_filemanager($input_label, $field_name);
$write .= <<< EOS
{$write_input_filemanager_varchar}
EOS;
} //-----------> End. Input browse filemanager (VARCHAR).


//-----------> Start. Input select
elseif (!empty($data_config['field_select']) && $data_config['field_select'] == $field_name) {
$write_input_select = write_input_select($input_label, $field_name, $val='', $data_config['field_select_option']);
$write .= <<<EOS
{$write_input_select}
EOS;
} //-----------> End. Input select


//-----------> Start. Input text (VARCHAR).
else {
$write_input_text_varchar = write_input_text($input_label, $field_name);
$write .= <<< EOS
{$write_input_text_varchar}
EOS;
} //-----------> End. Input text (VARCHAR).

} //-----------> End. FIELD TYPE VARCHAR.


//-----------> Start. FIELD TYPE INT.
elseif ($field_type == "INT") {
$write_input_number = write_input_number($input_label, $field_name, $val = '');
$write .= <<< EOS
{$write_input_number}
EOS;
} //-----------> End. FIELD TYPE INT.


//-----------> Start. FIELD TYPE DATE.
elseif ($field_type == "DATE") {
$write_input_date = write_input_date($input_label, $field_name);
$write .= <<<EOS
{$write_input_date}
EOS;
} //-----------> End. FIELD TYPE DATE.


//-----------> Start. FIELD TYPE TIME.
elseif ($field_type == "TIME") {
$write_input_time = write_input_time($input_label, $field_name);
$write .= <<<EOS
{$write_input_time}
EOS;
} //-----------> End. FIELD TYPE TIME.



//-----------> Start. FIELD TYPE DATETIME.
elseif ($field_type == "DATETIME") {
$write_input_datetime = write_input_datetime($input_label, $field_name);
$write .= <<<EOS
{$write_input_datetime}
EOS;
} //-----------> End. FIELD TYPE DATETIME.



//-----------> Start. FIELD TYPE TEXT.
elseif ($field_type == "TEXT") {

//-----------> Start. Input TinyMCE (TEXT).
if (!empty($data_config['field_tinymce']) && $data_config['field_tinymce'] == $field_name) {
$write_input_tinymce = write_input_tinymce($input_label, $field_name);
$write .= <<<EOS
{$write_input_tinymce}
EOS;
} //-----------> End. Input TinyMCE (TEXT).


//-----------> Input browse filemanager (TEXT).
elseif (!empty($data_config['field_browse']) && $data_config['field_browse'] == $field_name) {
$write_input_filemanager_varchar = write_input_filemanager($input_label, $field_name);
$write .= <<< EOS
{$write_input_filemanager_varchar}
EOS;
} //-----------> End. Input browse filemanager (TEXT).

//-----------> Start. Input textarea (TEXT).
else {
$write_input_textarea = write_input_textarea($input_label, $field_name);
$write .= <<<EOS
{$write_input_textarea}
EOS;
} //-----------> Start. Input textarea (TEXT).

} //-----------> End. FIELD TYPE TEXT.



//-----------> Start. Input ENUM.
elseif ($field_type == "ENUM") {
$op_enum = explode(',', $filed_lenght);
$write_input_enum = write_input_enum($input_label, $field_name, $op_enum, $filed_default, FALSE);
$write .= <<<EOS
{$write_input_enum}
EOS;
} //-----------> Start. Input ENUM.

} //-----------> End forech. $data_fields.

} //-----------> End if data['field']


$write .= <<<EOS
			</div> <!-- card-body -->
			<div class="card-footer">
				<button type="submit" class="btn btn-lg btn-primary mr-2"><i class="cificon licon-send mr-2"></i><?=lang_line('button_submit');?></button>
			</div>
		<?=form_close();?>
	</div> <!-- card -->
</div> <!-- page-inner -->
EOS;


return $write;
} //----------------------------------> End funcion. VIEW ADD NEW. 
} // endif















if ( ! function_exists('dump_view_edit')) {
/**
 * -----------------------------------------------------------
 *  VIEW EDIT
 * -----------------------------------------------------------
*/

/**
 * - View edit
 * - Ini adalah fungsi untuk membuat konten view view_edit.php
 * 
 * @param 	string|array 	$data
 * @return 	void|string
*/
function dump_view_edit($data = '') {

$write = '';
$data_general = $data['general'];
$component_name = trim($data_general['component_name']);

$data_field_1 = $data['com_filed_name_1'];

if (!empty($data['field'])) {	
	$data_fields = $data['field'];
}

$data_col_name_1 = $data['conf_column_name_1'];

if (!empty($data['col'])) {
	$data_cols = $data['col'];
}

$data_config = $data['conf'];

$write .= <<<EOS
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_component');?></a>
						<a href="#" class="breadcrumb-item">{$component_name}</a>
						<a href="#" class="breadcrumb-item">Edit Data</a>
					</div>
					<h4 class="pd-0 mg-0 tx-20">{$component_name}</h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-md pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url(\$this->mod);?>'"><i data-feather="arrow-left" class="mr-2"></i><?=lang_line('button_back');?></button>
		</div>
	</div>

	<div>
		<?=\$this->cifire_alert->show(\$this->mod);?>
		<div class="ajax_alert" style="display:none;"></div>
	</div>

	<div class="card">
		<div class="card-header">
			<h6 class="lh-5 mg-b-0">Edit Data</h6>
		</div>
		<?php 
			echo form_open('','autocomplete="off"');
			echo form_hidden('act', '1');
		?>
		<div class="card-body">\n
EOS;


//-- data_fields ---------------------------------------------------//.
if (!empty($data['field'])) {

foreach ($data_fields as $key_field => $val_field) {

$field_name    = $val_field['com_filed_name'];
$field_type    = $val_field['com_filed_type'];
$filed_lenght  = $val_field['com_filed_lenght'];
$filed_default = $val_field['com_filed_default'];
$input_label   = ucfirst($field_name);
$input_name    = $field_name;

//-----------> Start. FIELD TYPE VARCHAR.
if ($field_type == "VARCHAR") {

//-----------> Input browse filemanager (VARCHAR).
if (!empty($data_config['field_browse']) && $data_config['field_browse'] == $field_name) {
$write_input_filemanager_varchar = write_input_filemanager($input_label, $field_name, TRUE);
$write .= <<< EOS
{$write_input_filemanager_varchar}
EOS;
} //-----------> End. Input browse filemanager (VARCHAR).

//-----------> Start. Input select (VARCHAR)
elseif (!empty($data_config['field_select']) && $data_config['field_select'] == $field_name) {
$write_input_select = write_input_select($input_label, $field_name, TRUE, $data_config['field_select_option']);
$write .= <<<EOS
{$write_input_select}
EOS;
} //-----------> End. Input select (VARCHAR)

//-----------> Start. Input text (VARCHAR).
else {
$write_input_text_varchar = write_input_text($input_label, $field_name, TRUE);
$write .= <<< EOS
{$write_input_text_varchar}
EOS;
} //-----------> End. Input text (VARCHAR).

} //-----------> End. FIELD TYPE VARCHAR.
	

//-----------> Start. FIELD TYPE INT.
elseif ($field_type == "INT") {
$write_input_number = write_input_number($input_label, $field_name, TRUE);
$write .= <<< EOS
{$write_input_number}
EOS;
} //-----------> End. FIELD TYPE INT.


//-----------> Start. FIELD TYPE DATE.
elseif ($field_type == "DATE") {
$write_input_date = write_input_date($input_label, $field_name, TRUE);
$write .= <<<EOS
{$write_input_date}
EOS;
} //-----------> End. FIELD TYPE DATE.


//-----------> Start. FIELD TYPE TIME.
elseif ($field_type == "TIME") {
$write_input_time = write_input_time($input_label, $field_name, TRUE);
$write .= <<<EOS
{$write_input_time}
EOS;
} //-----------> End. FIELD TYPE TIME.


//-----------> Start. FIELD TYPE DATETIME.
elseif ($field_type == "DATETIME") {
$datetime = write_input_datetime($input_label, $field_name, TRUE);
$write .= <<<EOS
{$datetime}
EOS;
} //-----------> End. FIELD TYPE DATETIME.


//-----------> Start. FIELD TYPE TEXT.
elseif ($field_type == "TEXT") {

//-----------> Start. Input TinyMCE (TEXT).
if (!empty($data_config['field_tinymce']) && $data_config['field_tinymce'] == $field_name) {
$write_input_tinymce = write_input_tinymce($input_label, $field_name, TRUE);
$write .= <<<EOS
{$write_input_tinymce}
EOS;
} //-----------> End. Input TinyMCE (TEXT).

//-----------> Input browse filemanager (TEXT).
elseif (!empty($data_config['field_browse']) && $data_config['field_browse'] == $field_name) {
$write_input_filemanager_varchar = write_input_filemanager($input_label, $field_name, TRUE);
$write .= <<< EOS
{$write_input_filemanager_varchar}
EOS;
} //-----------> End. Input browse filemanager (TEXT).

//-----------> Start. Input textarea (TEXT).
else {
$write_input_textarea = write_input_textarea($input_label, $field_name, TRUE);
$write .= <<<EOS
{$write_input_textarea}
EOS;
} //-----------> Start. Input textarea (TEXT).

} //-----------> End. FIELD TYPE TEXT.


//-----------> Start. Input ENUM.
elseif ($field_type == "ENUM") {
$op_enum = explode(',', $filed_lenght);
$write_input_enum = write_input_enum($input_label, $field_name, $op_enum, $filed_default, TRUE);
$write .= <<<EOS
{$write_input_enum}
EOS;
} //-----------> End. Input ENUM.

} //---------------------------------------------> End forech. $data_fields.

} //-----------> End. IF data_fields.

$write .= <<<EOS
			</div> <!-- card-body -->
			<div class="card-footer">
				<button type="submit" class="btn btn-lg btn-primary mr-2"><i class="cificon licon-save mr-2"></i><?=lang_line('button_save');?></button>
			</div>
		<?=form_close();?>
	</div> <!-- card -->
</div> <!-- page-inner -->
EOS;

return $write;
} //----------------------------------> End funcion. VIEW EDIT. 
} // endif















if ( ! function_exists('dump_file_javascript')) {
/**
 * -------------------------------------------------------------
 * Dum for file mod js *.js
 * -------------------------------------------------------------
*/

/**
 * - File Mod Js
 * - Ini adalah fungsi untuk membuat konten file script modjs.
 * 
 * @param 	string|array 	$data
 * @return 	void|string
*/
function dump_file_javascript($data = '') {
$rdate = DATE('Y-m-d | h:i');
$write = <<<EOS
/**
 * - This file was created using CoGen
 * 
 * - Date created : {$rdate}
 * - Author       : CiFireCMS
 * - License      : MIT License
*/

$(function() {
	'use strict'

	// DataTable
	$('#DataTable').DataTable({
		language: {
			url: datatable_lang,
		},
		autoWidth: false,
		responsive: true,
		processing: true,
		serverSide: true,
		order: [],
		columnDefs: [
			{targets: 'no-sort', orderable: false, searchable: false},
			{targets: 'th-action', orderable: false, searchable: false, width: '50px'},
			{targets: [0], width: '20px'},
			{targets: [1], width: '20px'}
		],
		lengthMenu: [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, 'All']
		],
		ajax: {
			type : 'POST',
			data : csrfData
		},
		drawCallback: function(settings) {
			var apiTable = this.api();
			dataTableDrawCallback(apiTable);
		}
	});


	// datetime-picker  
	$('#datetime-picker').datetimepicker({
		format    : 'YYYY-MM-DD HH:mm:ss',
		showClear : true,
		showTodayButton : true,
		icons : {
			previous: 'icon-arrow-left8',
			next  : 'icon-arrow-right8',
			today : 'fa fa-calendar-check-o',
			clear : 'icon-bin',
		},
	});


	// datepicker
	$('#date-picker').datetimepicker({
		format : 'YYYY-MM-DD',
		showClear : true,
		showTodayButton : true,
		icons : {
			previous : 'icon-arrow-left8',
			next     : 'icon-arrow-right8',
			today    : 'fa fa-calendar-check-o',
			clear    : 'icon-bin',
		},
	});


	// clockpicker
	$('#time-picker').datetimepicker({
		format : 'HH:mm:ss',
		showClear : true,
		showTodayButton : true,
		icons : {
			up    : 'icon-arrow-up7',
			down  : 'icon-arrow-down7',
			today : 'fa fa-clock-o',
			clear : 'icon-bin',
		},
	});


	// textarea-tinymce
	cfTnyMCE('#textarea-tinymce', 300);


	// filemanager
	$('#browse-filemanager').fancybox({ 
	    width     : 1000, 
	    height    : 1000, 
	    type      : 'iframe', 
	    autoScale : false,
	});
});

// filemanager callback
function responsive_filemanager_callback(field_id) {
    // console.log(field_id);
    var url = $('#' + field_id).val();
    $('#prv').val(url);
    parent.$.fancybox.close();
}

EOS;
return $write;
} //----------------------> End funcion. File javasript.
} // endif















if ( ! function_exists('dump_file_controller')) {
/**
 * ----------------------------------------------------------------
 * Dum for file Controller *.php
 * ----------------------------------------------------------------
*/

/**
 * - File Controller.
 * - Ini adalah fungsi untuk membuat konten file controller.
 * 
 * @param 	string|array 	$data
 * @return 	void|string
*/
function dump_file_controller($data = '') {
$data_general   = $data['general'];
$cname          = trim($data['general']['class_name'],'_'); // trim left char '_'
$component_name = trim($data['general']['component_name']);
$class_name     = ucfirst($cname);
$class_mod      = seotitle($cname);
$model_name     = $cname."_model";

// $component_name = $data_general['component_name'];

$data_field_1 = $data['com_filed_name_1'];

if (!empty($data['field'])) {
	$data_fields = $data['field'];
}
$data_col_name_1 = $data['conf_column_name_1'];

if (!empty($data['col'])) {
	$data_cols = $data['col'];
}

$data_config = $data['conf'];

$write = '';
$rdate = DATE('Y-m-d | h:i');
$write .= <<<EOS
<?php
/**
 * - This file was created using CoGen
 * 
 * - Date created : {$rdate}
 * - Author       : CiFireCMS
 * - License      : MIT License
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class {$class_name} extends Backend_Controller {
	
	public \$mod = '{$class_mod}';

	public function __construct() 
	{
		parent::__construct();
		
		\$this->load->model("mod/{$model_name}");
		\$this->meta_title("{$component_name}");
	}


	public function index()
	{
		if (\$this->role->i('read'))
		{
			if (\$this->input->is_ajax_request()) 
			{
				if (\$this->input->post('act')=='delete') {
					return \$this->_delete();
				}
				else
				{
					\$data = array();

					foreach (\$this->{$model_name}->datatable('_data', 'data') as \$val) 
					{
						\$row = [];\n
EOS;

if (!empty($data_config['action']['delete_multiple'])) {
$write .= <<< EOS
						// checkbox (all row)
						\$row[] = '<div class="text-center"><input type="checkbox" class="row_data" value="'. encrypt(\$val['{$data_field_1}']) .'"></div>';\n
EOS;
}

$write .= <<< EOS
						\$row[] = \$val['{$data_field_1}'];\n
EOS;


//---- data_fields cols -----------------------------------------------//
if (!empty($data['col'])) {
foreach ($data_cols as $key => $val) {
$row_value = $val['col_field'];

$write .= <<< EOS
						\$row[] = \$val['{$row_value}'];\n
EOS;

} //-----> End. Foreach data_fields.
}


$write .= <<< EOS
						\$row[] = '<div class="text-center"><div class="btn-group">\n
EOS;

if (!empty($data_config['action']['edit'])) {
$write .= <<< EOS
									<button type="button" onclick="location.href=\''. admin_url(\$this->mod."/edit/".\$val['{$data_field_1}']) .'\'" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" data-title="'. lang_line('button_edit') .'"><i class="cificon licon-edit"></i></button>\n
EOS;
}

if (!empty($data_config['action']['delete'])) {
$write .= <<< EOS
									<button type="button" class="btn btn-xs btn-white delete_single" data-toggle="tooltip" data-placement="top" data-title="'.lang_line('button_delete').'" data-pk="'. encrypt(\$val['{$data_field_1}']) .'"><i class="cificon licon-trash-2"></i></button>\n
EOS;
}

$write .= <<< EOS
								</div></div>';\n
EOS;


$write .= <<< EOS
						\$data[] = \$row;
					} // endforeach.

					\$this->json_output(['data' => \$data, 'recordsFiltered' => \$this->{$model_name}->datatable('_data', 'count')]);
				}

			}
			else
			{
				\$this->render_view('view_index');
			}
		}
		else
		{
			\$this->render_403();
		}
	}


	public function add()
	{
		if (\$this->role->i('write') ) 
		{
			if (\$this->input->method() == 'post')
			{\n
EOS;


if (!empty($data['field'])) {
foreach ($data_fields as $key_field => $val_field) {
$i_type = $val_field['com_filed_type'];
$i_name = $val_field['com_filed_name'];
$i_label = humanize($i_name);

if ($i_type == "INT") {
$write .= <<<EOS
				\$this->form_validation->set_rules(array(array(
					'field' => '{$i_name}',
					'label' => '{$i_label}',
					'rules' => 'trim'
				)));
\n
EOS;
}//----> End if.

elseif ($i_type == "TEXT") {
$write .= <<<EOS
				\$this->form_validation->set_rules(array(array(
					'field' => '{$i_name}',
					'label' => '{$i_label}'
				)));
\n
EOS;
} //----> End elseif.

else {
$write .= <<<EOS
				\$this->form_validation->set_rules(array(array(
					'field' => '{$i_name}',
					'label' => '{$i_label}',
					'rules' => 'trim'
				)));
\n
EOS;
} //----> End else.

} //----> End foreach.
}

$write .= <<<EOS
				if (\$this->form_validation->run())
				{
					\$data_isert = array(\n
EOS;

if (!empty($data['field'])) {
foreach ($data_fields as $key => $di_val) {
$di_type = $di_val['com_filed_type'];
$di_name = $di_val['com_filed_name'];

if ($di_type == "VARCHAR") {
$write .= <<<EOS
						'{$di_name}' => xss_filter(\$this->input->post('{$di_name}')),\n
EOS;
} // End if.

elseif ($di_type == "INT") {
$write .= <<<EOS
						'{$di_name}' => xss_filter(\$this->input->post('{$di_name}')),\n
EOS;
} // End elseif.

else {
$write .= <<<EOS
						'{$di_name}' => xss_filter(\$this->input->post('{$di_name}')),\n
EOS;
} // End else.

} // End foreach
}

$write .= <<<EOS
					);

					if (\$this->{$model_name}->insert(\$data_isert))
					{
						\$this->cifire_alert->set(\$this->mod, 'info', 'Data has been successfully added');
						redirect(admin_url(\$this->mod),'refresh');
					}
					else
					{
						\$this->cifire_alert->set(\$this->mod, 'danger', "Oups..! Some error occurred.<br>Please complete the data correctly");
					}
				}
			}
			else
			{
				\$this->render_view('view_add');
			}
		}
		else
		{
			\$this->render_403();
		}
	}


	public function edit(\$id_data = '')
	{
		if (\$this->role->i('modify'))
		{
			\$id_edit = xss_filter(\$id_data, 'sql');
			\$cek_id = \$this->{$model_name}->cek_id(\$id_edit);

			if (\$cek_id == 1) 
			{
				if (\$this->input->method() == 'post')
				{\n
EOS;

if (!empty($data['field'])) {
foreach ($data_fields as $key_field => $val_field) {
$i_type = $val_field['com_filed_type'];
$i_name = $val_field['com_filed_name'];
$i_label = humanize($i_name);

if ($i_type == "INT") {
$write .= <<<EOS
					\$this->form_validation->set_rules(array(array(
						'field' => '{$i_name}',
						'label' => '{$i_label}',
						'rules' => 'trim'
					)));
\n
EOS;
}//----> End if.

elseif ($i_type == "TEXT") {
$write .= <<<EOS
					\$this->form_validation->set_rules(array(array(
						'field' => '{$i_name}',
						'label' => '{$i_label}'
					)));
\n
EOS;
} //----> End elseif.

else {
$write .= <<<EOS
					\$this->form_validation->set_rules(array(array(
						'field' => '{$i_name}',
						'label' => '{$i_label}',
						'rules' => 'trim'
					)));
\n
EOS;
} //----> End else.

} //----> End foreach.
}

$write .= <<< EOS
					if ( \$this->form_validation->run() )
					{
						\$data_update = array(\n

EOS;

if (!empty($data['field'])){
foreach ($data_fields as $key => $di_val) {
$di_type = $di_val['com_filed_type'];
$di_name = $di_val['com_filed_name'];

if ($di_type == "VARCHAR") {
$write .= <<<EOS
							'{$di_name}' => xss_filter(\$this->input->post('{$di_name}')),\n
EOS;
} // End if.

elseif ($di_type == "INT") {
$write .= <<<EOS
							'{$di_name}' => xss_filter(\$this->input->post('{$di_name}'), 'xss'),\n
EOS;
} // End elseif.

else {
$write .= <<<EOS
							'{$di_name}' => xss_filter(\$this->input->post('{$di_name}')),\n
EOS;
} // End else.

} // End foreach
}

$write .= <<< EOS
						);

						if (\$this->{$model_name}->update(\$id_edit, \$data_update))
						{
							\$this->cifire_alert->set(\$this->mod, 'info', 'Data has been successfully updated');
						}
						else
						{
							\$this->cifire_alert->set(\$this->mod, 'danger', "Oups..! Some error occurred.<br>Please complete the data correctly");
						}
					}
				}
				\$data_edit = \$this->{$model_name}->get_data_edit(\$id_edit);
				\$this->vars['data_row'] = \$data_edit;
				\$this->render_view('view_edit');
			}
			else
			{
				\$this->render_404();
			}
		}
		else
		{
			\$this->render_403();
		}
	}


	private function _delete()
	{
		if (\$this->input->is_ajax_request())
		{
			if (\$this->role->i('delete'))
			{
				\$data = \$this->input->post('data');

				foreach (\$data as \$key)
				{
					\$pk = xss_filter(decrypt(\$key),'sql');
					\$this->{$model_name}->delete(\$pk);
				}

				\$response['success'] = true;
				\$this->json_output(\$response);
			}
			else
			{
				\$response['success'] = false;
				\$this->json_output(\$response);
			}
		}
		else
		{
			show_403();
		}
	}
} // End Class.
EOS;
return $write;
} //----------------------> End funcion. File Controller.
} // endif















if ( ! function_exists('dump_file_model')) {
/**
 * ----------------------------------------------------------
 * Dum for file Model *.php
 * ----------------------------------------------------------
*/

/**
 * - File Model
 * - Ini adalah fungsi untuk membuat konten file model.
 * 
 * @param 	string|array 	$data
 * @return 	void|string
*/

function dump_file_model($data = '') {
$cname = trim($data['general']['class_name'],'_');
$component_name = trim($data['general']['component_name']);
$class_name = ucfirst($cname."_model");
$class_mod = seotitle($cname);

// $model_name = $class_mod."_model";
$table_name = $data['table_name'];
$data_general = $data['general'];
$component_name = $data_general['component_name'];
$data_field_1 = $data['com_filed_name_1'];

if (!empty($data['field'])) {	
	$data_fields = $data['field'];
}

$data_col_name_1 = $data['conf_column_name_1'];

if (!empty($data['col'])) {
	$data_cols = $data['col'];
}

$data_config = $data['conf'];

$write = '';
$rdate = DATE('Y-m-d | h:i');
$write .= <<<EOS
<?php
/**
 * - This file was created using CoGen
 * 
 * - Date created : {$rdate}
 * - Author       : CiFireCMS
 * - License      : MIT License
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class {$class_name} extends CI_Model {

	public \$vars;
	private \$_table = '{$table_name}';
	private \$_column_order = array(null, '{$data_field_1}',
EOS;
if (!empty($data['col'])) {
foreach ($data_cols as $key => $val) {
$coval = $val['col_field'];
$write .= <<< EOS
 '{$coval}',
EOS;
}
}

$write .= <<< EOS
 '{$data_field_1}');
	private \$_column_search = array('{$data_field_1}',
EOS;
if (!empty($data['col'])) {
foreach ($data_cols as $key => $val) {
$csval = $val['col_field'];
$write .= <<< EOS
 '{$csval}',
EOS;
}
}
$write .= <<< EOS
 '{$data_field_1}');

	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Function datatable
	 *
	 * @param     string    \$method (query method)
	 * @param     string    \$mode ('data' or 'count')
	 * @return    void
	*/
	public function datatable(\$method, \$mode = '')
	{
		if (\$mode == 'count')
		{
			\$this->\$method();
			
			\$result =  \$this->db->get()->num_rows();
		}

		elseif (empty(\$mode) || \$mode == 'data')
		{
			\$this->\$method();
			if (\$this->input->post('length') != -1) 
			{
				\$this->db->limit(\$this->input->post('length'), \$this->input->post('start'));
				\$query = \$this->db->get();
			}
			else
			{
				\$query = \$this->db->get();
			}
			
			\$result =  \$query->result_array();
		}

		return \$result;
	}


	private function _data()
	{
		\$this->db->select('*');
		\$this->db->from(\$this->_table);

		\$i = 0;	
		foreach (\$this->_column_search as \$item) 
		{
			if (\$this->input->post('search')['value'])
			{
				if (\$i === 0)
				{
					\$this->db->group_start();
					\$this->db->like(\$item, \$this->input->post('search')['value']);
				}
				else
				{
					\$this->db->or_like(\$item, \$this->input->post('search')['value']);
				}

				if (count(\$this->_column_search) - 1 == \$i) 
				{
					\$this->db->group_end(); 
				}
			}
			\$i++;
		}
		
		if (!empty(\$this->input->post('order'))) 
		{
			\$this->db->order_by(
				\$this->_column_order[\$this->input->post('order')['0']['column']], 
				\$this->input->post('order')['0']['dir']
			);
		}
		else
		{
			\$this->db->order_by('{$data_field_1}', 'DESC');
		}
	}


	public function insert(array \$data)
	{
		\$query = \$this->db->insert(\$this->_table, \$data);
		
		if (\$query == FALSE)
			return FALSE;
		else
			return TRUE;
	}


	public function update(\$key, \$data)
	{
		\$query = \$this->db->where('{$data_field_1}', \$key);
		\$query = \$this->db->update(\$this->_table, \$data);
		
		if (\$query == FALSE)
			return FALSE;
		else
			return TRUE;
	}


	public function delete(\$val = 0)
	{
		if (\$this->cek_id(\$val) == 1) 
		{
			\$this->db->where('{$data_field_1}', \$val)->delete(\$this->_table);
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	public function get_data_edit(\$val_id)
	{
		\$query = \$this->db->where('{$data_field_1}', \$val_id);
		\$query = \$this->db->get(\$this->_table);
		\$result = \$query->row_array();
		return \$result;
	}


	public function cek_id(\$val = 0)
	{
		\$query = \$this->db->select('{$data_field_1}');
		\$query = \$this->db->where('{$data_field_1}', \$val);
		\$query = \$this->db->get(\$this->_table);
		\$query = \$query->num_rows();
		\$int = (int)\$query;
		return \$int;
	}
} // End class.
EOS;
return $write;
} //----------------------> End funcion. File Model.
} // endif















if ( ! function_exists('write_input_text')) {
/**
 * - Text.
 * - Ini adalah fungsi untuk membuat input text.
 * 
 * @param 	string 	$input_label
 * @param 	string 	$field_name
 * @param 	string 	$val
 * @return 	void|string
*/
function write_input_text($input_label = '', $field_name = '', $val = '') {
$input_label = humanize($input_label);
$value = '';
if (!empty($val)) {
$value .= <<< EOS
value="<?=\$data_row['{$field_name}'];?>"
EOS;
}
$content = <<< EOS
			<!-- input text | {$input_label} -->
			<div class="form-group row">
				<label class="col-form-label col-md-2">{$input_label}</label>
				<div class="col-md-10">
					<input type="text" name="{$field_name}" {$value} class="form-control" />
				</div>
			</div>
			<!--/ input text | {$input_label} -->\n
EOS;
return $content;
} //---------> End function write_input_text.
} // endif




if ( ! function_exists('write_input_number')) {
/**
 * - Number.
 * - Ini adalah fungsi untuk membuat input number.
 * 
 * @param 	string 	$input_label
 * @param 	string 	$field_name
 * @param 	string 	$val
 * @return 	void|string
*/
function write_input_number($input_label = '', $field_name = '', $val = '') {
$input_label = humanize($input_label);
$value = '';
if (!empty($val)) {
$value .= <<< EOS
value="<?=\$data_row['{$field_name}'];?>"
EOS;
}
$content = <<< EOS
			<!-- input number | {$input_label} -->
			<div class="form-group row">
				<label class="col-form-label col-md-2">{$input_label}</label>
				<div class="col-md-10">
					<input type="number" name="{$field_name}" {$value} class="form-control" required />
				</div>
			</div>
			<!--/ input number | {$input_label} -->\n
EOS;
return $content;
} //---------> End function write_input_number.
} // endif




if ( ! function_exists('write_input_select')) {
/**
 * - Select Option.
 * - Ini adalah fungsi untuk membuat input select.
 * 
 * @param 	string 	$input_label
 * @param 	string 	$field_name
 * @param 	string 	$val
 * @return 	void|string
*/
function write_input_select($input_label = '', $field_name = '', $val = '', array $options) {
$input_label = humanize($input_label);

$option1 = '';
if (!empty($val)) {
$option1 .= <<< EOS
<option value="<?=\$data_row['{$field_name}'];?>" style="display:none;"><?=\$data_row['{$field_name}'];?></option>
EOS;
} else {
$option1 .= <<< EOS
<option value="" style="display:none;">- Select -</option>
EOS;
}

$option = '';
foreach ($options as $key => $ov) {
	$option .= '<option value="'.$ov.'">'.$ov.'</option>';
}
$content = <<<EOS
			<!-- input select | {$input_label} -->
			<div class="form-group row">
				<label class="col-form-label col-md-2">{$input_label}</label>
				<div class="col-md-10">
					<select name="{$field_name}" class="form-control" style="max-width:400px;" required>
						{$option1}{$option}
					</select>
				</div>
			</div>
			<!--/ input select | {$input_label} -->\n
EOS;
return $content;
} //---------> End Function write_input_select.
} // endif


if ( ! function_exists('write_input_enum')) {
/**
 * - Select ENUM.
 * - Ini adalah fungsi untuk membuat input select.
 * 
 * @param 	string 	$input_label
 * @param 	string 	$field_name
 * @param 	string 	$val
 * @return 	void|string
*/
function write_input_enum($input_label = '', $field_name = '', $options, $default, $val = FALSE) {
$input_label = humanize($input_label);

$option1 = '';
$option2 = '';

if ($val == TRUE) {
$option1 .= <<< EOS
<option value="<?=\$data_row['{$field_name}'];?>" style="display:none;"><?=\$data_row['{$field_name}'];?></option>
EOS;
foreach ($options as $key) {
$option2 .= <<< EOS
<option value="{$key}">{$key}</option>
EOS;
}
}

elseif ($val == FALSE) {
$option1 .= <<< EOS
<option value="{$default}" style="display:none;">{$default}</option>
EOS;
foreach ($options as $key) {
$option2 .= <<< EOS
<option value="{$key}">{$key}</option>
EOS;
}
}

$content = <<<EOS
			<!-- input select ENUM | {$input_label} -->
			<div class="form-group row">
				<label class="col-form-label col-md-2">{$input_label}</label>
				<div class="col-md-10">
					<select name="{$field_name}" class="form-control" style="max-width:400px;" required>
						{$option1}{$option2}
					</select>
				</div>
			</div>
			<!--/ input select ENUM | {$input_label} -->\n
EOS;
return $content;
} //---------> End Function write_input_enum.
} // endif


if ( ! function_exists('write_input_textarea')) {
/**
 * - Textarea.
 * - Ini adalah fungsi untuk membuat input textarea.
 * 
 * @param 	string 	$input_label
 * @param 	string 	$field_name
 * @param 	string 	$val
 * @return 	void|string
*/
function write_input_textarea($input_label = '', $field_name = '', $val = '') {
$input_label = humanize($input_label);
$value = '';
if (!empty($val)) {
$value .= <<< EOS
<?=\$data_row['{$field_name}'];?>
EOS;
}
$content = <<< EOS
			<!-- textarea | {$input_label} -->
			<div class="form-group row">
				<label class="col-form-label col-md-2">{$input_label}</label>
				<div class="col-md-10">
					<textarea name="{$field_name}" class="form-control">{$value}</textarea>
				</div>
			</div>
			<!--/ textarea | {$input_label} -->\n
EOS;
return $content;
} //---------> End function write_input_textarea.
} // endif



if ( ! function_exists('write_input_tinymce')) {
/**
 * - Textarea TinyMCE.
 * - Ini adalah fungsi untuk membuat input textarea dengan plugin TinyMCE.
 * 
 * @param 	string 	$input_label
 * @param 	string 	$field_name
 * @param 	string 	$val
 * @return 	void|string
*/
function write_input_tinymce($input_label = '', $field_name = '', $val = '') {
$input_label = humanize($input_label);
$value = '';
if (!empty($val)) {
$value .= <<< EOS
<?=\$data_row['{$field_name}'];?>
EOS;
}
$content = <<< EOS
			<!-- textarea TinyMCE | {$input_label} -->
			<div class="form-group row">
				<label class="col-form-label col-md-2">{$input_label}</label>
				<div class="col-md-10">
					<textarea id="textarea-tinymce" name="{$field_name}" class="form-control">{$value}</textarea>
				</div>
			</div>
			<!--/ textarea TinyMCE | {$input_label} -->\n
EOS;
return $content;
} //---------> End function write_input_tinymce.
} // endif



if ( ! function_exists('write_input_date')) {
/**
 * - Text DATE.
 * - Ini adalah fungsi untuk membuat input date.
 * 
 * @param 	string 	$input_label
 * @param 	string 	$field_name
 * @param 	string 	$val
 * @return 	void|string
*/
function write_input_date($input_label = '', $field_name = '', $val = '') {
$input_label = humanize($input_label);
$value = '';
if (!empty($val)) {
$value .= <<< EOS
value="<?=\$data_row['{$field_name}'];?>"
EOS;
}
else {
$value .= <<< EOS
value="<?=date('Y-m-d');?>"
EOS;
}
$content = <<<EOS
			<!-- input date | {$input_label} -->
			<div class="form-group row">
				<label class="col-form-label col-md-2">{$input_label}</label>
				<div class="col-md-10">
					<div class="input-group" style="max-width:250px;">
						<input id="date-picker" type="text" name="{$field_name}" {$value} class="form-control" placeholder="yyyy-mm-dd" required/>
						<div class="input-group-append">
							<span class="input-group-text"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
				</div>
			</div>
			<!--/ input date | {$input_label} -->\n
EOS;
return $content;
} //---------> End function write_input_date.
} // endif



if ( ! function_exists('write_input_time')) {
/**
 * - Text Time.
 * - Ini adalah fungsi untuk membuat input time.
 * 
 * @param 	string 	$input_label
 * @param 	string 	$field_name
 * @param 	string 	$val
 * @return 	void|string
*/
function write_input_time($input_label = '', $field_name = '', $val = '') {
$input_label = humanize($input_label);
$value = '';
if (!empty($val)) {
$value .= <<< EOS
value="<?=\$data_row['{$field_name}'];?>"
EOS;
}
else {
$value .= <<< EOS
value="<?=date('HH:ii:ss');?>"
EOS;
}
$content = <<<EOS
			<!-- input time | {$input_label} -->
			<div class="form-group row">
				<label class="col-form-label col-md-2">{$input_label}</label>
				<div class="col-md-10">
					<div class="input-group" style="max-width:250px;">
						<input id="time-picker" type="text" name="{$field_name}" {$value} class="form-control" placeholder="HH:ii:ss" required/>
						<div class="input-group-append">
							<span class="input-group-text"><i class="fa fa-clock-o"></i></span>
						</div>
					</div>
				</div>
			</div>
			<!--/ input time | {$input_label} -->\n
EOS;
return $content;
} //---------> End function write_input_time.
} // endif



if ( ! function_exists('write_input_datetime')) {
/**
 * - Text DateTime.
 * - Ini adalah fungsi untuk membuat input datetime.
 * 
 * @param 	string 	$input_label
 * @param 	string 	$field_name
 * @param 	string 	$val
 * @return 	void|string
*/
function write_input_datetime($input_label = '', $field_name = '', $val = '') {
$input_label = humanize($input_label);
$value = '';
if (!empty($val)) {
$value .= <<< EOS
value="<?=\$data_row['{$field_name}'];?>"
EOS;
}
else {
$value .= <<< EOS
value="<?=date('Y-m-d HH:ii:ss');?>"
EOS;
}
$content = <<<EOS
			<!-- input datetime | {$input_label} -->
			<div class="form-group row">
				<label class="col-form-label col-md-2">{$input_label}</label>
				<div class="col-md-10">
					<div class="input-group" style="max-width:250px;">
						<input id="datetime-picker" type="text" name="{$field_name}" {$value} class="form-control" placeholder="yyyy-mm-dd HH:ii:ss" required/>
						<div class="input-group-append">
							<span class="input-group-text"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
				</div>
			</div>
			<!--/ input datetime | {$input_label} -->\n
EOS;
return $content;
} //---------> End function write_input_datetime.
} // endif



if ( ! function_exists('write_input_filemanager')) {
/**
 * - File input browse filemanager.
 * - Ini adalah fungsi untuk membuat input browse filemanager.
 * 
 * @param 	string 	$input_label
 * @param 	string 	$field_name
 * @param 	string 	$val
 * @return 	void|string
*/
function write_input_filemanager($input_label = '', $field_name = '', $val = '') {
$input_label = humanize($input_label);
$value = '';
if (!empty($val)) {
$value .= <<< EOS
value="<?=\$data_row['{$field_name}'];?>"
EOS;
}
$content = <<< EOS
			<!-- input browse filemanager | {$input_label} -->
			<div class="form-group row">
				<label class="col-form-label col-md-2">{$input_label}</label>
				<div class="col-md-10">
					<div class="input-group" style="max-width:400px;">
						<div class="input-group-prepend">
							<button type="button" id="browse-filemanager" href="<?=content_url('plugins/filemanager/dialog.php?type=1&relative_url=1&field_id=pictures&sort_by=date&descending=1&akey='.fmkey());?>" class="btn btn-default">Browse</button>
						</div>
						<input id="prv" type="text" {$value} class="form-control" placeholder="Choose file..." readonly />
					</div>
					<input id="pictures" type="hidden" name="{$field_name}" {$value} class="form-control" />
				</div>
			</div>
			<!-- input browse filemanager | {$input_label} -->\n
EOS;
return $content;
} //---------> End function write_input_filemanager.
} // endif


















/**
 * ---------------------------------------------------------------------------------------------
 * Dum for frontend file Controller *.php
 * ---------------------------------------------------------------------------------------------
*/

function dump_frontend_controller($data) {
$component_name = $data['general']['component_name'];
$cname          = seotitle($data['general']['class_name'],'_');
$class_name     = "Mod_".$cname;
$class_mod      = underscore($cname);
$model_name     = $class_name."_model";
$meta_title     = ( !empty($data['frontend']['meta_title']) ? $data['frontend']['meta_title'] : $component_name);
$filename       = $class_name.".php";
$views_file     = "view_$cname";
$rdate          = DATE('Y-m-d | h:i');

$write = '';
$write .= <<<EOS
<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * - This file was created using CoGen
 * 
 * - Mod          : {$component_name}
 * - File         : {$filename}
 * - Date created : {$rdate}
 * - Author       : CiFireCMS
 * - License      : MIT License
*/

class {$class_name} extends Web_controller {

	public function __construct() 
	{
		parent::__construct();
		
		\$this->load->model("web/{$model_name}"); // Load model
		\$this->meta_title("{$meta_title}"); // Set meta title
	}


	public function index()
	{
		\$this->vars['datas'] = \$this->{$model_name}->get_data(); // get data
		
		// render view
		// \$this->render_view('header', \$this->vars);
		\$this->render_view('{$views_file}', \$this->vars);
		// \$this->render_view('footer', \$this->vars);
	}

} // End Class.
EOS;



return $write;
} //---------> End function dump_frontend_controller.



















/**
 * ---------------------------------------------------------------------------------------------
 * Dum for frontend file Model *.php
 * ---------------------------------------------------------------------------------------------
*/
function dump_frontend_model($data) {
$component_name = $data['general']['component_name'];
$cname = seotitle($data['general']['class_name'],'_');
$class_name = "Mod_".$cname."_model";
$table_name = $data['table_name'];
$data_general = $data['general'];
$data_field_1 = $data['com_filed_name_1'];

if (!empty($data['col'])) {
	$data_cols = $data['col'];
}

$filename = $class_name.".php";
$rdate = DATE('Y-m-d | h:i');
$write = '';
$write .= <<<EOS
<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * - This file was created using CoGen
 * 
 * - Mod          : {$component_name}
 * - File         : {$filename}.php
 * - Date created : {$rdate}
 * - Author       : CoGen
 * - License      : MIT License
*/

class {$class_name} extends CI_Model {

	private \$_table = '{$table_name}';

	public function __construct()
	{
		parent::__construct();
	}


	public function get_data()
	{
		\$query = \$this->db->select('{$data_field_1}
EOS;
if (!empty($data['col'])) {
foreach ($data_cols as $key => $val) {
$col_field = $val['col_field'];
$write .= <<< EOS
,{$col_field}
EOS;
}
}
$write .= <<<EOS
');
		\$query = \$this->db->from(\$this->_table);
		\$query = \$this->db->order_by('{$data_field_1}', 'DESC');
		\$query = \$this->db->get();
		\$result = \$query->result_array();
		return \$result;
	}
} // End Class.
EOS;
return $write;
} //---------> End function dump_frontend_model.
















/**
 * ---------------------------------------------------------------------------------------------
 * Dum for frontend file View  *.php
 * ---------------------------------------------------------------------------------------------
*/
function dump_frontend_view($data) {
$component_name = $data['general']['component_name'];
if (!empty($data['col'])) {
	$data_cols = $data['col'];
}

$write = '';
$write .= <<<EOS
<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- 
*******************************************************
	Include Header Template
******************************************************* 
-->
<?php \$this->CI->render_view('header'); ?>

<!-- 
*******************************************************
	Insert Content
******************************************************* 
-->

<section id="page-title">
	<div class="container clearfix">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?=site_url();?>">Home</a></li>
			<li class="breadcrumb-item active" aria-current="page">{$component_name}</li>
		</ol>
	</div>
</section>
<section id="content">
	<div class="content-wrap pt-5">
		<div class="container clearfix">
			<div class="">
				<h1>{$component_name}</h1>
			</div>
			<div class="col_full detail-content">

				<table class="table table-condensed table-sm table-bordered table-striped">
					<thead>
						<tr>\n
EOS;

if (!empty($data['col'])) {
foreach ($data_cols as $key => $val) {
$row_value = humanize($val['col_name']);
$write .= <<< EOS
							<th>{$row_value}</th>\n
EOS;
}
}

$write .= <<<EOS
						</tr>
					</thead>
					<tbody>
						<?php foreach (\$datas as \$result): ?>
						<tr>\n
EOS;

if (!empty($data['col'])) {
foreach ($data_cols as $key => $val) {
$td_value = $val['col_field'];
$write .= <<< EOS
							<td><?=\$result['{$td_value}'];?></td>\n
EOS;
}
}

$write .= <<<EOS
						</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</section>

<!-- 
*******************************************************
	Include Footer Template
******************************************************* 
-->
<?php \$this->CI->render_view('footer'); ?>
EOS;

return $write;
} //---------> End function dump_frontend_view.


















/**
 * ---------------------------------------------------------------------------------------------
 * Dum for frontend route
 * ---------------------------------------------------------------------------------------------
*/
function dump_frontend_route($data) {
$class_name  = "Mod_".seotitle($data['general']['class_name'],'_')."_model";
$class_route = (!empty($data['frontend']['route']) ? $data['frontend']['route'] : "mod-".seotitle($class_name));
$controller  = "mod_".seotitle($data['general']['class_name'],'_');

$write = <<<EOS
<?php defined('BASEPATH') OR exit('No direct script access allowed');
\$route['{$class_route}'] = '{$controller}/index';
EOS;
return $write;
} //---------> End function dump_frontend_view.