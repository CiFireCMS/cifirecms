<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_media');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('mod_title');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod);?>'"><i data-feather="arrow-left" class="mr-2"></i><?=lang_line('button_back');?></button>
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase modal_add_picture"><i data-feather="image" class="mr-2"></i><?=lang_line('button_add_picture');?></button>
		</div>
	</div>

	<div>
		<?=$this->cifire_alert->show($this->mod);?>
	</div>

	<div class="card">
		<div class="card-header">
			<h6 class="lh-5 mg-b-0 text-uppercaseX"><i class="icon-images2 mr-2 tx-gray-600"></i> <?=$res_album['title'];?> </h6>
		</div>
		<div class="card-body">
			<?php if (!$gallerys) echo "<p class='text-center'>No data</p>"; ?>
			<div class="row" style="margin-top:-15px;">
				<?php 
					foreach ($gallerys as $res):
						$src_imgs = post_images($res['picture'], '', TRUE);
						$thumb = post_images($res['picture'], 'thumb', TRUE);
				?>
				<div id="gallery-item<?=$res['id'];?>" class="col-sm-6 col-md-4 col-lg-3 mt-3 gbhs">
					<div class="card item-gal">
						<div class="pd-6 text-center">
							<style type="text/css">.gbhs2{position: absolute; right:6px; top: 0; } </style>
							<div class="btn-group mt-1 gbhs2" style="display:none;">
								<button class="btn btn-xs btn-danger delete_gallery_image" data-id="<?=encrypt($res['id']);?>"><i class="fa fa-times"></i></button>
							</div>
							<div class="theme-img-card mb-2">
								<a class="fancybox" data-fancybox-group="gallery" title="<?=$res['title'];?>" href="<?=$src_imgs;?>">
									<img src="<?=content_url('images/medium_noimage.jpg');?>" data-src="<?=$thumb;?>" class="lazy" style="width:100%;">
								</a>
							</div>
							<div><?=$res['title'];?></div>
						</div>
					</div>
				</div>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>


<div id="modal_add_picture" class="modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title"><?=lang_line('dialog_title_add_picture'); ?></h6>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<?=form_open(selft_url(),'autocomplete="off"');?>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label><?=lang_line('_title');?> <small class="text-danger">*</small></label>
							<input type="text" name="title" value="<?=ci_date(date('Y-m-d h:i:s'),'l, d M  Y, his')?>" class="form-control" minlength="2" required/>
						</div>
						<div class="form-group">
							<label><?=lang_line('_picture');?> <small class="text-danger">*</small></label>
							<div class="input-group">
								<div class="input-group-prepend">
									<a id="browse-files" href="<?=content_url('plugins/filemanager/dialog.php?type=1&relative_url=1&field_id=picture&sort_by=date&descending=1&akey='.fmkey());?>" class="btn btn-default"><?=lang_line('button_browse');?></a>
								</div>
								<input id="picture" type="text" name="picture" class="form-control" placeholder="<?=lang_line('button_choose_file');?>" readonly/>
							</div>
		                </div>
	                </div>
                </div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success pull-left"><?=lang_line('button_submit'); ?></button>
				<button type="reset" class="btn btn-default delpict" data-dismiss="modal" aria-hidden="true"><?=lang_line('button_cancel'); ?></button>
			</div>
			<?=form_close();?>
		</div>
	</div>
</div>