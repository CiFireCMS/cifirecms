<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title_add');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('mod_title_add');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod);?>'"><i data-feather="arrow-left" class="mr-2"></i><?=lang_line('button_back');?></button>
		</div>
	</div>

	<div class="card mg-b-50">
		<?=form_open_multipart('','id="form_add_user" autocomplete="off"');?>
		<div class="card-body">
			<div class="row">
				<!-- Group -->
				<div class="col-md-6 col-lg-3">
					<div class="form-group">
						<label><?=lang_line('_group');?> <span class="text-danger">*</span></label>
						<select class="select2" name="group"  data-placeholder="Group">
							<?php 
								echo '<option value=""></option>';
								$groups = $this->CI->user_model->data_groups();
								foreach ($groups as $resGroup) {
									echo '<option value="'. $resGroup['pk'] .'">'. $resGroup['title'] .'</option>';
								}
							?>
						</select>
					</div>
				</div>
				<!--/ Group -->

				<!-- Username -->
				<div class="col-md-6 col-lg-3">
					<div class="form-group">
						<label><?=lang_line('_username');?> <span class="text-danger">*</span></label>
						<input type="text" name="username" class="form-control" />
					</div>
				</div>
				<!--/ Username -->

				<!-- Email -->
				<div class="col-md-6 col-lg-3">
					<div class="form-group">
						<label><?=lang_line('_email');?> <span class="text-danger">*</span></label>
						<input type="text" name="email" class="form-control" />
					</div>
				</div>
				<!--/ Email -->

				<!-- Password -->
				<div class="col-md-6 col-lg-3">
					<div class="form-group">
						<label><?=lang_line('_password');?> <span class="text-danger">*</span></label>
						<input type="password" name="input_password" class="form-control input_password" />
					</div>
				</div>
				<!--/ Password -->

				<!-- Name -->
				<div class="col-md-6 col-lg-3">
					<div class="form-group">
						<label><?=lang_line('_name');?> <span class="text-danger">*</span></label>
						<input type="text" name="name" class="form-control"/>
					</div>
				</div>
				<!--/ Name -->

				<!-- Birthday -->
				<div class="col-md-6 col-lg-3">
					<div class="form-group">
						<label><?=lang_line('_birthday');?></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-date"><i class="fa fa-calendar"></i></span>
							</div>
							<input type="text" id="input-datepicker" name="birthday" class="form-control" aria-label="Date" aria-describedby="basic-date" value="<?=date('Y-m-d');?>" />
						</div>
					</div>
				</div>
				<!--/ Birthday -->

				<!-- Gender -->
				<div class="col-md-6 col-lg-3">
					<div class="form-group">
						<label><?=lang_line('_gender');?></label>
						<select name="gender" class="select-bs form-control">
							<option value="M"><?=lang_line('ui_male');?></option>
							<option value="F"><?=lang_line('ui_female');?></option>
						</select>
					</div>
				</div>
				<!--/ Gender -->

				<!-- Telephone -->
				<div class="col-md-6 col-lg-3">
					<div class="form-group">
						<label><?=lang_line('_tlpn');?></label>
						<input type="text" name="tlpn" class="form-control"/>
					</div>
				</div>
				<!--/ Telephone -->

				<!-- About -->
				<div class="col-md-6">
					<div class="form-group">
						<label><?=lang_line('_about');?></label>
						<textarea name="about" class="form-control" rows="5"></textarea>
					</div>
				</div>
				<!--/ About -->

				<!-- Address -->
				<div class="col-md-6">
					<div class="form-group">
						<label><?=lang_line('_address');?></label>
						<textarea name="address" class="form-control" rows="5"></textarea>
					</div>
				</div>
				<!--/ Address -->

				<!-- Status -->
				<div class="col-md-6 col-lg-3">
					<div class="form-group">
						<label><?=lang_line('_status');?></label>
						<select class="select-bs form-control" name="active">
							<option value="Y"><?=lang_line('ui_active');?></option>
							<option value="N"><?=lang_line('ui_deactive');?></option>
						</select>
					</div>
				</div>
				<!--/ Status -->

				<!-- Photo -->
				<div class="col-md-6 col-lg-5">
					<div class="form-group">
						<label><?=lang_line('_photo');?></label>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="upload-image" name="fupload"/>
							<label class="custom-file-label" for="upload-image" browse-label="<?=lang_line('button_browse');?>"><?=lang_line('button_choose_file');?></label>
						</div>
					</div>
				</div>
				<!--/ Photo -->
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-lg btn-primary submit_add"><i id="submit_icon" class="cificon licon-send mr-2"></i><?=lang_line('button_submit');?></button>
		</div>
		<?=form_close();?>
	</div>
</div>
