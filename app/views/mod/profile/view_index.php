<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-inner">
	<div class="d-sm-flex align-items-center justify-content-between pd-b-20">
		<div class="pageheader pd-t-20 pd-b-0">
			<div class="d-flex justify-content-between">
				<div class="clearfix">
					<div class="breadcrumb pd-0 pd-b-10 mg-0">
						<a href="#" class="breadcrumb-item"><?=lang_line('ui_dashboard');?></a>
						<a href="#" class="breadcrumb-item"><?=lang_line('mod_title');?></a>
					</div>
				</div>
			</div>
		</div>
		<div class="mg-t-15">
			<button type="button" class="btn btn-md pd-x-15 btn-white btn-uppercase" onclick="window.location='<?=admin_url($this->mod.'/edit');?>'"><i data-feather="edit" class="mr-2"></i><?=lang_line('button_edit');?></button>
		</div>
	</div>

	<div class="card mg-b-20">
		<div class="content-inner">
			<div class="bg-white pd-20">
				<div class="media col-md-10 col-lg-8 col-xl-7 pd-30 mx-auto">
					<div class="row">
						<div class="col-sm-3 col-md-4 col-lg-5 text-center mg-b-20">
							<img src="<?=user_photo(data_login('photo'));?>" alt="" class=" rounded-circle " style="width:100%;">
						</div>
						<div class="col-sm-7">
							<div class="media-body">
								<h4 class="tx-semibold"><?=$this->data['user_name']; ?></h4>
								<p class="tx-gray-500"><?=$this->data['user_about']; ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card mg-b-30">
		<div class="card-header d-flex align-items-center justify-content-between">
			<h6 class="tx-16 tx-semibold mg-b-0"><?=lang_line('_profile_information');?></h6>
			<nav class="nav nav-with-icon tx-13">
				<a href="<?=admin_url($this->mod.'/edit');?>" class="nav-link pd-0"><span data-feather="edit" class="wd-16"></span> <?=lang_line('button_edit');?></a>
			</nav>
		</div>
		 
		<div class="card-body">

			<h6 class="mg-t-0 mg-b-15"><?=lang_line('_account');?></h6>

			<div class="row mb-2">
				<div class="col-md-2 col-sm-3 tx-gray-700 tx-semiboldX"><?=lang_line('_email');?> :</div>
				<div class="col-sm-9"><?=$this->data['user_email']; ?></div>
			</div>
			<div class="row mb-2">
				<div class="col-md-2 col-sm-3 tx-gray-700 tx-semiboldX"><?=lang_line('_username');?> :</div>
				<div class="col-sm-9"><?=$this->data['user_username']; ?></div>
			</div>
			
			<h6 class="mg-t-30 mg-b-15"><?=lang_line('_personal');?></h6>

			<div class="row mb-2">
				<div class="col-md-2 col-sm-3 tx-gray-700 tx-semiboldX"><?=lang_line('_name');?> :</div>
				<div class="col-sm-9"><?=$this->data['user_name'];?></div>
			</div>
			<div class="row mb-2">
				<div class="col-md-2 col-sm-3 tx-gray-700 tx-semiboldX"><?=lang_line('_gender');?> :</div>
				<div class="col-sm-9"><?=$gender;?></div>
			</div>
			<div class="row mb-2">
				<div class="col-md-2 col-sm-3 tx-gray-700 tx-semiboldX"><?=lang_line('_birthday');?> :</div>
				<div class="col-sm-9"><?=ci_date($this->data['user_birthday'], 'l, d F Y'); ?></div>
			</div>
			<div class="row mb-2">
				<div class="col-md-2 col-sm-3 tx-gray-700 tx-semiboldX"><?=lang_line('_about');?> :</div>
				<div class="col-sm-9"><?=$this->data['user_about']; ?></div>
			</div>
			<div class="row mb-2">
				<div class="col-md-2 col-sm-3 tx-gray-700 tx-semiboldX"><?=lang_line('_tlpn');?> :</div>
				<div class="col-sm-9"><?=$this->data['user_tlpn']; ?></div>
			</div>
			<div class="row mb-2">
				<div class="col-md-2 col-sm-3 tx-gray-700 tx-semiboldX"><?=lang_line('_address');?> :</div>
				<div class="col-sm-9"><?=$this->data['user_address']; ?></div>
			</div>
		</div>
	</div>
</div>
