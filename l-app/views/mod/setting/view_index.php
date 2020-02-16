<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner p-settings mg-b-70">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_apperance');?></a>
						<a href="<?=admin_url($this->mod);?>" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('mod_title');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod.'/list');?>'"><i data-feather="grid" class="mr-2"></i><?=lang_line('button_list_settings');?></button>
		</div>
	</div>

	<?=$this->cifire_alert->show($this->mod);?>

	<ul class="nav nav-tabs nav-justified" role="tablist">
		<li class="nav-item">
			<a href="#Tab-General" class="nav-link tx-medium active" data-toggle="tab" style="color:#1b2e4b;"><?=lang_line('_general');?></a>
		</li>
		<li class="nav-item">
			<a href="#Tab-Image" class="nav-link tx-medium" data-toggle="tab" style="color:#1b2e4b;"><?=lang_line('_image');?></a>
		</li>
		<li class="nav-item">
			<a href="#Tab-Local" class="nav-link tx-medium" data-toggle="tab" style="color:#1b2e4b;"><?=lang_line('_local');?></a>
		</li>
		<li class="nav-item">
			<a href="#Tab-Mail" class="nav-link tx-medium" data-toggle="tab" style="color:#1b2e4b;"><?=lang_line('_mail');?></a>
		</li>
		<li class="nav-item">
			<a href="#Tab-Config" class="nav-link tx-medium" data-toggle="tab" style="color:#1b2e4b;"><?=lang_line('_config');?></a>
		</li>
		<li class="nav-item">
			<a href="#Tab-Other" class="nav-link tx-medium" data-toggle="tab" style="color:#1b2e4b;"><?=lang_line('_other');?></a>
		</li>
		<li class="nav-item">
			<a href="#Tab-MetaSocial" class="nav-link tx-medium" data-toggle="tab" style="color:#1b2e4b;"><?=lang_line('_metasocial');?></a>
		</li>
		<?php if (group_active()=='root'): ?>
		<li class="nav-item">
			<a href="#Tab-Backup" class="nav-link tx-medium" data-toggle="tab" style="color:#1b2e4b;"><?=lang_line('_backup');?></a>
		</li>
		<?php endif ?>
	</ul>

	<div class="tab-content bd bd-gray-400 bd-t-0 pd-20 bg-white">
		<!-- Tab-General -->
		<div id="Tab-General" class="tab-pane active">
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover mb-0">
						<?=$content_general;?>
					</table>
				</div>
			</div>
		</div>
		<!--/ Tab-General -->

		<!-- Tab-Image -->
		<div id="Tab-Image" class="tab-pane">
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover mb-0">
						<?=$content_image;?>
					</table>
				</div>
			</div>
		</div>
		<!--/ Tab-Image -->

		<!-- Tab-Local -->
		<div id="Tab-Local" class="tab-pane fadeX showX ">
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover mb-0">
						<?=$content_local;?>
					</table>
				</div>
			</div>
		</div>
		<!--/ Tab-Local -->

		<!-- Tab-Mail -->
		<div id="Tab-Mail" class="tab-pane fadeX showX ">
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover mb-0">
						<?=$content_mail; ?>
					</table>
				</div>
			</div>
		</div>
		<!--/ Tab-Mail -->

		<!-- Tab-Config -->
		<div id="Tab-Config" class="tab-pane fadeX showX ">
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover mb-0">
						<?=$content_config;?>
					</table>
				</div>
			</div>
		</div>
		<!--/ Tab-Config -->

		<!-- Tab-Other -->
		<div id="Tab-Other" class="tab-pane">
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover mb-0">
						<?=$content_other;?>
					</table>
				</div>
			</div>
		</div>
		<!--/ Tab-Other -->

		<!-- Tab-MetaSocial -->
		<div id="Tab-MetaSocial" class="tab-pane fadeX showX ">
			<div class="box-body">
				<?php 
					echo form_open('', 'id="form-meta"');
				?>
				<?php
					$filename_meta = VIEWPATH.'meta_social.php';
					if ( file_exists("$filename_meta") ) {
						$fh_meta = fopen($filename_meta, "r") or die("Could not open file!");
						$data_meta = fread($fh_meta, filesize($filename_meta)) or die("Could not read file!");
						fclose($fh_meta);
				?>
				<style type="text/css">.CodeMirror { height:600px;font-family:consolas;}</style>
				<textarea class="form-control content" id="code_metasocial" name="meta_content"><?=$data_meta;?></textarea>
				<button type="button" id="submit-meta" class="btn btn-lg btn-success mt-2"><i class="fa fa-save mr-2"></i><?=lang_line('button_save_metasocial');?></button>
				<div class="clearfix"></div>
				<?php } ?>
				<?=form_close();?>
			</div>
		</div>
		<!--/ Tab-MetaSocial -->


		<!-- Tab-Backup -->
		<div id="Tab-Backup" class="tab-pane">
			<style>#Tab-Backup label{cursor: pointer;}</style>
			<div class="box-body">
				<?php $this->cifire_alert->show('', 'default', lang_line('_back_warning_alert'), FALSE, TRUE); ?>
				<div class="card">
					<?php
						echo form_open(admin_url($this->mod.'/backup'),'id="form_backup"');
						echo form_hidden('act','backupSystem');
					?>
					<div class="card-body">
						<label class="tx-sans tx-10 tx-medium tx-spacing-1 tx-uppercase tx-color-03 mg-b-10"><?=lang_line('_webz');?></label>
						<div class="row">
							<div class="col-md-12 mg-b-30">
								<input id="c_webz" type="checkbox" name="web" value="1" class="c_data mr-1"> <label for="c_webz"><?=lang_line('_webzdata');?></label>
							</div>
						</div>
						
						<label class="tx-sans tx-10 tx-medium tx-spacing-1 tx-uppercase tx-color-03 mg-b-15"><?=lang_line('_database');?></label>
						<div class="row">
							<?php
								$tables = $this->db->list_tables();
								foreach ($tables as $table)
								{
								   echo '<div class="col-sm-4 col-md-3"><input id="c_'.$table.'" type="checkbox" name="table[]" value="'.$table.'" class="c_data mr-1"> <label for="c_'.$table.'">'.$table.'</label></div>';
								}
							?>
						</div>
						<hr>
						<div class="text-center">
							<div class="form-group mb-0">
								<input id="select-allz" type="checkbox" value="projectz"/>
								<label for="select-allz" class="mb-0 ml-1"><?=lang_line('ui_select_all');?></label>
							</div>
						</div>
					</div>
					<div id="btnBackupz" class="card-footer text-center" style="display:none;">
						<button type="submit" class="button_backup btn btn-lg btn-primary"><i class="fa fa-save mr-2"></i><?=lang_line("button_backup");?></button>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
			<script>
				var CountCheckedBackupz = $('.c_data:checkbox').prop('checked',false).length ;
				$("#select-allz").click(function(){
					$('.c_data:checkbox').not(this).prop('checked', this.checked);
					var backUpChecked = $('.c_data:checkbox:checked').length;
					if (backUpChecked < CountCheckedBackupz) {
						$('#select-allz').prop('checked',false);
					};
					if (backUpChecked > 0) {$('#btnBackupz').show();} else {$('#btnBackupz').hide();};
				});
				$('.c_data:checkbox').change(function(){
					var backUpChecked = $('.c_data:checkbox:checked').length;
					if (backUpChecked < CountCheckedBackupz) {
						$('#select-allz').prop('checked',false);
					};
					if (backUpChecked > 0) {$('#btnBackupz').show();} else {$('#btnBackupz').hide();};
				});
				$('.button_backup').click(function(){
					$('#btnBackupz').hide();
				});
			</script>
		</div>
		<!--/ Tab-Backup -->
	</div>
</div>



<!-- Modal upload image -->
<div id="modal_fedit" class="modal fadeX" id="modal-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modal_title"><i class="fa fa-upload"></i></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			</div>
			<?php 
				echo form_open_multipart(admin_url('setting/submit'),'autocomplete="off"');
			?>
			<div class="modal-body">
				<input id="data_act" type="hidden" name="pk">
				<div class="form-group">
					<label><?=lang_line('button_choose_file')?></label>
					<div class="custom-file">
						<input id="upload-image" type="file" class="custom-file-input" name="fupload"/>
						<label class="custom-file-label" for="upload-image" browse-label="<?=lang_line('button_browse');?>"><?=lang_line('button_choose_file')?></label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success"><i class="fa fa-upload mr-2"></i><?=lang_line('button_upload');?></button>
			</div>
			<?=form_close();?>
		</div>
	</div>
</div>
