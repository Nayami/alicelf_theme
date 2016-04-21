/**
 * Base Theme Script ver 0.1.0
 */

window.onload = function() {

	"use strict";

	//Element, check existing class
	var elemHasClass = function(el, cls) {
		return el.className && new RegExp("(\\s|^)" + cls + "(\\s|$)").test(el.className);
	};
	// Wrapper for wp-pagenavi
	var paginator = function() {
		var pagenavi, ul, li, elemlist, htmlProcess;

		pagenavi = document.getElementsByClassName('wp-pagenavi');

		if (pagenavi !== undefined) {
			htmlProcess = function(elem) {
				if (elem.childNodes !== undefined) {
					var nodeTypeElem;
					elemlist = elem.childNodes.length;

					ul = document.createElement('ul');
					ul.classList.add('pagination');
					elem.insertBefore(ul, elem.firstChild);

					while (elemlist--) {
						nodeTypeElem = elem.childNodes[elemlist];
						if (nodeTypeElem.tagName === undefined || nodeTypeElem.tagName === 'UL')
							continue;
						li = document.createElement('li');
						li.appendChild(nodeTypeElem);
						ul.insertBefore(li, ul.firstChild);
					}

				}

			};
			for (var i = 0; i < pagenavi.length; i++) {
				var navi = pagenavi[i];
				navi.innerHtml = htmlProcess(navi);
			}

		}

	};
	paginator();

	//Validation form without jq
	var formValidator = function() {
		var formHolder = document.getElementById('alicelf-commentform');

		if (formHolder) {
			var author = formHolder.elements['author'],
				email = formHolder.elements['email'],
				comment = formHolder.elements['comment'],
				respond = document.getElementById('respond');

			formHolder.onsubmit = function(e) {
				var counterEl = formHolder.elements.length,
					pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/,
					alertMsg = '<div class="alert alert-danger alert-dismissible" role="alert">' +
						'<button type="button" class="close" data-dismiss="alert">' +
						'<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' +
						'<h4>Fill correct all required fields!</h4>' +
						'<p>Note: name cannot be blank, email must be a valid mail, comment field also must be filled</p>',
					currElem, typeItem, matchedElem;

				if (author.value === '' || email.value === '' || email.value.search(pattern) === -1 || comment.value === '') {
					e.preventDefault();

					//beforebegin afterbegin beforeend afterend
					if (!elemHasClass(respond.firstChild, 'alert-danger'))
						respond.insertAdjacentHTML('afterbegin', alertMsg);

					while (counterEl--) {

						currElem = formHolder.elements[counterEl];
						typeItem = currElem.type;

						matchedElem = !!(typeItem === 'text' || typeItem === 'textarea');
						if (matchedElem) {

							if (currElem.value === '') {
								currElem.parentNode.classList.remove('has-success');
								currElem.parentNode.classList.add('has-error');

							} else
								if (currElem.value !== '' && currElem.name === 'email') {
									if (currElem.value.search(pattern) === -1) {
										currElem.parentNode.classList.remove('has-success');
										currElem.parentNode.classList.add('has-error');
									} else {
										currElem.parentNode.classList.remove('has-error');
										currElem.parentNode.classList.add('has-success');
									}
								} else {
									currElem.parentNode.classList.remove('has-error');
									currElem.parentNode.classList.add('has-success');
								}
						}

					}

					alert('Fill Correct All required Fields!');
					return false;
				}
			};
		}
	};
	formValidator();

	var arrowScroll = function() {
		//document.documentElement.scrollTop || document.body.scrollTop;
		//document.documentElement.scrollLeft || document.body.scrollLeft;
		var arrow = document.getElementById('footer-angle-arrow');
		window.document.addEventListener('scroll', function() {
			var topOffset = document.documentElement.scrollTop || document.body.scrollTop;
			topOffset > 300 ? arrow.classList.add('visible-arrow') : arrow.classList.remove('visible-arrow');
		});
	};
	arrowScroll();
	/**
	 * Smooth Scrolling
	 * Easing: https://github.com/cferdinandi/smooth-scroll#user-content-easing-options
	 */

	if (typeof(smoothScroll) === 'object') {
		var triggerClick = document.querySelector('#footer-angle-arrow'),
			scrolledOptions = {
				speed : 700,
				easing: 'easeOutQuart'
			};
		triggerClick.onclick = function(e) {
			e.preventDefault();
			smoothScroll.animateScroll(triggerClick, '#scroll-trigger-top', scrolledOptions);
		};
	}

	/* Parallax things */
	var parallaxFn = function(elem, speed, param) {
		var handlerElement = document.querySelector(elem);
		if (handlerElement) {
			handlerElement.style.backgroundPositionY = Math.round(-(window.pageYOffset / speed + param)) + 'px';
			window.document.addEventListener('scroll', function() {
				handlerElement.style.backgroundPositionY = Math.round(-(window.pageYOffset / speed + param)) + 'px';
			});
		}
	};
};

