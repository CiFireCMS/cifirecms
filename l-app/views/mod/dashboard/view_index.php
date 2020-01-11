<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="page-inner">
	<div class="mb-4 mt-4">
		<?= $this->cifire_alert->show('ENV'); ?>
	</div>
	
	<div class="card card-body ht-lg-100 mb-4 mt-4">
		<div class="media">
			<span class="tx-color-04"><i data-feather="tv" class="wd-60 ht-60"></i></span>
			<div class="media-body mg-l-20">
				<h6 class="mg-b-10 text-uppercase"><?=lang_line('welcome');?></h6>
				<p class="tx-color-03 mg-b-0"><?=lang_line('welcome_content');?></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6 col-md-3 mb-3">
			<div class="card card-body">
				<div class="media">
					<a href="<?=admin_url('post');?>"><div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-primary tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded"><i data-feather="book-open"></i></div></a>
					<div class="media-body">
						<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8"><?=lang_line('total_post');?></h6>
						<h4 class="tx-20 tx-sm-18 tx-md-20 tx-normal tx-rubik mg-b-0"><?=$h_post;?> <?=lang_line('items');?></h4>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-3 mb-3">
			<div class="card card-body">
				<div class="media">
					<a href="<?=admin_url('category');?>"><div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-success tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded"><i data-feather="folder-plus"></i></div></a>
					<div class="media-body">
						<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8"><?=lang_line('total_categories');?></h6>
						<h4 class="tx-20 tx-sm-18 tx-md-20 tx-normal tx-rubik mg-b-0"><?=$h_category;?> <?=lang_line('items');?></h4>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-3 mb-3">
			<div class="card card-body">
				<div class="media">
					<a href="<?=admin_url('tag');?>"><div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-warning tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded"><i data-feather="tag"></i></div></a>
					<div class="media-body">
						<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8"><?=lang_line('total_tags');?></h6>
						<h4 class="tx-20 tx-sm-18 tx-md-20 tx-normal tx-rubik mg-b-0"><?=$h_tags;?> <?=lang_line('items');?></h4>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-3 mb-3">
			<div class="card card-body">
				<div class="media">
					<a href="<?=admin_url('pages');?>"><div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-danger tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded"><i data-feather="file-text"></i></div></a>
					<div class="media-body">
						<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8"><?=lang_line('total_pages');?></h6>
						<h4 class="tx-20 tx-sm-18 tx-md-20 tx-normal tx-rubik mg-b-0"><?=$h_pages;?> <?=lang_line('items');?></h4>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-3 mb-3">
			<div class="card card-body">
				<div class="media">
					<a href="<?=admin_url('theme');?>"><div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-success tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded"><i data-feather="aperture"></i></div></a>
					<div class="media-body">
						<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8"><?=lang_line('total_themes');?></h6>
						<h4 class="tx-20 tx-sm-18 tx-md-20 tx-normal tx-rubik mg-b-0"><?=$h_theme;?> <?=lang_line('items');?></h4>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-3 mb-3">
			<div class="card card-body">
				<div class="media">
					<a href="<?=admin_url('component');?>"><div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-primary tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded"><i data-feather="package"></i></div></a>
					<div class="media-body">
						<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8"><?=lang_line('total_components');?></h6>
						<h4 class="tx-20 tx-sm-18 tx-md-20 tx-normal tx-rubik mg-b-0"><?=$h_component;?> <?=lang_line('items');?></h4>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-3 mb-3">
			<div class="card card-body">
				<div class="media">
					<a href="<?=admin_url('mail');?>"><div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-danger tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded"><i data-feather="mail"></i></div></a>
					<div class="media-body">
						<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8"><?=lang_line('total_mails');?></h6>
						<h4 class="tx-20 tx-sm-18 tx-md-20 tx-normal tx-rubik mg-b-0"><?=$h_mail;?> <?=lang_line('items');?></h4>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-3 mb-3">
			<div class="card card-body">
				<div class="media">
					<a href="<?=admin_url('user');?>"><div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-warning tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded"><i data-feather="users"></i></div></a>
					<div class="media-body">
						<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8"><?=lang_line('total_users');?></h6>
						<h4 class="tx-20 tx-sm-18 tx-md-20 tx-normal tx-rubik mg-b-0"><?=$h_users;?> <?=lang_line('items');?></h4>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-5">
			<div class="card">
				<div class="card-header">
					<h6 class="lh-5 mg-b-0 text-uppercase"><?=lang_line('popular_posts');?></h6>
				</div>
				<div class="card-body pd-0">
					<div class="scrollbar-lg popular-post" id="popular-post" style="height: 362px;  position: relative;">
						<?php foreach ($this->CI->Model->popular_post('', $limit = '5') as $popular): ?>
						<div class="d-sm-flex pd-20">
							<a href="<?=post_url($popular['post_seotitle']);?>" class="wd-100 wd-md-50 wd-lg-100 ht-60 ht-md-40 ht-lg-60" target="_blank">
								<img src="<?=post_images($popular['post_picture'],'medium',TRUE);?>" class="img-fit-cover">
							</a>
							<div class="media-body mg-t-20 mg-sm-t-0 mg-sm-l-20">
								<p class="tx-color-03 tx-12 mg-b-0"><?=ci_date($popular['post_datepost'],'l, d F Y');?></p>
								<h6 class="tx-14"><a href="<?=post_url($popular['post_seotitle']);?>" class="link-01" target="_blank"><?=$popular['post_title'];?></a></h6>
							</div>
						</div>
						<hr class="mg-0">
						<?php endforeach ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-7">
			<div class="card">
				<div class="card-header">
					<h6 class="lh-5 mg-b-0 text-uppercase"><?=lang_line('Analytic');?></h6>
				</div>
				<div class="card-body">
					<div class="clearfix">
						<div id="DashboardVisitors"></div>
					</div>
				</div>
			</div>
		</div>
	</div>


