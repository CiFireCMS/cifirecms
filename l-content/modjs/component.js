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
			{targets: 'no-sort', width: '90px', orderable: false, searchable: false}
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
			var urlTable = admin_url+a_mod;
			dataTableDrawCallback();
			$('.delete_single').on('click', function(e) {
				e.preventDefault();
				var pk = [];
				pk = [$(this).attr('data-pk')];
				cfSwalDelete(pk, apiTable, urlTable);
			});
		}
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
				if (fileHeader !== '504b34' || type == '' || type !== 'application/x-zip-compressed') {
					$(this).val('');
					$('#fupload').val('');
					$('.custom-file-label').html(file.name);
					// $('.detail-package').show().html('<div class="text-danger tx-13">The file type you are attempting to upload is not allowed.!</div>');
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
});