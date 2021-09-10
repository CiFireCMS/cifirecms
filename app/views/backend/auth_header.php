<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?=$this->meta_description;?>">
	<meta name="keyword" content="<?=$this->meta_keywords;?>">
	<meta name="author"  content="CifireCMS"/>
	<title><?=$this->meta_title;?></title>

	<link rel="icon" href="<?=favicon();?>" type="image/x-icon"/>
	
	<link rel="stylesheet" href="<?=content_url('plugins/icomoon/styles.css');?>" type="text/css"/>
	<link rel="stylesheet" href="<?=content_url('plugins/font-awesome/font-awesome.min.css');?>" type="text/css"/>
	<link rel="stylesheet" href="<?=content_url('plugins/cifireicon-feather/cifireicon-feather.min.css');?>" type="text/css"/>
	<link rel="stylesheet" href="<?=content_url('plugins/bootstrap/css/bootstrap.min.css');?>" type="text/css"/>
	<link rel="stylesheet" href="<?=content_url('themes/backend/css/style.css');?>" type="text/css"/>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn"t work if you view the page via file:// -->
	  <!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	  <![endif]-->
	<script type="text/javascript">
		var admin_url = '<?=admin_url()?>';
		var a_mod = '';
		var a_act = '';
		var _LOGIN_URL = '<?=admin_url("login/")?>';
		var csrfName = '<?=$this->security->get_csrf_token_name();?>';
	    var csrfToken = '<?=$this->security->get_csrf_hash();?>';
	</script>

	<script src="<?=content_url('plugins/jquery/jquery.min.js');?>"></script>
</head>
<body id="auth">
