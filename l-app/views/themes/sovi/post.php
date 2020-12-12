<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- left content -->
<div class="col-lg-8 col-md-12 clearfix mb-5 left-content">
	<div class="post-inner clearfix">
		<!-- post tags -->
		<div class="tagcloud clearfix mb-1">
			<?php
				$tags = explode(',', $result_post['tag']);
				if ( ! empty($result_post['tag']) && $tags > 0) {
					foreach ($tags as $tag) {
						$tag = seotitle($tag, NULL);
						$resTag = $this->CI->db->where('seotitle', $tag)->get('t_tag')->row_array();
						if ( $tag == $resTag['seotitle'] )
							echo '<a href="'.site_url('tag/'.$tag).'" rel="tag">'.$resTag['title'].'</a>';
					}
				}
			?>
		</div>
		<!--/ post tags -->

		<!-- post title -->
		<div class="entry-title mb-1">
			<h2><?=$result_post['post_title'];?></h2>
		</div>
		<!--/ post title -->

		<!-- post meta -->
		<ul class="entry-meta clearfix">
			<li><i class="cificon licon-calendar"></i> <?=ci_date($result_post['datepost'], 'l, d F Y');?> &nbsp; <i class="cificon licon-clock"></i> <?=ci_date($result_post['timepost'], 'h:i A');?></li>
			<li><a href="<?=site_url('category/'.$result_post['category_seotitle']);?>"><i class="cificon licon-folder"></i> <?=$result_post['category_title'];?></a></li>
			<li><a href="#comments"><i class="cificon licon-message-square"></i> <?=$result_post['comment'];?></a></li>
			<li><i class="cificon licon-eye"></i> <?=$result_post['hits'];?></li>
		</ul>
		<!--/ post meta -->

		<!-- post image -->
		<?php if ( post_images($result_post['picture']) ):?>
		<div class="entry-image">
			<img src="<?=post_images($result_post['picture']);?>" alt="<?=$result_post['image_caption'];?>"/>
			<!-- image description -->
			<?php if ($result_post['image_caption']): ?>
			<div class="image-description"><?=$result_post['image_caption'];?></div>
			<?php endif ?>
			<!--/ image description -->
		</div>
		<?php endif ?>
		<!-- post image -->

		<!-- post content -->
		<div class="entry-content detail-content mt-3 clearfix">
			<?=$result_post['content'];?>
		</div>
		<!-- post content -->
	</div>

	<!-- content paging -->
	<?php if ($content_paging): ?>
	<div class="post-footer">
		<div class="content-pagination clearfix">
			<!-- <div class="cp-text pull-left">Page</div> -->
			<ul class="pagination">
				<?=$content_paging;?>
			</ul>
		</div>
	</div>
	<?php endif ?>
	<!--/ content paging -->

	<!-- Author Info -->
	<div class="post-head mt-2">
		<h4>Editor : <small class="tx-capitalize text-primary"><?=$result_post['author_name']?></small>	</h4>
	</div>
	<div class="post-inner clearfix mb-2">
		<div class="entry-author">
			<div class="author-image">
				<img src="<?=user_photo($result_post['author_photo'])?>" alt="<?=$result_post['author_name']?>" class="rounded-circle">
			</div>
			<div class="author-about"><span><?=$result_post['author_about']?></span></div>
		</div>
	</div>
	<!--/ Author Info -->

	<!-- Posts navigation -->
	<div class="post-inner clearfix mb-2">
		<div class="post-np">
			<div class="row">
				<div class="col-sm-6 text-left">
					<a href="<?=$prev_post['url']?>" title="Previous Post" class="btn btn-light">&lArr; Previous</a>
					<div class="post-np-title"><small><a href="<?=$prev_post['url']?>" title="<?=$prev_post['title']?>" class="text-dark"><?=cut($prev_post['title'],100)?>...</a></small></div>
				</div>
				<div class="col-sm-6 text-right">
					<a href="<?=$next_post['url']?>" title="Next Post" class="btn btn-light">Next &rArr;</a>
					<div class="post-np-title"><small><a href="<?=$next_post['url']?>" title="<?=$next_post['title']?>" class="text-dark"><?=cut($next_post['title'],100)?>...</a></small></div>
				</div>
			</div>
		</div>
	</div>
	<!--/ Posts navigation -->


	<!-- related posts -->
	<div class="post-head">
		<h4>Related Posts</h4>
	</div>
	<div class="post-inner clearfix mb-2">
		<div class="related-posts clearfix">
			<ul>
			<?php
				$related_posts = $this->CI->post_model->related_post($result_post['tag'], $result_post['post_id'], 4);
				foreach ($related_posts as $res_relatedpost):
			?>
				<li><a href="<?=post_url($res_relatedpost['seotitle']);?>" title="<?=$res_relatedpost['title'];?>"><?=$res_relatedpost['title'];?></a></li>
			<?php endforeach ?>
			</ul>
		</div>
	</div>
	<!--/ related posts -->

	<?php if ( get_setting('post_comment')=='Y' && $result_post['post_comment']=='Y' ): ?>
	<!-- comments list -->
	<div id="comments" class="post-head">
		<h4>Comments (<?=$result_post['comment'];?>)</h4>
	</div>
	<div class="post-inner clearfix mb-2">
		<div class="entry-comment clearfix">
			<div class="list-comment clearfix ">
				<?php
					$data_comments = $this->CI->db
						->where('id_post', $result_post['post_id'])
						->where('active != "N"')
						->where('parent = "0"', NULL, FALSE)
						->get('t_comment');

					foreach ($data_comments->result_array() as $comment):
						$usersa = $this->CI->db
							->select('id,photo')
							->where('id', $comment['id_user'])
							->get('t_user')
							->row_array();
				?>
				<div class="media mt-2">
					<img src="<?=user_photo($usersa['photo']);?>" class="mr-3" width="45">
					<div class="media-body">
						<h5 class="comment-name mt-0">
							<?=$comment['name'];?> 
							<small class="text-muted ml-3"><?=ci_date($comment['date'],'d M Y | h:i A');?></small>
						</h5>
						<div class="comment-body mb-5">
							<?php 
								if ($comment['active'] == 'X') {
									echo '<i class="text-danger">****</i>';
								} else {
									echo '<p>'.auto_link($comment['comment']).'</p>';
										echo '<a href="#form-comment" class="reply_comment" data-parent="'.encrypt($comment['id']).'">Reply</a>';
								}
							?>
						</div>
						

						<!-- child -->
						<?php
							if ($comment['active'] != 'X'):
								
								$child_comments = $this->CI->db
									->where('parent', $comment['id'])
									->where('active != "N"')
									->get('t_comment');

								foreach ($child_comments->result_array() as $res_child):
									$users_rep = $this->CI->db
										->select('id,photo')
										->where('id', $res_child['id_user'])
										->where('active', 'Y')
										->get('t_user')
										->row_array();
						?>
						
						<div class="media mt-4 mb-5">
							<img src="<?=user_photo($users_rep['photo']);?>" class="mr-3" width="45">
							<div class="media-body">
								<h5 class="comment-name mt-0">
									<?=$res_child['name'];?> 
									<small class="text-muted ml-3"><?=ci_date($res_child['date'],'d M Y | h:i A');?></small>
								</h5>
								<div class="comment-body">
									<?php 
										if ($res_child['active'] == 'X') {
											echo '<i class="text-danger">****</i>';
										} else {
											echo '<p>'.auto_link($res_child['comment']).'</p>';
											echo '<a href="#form-comment" class="reply_comment" data-parent="'.encrypt($comment['id']).'">Reply</a>';
										}
									?>
								</div>
							</div>
						</div>
						<?php endforeach ?>
						<?php endif ?>
						<!--/ child -->
					</div>
				</div>
				<?php endforeach ?>
			</div>
		</div>
	</div>
	<!--/ comments list -->

	<!-- form comment -->
	<div id="form-comment" class="post-head">
		<h4>Leave a Comment</h4>
	</div>
	<div class="post-inner">
		<div class="form-comment">
			<?php
				$this->cifire_alert->show('alert_comment');
				echo form_open(selft_url().'#form-comment','class="clearfix"');
			?>
			<input type="hidden" name="parent" class="input_parent"/>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="name">Name <span class="text-danger">*</span></label>
						<input id="name" type="text" name="name" class="form-control"/>
					</div>
					<div class="form-group">
						<label for="email">Email <span class="text-danger">*</span></label>
						<input id="email" type="email" name="email" class="form-control"/>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="comment">Comment <span class="text-danger">*</span></label>
				<textarea name="comment" rows="3" class="form-control"></textarea>
			</div>
				
			<div class=" text-right">
				<?php if (get_setting('recaptcha')=="Y"): ?>
				<div class="g-recaptcha pull-left" data-sitekey="<?=get_setting('recaptcha_site_key')?>" style="margin-bottom:9px;"></div>
				<?php endif ?>
				<button type="submit" tabindex="5" class="btn btn-primary btn-send pull-tight"><i class="cificon licon-send mr-1"></i><?=lang_line('button_send')?></button>
			</div>

			<?=form_close();?>
		</div>
	</div>
	<!--/ form comment -->
	<?php endif ?>
</div>
<!--/ left content -->

<!-- sidebar -->
<div class="col-lg-4 col-md-12 clearfix mb-5 sidebar">
	<?php $this->CI->_layout('sidebar'); ?>
</div>
<!--/ sidebar -->
