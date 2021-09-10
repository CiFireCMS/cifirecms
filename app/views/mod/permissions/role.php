<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="<?=admin_url('user');?>" class="breadcrumb-item"><?=lang_line('ui_users');?></a>
						<a href="<?=admin_url($this->mod);?>" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('_role');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('_role');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod);?>'"><i data-feather="arrow-left" class="mr-2"></i><?=lang_line('button_back');?></button>
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod.'/add-role/'.$res_group['pk']);?>'"><i data-feather="plus" class="mr-2"></i><?=lang_line('button_add');?></button>
		</div>
	</div>

	<div class="card">
		<div class="card-header">
			<span class="btn btn-xs rounded-pill btn-white mr-2"><i class="cificon licon-users"></i></span>
			<span class="tx-medium"><?=$res_group['title'];?></span>
		</div>
		<div class="table-responsive">
			<div class="card-body">
				<table id="DataTableGroupRole" class="table table-striped table-bordered table-datatable">
					<thead>
						<tr>
							<th class="no-sort text-center">
								<input type="checkbox" class="select_all" data-toggle="tooltip" data-placement="top" title="<?=lang_line('ui_select_all');?>">
							</th>
							<th><?=lang_line('_id');?></th>
							<th><?=lang_line('_module');?></th>
							<th><?=lang_line('_read_access');?></th>
							<th><?=lang_line('_write_access');?></th>
							<th><?=lang_line('_modify_access');?></th>
							<th><?=lang_line('_delete_access');?></th>
							<th class="th-action text-center"><?=lang_line('ui_action');?></th>
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
