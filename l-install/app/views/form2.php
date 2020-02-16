<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div id="container">
	<h2 class="text-center">CONFIGURATIONS</h2>
	<?php echo form_open('','autocomplete="on"'); ?>
		<input type="hidden" name="act" value="step2"/>
		<input type="hidden" name="db_host" value="<?=DB_HOST;?>"/>
		<input type="hidden" name="db_name" value="<?=DB_NAME;?>"/>
		<input type="hidden" name="db_user" value="<?=DB_USER;?>"/>
		<input type="hidden" name="db_pass" value="<?=DB_PASS;?>"/>
		<input type="hidden" name="db_port" value="<?=DB_PORT;?>"/>
		<hr>

		<div id="body">
			
			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Website Url</label>
				<div class="col-sm-9">
					<input type="text" name="site_url" class="form-control" value="<?=site_url();?>" required/>
					<small class="text-muted">Your website url.</small><br/>
					<small class="text-muted">Example : http://www.mydomain.com/</small>
				</div>
			</div>
			<br/>

			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Website Name</label>
				<div class="col-sm-9">
					<input type="text" name="site_name" class="form-control" required/>
					<small class="text-muted">Your website name.</small><br/>
					<small class="text-muted">Example : Great Community</small>
				</div>
			</div>
			<br/>

			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Website Slogan</label>
				<div class="col-sm-9">
					<input type="text" name="site_slogan" class="form-control" required/>
					<small class="text-muted">Your website slogan.</small><br/>
					<small class="text-muted">Example : CMS Gratis Rasa Premium</small>
				</div>
			</div>
			<br/>

			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Website Description</label>
				<div class="col-sm-9">
					<input type="text" name="site_desc" class="form-control" required/>
					<small class="text-muted">Your site description.</small><br/>
					<small class="text-muted">Example : Indonesian free CMS and great community support.</small>
				</div>
			</div>
			<br/>

			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Website Email</label>
				<div class="col-sm-9">
					<input type="email" name="site_email" class="form-control" required/>
					<small class="text-muted">Your site email.</small><br/>
					<small class="text-muted">Example : site@email.here</small>
				</div>
			</div>

			<br/><hr/><br/>

			<div class="form-group row">
				<label class="col-sm-3 col-form-label">User name</label>
				<div class="col-sm-9">
					<input type="text" name="adm_user" class="form-control" maxlength="15" minlength="5" required/>
					<small class="text-muted">Username for login backend, please just write letters and numbers (lowercase).</small><br/>
					<small class="text-muted">Example : admin123</small>
				</div>
			</div>
			<br/>

			<div class="form-group row">
				<label class="col-sm-3 col-form-label">User password</label>
				<div class="col-sm-9">
					<input type="text" name="adm_pass" class="form-control" maxlength="20" minlength="6" required/>
					<small class="text-muted">Password for login backend, please enter character more than 6 characters.</small><br/>
					<small class="text-muted">Example : admin123</small>
				</div>
			</div>
			<br/>

			<div class="form-group row">
				<label class="col-sm-3 col-form-label">User email</label>
				<div class="col-sm-9">
					<input type="email" name="adm_email" class="form-control" required/>
					<small class="text-muted">Your user email.</small><br/>
					<small class="text-muted">Example : your@email.here</small>
				</div>
			</div>
			
			<br/><hr/><br/>

			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Default Timezone</label>
				<div class="col-sm-9">
					<select class="form-control col-sm-5" name="timezone" required style="max-width:300px;">
						<option value="Asia/Jakarta">Asia/Jakarta</option>
						<?php
							$arr_timez_id_lst3 = DateTimeZone::listIdentifiers();
							foreach ($arr_timez_id_lst3 as $timez) {
						?>
						<option value="<?=$timez;?>"><?=$timez;?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<br/>

		</div>
		<hr>
		<div class="action text-center">
			<button type="submit" class="btn btn-lg btn-success">Install Now</button>
		</div>
	<?php echo form_close(); ?>
</div>