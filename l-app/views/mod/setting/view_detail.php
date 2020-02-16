<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_apperance');?></a>
						<a href="<?=admin_url($this->mod);?>" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('_detail_setting');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('_detail_setting');?></h4>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod.'/list');?>'"><i data-feather="arrow-left" class="mr-2"></i><?=lang_line('button_back');?></button>
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod.'/edit/?id='.urlencode(encrypt($result['id'])));?>'"><i data-feather="edit" class="mr-2"></i><?=lang_line('button_edit');?></button>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<table class="table table-bordered no-footer">
				<tr>
					<td class="tx-medium wd-100"><?=lang_line('_groups');?></td>
					<td><?=$result['groups'];?></td>
				</tr>
				<tr>
					<td class="tx-medium wd-100"><?=lang_line('_options');?></td>
					<td><?=$result['options'];?></td>
				</tr>
				<tr>
					<td class="tx-medium wd-100"><?=lang_line('_value');?></td>
					<td>
						<?php if ($result['type']=='html'): ?>
							<pre><code class="language-html"><?php echo $result['value']; ?></code></pre>
						<?php elseif ($result['type']=='password'): ?>
							<em class="text-muted">Hidden</em>
						<?php else: ?>
							<?php echo $result['value']; ?>
						<?php endif ?>
					</td>
				</tr>
				<tr>
					<td class="tx-medium wd-100"><?=lang_line('_type');?></td>
					<td><?=$result['type'];?></td>
				</tr>
				<tr>
					<td class="tx-medium wd-100"><?=lang_line('_content');?></td>
					<td><?=$result['content'];?></td>
				</tr>
				<tr>
					<td class="tx-medium wd-100"><?=lang_line('_usage');?></td>
					<td>
						<code>get_setting('<?=$result['options'];?>')</code>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
