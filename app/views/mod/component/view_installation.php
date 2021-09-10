<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_module');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title_installation');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('mod_title_installation');?></h4>
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
	
	<div class="content">
		<?php 
			echo form_open_multipart();
			echo form_hidden('act', 1);
		?>
		<div class="content-inner">
			<div class="card bd pd-20 mg-b-10sX mg-b-30">
				<label class="tx-sans tx-10 tx-medium tx-spacing-1 tx-uppercase tx-color-03 mg-b-10"><?=lang_line('_instructions');?></label>
				<ol class="lh-7 mg-b-0">
					<?=lang_line('_instruction_content');?>
				</ol>
			</div>
			<div class="form-group mb-0">
				<label class="control-label"><?=lang_line('_component_package');?> (.zip) <span class="text-danger">*</span></label>
				<div class="custom-file">
					<input id="fupload" type="file" class="custom-file-input" name="file"/>
					<label label-for="fupload" class="custom-file-label" browse-label="<?=lang_line('button_browse');?>"><?=lang_line('button_choose_file');?></label>
				</div>
				<div class="detail-package mg-t-10 card pd-10" style="display:none;"></div>
			</div>
		</div>
		<div class="content-footer">
            <button id="install-button" type="submit" class="btn btn-lg btn-primary" disabled><i class="icon-cog mr-2"></i><?=lang_line('button_install');?></button>
		</div>
		<?=form_close();?>
	</div>
</div>