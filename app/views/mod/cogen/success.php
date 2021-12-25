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
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1">Component Generator</h4>
				</div>
			</div>
		</div>
	</div>

	<div class="card compogen mg-b-100">
		<div class="card-body">
			<div class="text-center">
				<h4><?=lang_line('mod_success1')?></h4>
				<br>
				<?php if ($fitur): ?>
				<p><?=$fitur?></p>
				<br>
				<?php endif ?>
				<button type="button" onclick="location.href='<?=admin_url($c_link)?>'" class="btn btn-lg btn-primary mb-3">
					<span class="ml-4 mr-4"><?=lang_line('button_goto_component')?></span>
				</button>
			</div>
		</div>
	</div>
</div>