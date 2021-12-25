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

	$('.submit_add').on('click',function(e){
		e.preventDefault();
		var form = $('#form_add');
		form.find('.submit_add i').attr('class','fa fa-spin fa-spinner mr-2');
		tinyMCE.triggerSave();
		$.ajax({
			type: 'POST',
			data: form.serialize(),
			dataType: 'json',
			cache: false,
			success:function(data){
				if (data['success']==true) {
					$(location).attr('href',admin_url+a_mod);
				} else {
					cfNotif(data['alert']);
				}
				form.find('.submit_add i').attr('class','cificon licon-send mr-2');
			}
		});
	});

	$('.submit_update').on('click',function(e){
		e.preventDefault();
		var form = $('#form_update');
		form.find('.submit_update i').attr('class','fa fa-spin fa-spinner mr-2');
		tinyMCE.triggerSave();
		$.ajax({
			type: 'POST',
			data: form.serialize(),
			dataType: 'json',
			cache: false,
			success:function(data){
				cfNotif(data['alert']);
				form.find('.submit_update i').attr('class','fa fa-save mr-2');
			}
		});
	});

	cfTnyMCE('#Content', 200);

	$('#delpict').on('click',function(e){
		e.preventDefault();
		$('#picture').val(''),
		$('#imgprv').attr('src',site_url+'images/noimage.jpg')
	});
});