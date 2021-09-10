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
		<?=form_open('', 'id="form_update" autocomplete="off"');?>
		<div class="content-inner">
			<div class="row">
				<div class="col-md-9">
					<!-- Title -->
					<div class="form-group">
						<label><?=lang_line('_title');?> <span class="text-danger">*</span></label>
						<input id="title" type="text" name="title" class="form-control" value="<?=$result_post['post_title'];?>"/>
					</div>
					<!--/ Title -->

					<!-- seotitle -->
					<div class="form-group mg-b-30">
						<label><?=lang_line('_seotitle');?> <span class="text-danger">*</span></label>
						<input id="seotitle" type="text" name="seotitle" class="form-control" value="<?=$result_post['post_seotitle'];?>"/>
					</div>
					<!--/ seotitle -->

					<!-- Content -->
					<div class="form-group">
						<label><?=lang_line('_content');?> <span class="text-danger">*</span></label>
						<span class="btn-group pull-right">
							<button type="button" id="tiny-text" class="btn btn-xs btn-white"><?=lang_line('button_text');?></button type="button">
							<button type="button" id="tiny-visual" class="btn btn-xs btn-white"><?=lang_line('button_visual');?></button type="button">
						</span>
						<textarea id="Content" name="content" class="form-control" rows="20"><?=$result_post['post_content'];?></textarea>
					</div>
					<!--/ Content -->
				</div>

				<div class="col-md-3">
					<!-- Category -->
					<div class="form-group">
						<label><?=lang_line('_category');?> <span class="text-danger">*</span></label>
						<select name="category" class="select2 form-control" data-placeholder="- <?=lang_line('_category');?> -">
							<?php
								if (is_null($result_post['category_id'])) {
									echo '<option style="display:none;"></option>';
								}

								foreach ($all_category as $category) {
									$selected = ($category['id']==$result_post['category_id']?'selected':'');
									echo '<option value="'. encrypt($category['id']) .'" '. $selected .'>'. $category['title'] .'</option>';
								}
							?>
						</select>
					</div>
					<!--/ Category -->

					<!-- tags -->
					<div class="form-group">
						<label><?=lang_line('_tags');?></label>
						<input id="tagsjs" type="text" name="tags" class="form-control" value="<?=$this->CI->post_model->valtag($result_post['tag']);?>" />
					</div>
					<!--/ tags -->

					<!-- picture -->
					<div class="form-group">
						<label><?=lang_line('_picture');?></label>
						<img id="imgprv" class="img-thumbnail" src="<?=post_images($result_post['post_picture'], 'medium', TRUE);?>" style="width:100%;">
						<div class="btn-group mt-2">
							<a id="filemanager" href="<?=content_url('plugins/filemanager/dialog.php?type=1&relative_url=1&field_id=picture&sort_by=date&descending=1&akey='.fmkey());?>" class="btn btn-xs btn-white"><i class="cificon licon-folder-plus"></i> <?=lang_line('button_browse');?></a>
							<button type="button" id="delpict" class="btn btn-xs btn-white"><i class="cificon licon-trash-2"></i> <?=lang_line('button_delete');?></button>
						</div>
						<input id="picture" type="hidden" name="picture" value="<?=$result_post['post_picture'];?>" />
					</div>
					<!--/ picture -->

					<!-- Image descrption -->
					<div class="mt-3 form-group">
						<label><?=lang_line('_caption');?></label>
						<textarea name="image_caption" class="form-control" maxlength="100"><?=$result_post['image_caption'];?></textarea>
					</div>
					<!--/ Image descrption -->

					<div class="row">
						<div class="col-lg-6">
							<!-- date -->
							<div class="form-group">
								<label><?=lang_line('_date');?></label>
								<input type="text" id="publishdate" name="datepost" class="form-control" aria-label="Date" aria-describedby="basic-date" value="<?=date('Y-m-d',strtotime($result_post['datepost']));?>" required />
							</div>
							<!--/ date -->
						</div>
						<div class="col-lg-6">
							<!-- time -->
							<div class="form-group">
								<label><?=lang_line('_time');?></label>
								<input type="text" id="publishtime" name="timepost" class="form-control" aria-label="Time" aria-describedby="basic-time" value="<?=$result_post['timepost'];?>" required />
							</div>
							<!--/ time -->
						</div>
					</div>

					<div class="row">
						<div class="col-md-12 col-lg-6">
							<!-- Headline -->
							<div class="form-group">
								<label><?=lang_line('_headline');?></label>
								<select name="headline" class="select-bs form-control">
									<?=$headline;?>
									<option value="Y"><?=lang_line('ui_yes');?></option>
									<option value="N"><?=lang_line('ui_no');?></option>
								</select>
							</div>
							<!--/ Headline -->
						</div>
						<div class="col-md-12 col-lg-6">
							<!-- Comment -->
							<div class="form-group">
								<label><?=lang_line('_comment');?></label>
								<select name="comment" class="select-bs form-control">
									<?=$comment;?>
									<option value="Y"><?=lang_line('ui_active');?></option>
									<option value="N"><?=lang_line('ui_deactive');?></option>
								</select>
							</div>
							<!--/ Comment -->
						</div>
						<div class="col-md-12">
							<!-- Author -->
							<div class="form-group">
								<label><?=lang_line('_author');?> <span class="text-danger">*</span></label>
								<select name="author" class="select2 form-control">
									<?php 
										if ( group_active() == 'root' || group_active() == 'admin' ) {
											foreach ( $all_user as $key_user ) {
												$selected = ( $result_post['user_id']==$key_user['id'] ? 'selected' : '');		
												echo '<option value="'. $key_user['id'] .'" '. $selected .'>'. $key_user['name'] .'</option>';
											}
										} else {
											echo '<option value="'. $result_post['user_id'] .'" selected>'. $result_post['user_name'] .'</option>';
										}
									?>
								</select>
							</div>
							<!--/ Author -->
						</div>
						<div class="col-md-12">
							<!-- Status -->
							<div class="form-group">
								<label><?=lang_line('_status');?> <span class="text-danger">*</span></label>
								<select name="status" class="select-bs form-control">
									<?=$status;?>
									<option value="Y"><?=lang_line('ui_publish');?></option>
									<option value="N"><?=lang_line('ui_draft');?></option>
								</select>
							</div>
							<!--/ Status -->
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="content-footer">
			<button type="submit" class="btn btn-lg btn-primary mr-2 submit_update"><i id="submit_icon" class="fa fa-save mr-2"></i><?=lang_line('button_save');?></button>
		</div>
		<?=form_close();?>
	</div>
</div>