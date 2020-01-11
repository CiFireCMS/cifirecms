<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase modal_add_album"><i data-feather="plus" class="mr-2"></i><?=lang_line('button_add_album');?></button>
		</div>
	</div>

	<div>
		<?=$this->cifire_alert->show($this->mod);?>
	</div>

	<div class="card">
		<div class="card-body">
			<?php if (!$albums) echo "<p class='text-center'>No data</p>"; ?>
			<div class="row" style="margin-top:-15px;">
				<?php 
					foreach ($albums as $res_album):
						$res = $this->db
							->select('picture')
							->where('id_album', $res_album['id'])
							->limit(1)
							->order_by('id', 'DESC')
							->get('t_gallery')
							->row_array();

						$photosrc = post_images($res['picture'],'thumb',TRUE);
				?>
				<div id="gallery-item<?=$res_album['id'];?>" class="col-sm-6 col-md-4 col-lg-3 mt-3 gbhs">
					<div class="card">
						<div class="pd-6 text-center">
							<style type="text/css">.gbhs2{position: absolute; right:8px; top: 0; } </style>
							<div class="btn-group mt-2 mb-2 gbhs2" style="display:none;">
								<button class="btn btn-xs btn-white" onclick="location.href='<?=admin_url($this->mod.'/album/?id='.urlencode(encrypt($res_album['id'])));?>'"><i class="cificon licon-eye"></i></button>
								<button class="btn btn-xs btn-white delete_album" data-id="<?=encrypt($res_album['id']);?>"><i class="cificon licon-trash-2"></i></button>
							</div>
							<div class="theme-img-card mb-2">
								<a href="<?=admin_url($this->mod.'/album/?id='.urlencode(encrypt($res_album['id'])));?>" title="<?=$res_album['title'];?>">
									<img src="<?=post_images(null,'thumb',TRUE);?>" data-src="<?=$photosrc;?>" class="lazy" style="width:100%;">
								</a>
							</div>
							<div><?=humanize($res_album['title']);?></div>
						</div>
					</div>
				</div>
				
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>

<div id="modal_add_album" class="modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<?=form_open(selft_url(),'autocomplete="off"');?>
			<div class="modal-header">
				<h5 class="modal-title"><?=lang_line('dialog_title_add_album');?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label><?=lang_line('_title_album');?> <small class="text-danger">*</small></label>
					<input type="text" name="title" value="<?=ci_date(date('Y-m-d h:i:s'),'l, d M  Y, his')?>" class="form-control" required/>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success"><?=lang_line('button_submit');?></button>
				<button type="reset" class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?=lang_line('button_cancel');?></button>
			</div>
			<?=form_close();?>
		</div>
	</div>
</div>
