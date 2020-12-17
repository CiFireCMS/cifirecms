<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?=$this->meta_title;?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta name="description" content="<?=$this->meta_description;?>"/>
	<meta name="keywords" content="<?=$this->meta_keywords;?>"/>
	<meta name="author" content="<?=get_setting('web_author');?>"/>
	<meta http-equiv="Copyright" content="<?=get_setting('web_name');?>"/>
	<meta http-equiv="imagetoolbar" content="no"/>
	<meta name="language" content="english"/>
	<meta name="revisit-after" content="7"/>
	<meta name="webcrawlers" content="all"/>
	<meta name="rating" content="general"/>
	<meta name="spiders" content="all"/>

	<!-- favicon -->
	<link rel="shortcut icon" href="<?=favicon();?>"/>

	<!-- metasocial -->
	<?php $this->load->view('meta_social'); ?>

	<!-- stylesheet -->
	<link rel="stylesheet" href="<?=content_url('plugins/bootstrap/css/bootstrap.min.css');?>"/>
	<link rel="stylesheet" href="<?=content_url('plugins/prism/prism.css');?>"/>
	<link rel="stylesheet" href="<?=content_url('plugins/font-awesome/font-awesome.min.css');?>" type="text/css"/>
	<link rel="stylesheet" href="<?=content_url('plugins/cifireicon-feather/cifireicon-feather.min.css');?>" type="text/css"/>
	<link rel="stylesheet" href="<?=content_url('plugins/photoswipe/photoswipe.css');?>">
	<link rel="stylesheet" href="<?=content_url('plugins/photoswipe/default-skin/default-skin.css');?>"> 
	<link rel="stylesheet" href="<?=$this->CI->theme_asset('css/style.css');?>" />

	<!-- script -->
	<script src="<?=content_url('plugins/jquery/jquery3.4.1.min.js');?>"></script>

	<!-- google analytics -->
	<?=google_analytics();?>
	
</head>
<body>
	<section class="header">
		<div class="header-top">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="top-link">
							<ul>
								<li><a href="<?=site_url('pages/about-us')?>">About Us</a></li>
								<li><a href="<?=site_url('contact')?>">Contact</a></li>
								<li><a href="#"><i class="fa fa-phone"></i> <?=get_setting('fax')?></a></li>
								<li><a href="mailto:<?=get_setting('web_email')?>"><i class="fa fa-envelope-o"></i> <?=get_setting('web_email')?></a></li>
							</ul>
						</div>
					</div>
					<div class="col text-right">
						<div class="top-social">
							<a href="#"><i class="fa fa-facebook"></i></a>
							<a href="#"><i class="fa fa-twitter"></i></a>
							<a href="#"><i class="fa fa-pinterest"></i></a>
							<a href="#"><i class="fa fa-instagram"></i></a>
							<a href="#"><i class="fa fa-github"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="header-inner">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<div class="logo">
							<a href="<?=site_url();?>" title="<?=get_setting('web_name');?>">
								<img src="<?=favicon('logo');?>" alt="Logo"/>
							</a>
						</div>
					</div>
					<div class="top-ads col-md-8 text-right">
						<a href="#">
							<img src="<?=post_images('add728x90.jpg');?>" alt="ADS" style="max-width:100%;height:auto">
						</a>
					</div>
				</div>
			</div>
		</div>
		<div id="navsticky">
			<!-- form search -->
			<div class="top-search-warper">
				<div class="container">
					<?=form_open(site_url('search'),'class="search-form"');?>
					<input type="text" name="kata" class="input-search" placeholder="Search..."/>
					<?=form_close();?>
				</div>
			</div>
			<!--/ form search -->

			<!-- top nav -->
			<nav class="navbar navbar-expand-sm bg-white top-menu">
				<div class="container">
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#topMenu" aria-controls="topMenu" aria-label="Toggle navigation">
						<span class="fa fa-navicon"></span>
					</button>
					<div id="topMenu" class="collapse navbar-collapse">
						<?php 
							// Load web menu.
							$this->CI->load_menu(
												  $menu_group = 2, 
												  $ul = 'class="navbar-nav"', 
												  $ul_li = 'class="nav-item dropdown"', 
												  $ul_li_a ='class="nav-link"', 
												  $ul_li_a_ul = 'class="dropdown-menu"'
												)
						?>
					</div>
					<div class="top-seach-link">
						<a href="javascript:void(0)" class="search-toggle"><i class="fa fa-search"></i></a>
					</div>	
				</div>
			</nav>
			<!--/ top nav -->
		</div>
	</section>

	<section id="main" class="container main-content mt-5">
		<div class="row">
			<?php $this->CI->_layout($this->CI->__content_view); ?>
		</div>
	</section>

	<section id="footer">
		<div class="footer">
			<div class="container">
				<p><?=copyright();?></p>
			</div>
		</div>
	</section>
	
	<!-- script -->
	<script src="<?=content_url('plugins/popper/popper.js');?>"></script>
	<script src="<?=content_url('plugins/bootstrap/js/bootstrap.min.js');?>"></script>
	<script src="<?=content_url('plugins/sticky/jquery.sticky.js');?>"></script>
	<script src="<?=content_url('plugins/prism/prism.js');?>"></script>
	<script src="<?=content_url('plugins/photoswipe/photoswipe.min.js');?>"></script>
	<script src="<?=content_url('plugins/photoswipe/photoswipe-ui-default.min.js');?>"></script>
	<?php if (get_setting('recaptcha')=="Y"): ?>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<?php endif ?>
	<script src="<?=$this->CI->theme_asset('js/javascript.js');?>"></script>
</body>
</html>