$(function() {
	'use strict'

	$('.c_blank_theme').on('click',function(e) {
		e.preventDefault();
		$('#modal_create_blank').modal('show');
	});

	$('.modal_active').click(function(e) {
		e.preventDefault();
		var dataPk = $(this).attr('idActive');
		var dataUrl = admin_url + a_mod;
		var dataAct = 'active-theme';
		getLangJSON().done(function(lang){
			var _title = '<span class="mg-t-30">'+ lang.modal['theme_activate_title'] +'</span>';
			var _text  = lang.modal['theme_activate_content'];
			var _confirmButtonClass = 'btn btn-lg btn-success';
			var _confirmButtonText  = lang.button['active'];

			swal.fire({
				title               : _title,
				text                : _text,
				showConfirmButton   : true,
				confirmButtonClass  : _confirmButtonClass,
				confirmButtonText   : _confirmButtonText,
				showCancelButton    : true,
				cancelButtonClass   : 'btn btn-lg btn-secondary',
				cancelButtonText    : lang.button['cancel'],
				animation           : false,
				buttonsStyling      : false,
				showCloseButton     : false,
				showLoaderOnConfirm : true,
				allowOutsideClick   : false,
				preConfirm: function() {
					return new Promise(function(resolve, reject) {
						$.ajax({
							type: 'POST',
							url: dataUrl,
							dataType: 'json',
							data: {
								'pk': dataPk,
								'act': dataAct,
								'csrf_name': csrfToken
							},
							cache: false,
							success:function(response) {
								if (response['success']==true) {
									// resolve();
									window.location.href=dataUrl;
								}
								else {
									Swal({
										type     : 'error',
										title    : '<span class="mg-b-0">ERROR</span>',
										animation         : false,
										allowOutsideClick : false,
										showConfirmButton : false,
										showCloseButton   : true
									});
								};
							}
						});
					});
				},
			});
		});
	});

	$('.delete_theme').on('click',function(e) {
		e.preventDefault();
		var idTeme = $(this).attr("data-id");
		var folderTheme = $(this).attr("data-folder");
		var dataPk = {
			'id': idTeme,
			'folder': folderTheme
		};
		var dataUrl = admin_url + a_mod + '/delete-theme';

		getLangJSON().done(function(lang){
			swal({
				title               : '<span class="mg-t-30">'+lang.modal['delete_title']+'</span>',
				text                : lang.modal['delete_content'],
				showConfirmButton   : true,
				confirmButtonClass  : 'btn btn-lg btn-danger',
				confirmButtonText   : lang.button['delete'],
				showCancelButton    : true,
				cancelButtonClass   : 'btn btn-lg btn-secondary',
				cancelButtonText    : lang.button['cancel'],
				animation           : false,
				buttonsStyling      : false,
				showCloseButton     : false,
				showLoaderOnConfirm : true,
				allowOutsideClick   : false,
				preConfirm: function() {
					return new Promise(function(resolve, reject) {
						$.ajax({
							type: 'POST',
							url: dataUrl,
							dataType: 'json',
							data: {
								'data': dataPk,
								'csrf_name': csrfToken
							},
							cache: false,
							success:function(response) {
								if (response['success']==true) {
									$('#theme-item-'+response['dataDelete']).remove()
									resolve();                              
								}
								else {
									Swal({
										type     : 'error',
										title    : '<span class="mg-b-0">ERROR</span>',
										animation         : false,
										allowOutsideClick : false,
										showConfirmButton : false,
										showCloseButton   : true
									});
								}
							}
						});
					});
				},
			});
		});
	});

	$(':file#fupload').change(function() {
		var file = $(this)[0].files[0];
		var fileReader = new FileReader();
		getLangJSON().done(function(lang){
			fileReader.onloadend = function (e) {
				var arr = (new Uint8Array(e.target.result)).subarray(0, 4);
				var fileHeader = '';
				for (var i = 0; i < arr.length; i++) {
					fileHeader += arr[i].toString(16);
				}
				var type = mimeFileType(fileHeader,'');
				// console.log('File header: ' + file.type);

				// 504b34 = application/x-zip-compressed
				if (fileHeader !== '504b34' || file.type == '' || file.type !== 'application/x-zip-compressed') {
					$(this).val('');
					$('#fupload').val('');
					$('.custom-file-label').html(file.name);
					$('.detail-package').show().html('<div class="text-danger tx-13">'+ lang.message['error_filetype'] +'</div>');
					$('#install-button').prop('disabled', true);
				} else {
					$('.custom-file-label').html(file.name);
					$('.detail-package').show().html('<div class="tx-gray-700 tx-13"><div> File : '+ file.name +'</div><div> Type : '+ file.type +'</div> <div> Size : '+ formatFileBytes(file.size) +'</div> </div>');
					$('#install-button').prop('disabled', false);
				}
			};
			fileReader.readAsArrayBuffer(file);
		});
	});

	$('.create_file').click(function(e) {
		e.preventDefault();
		$('#modal_create_file').modal('show');
	});
});