$(function(){
	'use strict'

	var tableListGroups = $('#DataTableGroups').DataTable({
		language : {
			url : datatable_lang,
		},
		autoWidth: false,
		responsive: false,
		processing: true,
		serverSide: true,
		stateSave: true,
		order      : [[1,'ASC']],
		columnDefs : [
			{targets : 'no-sort',   orderable : false, searchable : false},
			{targets : 'th-action', orderable : false, searchable : false, width : '70px'},
			{targets : [0], width : '20px'},
			{targets : [1], width : '20px'}
		],
		lengthMenu : [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, 'All']
		],
		ajax: {
			url: window.location.href,
			type: 'POST',
			data: {
				'act': 'dataTableGroups',
				'csrf_name': csrfToken
			}
		},
		drawCallback: function(settings) {
			var apiTable = this.api();
			var urlTable = window.location.href;
			dataTableDrawCallback(apiTable,urlTable);
		}
	});

	var tableListRoles = $('#DataTableRoles').DataTable({
		language : {
			url : datatable_lang,
		},
		autoWidth: false,
		responsive: false,
		processing: true,
		serverSide: true,
		stateSave: true,
		order      : [[1,'DESC']],
		columnDefs : [
			{targets : 'no-sort',   orderable : false, searchable : false},
			{targets : 'th-action', orderable : false, searchable : false, width : '70px'},
			{targets : [0], width : '20px'},
			{targets : [1], width : '20px'}
		],
		lengthMenu : [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, 'All']
		],
		ajax: {
			url: window.location.href,
			type: 'POST',
			data: {
				'act': 'dataTableRoles',
				'csrf_name': csrfToken
			}
		},
		drawCallback: function(settings) {
			var apiTable = this.api();
			var urlTable = window.location.href;
			dataTableDrawCallback(apiTable,urlTable);
		}
	});

	var tableGroupRole = $('#DataTableGroupRole').DataTable({
		language : {
			url : datatable_lang,
		},
		autoWidth: false,
		responsive: false,
		processing: true,
		serverSide: true,
		stateSave: true,
		order      : [[1,'DESC']],
		columnDefs : [
			{targets : 'no-sort',   orderable : false, searchable : false},
			{targets : 'th-action', orderable : false, searchable : false, width : '20px'},
			{targets : [0], width : '20px'},
			{targets : [1], width : '20px'}
		],
		lengthMenu : [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, 'All']
		],
		ajax: {
			url: window.location.href,
			type: 'POST',
			data: {
				'act': 'tableGroupRole',
				'csrf_name': csrfToken
			}
		},
		drawCallback: function(settings) {
			var apiTable = this.api();
			var urlTable = window.location.href;
			dataTableDrawCallback(apiTable,urlTable);
		}
	});


	$('#form_group').on('submit', function(e){
		e.preventDefault();
		var form = $(this);
		var buttonSubmit = $('.button_submit');
		buttonSubmit.find('i').attr('class','fa fa-spin fa-spinner mr-2');
		$.ajax({
			type: 'POST',
			data: form.serialize(),
			dataType: 'json',
			// cache: false,
			success:function(response){
				buttonSubmit.find('i').attr('class','cificon licon-send mr-2');
				if (response['success']==true) {
					if (response['url']) {
						$(location).attr('href',response['url']);
					} else {
						cfNotif(response['alert']);
					}
				} else {
					if (response['alert']) {
						cfNotif(response['alert']);
					} else {
						cfNotif({type:'error',content:'ERROR'});
					}
				}
			}
		});
	});

	$('#group').on('input',function(){var e;e=(e=(e=$(this).val()).replace(/\s+/g,' ')).replace(/_/g,' '),$(this).val(e.toLowerCase()),$(this).val($(this).val().replace(/\W/g,' ')),$(this).val($(this).val().replace(/\s+/g,'-'))});
});