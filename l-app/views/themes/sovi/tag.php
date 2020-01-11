<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- left content -->
<div class="col-lg-8 col-md-12 clearfix mb-5 left-content">
	<div class="box-category">
		<div class="post-head">
			<h4><i class="cificon licon-tag mr-1"></i> <?=$result_tag['title']?></h4>
		</div>
		<div class="post-inner clearfix">
			<?php foreach ($tag_post as $res): ?>
			<div class="post-lists">
				<div class="row">
					<div class="col-md-4">
						<div class="image-warper">
							<a href="<?=post_url($res['post_seotitle']);?>" title="<?=$res['post_title'];?>">
								<img  src="<?=post_images($res['picture'],'medium',TRUE);?>" alt="<?=$res['post_title'];?>">
							</a>
						</div>
					</div>
					<div class="col-md-8">
						<div class="media-body post-info">
							<h5>
								<a href="<?=post_url($res['post_seotitle']);?>" title="<?=$res['post_title'];?>"><?=$res['post_title'];?></a>
							</h5>
							<!-- meta -->
							<ul class="entry-meta clearfix">
								<li><i class="cificon licon-calendar"></i> <?=ci_date($res['datepost'].$res['timepost'], 'l, d F Y');?></li>
								<li><i class="cificon licon-folder"></i> <a href="<?=site_url('category/'.$res['category_seotitle']);?>"><?=$res['category_title'];?></a></li>
							</ul>
							<!--/ meta -->
							<p class="description"><?=cut($res['content'],150);?>...</p>
							<a href="<?=post_url($res['post_seotitle']);?>" class="readmore">Read More</a>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach ?>
		</div>
		<div class="post-footer">
			<div class="">
				<ul class="pagination">
					<?=$page_link;?>
				</ul>
			</div>
		</div>
	</div>
</div>
<!--/ left content -->

<!-- sidebar -->
<div class="col-lg-4 col-md-12 clearfix mb-5 sidebar">
	<?php $this->CI->_layout('sidebar'); ?>
</div>
<!--/ sidebar -->
