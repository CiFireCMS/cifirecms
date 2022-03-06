
function getLangJSON(){
	var result = $.ajax({
		dataType: 'json',
		url: content_url+'plugins/json/lang/'+lang_active+'.json',
	});
	return result;
}


function dataTableDrawCallback(apiTable,urlTable) {
	var deleteLocation = this.location.href;
	$('tfoot').hide();

	if ($('.row_data:checkbox:checked').length == 0) {
		$('.select_all:checkbox').prop('checked',false);
	};

	$('.select_all').click(function() {
		var checkedIndex = this.checked;
		$(".row_data:checkbox").each(function() {
			this.checked = checkedIndex;
			if (checkedIndex == this.checked) {
				$(this).closest('table tbody tr').removeClass('table-selected');
			}
			if (this.checked) {
				$(this).closest('table tbody tr').addClass('table-selected');
			}
		});
		var countChecked = $('.row_data:checkbox:checked').length;
		if (countChecked > 0) {
			$('tfoot').show();
		} else {
			$('tfoot').hide();
		}
	});

	$('.row_data:checkbox').on('click', function () {
		var countChecked = $('.row_data:checkbox:checked').length;
		var checkedIndex = this.checked;
		this.checked = checkedIndex;
		if (checkedIndex == this.checked) {
			$(this).closest('table tbody tr').removeClass('table-selected');
			$('.select_all:checkbox').prop('checked',false);
		}
		if (this.checked) {
			$(this).closest('table tbody tr').addClass('table-selected');
		}
		if (countChecked > 0) {
			$('tfoot').show();
		} else {
			$('tfoot').hide();
		}
	});


	$('.delete_single').on('click', function(i) {
		var pk = [];
		pk = [$(this).attr('data-pk')];
		cfSwalDelete(pk, apiTable, deleteLocation);
	});
	
	$('.delete_multi').on('click', function() {
		var pk = [];
		$('.row_data:checked').each(function(i) {
			pk[i] = $(this).val();
		});
		if (pk != '' && pk != 'on') {
			cfSwalDelete(pk, apiTable, deleteLocation);
		}
	});


	$('.dataTables_length select').select2({
		minimumResultsForSearch: Infinity,
		dropdownAutoWidth: true,
		width: 'auto'
	});

	$('[data-toggle="tooltip"]').tooltip({
		trigger: 'hover',
		animation: false,
		delay: 1
	});
}


function cfSwalDelete(pk,api_table,uri){
	var act = 'delete';
	var dataPk = pk;
	var dataUrl = uri;
	var dataTable = api_table;
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
							data: dataPk,act,
							csrf_name: csrfToken
						},
						cache: false,
						success:function(response) {
							if (response['success']==true) {
								$('.select_all:checkbox').prop('checked',false);
								dataTable.row($('#DataTable tr.table-selected')).remove().draw(false);
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
								})
							};
						}
					});
				});
			},
		})
		// .then(function(result) {
		// 	if (result.value==true) {
		// 		Swal({
		// 			type      : 'success',
		// 			title     : '<span class="mg-b-30">'+lang.message['delete_success']+'</span>',
		// 			animation : false,
		// 			timer     : 1000,
		// 			showConfirmButton : false,
		// 			showCloseButton   : true,
		// 		})
		// 	}
		// });
	});
}


function cfNotif(data){
	Noty.overrideDefaults({
		theme: 'default',
		layout: 'topRight',
		type: 'alert',
		timeout: 4000
		
	});   
	new Noty({
		type: data['type'],
		text: data['content'],
		// modal: true
		// animation:'ease-in',
	}).show();
}


function cfAlert(data){
	$('#alert-notif').html('<div class="alert alert-' + data['type'] + ' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + data['content'] + '</div>').show();
	$('.alert').fadeTo(15353, 50).slideUp(300, function() {
		$('.alert').alert('close');
		$('#alert-notif').hide();
	});
}


function cfCompogen(){
	getLangJSON().done(function(lang){
		$('.steps-validation').steps({
			headerTag: 'h6',
			bodyTag: 'fieldset',
			titleTemplate: '<span class="number">#index#</span> #title#',
			labels: {
				previous: '<i class="icon-arrow-left13 mr-2" /> Previous',
				next: 'Next <i class="icon-arrow-right14 ml-2" />',
				finish: 'Generate now <i class="icon-arrow-right14 ml-2" />'
			},
			transitionEffect: 'none',
			autoFocus: true,
			onStepChanging: function (event, currentIndex, newIndex) {
				var formCoGen = $(this).show();
				if (currentIndex > newIndex) {
					return true;
				}
				if (currentIndex < newIndex) {
					formCoGen.find('.body:eq(' + newIndex + ') label.error').remove();
					formCoGen.find('.body:eq(' + newIndex + ') .error').removeClass('error');
				}
				formCoGen.validate().settings.ignore = ':disabled,:hidden';
				return formCoGen.valid();
			},
			onFinishing: function (event, currentIndex) {
				form.validate().settings.ignore = ':disabled';
				return form.valid();
			},
			onFinished: function (event, currentIndex) {
				event.preventDefault();
				var formCoGen = $(this);
				var form_data = formCoGen.serialize();
				$.ajax({
					url: admin_url + a_mod +'/submit',
					type: 'POST',
					data: form_data,
					dataType: 'json',
					cache: false,
					success:function(response) {
						if (response['success']==true) {
							window.location.href=admin_url+a_mod+'/finish/'+response['class'];
						} else{
							alert('ERROR')
						};
					}
				});
			}
		});

		$('.steps-validation').validate({
			ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
			errorClass: 'validation-invalid-label',
			highlight: function(element, errorClass) {
				$(element).removeClass(errorClass);
			},
			unhighlight: function(element, errorClass) {
				$(element).removeClass(errorClass);
			},
			errorPlacement: function(error, element) {
				// Unstyled checkboxes, radios
				if (element.parents().hasClass('form-check')) {
					error.appendTo( element.parents('.form-check').parent() );
				}
				// Input with icons and Select2
				else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
					error.appendTo( element.parent() );
				}
				// Input group, styled file input
				else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
					error.appendTo( element.parent().parent() );
				}
				// Other elements
				else {
					error.insertAfter(element);
				}
			},
			rules: {
				email: {
					email: true
				}
			}
		});
	});
}


