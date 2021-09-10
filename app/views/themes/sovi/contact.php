<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12 clearfix mb-5 left-content">
	
	<?=$this->cifire_alert->show('contact');?>
	
	<div class="box-pages">
		<div class="post-head">
			<h4><i class="cificon licon-mail mr-1"></i> Contact</h4>
		</div>
		<div class="post-inner clearfix">
			<div class="row">
				<div class="col-md-4 mb-2">
					<div class="card">
						<div class="card-body">
							<div>
								<small><b>Email :</b></small>
								<p><?=get_setting('web_email')?></p>
							</div>
							<div>
								<small><b>Telephone :</b></small>
								<p><?=get_setting('telephone')?></p>
							</div>
							<div>
								<small><b>Fax :</b></small>
								<p><?=get_setting('fax')?></p>
							</div>
							<div>
								<small><b>Address :</b></small>
								<p><?=get_setting('address')?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<?=form_open('','class="form-contact"');?>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label>Name <span class="text-danger">*</span></label>
								<input type="text" name="name" class="form-control"/>
							</div>
							<div class="form-group">
								<label>Email <span class="text-danger">*</span></label>
								<input type="email" name="email" class="form-control"/>
							</div>
							<div class="form-group">
								<label>Subject <span class="text-danger">*</span></label>
								<input type="text" name="subject" class="form-control"/>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Message <span class="text-danger">*</span></label>
						<textarea name="message" class="form-control" rows="5"></textarea>
					</div>
					<div class="pull-left">
					<?php if (get_setting('recaptcha')=="Y"): ?>
						<div class="g-recaptcha pull-left" data-sitekey="<?=get_setting('recaptcha_site_key');?>" style="margin-bottom:5px;"></div>
					<?php endif ?>
					</div>
					<div class="pull-right">
						<button type="submit" class="btn btn-primary btn-send"><i class="cificon licon-send mr-1"></i> Send</button>
					</div>
					<?=form_close();?>
				</div>
			</div>
		</div>
	</div>
</div>