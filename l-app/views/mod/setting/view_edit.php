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
						<a href="#" class="breadcrumb-item"><?=lang_line('_edit_setting');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('_edit_setting');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod);?>'"><i data-feather="arrow-left" class="mr-2"></i><?=lang_line('button_back');?></button>
		</div>
	</div>

	<div>
		<?=$this->cifire_alert->show($this->mod);?>
	</div>

	<div class="card">
		<?php
			echo form_open(admin_url($this->mod.'/edit/?id='.urlencode(encrypt($result['id']))),'id="form_edit"');
			echo form_hidden('pk', encrypt($result['id']));
		?>
		<div class="card-body">
			<div class="row">
				<!-- groups -->
				<div class="col-md-4">
					<div class="form-group">
						<label><?=lang_line('_groups');?> <span class="text-danger">*</span></label>
						<select name="groups" class="select-bs form-control">
							<option value="<?=$result['groups'];?>" style="display:none;"><?=humanize($result['groups']);?></option>
							<option value="other">Other</option>
							<option value="general">General</option>
							<option value="image">Image</option>
							<option value="local">Local</option>
							<option value="mail">Mail</option>
							<option value="config">Config</option>
						</select>
					</div>
				</div>
				<!--/ groups -->

				<!-- options -->
				<div class="col-md-4">
					<div class="form-group">
						<label><?=lang_line('_options');?> <span class="text-danger">*</span></label>
						<input id="options" type="text" name="options" class="form-control" value="<?=$result['options'];?>" />
						<small><em><?=lang_line('_option_desc');?></em></small>
					</div>
				</div>
				<!-- options -->

				<!-- type -->
				<div class="col-lg-4">
					<div class="form-group">
						<label for="options"><?=lang_line('_type');?> <span class="text-danger">*</span></label>
						<select id="selectType" name="type" class="select-bs form-control">
							<option value="<?=$result['type'];?>" style="display:none;"><?=humanize($result['type']);?></option>
							<option value="text">Text</option>
							<option value="select">Select</option>
							<option value="file">File</option>
							<option value="slug">Slug</option>
							<option value="timezone">Timezone</option>
							<option value="password">Password</option>
							<option value="html">Html</option>
							<option value="other">Other</option>
						</select>
					</div>
				</div>
				<!-- type -->

				<!-- value -->
				<div class="col-lg-12">
					<div class="form-group mb-0">
						<label><?=lang_line("_value");?></label>
						<div id="formType">
						</div>
					</div>
				</div>
				<!-- value -->
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-lg btn-primary" name="pk" value="<?=encrypt($result['id'])?>"><i class="cificon licon-save mr-2"></i><?=lang_line('button_save');?></button>
		</div>
	</div>
	<?=form_close();?>
</div>

<script>
	var _ACT_METHOD = 'edit';
	var _TYPE = `<?=$result['type'];?>`;
	var _VALUE = `<?=($result['type']=='password'? decrypt($result['value']) : $result['value']);?>`;
	var _SELECT_OPTION_CONTENT = `<?=$select_option;?>`;
</script>
