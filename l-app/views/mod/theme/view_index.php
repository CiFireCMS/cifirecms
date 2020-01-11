<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_apperance');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title_all');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('mod_title_all');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod.'/add');?>'"><i data-feather="plus" class="mr-2"></i><?=lang_line('button_add');?></button>

			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase c_blank_theme"><i class="fa fa-magic mr-1"></i><?=lang_line('button_create_blank_theme');?></button>
		</div>
	</div>

	<div>
		<?=$this->cifire_alert->show($this->mod); ?>
	</div>
	
	<div class="card">
		<div class="card-body">
			<div class="row">
				<?php
					foreach ( $all_themes as $res ):
						$img_preview = ( file_exists(CONTENTPATH.'/themes/'.$res['folder'].'/preview.jpg') ? content_url('themes/'.$res['folder'].'/preview.jpg') : content_url('images/noimage.jpg') );
				?>
				<div id="theme-item-<?=$res['id'];?>" class="col-lg-3">
					<div class="card">
						<div class="card-body text-center">
							<style>
								.theme-img-card{
									background-color: #f1f1f1;
									overflow: hidden;
									width: 100%;
									height: 150px;
									border: 1px solid #ddd;
									display: block;
									border-radius: 3px;
									margin: auto;
								}
								.theme-img-card img{
									width: 100%;	
									margin-top: -4%;
								}
							</style>
							<p><?=$res['title'];?></p>
							<div class="theme-img-card">
								<a href="<?=$img_preview;?>" title="<?=$res['title'];?>" class="fancybox" data-fancybox-group="">
									<img src="<?=$img_preview;?>" style="width:100%;" alt="<?=$res['title'];?>">
								</a>
							</div>

							<p class="mb-3"></p>
							<div class="btn-group">
								<?php if ($res['active'] == 'Y'): ?>
								<button class="btn btn-xs btn-white"><i class="fa fa-star text-warning"></i> <?=lang_line('button_active');?></button>
								<?php endif ?>

								<?php if ($res['active'] == 'N'): ?>
								<button class="btn btn-xs btn-white modal_active" idActive="<?=$res['id'];?>" data-toggle="tooltip" data-title="<?=lang_line('button_active');?>"><i class='cificon licon-star'></i></button>
								<?php endif ?>
								
								<a href="<?=admin_url($this->mod.'/edit/'.$res['id'].'/home');?>" class="btn btn-xs btn-white alertedit" data-toggle="tooltip" title="<?=lang_line('button_edit');?>"><i class="cificon licon-edit"></i></a>

								<a  href="<?=admin_url($this->mod.'/backup/?id='.urlencode(encrypt($res['id'])));?>" class="btn btn-xs btn-white" data-toggle="tooltip" data-title="<?=lang_line('button_backup');?>"><i class="cificon licon-download"></i></a>

								<?php if ($res['active'] == 'N'): ?>
								<button class="btn btn-xs btn-white delete_theme" data-toggle="tooltip" data-title="<?=lang_line('button_delete');?>" data-id="<?=encrypt($res['id']);?>" data-folder="<?=encrypt($res['folder']);?>"><i class="cificon licon-trash-2"></i></button>
								<?php endif ?>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>


<div id="modal_create_blank" class="modal fadeX" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<?=form_open('','autocomplete="off"');?>
			<input type="hidden" name="act" value="blank_theme">
			<div class="modal-header">
				<h5 class="modal-title"><?=lang_line('dialog_title_create_blank'); ?></h5> 
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label><?=lang_line('form_label_title'); ?> <span class="text-danger">*</span></label>
					<input type="text" name="title" class="form-control" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-md btn-success"><i class="fa fa-check"></i>&nbsp; <?=lang_line('button_create_now');?></button>
				<button type="button" class="btn btn-md btn-default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i>&nbsp; <?=lang_line('button_cancel');?></button>
			</div>
			<?=form_close();?>
		</div>
	</div>
</div>

<div id="modal_active" class="modal fadeX" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php 
				echo form_open('','autocomplete="off"');
				echo form_hidden('act', 'active');
				echo form_input(array(
				                	'type' => 'hidden',
				                	'name' => 'id',
				                	'id'  => 'idActive',
				                ));
			?>
			<div class="modal-header">
				<h5 class="modal-title"><?=lang_line('dialog_title_activate'); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> 
			</div>
			<div class="modal-body">
				<h5 class="mt-3 mb-3"><?=lang_line('dialog_content_active_theme');?></h5>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success"><i class="fa fa-check mr-2"></i><?=lang_line('button_yes');?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-sign-out mr-2"></i><?=lang_line('button_no');?></button>
			</div>
			<?=form_close();?>
		</div>
	</div>
</div>
