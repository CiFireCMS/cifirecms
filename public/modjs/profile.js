$(function() {
	'use strict'

	$('#form_update').on('submit',function(e){
		e.preventDefault();
		var formData = new FormData(this);
		var submitButton = $('.submit_update');
		submitButton.find('i').attr('class','fa fa-spin fa-spinner mr-2');
		$.ajax({
			type: 'POST',
			data: formData,
			dataType: 'json',
			contentType: false,  
			processData:false,
			cache: false,
			success:function(data){
				cfNotif(data['alert']);
				submitButton.find('i').attr('class','fa fa-save mr-2');
			}
		});
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

	$(':file#upload-image').on('change',function() {
		var file = $(this)[0].files[0];
		var fileReader = new FileReader();
		getLangJSON().done(function(lang){
			fileReader.onloadend = function (e) {
				const arrayBuffer = fileReader.result;
				const blob = new Blob([arrayBuffer], {type: 'image/png'});
				let src = URL.createObjectURL(blob);
				var arr = (new Uint8Array(e.target.result)).subarray(0, 4);
				var fileHeader = '';
				for (var i = 0; i < arr.length; i++) {
					fileHeader += arr[i].toString(16);
				}
				var type = mimeFileType(fileHeader);
				if (type == 'image/jpeg' || type == 'image/png') {
					$(".us-avatar").attr("src", src);
					document.getElementById("delimg").checked = false;
				} else {
					$('#upload-image').val('');
					alert('Please insert valid image file');
				}
			};
			fileReader.readAsArrayBuffer(file);
		});
	});

	$('#delpict').on('click',function(e){
		e.preventDefault();
		$('#upload-image').val('');
		$('.custom-file-label').html('');
		$('#image-preview').attr('src',site_url+'/images/avatar.jpg');
		document.getElementById("delimg").checked = true;
	});

	$('#resetpict').on('click',function(e){
		e.preventDefault();
		$('#upload-image').val('');
		$('.custom-file-label').html('');
		var urlPhoto = $(this).attr('data-photo');
		$('#image-preview').attr('src',urlPhoto);
		document.getElementById("delimg").checked = false;
	});
});