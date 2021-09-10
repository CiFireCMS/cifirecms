$(function() {
	'use strict'

	var _dTable = $('#DataTable').DataTable({
		language: {
			url: datatable_lang,
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
			{targets: [1], width: '20px'}
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
			dataTableDrawCallback(apiTable);
		}
	});

	$('#form_add').on('submit',function(e){
		e.preventDefault();
		var form = $(this);
		form.find('.submit_add i').attr('class','fa fa-spin fa-spinner mr-2');
		tinyMCE.triggerSave();
		$.ajax({
			type: 'POST',
			data: form.serialize(),
			dataType: 'json',
			cache: false,
			success:function(response){
				if (response['success']==true) {
					$(location).attr('href',admin_url+a_mod);
				} else {
					cfNotif(response['alert']);
				}
				form.find('.submit_add i').attr('class','cificon licon-send mr-2');
			}
		});
	});

	$('#form_update').on('submit',function(e){
		e.preventDefault();
		var form = $(this);
		form.find('.submit_update i').attr('class','fa fa-spin fa-spinner mr-2');
		tinyMCE.triggerSave();
		$.ajax({
			type: 'POST',
			data: form.serialize(),
			dataType: 'json',
			cache: false,
			success:function(response){
				cfNotif(response['alert']);
				form.find('.submit_update i').attr('class','fa fa-save mr-2');
			}
		});
	});

	cfTnyMCE('#Content', 200); // load TnyMCE

	$('#delpict').on('click',function(e){
		e.preventDefault();
		$('#picture').val('');
		$('#imgprv').attr('src', content_url + '/images/noimage.jpg');
	});

	$('input:not(textarea)').keydown(function(e){if(13==(e.witch||e.keyCode))return e.preventDefault(),!1});
	$('#title').on('input',function(){var e;e=(e=(e=$(this).val()).replace(/\s+/g,' ')).replace(/_/g,' '),$('#seotitle').val(e.toLowerCase()),$('#seotitle').val($('#seotitle').val().replace(/\W/g,' ')),$('#seotitle').val($('#seotitle').val().replace(/\s+/g,'-'))});
	$('#seotitle').on('input',function(){var e;e=(e=(e=$(this).val()).replace(/\s+/g,' ')).replace(/_/g,' '),$(this).val(e.toLowerCase()),$(this).val($(this).val().replace(/\W/g,' ')),$(this).val($(this).val().replace(/\s+/g,'-'))});
});