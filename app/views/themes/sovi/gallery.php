<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12 clearfix mb-5 left-content">
	<div class="box-pages">
		<div class="post-head">
			<h4><i class="cificon licon-image mr-1"></i> Gallery</h4>
		</div>
		<div class="post-inner clearfix">
			<div class="container">
				<div id="gallery" class="row">
					<?php 
						foreach ($all_gallery_image as $res):
							$album = $this->CI->gallery_model->get_album($res['id_album']);
					?>
						<div class="g-item col-md-3">
							<a class="g-link" href="<?=post_images($res['picture'])?>" data-caption="<?='<strong>'.$res['title'].'</strong> <br/> <small><em>Album : '.$album['title'].'</em></small>'?>" data-width="1200" data-height="900">
								<img src="<?=post_images($res['picture'],'medium',true)?>" itemprop="thumbnail" alt="<?=$res['title']?>" />
							</a>
						</div>
					<?php endforeach ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="pswp__bg"></div>
	<div class="pswp__scroll-wrap">
		<div class="pswp__container">
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
		</div>
		<div class="pswp__ui pswp__ui--hidden">
			<div class="pswp__top-bar">
				<div class="pswp__counter"></div>
				<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
				<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
				<div class="pswp__preloader">
					<div class="pswp__preloader__icn">
						<div class="pswp__preloader__cut">
							<div class="pswp__preloader__donut"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
				<div class="pswp__share-tooltip"></div>
			</div>
			<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
			</button>
			<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
			</button>
			<div class="pswp__caption">
				<div class="pswp__caption__center"></div>
			</div>
		</div>
	</div>
</div>