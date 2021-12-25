<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="auth-container mg-t-50">
	<div class="text-center mg-b-30">
		<img class="img-fluid wd-50 rounded-circle mg-b-20" src="<?=site_url('images/logo.png');?>" alt="logo">
		<h4 class="mg-b-10 tx-semiboldX"><?=lang_line('signin_title');?></h4>
		<p><?=lang_line('signin_welcome');?></p>
	</div>
	<div class="err"><?= $this->cifire_alert->show('login'); ?></div>
	<div class="card auth-box">
		<?=form_open('', 'id="form-login" class="login-form" autocomplete="off"');?>
		<div class="form-group">
			<label><?=lang_line('username')?></label>
			<input id="username" type="text" name="<?=$input_uname;?>" class="form-control input-username" maxlength="20"/>
		</div>
		<div class="form-group">
			<label><?=lang_line('password');?></label>
			<input type="password" name="<?=$input_pwd;?>" id="password" class="form-control" required/>
		</div>
		<button type="submit" class="btn btn-brand btn-block mg-t-20"><?=lang_line('button_login');?></button>
		<div class="text-center mg-t-20">
			<a href="<?=admin_url('forgot');?>"><?=lang_line('forgot_password');?>?</a>
		</div>
		<?=form_close();?>
	</div>
</div>