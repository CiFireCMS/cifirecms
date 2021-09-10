<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_apperance');?></a>
						<a href="<?=admin_url($this->mod);?>" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('_list_settings');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('_list_settings');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod);?>'"><i data-feather="arrow-left" class="mr-2"></i><?=lang_line('button_back');?></button>
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod.'/add-list');?>'"><i data-feather="plus" class="mr-2"></i><?=lang_line('button_add');?></button>
		</div>
	</div>

	<div>
		<?=$this->cifire_alert->show($this->mod);?>
	</div>
	
	<div class="card">
		<div class="table-responsive">
			<div class="card-body">
				<table id="DataTable" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th class="no-sort text-center"><input type="checkbox" class="select_all" data-toggle="tooltip" data-placement="top" title="<?=lang_line('ui_select_all');?>"></th>
							<th><?=lang_line('_id');?></th>
							<th><?=lang_line('_groups');?></th>
							<th><?=lang_line('_options');?></th>
							<th class="no-sort"><?=lang_line('_value');?></th>
							<th class="th-action text-center"><?=lang_line('_action');?></th>
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
