<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div id="container">
	<div class="text-center">
		<h2>CiFireCMS <?=build_version();?></h2>
		<h4>Welcome to Installation Page</h4>
	</div>
	<div class="license">
		<div id="body">
			<textarea class="form-control" style="background-color: #fafafa;" readonly><?=license();?></textarea>
		</div>
	</div>
	<hr/>
	<div class="action">
		<form method="POST">
			<input type="hidden" name="act" value="start">
			<button type="submit" class="btn btn-lg btn-success">Start Installation</button>
		</form>
	</div>
</div>