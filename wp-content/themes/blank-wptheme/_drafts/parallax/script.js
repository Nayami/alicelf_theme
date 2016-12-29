/**
 * ==================== Fit Image according to screen ======================
 * 07.08.2016
 */
jQuery(document).ready(function ($){
	var fitImageMediasize = function(img) {
		var image = $(img),
			breakPoint = image.attr('data-mediabreakpoint');
		$(window).width() > breakPoint ? image.css('width', '100%') : image.css('width', 'auto');
	};
	fitImageMediasize('[data-mediabreakpoint]');
	$(window).on('resize', function() {
		fitImageMediasize('[data-mediabreakpoint]');
	});
});