jQuery(document).ready(function($) {

	var slickSliderOpt = function() {
		$('.slider-for').slick({
			slidesToShow  : 1,
			slidesToScroll: 1,
			arrows        : false,
			fade          : true,
			asNavFor      : '.slider-nav'
		});
		$('.slider-nav').slick({
			slidesToShow  : 3,
			slidesToScroll: 1,
			asNavFor      : '.slider-for',
			dots          : false,
			centerMode    : true,
			focusOnSelect : true
		});
	};
	if (typeof $.fn.slick === 'function')
		slickSliderOpt();

	var stickNavbar = function() {

		$(window).on('scroll', function() {
			var topOffset = document.documentElement.scrollTop || document.body.scrollTop,
				selection = $('.stick-to-top').find('>.container > header'),
				wpAdminBarH = $('#wpadminbar').height(),
				selectionHeight = selection.height();

			if ($(window).width() < 600)
				wpAdminBarH = 0;

			$(window).on('resize', function() {
				if ($(window).width() < 600)
					wpAdminBarH = 0;
				selectionHeight = $('.stick-to-top').find('>.container > header').height();
			});

			if (topOffset > selectionHeight) {
				selection.css({
					position : 'fixed',
					width    : '100%',
					top      : (0 + wpAdminBarH) + 'px',
					'z-index': '999'
				});
				if (!selection.hasClass('header-touch-top')) {
					selection.css({
						top    : -selectionHeight + 'px',
						opacity: '0'
					});
					selection.animate({
						top    : (0 + wpAdminBarH) + 'px',
						opacity: 1
					}, 500)
				}
				selection.addClass('header-touch-top');
				$('#shock-absorber').css({height: selectionHeight + "px"});
			} else {
				selection.css({
					position: 'static',
					width   : 'auto'
				});
				selection.removeClass('header-touch-top');
				$('#shock-absorber').css({height: 0});
			}
		});

	};
	stickNavbar();

	transformicons.add('.tcon');
	;
	(function() {

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
				setTimeout(function() {
					menuContainer.css('display', 'none');
				}, 300);
				launcher.removeClass('tcon-transform');
			}
		});


	})();




	/**
	 * ==================== FRONTEND MODALS ======================
	 * 21.04.2016
	 */
	$('[data-modal-trigger]').on('click', function(e) {

		e.preventDefault();
		var that = $(this),
			type = that.attr('data-modal-trigger'),
			body = $('body'),
			relatedModal = that.attr('data-related-modal');

		body.find(relatedModal).css({'display': 'block'});
		setTimeout(function() {
			body.find(relatedModal).addClass('show');
		}, 10);

		$(window).trigger('aaModalOpened', [type, relatedModal]);

	});

	/**
	 * ==================== Open modal from event ======================
	 * raizeModalEvent("#login-modal")
	 */
	var raizeModalEvent = function(modal) {
		var relatedTrigger = $('[data-related-modal="' + modal + '"]') || null,
			type = relatedTrigger.length > 0 ? relatedTrigger.attr('data-modal-trigger') : null;

		$(modal).css({'display': 'block'});
		setTimeout(function() {
			$(modal).addClass('show');
		}, 10);

		$(window).trigger('aaModalOpened', [type, relatedTrigger]);
	};

	/**
	 * ==================== Remove Modal from event ======================
	 * detachModalEvent("#login-modal")
	 */
	var detachModalEvent = function(modal) {
		var modalOverlay = $(modal),
			relatedTrigger = $('[data-related-modal="' + modal + '"]') || null,
			type = relatedTrigger.length > 0 ? relatedTrigger.attr('data-modal-trigger') : null;

		modalOverlay.removeClass('show');
		setTimeout(function() {
			modalOverlay.css('display', 'none');
			$(window).trigger('aaModalClosed', [type, relatedTrigger])
		}, 300);

	};

	/**
	 * ==================== Close modal button ======================
	 * closeParentModal("#login-modal")
	 */
	var closeParentModal = function(modal) {
		var closeTrigger = $('[data-destroy-modal="' + modal + '"]'),
			modalOverlay = $(closeTrigger.attr('data-destroy-modal'));

		closeTrigger.on('click', function(e) {
			e.preventDefault();
			modalOverlay.removeClass('show');
			setTimeout(function() {
				modalOverlay.css('display', 'none');
				$(window).trigger('aaModalClosed')
			}, 300);
		});

	};

	var closeTrigger = $('[data-destroy-modal]');
	closeTrigger.on('click', function(e) {
		e.preventDefault();
		var that = $(this),
			modalOverlay = $(that.attr('data-destroy-modal'));

		modalOverlay.removeClass('show');
		setTimeout(function() {
			modalOverlay.css('display', 'none');
			$(window).trigger('aaModalClosed')
		}, 300);
	});


	//raizeModalEvent("#login-modal");
	//setTimeout(function(){
	//	detachModalEvent("#login-modal");
	//}, 3000);
	//$(window).on('aaModalOpened', function(e, type, relatedModal){
	//	console.log(e, type, relatedModal);
	//});

});