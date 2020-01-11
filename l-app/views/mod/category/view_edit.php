<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner mg-b-90">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_content');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title_edit');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('mod_title_edit');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod);?>'"><i data-feather="arrow-left" class="mr-2"></i><?=lang_line('button_back');?></button>
		</div>
	</div>

	<div class="card">
		<?php 
			echo form_open('','id="form_update" autocomplete="off" class="form-bordered"');
			echo form_hidden('act', 'update');
		?>
		<div class="card-body">

			<!-- title -->
			<div class="form-group">
				<label><?=lang_line('_title');?> <span class="text-danger">*</span></label>
				<input type="text" name="title" id="title" class="form-control" value="<?=$res_category['title'];?>"/>
			</div>
			<!--/ title -->

			<!-- seotitle -->
			<div class="form-group">
				<label><?=lang_line('_seotitle');?> <span class="text-danger">*</span></label>
				<input type="text" name="seotitle" id="seotitle" class="form-control" value="<?=$res_category['seotitle'];?>" />
			</div>
			<!--/ seotitle -->

			<!-- description -->
			<div class="form-group">
				<label><?=lang_line('_description');?></label>
				<textarea name="description" class="form-control"><?=$res_category['description'];?></textarea>
			</div>
			<!--/ description -->

			<div class="row">
				<div class="col-md-4">
					<!-- parent -->
					<div class="form-group">
						<label><?=lang_line('_parent');?></label>
						<select class="select2" name="parent"  data-placeholder="<?=lang_line('_parent');?>">
							<option value="0"><?=lang_line('_no_parent');?></option>
							<?php
								foreach ($parents as $res_parent) {
									$selected = ( $res_parent['id'] == $res_category['id_parent'] ? 'selected': '' ); 
									echo '<option value="'. encrypt($res_parent['id']) .'" '. $selected .'>'. $res_parent['title'] .'</option>';
								}
							?>
						</select>
					</div>
					<!--/ parent -->
				</div>
				<div class="col-md-5">
					<!-- picture -->
					<div class="form-group">
						<label><?=lang_line('_picture');?></label>
						<div class="input-group" style="max-width:405px;">
							<div class="input-group-prepend">
								<div href="<?=content_url('plugins/filemanager/dialog.php?type=1&relative_url=1&field_id=picture&sort_by=date&descending=1&akey='.fmkey());?>" class="btn btn-default browse-files"><?=lang_line('button_browse');?></div>
							</div>
							<input id="picture" type="text" name="picture" value="<?=$res_category['picture'];?>" class="form-control" placeholder="Choose file..." readonly />
						</div>
					</div>
					<!--/ picture -->
				</div>
				<div class="col-md-2">
					<!-- Status -->
					<div class="form-group">
						<label><?=lang_line('_status');?> <span class="text-danger">*</span></label>
						<select name="status" class="select-bs">
							<option value="<?=$res_category['active'];?>" style="display:none;"><?=($res_category['active'] == 'Y' ? lang_line('ui_publish') : lang_line('ui_draft'));?></option>
							<option value="Y"><?=lang_line('ui_publish');?></option>
							<option value="N"><?=lang_line('ui_draft');?></option>
						</select>
					</div>
					<!--/ Status -->
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-lg btn-primary mr-2 submit_update"><i class="fa fa-save mr-2"></i><?=lang_line('button_save');?></button>
		</div>
	</div>
	<?=form_close();?>
</div>