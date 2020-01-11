<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner mg-b-50">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_content');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title_preview');?></a>
					</div>
					<div class="">
						<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('mod_title_preview');?></h4>
					</div>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod);?>'"><i data-feather="arrow-left" class="mr-2"></i><?=lang_line('button_back');?></button>
			<button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod.'/edit/?id='.urlencode(encrypt($res['post_id'])));?>'"><i data-feather="edit" class="mr-2"></i><?=lang_line('button_edit');?></button>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<div class="row justify-content-center">
				<div class="col-md-9 align-self-center detailpost">
					<div class="mt-2">
						<?php
							$tags = explode(',', $res['tag']);
							if ( ! empty($res['tag']) && $tags > 0) {
								foreach ($tags as $tag) {
									$tag = seotitle($tag, NULL);
									$resTag = $this->CI->db->where('seotitle', $tag)->get('t_tag')->row_array();
									if ( $tag == $resTag['seotitle'] ) {
										echo '<span class="posttag text-primary">#'.$resTag['title'].'</span>';
									}
								}
							}
						?>
					</div>

					<h4 class="title"><?=$res['post_title'];?></h4>
					
					<div class="mg-b-20">
						<em class="text-muted meta">
							<div>
								<span><i class="cificon licon-user"></i> <?=$res['user_name'];?></span>
								<span class="mr-1 ml-1">/</span>
								<span  class="mr-1"><i class="cificon licon-calendar"></i> <?=ci_date($res['datepost'], 'l, d F Y');?></span>
								<i class="cificon licon-clock"></i> <?=ltrim(ci_date($res['timepost'], 'h:i A'),'0');?>
								<span class="mr-1 ml-1">/</span>
								<span><i class="cificon licon-folder-plus"></i> <?=$res['category_title'];?></span>
								<span class="mr-1 ml-1">/</span>
								<span><i class="cificon licon-eye"></i> <?=$res['hits'];?></span>
								<span class="mr-1 ml-1">/</span>
								<span><i class="cificon licon-message-square"></i> <?=$res['comment'];?></span>
							</div>
						</em>
					</div>
					<?php if (post_images($res['picture'])): ?>					
						<img src="<?=post_images($res['picture']);?>" class="pic" />
						<?php if ($res['image_caption']): ?>
						<p class="imgcaption"><?=$res['image_caption'];?></p>	
						<?php endif ?>
						<br>
					<?php endif ?>
					<link rel="stylesheet" href="<?php echo content_url('plugins/prism/prism.css');?>" type="text/css"/>
					<div class="preview-content"><?=html_entity_decode($res['content']);?></div>	
				</div>
			</div>
		</div>
	</div>
</div>