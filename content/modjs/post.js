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
			{targets: [1], width: '20px'},
			{targets: [4], width: '115px'}
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

			$('.headline_toggle').on('click', function(e) {
				e.preventDefault();
				var data_pk = $(this).attr('data-pk');
				var buttonClass = $(this);
				buttonClass.find('i').attr('class','fa fa-spin fa-spinner');
				$.ajax({
					type: 'POST',
					dataType: 'json',
					data:{
						'pk': data_pk,
						'act': 'headline',
						'csrf_name': csrfToken
					},
					success:function(response){
						if (response['status']==true) {
							var classRow = '.'+response['index'];
							var content = response['html'];
							$(classRow).html(content);
							cfNotif(response['alert']);
						} else {
							cfNotif(response['alert']);
						}
						buttonClass.find('i').attr('class','cificon licon-star');
					}
				});
			});
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
			success:function(data){
				cfNotif(data['alert']);
				form.find('.submit_update i').attr('class','fa fa-save mr-2');
			}
		});
	});

	cfTnyMCE('#Content', 445); // load TnyMCE

	var tagName = new Bloodhound({
		datumTokenizer:Bloodhound.tokenizers.obj.whitespace('title'),
		queryTokenizer:Bloodhound.tokenizers.whitespace,
		remote:{
			url: admin_url+'post/ajax-tags',
			prepare:function(e,t){
				return $('.tt-hint').show(),
				t.type='POST',
				t.data='seotitle='+e+'&csrf_name='+csrfToken,
				t
			},
			filter:function(e){
				return $('.tt-hint').hide(),e
			}
		}
	});
	tagName.initialize();

	$('#tagsjs').tagsinput({
		typeaheadjs:{
			name: 'tagName',
			displayKey: 'title',
			valueKey: 'title',
			source: tagName.ttAdapter()
		}
	});

	$('.twitter-typeahead').css('display','inline');

	$('#publishdate').datetimepicker({
		format:'YYYY-MM-DD',
		showTodayButton:true,
		showClear:true,
		icons:{
			previous:'icon-arrow-left8',
			next:'icon-arrow-right8',
			today:'fa fa-calendar-check-o',
			clear:'icon-bin'
		}
	});

	$('#publishtime').datetimepicker({
		format:'HH:mm:ss',
		showTodayButton:true,
		showClear:true,
		icons:{
			up:'icon-arrow-up7',
			down:'icon-arrow-down7',
			today:'fa fa-clock-o',
			clear:'icon-bin'
		}
	});

	$('#delpict').on('click',function(e){
		e.preventDefault();
		$('#picture').val(''),
		$('#imgprv').attr('src',content_url+'images/noimage.jpg')
	});

	// $('input:not(textarea)').keydown(function(e){if(13==(e.witch||e.keyCode))return e.preventDefault(),!1});
	$('#title').on('input',function(){var e;e=(e=(e=$(this).val()).replace(/\s+/g,' ')).replace(/_/g,' '),$('#seotitle').val(e.toLowerCase()),$('#seotitle').val($('#seotitle').val().replace(/\W/g,' ')),$('#seotitle').val($('#seotitle').val().replace(/\s+/g,'-'))});
	$('#seotitle').on('input',function(){var e;e=(e=(e=$(this).val()).replace(/\s+/g,' ')).replace(/_/g,' '),$(this).val(e.toLowerCase()),$(this).val($(this).val().replace(/\W/g,' ')),$(this).val($(this).val().replace(/\s+/g,'-'))});
});

function responsive_filemanager_callback(){
	var pict = $('#picture').val();
	var url = content_url + 'uploads/medium/' + pict;
	$('#imgprv').attr('src', url).show();
	parent.$.fancybox.close();
}

// $('html, body').animate({scrollTop:0});