function cfTnyMCE(element,height){
	var _height = height;
	var _element = element;
	
	$('#tiny-text').click(function (e) {
		e.stopPropagation();
		tinymce.EditorManager.execCommand('mceRemoveEditor', true, 'Content');
	});
	$('#tiny-visual').click(function (e) {
		e.stopPropagation();
		tinymce.EditorManager.execCommand('mceAddEditor', true, 'Content');
	});
	tinymce.init({
		contextmenu: false,
		selector: _element,
		editor_deselector: 'mceNoEditor',
		// skin: 'lightgray',
		plugins: [
			// contextmenu   
			"advlist autolink link image lists charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
			"table directionality emoticons paste textcolor",
			"code fullscreen youtube autoresize codemirror codesample responsivefilemanager pagebreak"
		],
		toolbar1:'undo redo | bold italic underline forecolor backcolor | alignjustify alignleft aligncenter alignright | outdent indent bullist numlist table | pagebreak',
		toolbar2:'removeformat styleselect | fontsizeselect | responsivefilemanager image media youtube | hr charmap link unlink  codesample code | visualblocks preview fullscreen',
		branding: false,
		menubar: false,
		relative_urls: false,
		remove_script_host: false,
		image_caption: true,
		image_advtab: true,
		resize: true,
		fontsize_formats: '8px 10px 12px 14px 18px 24px 36px',
		autoresize_min_height: _height,
		autoresize_top_margin:5,
		autoresize_bottom_margin:2,
		visualblocks_default_state: true,
		content_css: content_url+'plugins/tinymce/plugins/bootstrap/css/bootstrap.min.css,' + content_url + 'plugins/font-awesome/font-awesome.min.css',
		codemirror: {
		    indentOnInit: true,
		    path: content_url+'/plugins/codemirror'
		},
		filemanager_title: 'File Manager',
		filemanager_access_key: _FMKEY,
		external_filemanager_path: content_url+'plugins/filemanager/',
		external_plugins: {
			'responsivefilemanager': content_url + 'plugins/tinymce/plugins/responsivefilemanager/plugin.min.js',
			'filemanager': content_url+'plugins/filemanager/plugin.min.js'
		}
	});
}


function str_seotitle(str){
	var seotitle;
	str = str.replace(/^\s+|\s+$/g, ''); // trim
	str = str.toLowerCase();
	// remove accents, swap ñ for n, etc
	var from = "ÁÄÂÀÃÅČÇĆĎÉĚËÈÊẼĔȆÍÌÎÏŇÑÓÖÒÔÕØŘŔŠŤÚŮÜÙÛÝŸŽáäâàãåčçćďéěëèêẽĕȇíìîïňñóöòôõøðřŕšťúůüùûýÿžþÞĐđßÆa·/_,:;";
	var to = "AAAAAACCCDEEEEEEEEIIIINNOOOOOORRSTUUUUUYYZaaaaaacccdeeeeeeeeiiiinnooooooorrstuuuuuyyzbBDdBAa------";
	for (var i = 0, l = from.length; i < l; i++) {
		str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
	}
	str = str.replace(/[^a-z0-9 -]/g, " ") // remove invalid chars
		.replace(/\s+/g, '-') // collapse whitespace and replace by -
		.replace(/-+/g, '-') // collapse dashes
		.replace(/\W/g, ' '); // collapse dashes
	seotitle = $.trim(str).replace(/\W/g, ' ').replace(/\s+/g, '-');
	return seotitle;
}


function formatFileBytes(bytes,decimals) {
   if(bytes == 0) return '0 Bytes';
   var k = 1024,
	   dm = decimals <= 0 ? 0 : decimals || 2,
	   sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
	   i = Math.floor(Math.log(bytes) / Math.log(k));
   return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}


function mimeFileType(mimesCode) {
	var type = '';
	switch (mimesCode) {
		case 'ffd8ffe0':
		case 'ffd8ffe1':
		case 'ffd8ffe2':
			type = 'image/jpeg';
		break;

		case '89504e47':
			type = 'image/png';
		break;

		case '47494638':
			type = 'image/gif';
		break;

		case '25504446':
			type = 'application/pdf';
		break;

		case '504b34':
			type = 'application/x-zip-compressed';
		break;
	}
	return type;
}


function responsive_filemanager_callback(){
	var pict = $('#picture').val();
	var url = content_url + 'uploads/' + pict;
	$('#imgprv').attr('src', url).show();
	parent.$.fancybox.close();
}


$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip({
		trigger: 'hover',
		animation: false,
		delay: 1
	});

	$('.fancybox').fancybox();

	$('.browse-files').fancybox({ 
		width: 1000, 
		height: 1000, 
		type: 'iframe', 
		autoScale: true,
	});

	$('#filemanager').fancybox({ 
		width: 1000, 
		height: 1000, 
		type: 'iframe', 
		autoScale: true,
	});

	$('.select-bs').selectpicker();

	$('.select2').select2();

	$('.select-2-nosearch').select2({
		minimumResultsForSearch: Infinity
	});
});