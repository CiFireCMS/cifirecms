$(function() {
	'use strict'

	$('#form_update').on('submit',function(e){
		e.preventDefault();
		var formData = new FormData(this);
		var submitButton = '.submit_update';
		$(submitButton).find('i').attr('class','fa fa-spin fa-spinner mr-2');
		$('.noty_layout').remove();
		$.ajax({
			url: admin_url + a_mod + '/submit-update',
			type: 'POST',
			data: formData,
			dataType: 'json',
			contentType: false,  
			processData:false,
			cache: false,
			success:function(response){
				cfNotif(response['alert']);
				$(submitButton).find('i').attr('class','fa fa-save mr-2');
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

	$('input:not(textarea)').keydown(function(event){
		var a = event.witch || event.keyCode;
		if(a == 13){
			event.preventDefault();
			return false;
		}
	});

	$(':file#fupload').on('change',function() {
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
					$('#fupload').val('');
					$('.custom-file-label').html('error');
				}
			};
			fileReader.readAsArrayBuffer(file);
		});
	});
});