<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12 clearfix mb-5 left-content">
	<div class="box-pages">
		<div class="post-head">
			<h4>RSS Feeds</h4>
		</div>
		<div class="post-inner clearfix">
			<div class="entry-content detail-content clearfix">
				<!-- rss/all-posts -->
				<div class="row mb-1">
					<div class="col-sm-3">
						<i class="fa fa-rss mr-1 mr-1"></i><a href="<?=site_url('rss/all-posts');?>" target="_blank" title="Rss Latest Posts" class="text-dark">All Posts</a>
					</div>
					<div class="col-sm-9">
						<span><?=site_url('rss/all-posts');?></span>
					</div>
				</div>
				<!--/ rss/all-posts -->

				<?php foreach ($rss_lists as $res): ?>
				<div class="row mb-1">
					<div class="col-sm-3">
						<i class="fa fa-rss mr-1 mr-1"></i><a href="<?=site_url('rss/category/'.$res['seotitle']);?>" target="_blank" title="Rss <?=$res['title'];?>" class="text-dark"><?=$res['title'];?></a>
					</div>
					<div class="col-sm-9">
						<span><?=site_url('rss/category/'.$res['seotitle']);?></span>
					</div>
				</div>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>