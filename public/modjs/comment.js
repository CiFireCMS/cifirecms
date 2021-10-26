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

			$('.modal_detail').click(function() {
				var idDet = $(this).attr('idDet');
				$('#modal_detail').modal('show');
				$('#cdet').html('<div class="text-center pd-30"><i class="fa fa-spin fa-spinner text-muted"></i></div>');
				$.ajax({
					type: 'POST',
					url: admin_url + a_mod + '/view-detail/' + idDet,
					data:{
						'id': idDet,
						'csrf_name': csrfToken
					},
					success: function(data){
						if (data==false) {
							$('#cdet').html('<div class="text-center text-danger tx-16">Access denied !</div>');
						} else {
							$('#cdet').html('<div><b>' + data.name + '</b>&nbsp;<small class="text-muted">(' + data.email + ')</small></div><div style="font-size:14px;color:#888;"><small>' + data.date + '</small></div><div style="margin-bottom:18px;font-size:14px;color:#888;"><small>' + data.ip + '</small></div><div><p>' + data.comment + '</p></div>'+data.link);
							$('#cstatus_'+idDet).attr('class',data['class']).html(data['text']);
						};
					}
				});
			});

			$('.btn_ctoggle').click(function(){
				var dataUrl = admin_url + a_mod;
				var dataPk = $(this).attr('data-pk');
				var dataAct = $(this).attr('data-act');
				_cToggle(dataPk,dataUrl,dataAct);
			});
		}
	});
});

function _cToggle(_pk,_uri,_act){
	var dataPk = _pk;
	var dataUrl = _uri;
	var dataAct = _act;

	getLangJSON().done(function(lang){
		if (dataAct=='banned') {
			var _title = '<span class="mg-t-30">'+ lang.modal['comment_banned_title'] +'</span>';
			var _text  = lang.modal['comment_banned_content'];
			var _confirmButtonClass = 'btn btn-lg btn-danger';
			var _confirmButtonText  = lang.button['banned'];
		}
		else if (dataAct=='active') {
			var _title = '<span class="mg-t-30">'+ lang.modal['comment_activate_title'] +'</span>';
			var _text  = lang.modal['comment_activate_content'];
			var _confirmButtonClass = 'btn btn-lg btn-success';
			var _confirmButtonText  = lang.button['active'];
		}

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
							pk: dataPk,
							act: dataAct,
							'csrf_name': csrfToken
						},
						cache: false,
						success:function(response) {
							if (response['success']==true) {
								var c_pk = response['pk'];
								var c_act = response['act'];
								var c_icon = response['icon'];
								var c_statusClass = response['status-class'];
								var c_statusText = response['status-text'];
								var c_tooltip = response['tooltip-text'];

								$('#cstatus_'+c_pk).attr('class',c_statusClass).html(c_statusText);
								$('.ctoggle'+c_pk).attr('data-act',c_act);
								$('.ctoggle'+c_pk).attr('data-original-title',c_tooltip);
								$('.ctoggle'+c_pk).find('i').removeClass().addClass(c_icon);
								
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
							};
						}
					});
				});
			},
		});
	});
}
