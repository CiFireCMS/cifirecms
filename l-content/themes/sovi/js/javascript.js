$(document).ready(function(){

	$('#navsticky').sticky({topSpacing:0});

	$('.input-search').attr('maxlength','80');
	$('.search-toggle').click(function(){
		$( '.top-search-warper' ).slideToggle('fast', function(){
			$('.input-search').focus().val('');
			$('.search-toggle i').toggleClass('fa-times');
		});
	});

	$('.reply_comment').click(function(){
		var id = $(this).attr('data-parent');
		$('.input_parent').val(id);
	});

	$('#headlines').carousel({
		interval: 10900
	});

	var pw_container = [];

	$('#gallery').find('.g-item').each(function() {
		var $link = $(this).find('.g-link'),
		item = {
			src: $link.attr('href'),
			w: $link.data('width'),
			h: $link.data('height'),
			title: $link.data('caption')
		};
		pw_container.push(item);
	});

	// Define click event on gallery item
	$('.g-link').click(function(event) {
		event.preventDefault();

		// Define object and gallery options
		var $pswp = $('.pswp')[0],
		options = {
			index: $(this).parent('.g-item').index(),
			// bgOpacity: 0.90,
			showHideOpacity: true
		};

		// Initialize PhotoSwipe
		var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, pw_container, options);
		gallery.init();
	});
});
