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

	<div class="content">
		<?=form_open('','id="form_update" autocomplete="off"');?>
		<div class="content-inner">
			<div class="row">
				<div class="col-md-9">
					<!-- Title -->
					<div class="form-group">
						<label><?=lang_line('_title');?> <span class="text-danger">*</span></label>
						<input id="title" type="text" name="title" class="form-control" value="<?=$res_pages['title'];?>" />
					</div>
					<!--/ Title -->

					<!-- seotitle -->
					<div class="form-group mg-b-30">
						<label><?=lang_line('_seotitle');?> <span class="text-danger">*</span></label>
						<input id="seotitle" type="text" name="seotitle" class="form-control" value="<?=$res_pages['seotitle'];?>"/>
					</div>
					<!--/ seotitle -->

					<!-- Content -->
					<div class="form-group mb-0">
						<label><?=lang_line('_content');?></label>
						<span class="btn-group pull-right">
							<button type="button" id="tiny-text" class="btn btn-xs btn-white"><?=lang_line('button_text');?></button type="button">
							<button type="button" id="tiny-visual" class="btn btn-xs btn-white"><?=lang_line('button_visual');?></button type="button">
						</span>
						<textarea id="Content" name="content" class="form-control" rows="20"><?=$res_pages['content'];?></textarea>
					</div>
					<!--/ Content -->
				</div>

				<div class="col-md-3">
					<!-- Status -->
					<div class="form-group">
						<label><?=lang_line('_status');?> <span class="text-danger">*</span></label>
						<select name="status" class="select-bs">
							<option value="<?=$res_pages['active'];?>" style="display:none;"><?=($res_pages['active'] == 'Y' ? lang_line('ui_publish') : lang_line('ui_draft'));?></option>
							<option value="Y"><?=lang_line('ui_publish');?></option>
							<option value="N"><?=lang_line('ui_draft');?></option>
						</select>
					</div>
					<!--/ Status -->

					<!-- picture -->
					<div class="form-group">
						<label><?=lang_line('_picture');?></label>
						<img id="imgprv" class="img-thumbnail" src="<?=post_images($res_pages['picture'],'medium',TRUE);?>" style="width:100%;">
						<div class="btn-group mt-2">
							<a id="filemanager" href="<?=content_url('plugins/filemanager/dialog.php?type=1&relative_url=1&field_id=picture&sort_by=date&descending=1&akey='.fmkey());?>" class="btn btn-xs btn-white"><i class="cificon licon-folder-plus"></i> <?=lang_line('button_browse');?></a>
							<button type="button" id="delpict" class="btn btn-xs btn-white"><i class="cificon licon-trash-2"></i> <?=lang_line('button_delete');?></button>
						</div>
						<input id="picture" type="hidden" name="picture" value="<?=$res_pages['picture'];?>" />
					</div>
					<!--/ picture -->
				</div>
			</div>
		</div>
		<div class="content-footer">
			<button type="submit" class="submit_update btn btn-lg btn-primary mr-2"><i id="submit_icon" class="fa fa-save mr-2"></i><?=lang_line('button_save');?></button>
		</div>
		<?=form_close();?>
	</div>
</div>