<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<h2>Add Menu Group</h2>
<p>
	<label for="menu-group-title">Title</label>
	<input type="text" name="title" id="menu-group-title" class="form-control grouptitlez" required>
</p>

<script>$('.grouptitlez').on('input',function(){var e;e=(e=(e=$(this).val()).replace(/\s+/g,' ')).replace(/_/g,' '),$(this).val(e.toLowerCase()),$(this).val($(this).val().replace(/\W/g,' ')),$(this).val($(this).val().replace(/\s+/g,' '))});</script>