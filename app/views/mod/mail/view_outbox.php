<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_contact');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('mod_title');?></h4>
				</div>
			</div>
		</div>
	</div>

	<div class="card">
		<div class="card-header">
			<button type="button" onclick="location.href='<?=admin_url($this->mod);?>'" class="btn btn-white"><i data-feather="inbox" class="wd-16"></i><span class="d-none d-lg-inline-block ml-2"><?=lang_line('button_inbox');?></span></button>
			<button type="button" onclick="location.href='<?=admin_url($this->mod.'/outbox');?>'" class="btn btn-primary"><i data-feather="external-link" class="wd-16"></i><span class="d-none d-lg-inline-block ml-2"><?=lang_line('button_outbox');?></span></button>
			<button type="button" onclick="location.href='<?=admin_url($this->mod.'/write');?>'" class="btn btn-white"><i data-feather="edit-3" class="wd-16"></i><span class="d-none d-lg-inline-block ml-2"><?=lang_line('button_write');?></span></button>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="DataTableOut" class="table table-striped table-bordered table-datatable">
					<thead>
						<tr>
							<th class="no-sort text-center"><input type="checkbox" class="select_all" data-toggle="tooltip" data-placement="top" title="<?=lang_line('ui_select_all');?>"></th>
							<th><?=lang_line('_id');?></th>
							<th><?=lang_line('_destination');?></th>
							<th><?=lang_line('_subject');?></th>
							<th><?=lang_line('_date');?></th>
							<th class="th-action text-center"><?=lang_line('_action');?></th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<td colspan="6">
								<button type="button" class="btn btn-sm btn-danger delete_multi"><?=lang_line('button_delete_selected_item');?></button>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
