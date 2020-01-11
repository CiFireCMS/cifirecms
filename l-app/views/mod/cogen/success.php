<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="card compogen mg-b-100 mg-t-20">
		<div class="card-header tx-center cogen-title">
			<h3>
				<font class="text-primary">C</font>
				<font class="text-success">o</font>
				<font class="text-warning">G</font>
				<font class="text-danger">e</font>
				<font class="text-info">n</font>
			</h3>
			<span>Component Generateor</span>
		</div>
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