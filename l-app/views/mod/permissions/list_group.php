<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="<?=admin_url('dashboard');?>" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="<?=admin_url('user');?>" class="breadcrumb-item"><?=lang_line('ui_users');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('_list_group');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><i data-feather="users" class="mr-2"></i><?=lang_line('_list_group');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<a href="<?=admin_url($this->mod.'/group/add');?>" class="btn btn-sm pd-x-15 btn-white btn-uppercase"><i data-feather="plus" class="mr-2"></i><?=lang_line('button_add');?></a>
			<a href="<?=admin_url($this->mod.'/list-role');?>" class="btn btn-sm pd-x-15 btn-white btn-uppercase"><i data-feather="shield" class="mr-2"></i><?=lang_line('_list_role');?></a>
		</div>
	</div>

	<div class="card">
		<div class="table-responsive">
			<div class="card-body">
				<table id="DataTableGroups" class="table table-striped table-bordered table-datatable">
					<thead>
						<tr>
							<th class="no-sort text-center"><input type="checkbox" class="select_all" data-toggle="tooltip" data-placement="top" title="<?=lang_line('ui_select_all');?>"></th>
							<th><?=lang_line('_id')?></th>
							<th><?=lang_line('_title')?></th>
							<th><?=lang_line('_group')?></th>
							<th class="th-action text-center"><?=lang_line('ui_action');?></th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<td colspan="5">
								<button type="button" class="btn btn-sm btn-danger delete_multi"><?=lang_line('button_delete_selected_item');?></button>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
