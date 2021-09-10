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
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title_edit');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('mod_title_edit');?> - <em><?=$res_theme['title'];?></em> </h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod);?>'"><i data-feather="arrow-left" class="mr-2"></i><?=lang_line('button_back');?></button>
		</div>
	</div>

	<div>
		<?=$this->cifire_alert->show($this->mod); ?>
	</div>


	<div class="card">
		<div class="card-header">
			<div class="btn-groupX">
				<!-- files -->
				<ul class="nav pull-left mg-r-3">
					<li class="nav-item dropdown">
						<button type="button" class="btn btn-sm btn-white nav-link dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-text-o mr-2"></i><?=$file_layout.".php";?></button>
						<div class="dropdown-menu dropdown-menu-left">
							<?php
								$fileLayout = VIEWPATH.'themes/'.$res_theme['folder']."/$file_layout.php";

								$data = read_file($fileLayout);
								$data = str_replace("textarea", "textarea_CI", $data);
								$theme_files = get_filenames(VIEWPATH.'themes/'.$res_theme['folder']);

								foreach ($theme_files as $value) {
									$ekstensi = pathinfo($value, PATHINFO_EXTENSION);
									$filename = pathinfo($value, PATHINFO_FILENAME);
									if ($ekstensi === 'php') {
										echo '<a class="dropdown-item" href="'.admin_url($this->mod.'/edit/'.$res_theme['id'].'/'.$filename).'">'.$value.'</a>';
									}
								}
							?>
						</div>
					</li>
				</ul>
				<!-- button create_file -->
				<button type="button" class="btn btn-sm btn-white create_file" data-toggle="tooltip" data-placement="top" title="Create File"><i class="icon-file-plus "></i></button>
			</div>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
					<?php 
						echo form_open('','autocomplete="off"'); 
						echo form_hidden('act','edit');
					?>
					<div class="box-body">
						<style type="text/css">.CodeMirror{height:400px;font-family:consolas;}.CodeMirror.CodeMirror-fullscreen{z-index:1060;height: 100% !important;}</style>
						<textarea id="AreaCodemirrors" name="code_content" class="form-control mt-0"><?=$data;?></textarea>
					</div>
					<div class="block-actions mt-3">
						<button type="submit" class="btn btn-lg btn-primary text-b"><i class="fa fa-save mr-2"></i><?=lang_line('button_save');?></button>
					</div>	
					<?=form_close();?>
				</div>
			</div>
		</div>
	</div>
</div>


<div id="modal_create_file" class="modal fadeX" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php 
				echo form_open('','autocomplete="off"');
				echo form_hidden('act','create_file');
			?>
			<div class="modal-header">
				<h5 class="modal-title"><i class="icon-file-plus mr-2"></i>Create File</h5>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label><?=lang_line('_filename');?></label>
					<input type="text" name="filename" class="form-control" required/>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success"><?=lang_line('button_submit')?></button>
				<span class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?=lang_line('button_cancel')?></span>
			</div>
			<?=form_close();?>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
	    var editor = CodeMirror.fromTextArea(document.getElementById("AreaCodemirrors"), {
	       mode: "php",
	        extraKeys: {
	            "Ctrl-J": "toMatchingTag",
	            "F11": function(cm) {
	                cm.setOption("fullScreen", !cm.getOption("fullScreen"));
	            },
	            "Esc": function(cm) {
	                if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
	            },
	            "Ctrl-Space": "autocomplete"
	        },
	       	theme: "github",
	        lineWrapping: true,
	        cursorBlinkRate: 200,
	        autocorrect: true,
	        autofocus: true,
	        lineNumbers: true,
	        gutters: ["CodeMirror-linenumbers"],
	        styleActiveLine: true,
	        autoCloseBrackets: true,
	        autoCloseTags: true
	        // scrollbarStyle:"simple",
	    });
	});
</script>