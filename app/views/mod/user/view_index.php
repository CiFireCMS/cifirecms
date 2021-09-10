<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title_all');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('mod_title_all');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod.'/add');?>'"><i data-feather="user-plus" class="mr-2"></i><?=lang_line('button_add');?></button>
		</div>
	</div>
	
	<div class="card">
		<div class="table-responsive">
			<div class="card-body">
				<table id="DataTable" class="table table-striped table-bordered table-datatable">
					<thead>
						<tr>
							<th class="no-sort text-center"><input type="checkbox" class="select_all" data-toggle="tooltip" data-placement="top" title="<?=lang_line('ui_select_all');?>"></th>
							<th><?=lang_line('_id');?></th>
							<th class="no-sort text-center"><i class="cificon licon-camera"></i></th>
							<th><?=lang_line('_username');?></th>
							<th><?=lang_line('_name');?></th>
							<th><?=lang_line('_group');?></th>
							<th><?=lang_line('_status');?></th>
							<th class="th-action text-center"><?=lang_line('_action');?></th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<td colspan="8">
								<button type="button" class="btn btn-sm btn-danger delete_multi"><?=lang_line('button_delete_selected_item');?></button>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
