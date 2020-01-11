<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title_edit');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('mod_title_edit');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-md pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod);?>'"><i data-feather="user" class="mr-2"></i><?=lang_line('mod_title');?></button>
		</div>
	</div>
	
	<div class="content">
		<?=form_open_multipart('','id="form_update" autocomplete="off"');?>
		<div class="content-inner">

			<div class="row">
				<!-- Usermane -->
				<div class="col-md-4 col-lg-4">
					<div class="form-group mg-b-25">
						<label><?=lang_line('_username');?></label>
						<input type="text" class="form-control" value="<?=$this->data['user_username'];?>" disabled />
					</div>
				</div>
				<!--/ Usermane -->
				<!-- Email -->
				<div class="col-md-4 col-lg-4">
					<div class="form-group mg-b-25">
						<label><?=lang_line('_email');?> <span class="text-danger">*</span></label>
						<input type="text" name="email" class="form-control" value="<?=$this->data['user_email'];?>"/>
					</div>
				</div>
				<!--/ Email -->
				<!-- Password -->
				<div class="col-md-4 col-lg-4">
					<div class="form-group mg-b-25">
						<label><?=lang_line('_password');?></label>
						<input type="password" name="input_password" class="form-control" />
						<em><small class="text-muted">Please leave empty if password don't change</small></em>
					</div>
				</div>
				<!--/ Password -->
			</div>
			
			<hr class="mg-t-0 mg-b-25" />

			<div class="row">
				<div class="col-md-6">
					<!-- Name -->
					<div class="form-group mg-b-25">
						<label><?=lang_line('_name');?> <span class="text-danger">*</span></label>
						<input type="text" name="name" class="form-control" value="<?=$this->data['user_name'];?>" />
					</div>
					<!--/ Name -->
					<!-- Birthday -->
					<div class="form-group mg-b-25">
						<label><?=lang_line('_birthday');?></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-date"><i class="fa fa-calendar"></i></span>
							</div>
							<input type="text" id="input-datepicker" name="birthday" class="form-control" aria-label="Date" aria-describedby="basic-date" value="<?=$this->data['user_birthday'];?>" />
						</div>
					</div>
					<!--/ Birthday -->
					<!-- Gender -->
					<div class="form-group mg-b-25">
						<label><?=lang_line('_gender');?></label>
						<select class="select-bs" name="gender">
							<option value="<?=$this->data['user_gender'];?>" style="display:none;"><?=($this->data['user_gender'] == 'M' ? lang_line('ui_male') : lang_line('ui_female') );?></option>
							<option value="M"><?=lang_line('ui_male');?></option>
							<option value="F"><?=lang_line('ui_female');?></option>
						</select>
					</div>
					<!--/ Gender -->
					<!-- Telephone -->
					<div class="form-group mg-b-25">
						<label><?=lang_line('_tlpn');?> <span class="text-danger">*</span></label>
						<input type="text" name="tlpn" class="form-control" placeholder="+62 000-0000-0000" value="<?=$this->data['user_tlpn'];?>" />
					</div>
					<!--/ Telephone -->
				</div>

				<div class="col-md-6">
					<!-- About -->
					<div class="form-group mg-b-25">
						<label><?=lang_line('_about');?></label>
						<textarea name="about" class="form-control" style="min-height:128px;max-height:128px;"><?=trim(set_value('about')) != "" ? trim(set_value('about')) : $this->data['user_about'];?></textarea>
					</div>
					<!--/ About -->
					<!-- Address -->
					<div class="form-group mg-b-25">
						<label><?=lang_line('_address');?></label>
						<textarea name="address" class="form-control" style="min-height:128px;max-height:128px;"><?=trim(set_value('address')) != "" ? trim(set_value('address')) : $this->data['user_address'];?></textarea>
					</div>
					<!--/ Address -->
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<!-- Photo -->
					<div class="form-group">
						<label><?=lang_line('_photo');?></label>
						<div class="custom-file">
							<input id="fupload" type="file" name="fupload" class="custom-file-input"/>
							<label label-for="fupload" class="custom-file-label" browse-label="<?=lang_line('button_browse');?>"><?=lang_line('button_choose_file');?></label>
						</div>
					</div>
					<div class="text-center mg-t-30" style="width:100px;">
						<img id="upload-image-preview" src="<?=user_photo($this->data['user_photo']);?>" style="border:1px solid #ddd;padding:5px;border-radius:4px;width:100%;">
					</div>
					<!--/ Photo -->
				</div>
			</div>
		</div>
		<div class="content-footer">
			<button type="submit" class="btn btn-lg btn-primary submit_update"><i id="submit_icon" class="fa fa-save mr-2"></i><?=lang_line('button_save');?></button>
		</div>
		<?=form_close();?>
	</div>
</div>
