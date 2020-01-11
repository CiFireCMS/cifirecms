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
						<a href="#" class="breadcrumb-item"><?=lang_line('_add_role');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('_add_role');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod.'/list-role');?>'"><i data-feather="arrow-left" class="mr-2"></i><?=lang_line('button_back');?></button>
		</div>
	</div>

	<div class="card">
		<?php 
			echo form_open('','id="form_group" autocomplete="off"');
			echo form_hidden('act','add-role');
		?>
		<div class="card-body">
			<div class="row">
				<!-- Group -->
				<div class="col-md-6">
					<div class="form-group">
						<label><?=lang_line('_group');?> <span class="text-danger">*</span></label>
						<select name="group" class="select2">
							<?php
								$groups = $this->CI->db->get('t_user_group')->result_array();
								foreach ($groups as $res):
									if ($res['group']=='root') continue;
							?>
							<option value="<?=$res['group'];?>"><?=$res['title'];?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<!--/ Group -->

				<!-- Module -->
				<div class="col-md-6">
					<div class="form-group">
						<label><?=lang_line('_module');?> <span class="text-danger">*</span></label>
						<select name="module" class="select2">
							<?php
								$module = $this->CI->db->get('t_mod')->result_array();
								foreach ($module as $res):
							?>
							<option value="<?=$res['mod'];?>"><?=$res['mod'];?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<!--/ Module -->

				<!-- Permission -->
				<div class="col-lg-12">
					<label><?=lang_line('_permission');?></label>
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-3">
									<label class="mb-0"><input name="read_access" value="1" type="checkbox" class="mr-2"><?=lang_line('_read_access');?></label>
								</div>
								<div class="col-sm-3">
									<label class="mb-0"><input name="write_access" value="1" type="checkbox" class="mr-2"><?=lang_line('_write_access');?></label>
								</div>
								<div class="col-sm-3">
									<label class="mb-0"><input name="modify_access" value="1" type="checkbox" class="mr-2"><?=lang_line('_modify_access');?></label>
								</div>
								<div class="col-sm-3">
									<label class="mb-0"><input name="delete_access" value="1" type="checkbox" class="mr-2"><?=lang_line('_delete_access');?></label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Permission -->
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-lg btn-primary button_submit"><i class="cificon licon-send mr-2"></i><?=lang_line('button_submit');?></button>
		</div>
		<?=form_close();?>
	</div>
</div>
