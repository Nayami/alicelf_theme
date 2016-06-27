+function($) {

	transformicons.add('.tcon');

	var openCloseMenu = function(selector, menu) {
		if (selector.hasClass('tcon-transform')) {
			$('body').addClass('disable-scroll');
			menu.css('display', 'block');
			setTimeout(function() {
				menu.addClass('open-menu');
			}, 10);
		} else {
			$('body').removeClass('disable-scroll');
			menu.removeClass('open-menu');
			setTimeout(function() {
				menu.css('display', 'none');
			}, 300);
		}
	};

	var launcher = $('#mobile-menu-trigger').find('> button'),
		menuContainer = $('#main-alicelf-nav');

	openCloseMenu(launcher, menuContainer);

	launcher.on('click', function() {
		openCloseMenu($(this), menuContainer);
	});

	menuContainer.find('.caret').on('click', function(event) {
		event.stopPropagation();
		event.preventDefault();
		var that = $(this), thatParent = that.parent();
		that.toggleClass('fa-minus');
		thatParent.siblings('.sub-menu').toggleClass('shown-submenu');
	});

	menuContainer.on('click', function(e) {
		e.stopPropagation();
		if ($(e.target).hasClass('main-navigation')) {
			menuContainer.removeClass('open-menu');
			$('body').removeClass('disable-scroll');
			setTimeout(function() {
				menuContainer.css('display', 'none');
			}, 300);
			launcher.removeClass('tcon-transform');
		}
	});

}(jQuery);