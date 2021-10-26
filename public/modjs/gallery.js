$(function() {
	'use strict'

	$('.fancybox').fancybox();

	$('#browse-files').fancybox({ 
		width: 1000, 
		height: 1000, 
		type: 'iframe', 
		autoScale: false
	}); 

	$('.modal_add_album').on('click',function() {
		$('#modal_add_album').modal('show');
	});

	$('.modal_add_picture').on('click',function() {
		$('#modal_add_picture').modal('show');
	});

	$('.delete_album').on('click',function(e) {
		e.preventDefault();
		var dataPk = [$(this).attr('data-id')];
		var dataUrl = admin_url + a_mod + '/delete/album';
		_deleteGallery(dataPk,dataUrl)
	});

	$('.delete_gallery_image').on('click',function(e) {
		e.preventDefault();
		var dataPk = [$(this).attr('data-id')];
		var dataUrl = admin_url + a_mod + '/delete/image';
		_deleteGallery(dataPk,dataUrl)
	});

	function _deleteGallery(pk,uri){
		var dataPk = pk;
		var dataUrl = uri;
		getLangJSON().done(function(lang){
			swal({
				title               : '<span class="mg-t-30">'+lang.modal['delete_title']+'</span>',
				text                : lang.modal['delete_content'],

				showConfirmButton   : true,
				confirmButtonClass  : 'btn btn-lg btn-danger',
				confirmButtonText   : lang.button['delete'],

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
								'data': dataPk,
								'csrf_name': csrfToken
							},
							cache: false,
							success:function(response) {
								if (response['success']==true) {
									$('#gallery-item'+response['dataDelete']).remove();
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

	$(".gbhs").on("mouseover",function(s){s.preventDevault,$(this).find(".gbhs2").show(),$(this).mouseout(function(){$(this).find(".gbhs2").hide()})});
});

function responsive_filemanager_callback(field_id) {
	var url = $('#'+field_id).val();
	$('#prv').val(url);
	parent.$.fancybox.close();
}


/**
 * lazyload.js (c) Lorenzo Giuliani MIT License
 * expects a list of:
 * <img src="blank.gif" data-src="my_image.png" width="600" height="400" class="lazy">
 */
$(function(){function e(r,s){var t=new Image,u=r.getAttribute('data-src');t.onload=function(){!r.parent?r.src=u:r.parent.replaceChild(t,r),s?s():null},t.src=u}function g(r){var s=r.getBoundingClientRect();return 0<=s.top&&0<=s.left&&s.top<=(window.innerHeight||document.documentElement.clientHeight)}for(var h=function(r,s){if(document.querySelectorAll)s=document.querySelectorAll(r);else{var t=document,u=t.styleSheets[0]||t.createStyleSheet();u.addRule(r,'f:b');for(var v=t.all,w=0,x=[],y=v.length;w<y;w++)v[w].currentStyle.f&&x.push(v[w]);u.removeRule(0),s=x}return s},j=function(r,s){window.addEventListener?this.addEventListener(r,s,!1):window.attachEvent?this.attachEvent('on'+r,s):this['on'+r]=s},m=[],n=h('img.lazy'),o=function(){for(var r=0;r<m.length;r++)g(m[r])&&e(m[r],function(){m.splice(r,r)})},p=0;p<n.length;p++)m.push(n[p]);o(),j('scroll',o)});