+function($) {

	/**
	 * ==================== Popup ======================
	 * 29.04.2016
	 */
	$('.popup-holder').on('click', function(e) {
		e.preventDefault();

		var targetElem = $(e.target);
		var that = $(this),
			popup = that.find('.popup-window'),
			placement = popup.attr('data-placement');

		if(targetElem.parents('.popup-window').length < 1) {
			popup.css({display: 'block'});

			setTimeout(function() {
				popup.toggleClass('show');
			}, 10);

			popup.bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function() {
				if (!popup.hasClass('show'))
					popup.css({display: 'none'});
			});
		}

	});
	$('body').on('click', function(e){
		var targetElem = $(e.target);
		if(targetElem.parents('.popup-holder').length < 1) {

			var popup = $(this).find('.popup-window');
			setTimeout(function() {
				popup.removeClass('show');
			}, 10);
			popup.bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function() {
				if (!popup.hasClass('show'))
					popup.css({display: 'none'});
			});
		}
	});

	/**
	 * ==================== Popup onhover to desktop ======================
	 */
	var popupOnhoverDesktop = function() {
		var triggerHover = $('.popup-holder');

		if($(window).width() > 768) {
			triggerHover.on({
				mouseenter: function () {
					var that = $(this),
						popupWindow = that.find('.onhover');
					popupWindow.css({display: 'block'});

					setTimeout(function() {
						popupWindow.addClass('show');
					}, 200);
				},
				mouseleave: function () {
					var that = $(this),
						popupWindow = that.find('.onhover');
					popupWindow.removeClass('show');

					setTimeout(function() {
						popupWindow.css({display: 'none'});
					}, 500);
				}
			});
		}
	};
	popupOnhoverDesktop();
	$(window).on('resize', function(){
		popupOnhoverDesktop();
	});


}(jQuery);