</div> 
<!-- page-inner -->
<p>&nbsp;</p>

<!-- apex-chart -->
<script src="<?=content_url('plugins/apex-chart/apexcharts.min.js');?>"></script>
<script>
	<?php
		$rrhari = implode($arrhari, ",");
		$rrvisitors = implode(array_reverse($rvisitors), ",");
		$rrhits = implode(array_reverse($rhits), ",");
	?>
	$(function () {
		'use strict';

		var options = {
			chart: {
				height: 315,
				type: 'area',
				shadow: {
					enabled: true,
					color: '#000',
					top: 18,
					left: 7,
					blur: 10,
					opacity: 1
				},
				toolbar: {
					show: false
				},
				animations: {
					enabled: true,
					easing: 'easeinout',
					speed: 800,
					animateGradually: {
						enabled: false,
					},
					dynamicAnimation: {
						enabled: true,
						speed: 300
					}
				}
			},
			colors: ['#77B6EA', '#545454'],
			dataLabels: {
				enabled: false,
			},
			stroke: {
				curve: 'smooth',
				width: 1,
			},
			series: [
				{
					name: "<?=lang_line('hits');?>",
					data: [<?php echo implode(array_reverse($rhits), ",");?>]
				},
				{
					name: "<?=lang_line('visitors');?>",
					data: [<?php echo implode(array_reverse($rvisitors), ",");?>]
				}
			],
			grid: {
				borderColor: '#e7e7e7',
				row: {
					colors: ['#f3f3f3', 'transparent'],
					opacity: 0.5
				},
			},
			markers: {
				size: 4
			},
			xaxis: {
				categories: [<?php echo implode($arrhari, ",");?>]
			},
			yaxis: {
				title: {
					text: ''
				},
				// min: 0,
				// max: 40
			},

			legend: {
				show: true,
				position: 'top',
				horizontalAlign: 'center', 
				fontSize: '12px',
				floating: true,
				offsetY: -25,
				itemMargin: {
					horizontal: 20,
					vertical: 5
				},
				onItemClick: {
					toggleDataSeries: true
				},
				onItemHover: {
					highlightDataSeries: true
				},	
			}
		}

		var chart = new ApexCharts(
			document.querySelector("#DashboardVisitors"),
			options
		);

		chart.render();
	});
</script>


