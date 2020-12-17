$(function() {
	'use strict'

	var _dTable = $('#DataTable').DataTable({
		language: {
			url: datatable_lang
		},
		autoWidth: false,
		responsive: false,
		processing: true,
		serverSide: true,
		stateSave: true,
		order: [],
		columnDefs: [
			{targets: 'no-sort', orderable: false, searchable: false},
			{targets: 'th-action', orderable: false, searchable: false, width: '50px'},
			{targets: [0], width: '20px'},
			{targets: [1], width: '20px'},
			{targets: [2], width: '20px'}
		],
		lengthMenu: [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, 'All']
		],
		ajax: {
			type: 'POST',
			data: csrfData
		},
		drawCallback: function(settings) {
			var apiTable = this.api();
			$('.fancybox').fancybox();
			dataTableDrawCallback(apiTable);
		}
	});

	$('#form_add_user').on('submit', function(e){
		e.preventDefault();
		var form = $(this);
		var formData = new FormData(this);
		form.find('.submit_add i').attr('class','fa fa-spin fa-spinner mr-2');
		$.ajax({
			type: 'POST',
			data: formData,
			dataType: 'json',
			contentType: false,  
			processData:false,
			cache: false,
			success:function(response){
				if (response['success']==true) {
					$(location).attr('href', admin_url + a_mod);
				} else {
					cfNotif(response['alert']);
					form.find('.submit_add i').attr('class','cificon licon-send mr-2');
				}
			}
		})
		return false;
	});

	$('#form_update_user').on('submit',function(e){
		e.preventDefault();
		var form = $(this);
		var formData = new FormData(this);
		form.find('.submit_update i').attr('class','fa fa-spin fa-spinner mr-2');
		$.ajax({
			type: 'POST',
			data: formData,
			dataType: 'json',
			contentType: false,  
			processData: false,
			cache: false,
			success:function(response){
				cfNotif(response['alert']);
				form.find('.submit_update i').attr('class','fa fa-save mr-2');
			}
		})
		return false;
	});

	$('#input-datepicker').datetimepicker({
		format: 'YYYY-MM-DD',
		showTodayButton: true,
		showClear: true,
		icons: {
			previous: 'icon-arrow-left8',
			next: 'icon-arrow-right8',
			today: 'icon-calendar3',
			clear: 'icon-bin',
		},
	});

	$(':file#upload-image').on('change',function() {
		var file = $(this)[0].files[0];
		var fileReader = new FileReader();
		getLangJSON().done(function(lang){
			fileReader.onloadend = function (e) {
				var arr = (new Uint8Array(e.target.result)).subarray(0, 4);
				var fileHeader = '';
				for (var i = 0; i < arr.length; i++) {
					fileHeader += arr[i].toString(16);
				}
				var type = mimeFileType(fileHeader);

				if (type == 'image/jpeg' || type == 'image/png') {
					$('.custom-file-label').html(file.name);
				}
				else {
					$('#upload-image').val('');
					$('.custom-file-label').html('error');
				}
			};
			fileReader.readAsArrayBuffer(file);
		});
	});

	$('input:not(textarea)').keydown(function(e){if(13==(e.witch||e.keyCode))return e.preventDefault(),!1});
});