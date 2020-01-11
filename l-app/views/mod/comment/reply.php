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
						<a href="#" class="breadcrumb-item"><?=lang_line('_reply_comment');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('_reply_comment');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod);?>'"><i data-feather="arrow-left" class="mr-2"></i><?=lang_line('button_back');?></button>
		</div>
	</div>
	<?=$this->CI->cifire_alert->show($this->mod);?>
	<div class="card">
		<?php 
			echo form_open();
			echo form_hidden('act','reply');
			$parent = $result['parent']!=0?$result['parent']:$result['id'];
			echo form_hidden('parent', encrypt($parent));
		?>
		<div class="card-body">
			<div class="card pd-20 mg-b-20">
				<div class="tx-medium"><?=$result['name'];?> - <?=$result['email'];?></div>
				<div><small class="text-muted"><?=ci_date($result['date'], 'd M Y | h:i A');?></small></div>
				<p><small class="text-muted"><?=$result['ip'];?></small></p>
				<div><?=$result['comment'];?></div>
				<hr>
				<em><?=lang_line('_post');?>: <a href="<?=post_url($post['seotitle']);?>" target="_blank" class="text-primary"><?=$post['title'];?></a></em>
			</div>
			<div>
				<div class="form-group mb-0">
					<label><?=lang_line('_reply_comment');?> <span class="text-danger">*</span></label>
					<textarea id="Comments" name="comment" class="form-control" placeholder="<?=lang_line('_reply_comment');?>..." required></textarea>
				</div>
				
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-lg btn-primary"><i class="cificon licon-send mr-2"></i><?=lang_line('button_submit');?></button>
		</div>
		<?=form_close();?>
	</div>
</div>
