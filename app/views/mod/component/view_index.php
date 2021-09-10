<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_module');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title_all');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('mod_title_all');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod.'/installation');?>'"><i data-feather="plus" class="mr-2"></i><?=lang_line('button_add');?></button>
		</div>
	</div>

	<div>
		<?=$this->cifire_alert->show($this->mod);?>
	</div>
	
	<div class="content">
		<div class="table-responsive">
			<div class="content-inner">
				<table id="DataTable" class="table table-striped table-hover table-bordered table-datatable">
					<thead>
						<tr>
							<th><?=lang_line('_component');?></th>
							<th><?=lang_line('_mod');?></th>
							<th class="no-sort text-center"><?=lang_line('_action');?></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
