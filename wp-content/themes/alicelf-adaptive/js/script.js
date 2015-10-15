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
		pagenavi = document.getElementsByClassName('wp-pagenavi')[0];

		if (pagenavi !== undefined) {
			htmlProcess = function() {
				var nodeTypeElem;
				elemlist = pagenavi.childNodes.length;
				ul = document.createElement('ul');
				ul.classList.add('pagination');
				pagenavi.insertBefore(ul, pagenavi.firstChild);

				while (elemlist--) {
					nodeTypeElem = pagenavi.childNodes[elemlist];
					if (nodeTypeElem.tagName === undefined || nodeTypeElem.tagName === 'UL')
						continue;
					li = document.createElement('li');
					li.appendChild(nodeTypeElem);
					ul.insertBefore(li, ul.firstChild);
				}
			};

			return pagenavi.innerHtml = htmlProcess();
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
					pattern = /^[a-z]+@[a-z]+\.[a-z]{2,6}$/i,
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

});