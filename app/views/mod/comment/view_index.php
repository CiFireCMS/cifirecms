<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_content');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title_all');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('mod_title_all');?></h4>
				</div>
			</div>
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
							<th><?=lang_line('_comment');?></th>
							<th><?=lang_line('_date');?></th>
							<th><?=lang_line('_status');?></th>
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

<div id="modal_detail" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<?=form_open();?>
			<input type="hidden" name="id_comment" id="id_comment">
			<input type="hidden" name="id_parent" id="id_parent">
			<input type="hidden" name="id_post" id="id_post">

			<div class="modal-header">
				<h5 class="modal-title"><?=lang_line('_view_comment');?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div id="cdet"></div>
			</div>
			<?=form_close();?>
		</div>
	</div>
</div>
