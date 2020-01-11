$(function(e) {
	'use strict'

	var _tableInbox = $('#DataTable').DataTable({
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
		drawCallback : function(settings) {
			var apiTable = this.api();
			dataTableDrawCallback(apiTable);
		}
	});

	var _tableOutbox = $('#DataTableOut').DataTable({
		language: {
			url: datatable_lang
		},
		autoWidth: false,
		responsive: true,
		processing: true,
		serverSide: true,
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

	cfTnyMCE('#Content',200);
});