<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div id="container">
	<h2 class="text-center">DATABASE</h2>
	<form method="post">
		<hr>
		<div id="body">
			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Database host name</label>
				<div class="col-sm-9">
					<input type="text" name="db_host" value="localhost" class="form-control" required/>
					<small class="text-muted">Your database host name.</small><br/>
					<small class="text-muted">Example : localhost </small>
				</div>
			</div>
			<br/>

			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Database host port number</label>
				<div class="col-sm-9">
					<input type="text" name="db_port" value="3306" class="form-control"   required/>
					<small class="text-muted">Your database port</small><br/>
					<small class="text-muted">Example : 3306</small>
				</div>
			</div>
			<br/>

			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Database name</label>
				<div class="col-sm-9">
					<input type="text" name="db_name" class="form-control" required/>
					<small class="text-muted">Your database name</small><br/>
					<small class="text-muted">Example : db_cifirecms</small>
				</div>
			</div>
			<br/>

			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Database user</label>
				<div class="col-sm-9">
					<input type="text" name="db_user" class="form-control" required/>
					<small class="text-muted">Your database user</small><br/>
					<small class="text-muted">Example : YourName</small>
				</div>
			</div>
			<br/>

			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Database password</label>
				<div class="col-sm-9">
					<input type="text" name="db_pass" class="form-control"/>
						<small class="text-muted">Your database password</small><br/>
						<small class="text-muted">Example : MyPassword</small>
				</div>
			</div>
			<br/>
		</div>
		<hr>
		<div class="action text-center">
			<input type="hidden" name="act" value="step1">
			<button type="submit" class="btn btn-lg btn-success">Connect To Database</button>
		</div>
	</form>
	<br>
</div>