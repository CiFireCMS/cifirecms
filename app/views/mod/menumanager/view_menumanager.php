<?php 
	defined('BASEPATH') OR exit('No direct script access allowed'); 
	$get_group_id = (!empty($this->input->get('group_id')) ? $this->input->get('group_id') : 1);
?>
<script type="text/javascript">
	var current_group_id = <?=$get_group_id;?>;
</script>

<div class="page-inner mg-b-60">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_apperance');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
					</div>
					<h4 class="pd-0 mg-0 tx-20 tx-dark tx-spacing--1"><?=lang_line('mod_title');?></h4>
				</div>
			</div>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-9 ul-menu mg-b-50">
						<div>
							<ul id="menu-group">
								<li id="add-group"><a href="#" title="Add menu group" data-toggle="tooltip" data-placement="top" data-title="Edit">+</a></li>
								<?php
									$menugroup = $this->db->get('t_menu_group')->result_array();
									foreach ($menugroup as $mgr) {
								?>
								<li id="group-<?=$mgr['id'];?>">
									<a href="<?=admin_url('menumanager/?group_id='.$mgr['id']);?>"><?=$mgr['title'];?></a>
								</li>
								<?php } ?>
							</ul>
						</div>
						<div class="clearfix"></div>
						<?=form_open(admin_url($this->mod.'/savemenuposition'), 'id="form-menu" autocomplete="off"');?>
							<div class="ns-row" style="background:#fff;border-radius:0px;font-weight:600;">
								<div class="ns-actions">Action</div>
								<div class="ns-active">Active</div>
								<div class="ns-class">Class</div>
								<div class="ns-url">URL</div>
								<div class="ns-title">Title</div>
							</div>          
							<?=$menu_ul;?>
							<div id="ns-footer">
								<!-- <hr> -->
								<button type="submit" id="btn-save-menu" class="btn btn-success pull-left"><i class="fa fa-save mr-2"></i> Save Position</button>
								<button type="button" class="btn btn-secondary" onClick="window.location.reload()"><i class="fa fa-refresh mr-2"></i> Refresh</button>
							</div>
						<?=form_close(); ?>
					</div>

					<div class="col-md-3">
						<div>
							<!-- Nav tabs -->
							<ul class="nav nav-tabs nav-justified">
							<li class="nav-item">
								<a href="#AddMenu" class="nav-link active" data-toggle="tab">Add Menu</a>
							</li>
							<li class="nav-item">
								<a href="#MenuGroup" class="nav-link" data-toggle="tab">Menu Group</a>
							</li>
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="AddMenu">
									<br>
									<div class="box">
										<section>
											<?php echo form_open(admin_url('menumanager/actadd'), 'class="form-bordered" id="form-add-menu" autocomplete="off"'); ?>
												<input type="hidden" name="gid" value="<?=$get_group_id;?>">
												<div class="form-group mb-3">
													<label for="menu-title">Ttitle</label>
													<input class="form-control" type="text" name="title" id="menu-title" required>
												</div>
												<div class="form-group mb-3">
													<label for="menu-url">Url</label>
													<input class="form-control" type="text" name="url" id="menu-url">
												</div>
												<div class="form-group mb-3">
													<label for="menu-class">Class</label>
													<input class="form-control" type="text" name="class" id="menu-class">
												</div>
												<div class="form-group mb-3">
													<label for="menu-class">Active</label>
													<select name="active" id="edit-menu-active" class="form-control" >
														<option value="Y">Y</option>
														<option value="N">N</option>
													</select>

												</div>
												<div class="form-group mb-0">
													<input type="hidden" name="group_id" value="<?=$group_id;?>">
													<button id="add-menu" type="submit" class="btn btn-success">Submit</button>
												</div>
											<!-- </form> -->
											<?php echo form_close(); ?>
										</section>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane" id="MenuGroup">
									<br>
									<div class="box">
										<h2>Menu Group</h2>
										<section>
											<?php 
												$g_title = '';
												if ( ! empty($this->input->get('group_id')) ) {
													$g_title = $this->db
														->where('id', $this->input->get('group_id'))
														->get('t_menu_group')
														->row_array();
												} else {
													$g_title = $this->db
														->order_by('id','ASC')
														->get('t_menu_group')
														->row_array();
												}
											?>
											<span id="edit-group-input"><b><?=$g_title['title'];?></b> </span>
											<span class="label label-warning"><small>ID: <?=$get_group_id;?></small></span>
											<div style="margin-top:5px;">
												<a href="javascript:void(0)" id="edit-group" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a>
												<button id="submit-edit-group" type="submit" class="btn btn-sm btn-success" style="display: none;">Submit</button>
												<?php if ($get_group_id > 1): ?>
												<a href="javascript:void(0)" id="delete-group" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</a>
												<?php endif ?>
											</div>
										</section>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			<div id="loading">
				<div id="loading-in">
					<img src="<?=content_url('images/menu/ajax-loader.gif')?>"/>
				</div>
			</div>
		</div>
	</div>
</div>
<p>&nbsp;</p>