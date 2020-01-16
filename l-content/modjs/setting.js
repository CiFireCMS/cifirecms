$(function() {
	'use strict'

	function _loadValueType(TYPE,VALUE,OPTIONS,ACT_METHOD) {
		switch (TYPE)
		{
			case 'text':
				$('#formType').html('<input type="text" name="value" class="form-control" value="'+VALUE+'"/>');
			break;

			case 'select':
				if (ACT_METHOD=='edit')
				{
					$('#formType').html('<select name="value" class="select-bs form-control"><option value="'+VALUE+'" style="display:none;">'+VALUE+'</option>'+OPTIONS+'</select>');
					$('.select-bs').selectpicker();
				}
				if (ACT_METHOD=='add') {
					var index = 1;
					$('#formType').html('<div id="boxOption"><div><div class="input-group mg-b-8"><div class="input-group-prepend"><span class="input-group-text">Option '+index+'</span></div><input class="form-control" name="value[1]" required/><div class="input-group-append"><span class="input-group-text">Selected</span></div></div></div></div><button id="add" type="button" class="btn btn-xs btn-white">+ Add Option</button>');

					$('#add').on('click', function(i){
						i.preventDefault();
						index = index+1;
						$('#boxOption').append('<div id="index'+index+'"><div class="input-group mg-b-8"><div class="input-group-prepend"><span class="input-group-text">Option '+index+'</span></div><input class="form-control" name="value['+index+']" required/><div class="input-group-append"><button id="rmOption'+index+'" data-rm="'+index+'" type="button" class="btn btn-default">x</button></div></div>');
						_rmSelOption(index)
					});
				}
			break;

			case 'timezone':
				$('#formType').html('<select class="selectTimezone" name="value"><option value="'+VALUE+'">'+VALUE+'</option></select>');
				$('.selectTimezone').select2({
					minimumInputLength: 0,
					allowClear: false,
					placeholder: 'Select Timezone ',
					ajax: {
						url: admin_url + a_mod + '/jsonTimezone',
						type: 'POST',
						dataType: 'json',
						delay: 500,
						data: function (params) {
							return {
								search: params.term,
								'csrf_name':csrfToken
							}
						},
						processResults: function (data) {
							var dataResults = data.map(function (item) {
								return {
									id: item.value,
									text: item.text
								};
							 });
							return {
								results: dataResults
							};
						},
					}
				});
			break;

			case 'password':
				$('#formType').html('<input type="password" name="value" value="'+VALUE+'" class="form-control"/>');
			break;

			case 'slug':
				$('#formType').html('<select name="value" class="select-bs"><option value="'+VALUE+'" style="display:none;">'+VALUE+'</option><option value="slug/seotitle">slug/seotitle</option><option value="yyyy/seotitle">yyyy/seotitle</option><option value="yyyy/mm/seotitle">yyyy/mm/seotitle</option><option value="yyyy/mm/dd/seotitle">yyyy/mm/dd/seotitle</option><option value="seotitle">seotitle</option></select>');
				$('.select-bs').selectpicker();
			break;

			case 'file':
				$('#formType').html('<div class="input-group"><div class="input-group-prepend"><button type="button" id="browse" href="'+content_url+'plugins/filemanager/dialog.php?type=1&relative_url=1&field_id=valz&sort_by=date&descending=1&akey='+_FMKEY+'" class="btn btn-default">Browse</button></div><input id="valz" type="text" name="value" class="form-control" value="'+VALUE+'" /></div>');
				$('#browse').fancybox({ 
					type: 'iframe',
					width: 1000,
					height: 1000,
					autoScale: true,
				});
			break;

			case 'html':
				$('#formType').html('<textarea id="codeEditor" name="value" class="form-control">'+VALUE+'</textarea>');
				var _CodeEditor=CodeMirror.fromTextArea(document.getElementById("codeEditor"),{mode:"php",extraKeys:{"Ctrl-J":"toMatchingTag",F11:function(e){e.setOption("fullScreen",!e.getOption("fullScreen"))},Esc:function(e){e.getOption("fullScreen")&&e.setOption("fullScreen",!1)},"Ctrl-Space":"autocomplete"},theme:"github",lineWrapping:!0,cursorBlinkRate:200,autocorrect:!0,autofocus:!0,lineNumbers:!0,gutters:["CodeMirror-linenumbers"],styleActiveLine:!0,autoCloseBrackets:!0,autoCloseTags:!0,scrollbarStyle:"simple"});
			break;

			case 'other':
				$('#formType').html('<textarea name="value" class="form-control">'+VALUE+'</textarea>');
			break;

			default:
				$('#formType').html('<textarea name="value" class="form-control">'+VALUE+'</textarea>');
		} // switch
	}

	function _rmSelOption(index){
		$('#rmOption'+index).click(function(e){
			e.preventDefault();
			if( confirm("Are you sure you want to delete this field option "+index+"?") ){
				$('#index'+index).remove();
			} 
			else {
				return false; 
			}
		});
	}

	var _dTable = $('#DataTable').DataTable({
		language: {
			url: datatable_lang,
		},
		autoWidth: false,
		responsive: false,
		processing: true,
		serverSide: true,
		stateSave: true,
		order: [[1,'DESC']],
		columnDefs: [
			{targets: 'no-sort', orderable: false, searchable: false},
			{targets: 'th-action', orderable: false, searchable: false},
			{targets: [0], width: '20px'},
			{targets: [1], width: '30px'},
			{targets: [5], width: '70px'}
		],
		lengthMenu: [
			[10, 20, 50, 100, -1],
			[10, 20, 50, 100, 'All']
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

	$('#selectType').on('change', function(e){
		_TYPE = $(this).val();
		_loadValueType(_TYPE,_VALUE,_SELECT_OPTION_CONTENT,_ACT_METHOD)
	});
	if (typeof _TYPE != 'undefined' && _TYPE)
	{
		_loadValueType(_TYPE,_VALUE,_SELECT_OPTION_CONTENT,_ACT_METHOD)
	};


	var _CodeEditor = CodeMirror.fromTextArea(document.getElementById("code_metasocial"), {
		mode: "php",
		extraKeys: {
			"Ctrl-J": "toMatchingTag",
			"F11": function(cm) {
				cm.setOption("fullScreen", !cm.getOption("fullScreen"));
			},
			"Esc": function(cm) {
				if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
			},
			"Ctrl-Space": "autocomplete"
		},
		theme: "github",
		lineWrapping: true,
		cursorBlinkRate: 200,
		autocorrect: true,
		autofocus: true,
		lineNumbers: true,
		gutters: ["CodeMirror-linenumbers"],
		styleActiveLine: true,
		autoCloseBrackets: true,
		autoCloseTags: true,
		scrollbarStyle:"simple",
	});

	$('.nav-tabs a').on('shown.bs.tab', function() {
		_CodeEditor.refresh();
	});


	$("#options").on("input",function(){var e;e=(e=(e=$(this).val()).replace(/\s+/g," ")).replace(/_/g," "),$(this).val(e.toLowerCase()),$(this).val($(this).val().replace(/\W/g," ")),$(this).val($(this).val().replace(/\s+/g,"_"))});

	$('#submit-meta').on('click',function(){
		var _this = $(this);
		var contentText = $('#content').val();
		var content = _CodeEditor.getValue(contentText);
		_this.find('i').attr('class','fa fa-spin fa-spinner mr-2');
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				'act':'metasocial',
				'meta_content':content,
				'csrf_name': csrfToken
			},
			success:function(data) {
				cfNotif(data['alert']);
				_CodeEditor.refresh();
				_this.find('i').attr('class','fa fa-save mr-2');
			}
		});
	});
});