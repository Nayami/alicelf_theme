/**
 * Ajax dynamic theme script
 */

var SITE_URL = aa_ajax_var.site_url,
		AJAXURL = aa_ajax_var.ajax_url,
		THEME_URI = aa_ajax_var.template_uri,
		IMG_DIR = THEME_URI + '/img/';

var Loaders = {
	bouncingAbsolute: '<div id="loader-absolute" class="labsolut"><div class="preview-area"><div class="spinner-jx"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>',
	bouncingStatic  : '<div id="loader-static"><div class="preview-area"><div class="spinner-jx"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>',
	infiniteSpinner : '<div class="loader-backdrop"><div class="loader-infinite-spinner"><div class="lt"></div><div class="rt"></div><div class="lb"></div><div class="rb"></div></div></div>',
	svgLoader       : '<img id="svg-loader-process" src="' + THEME_URI + '/svg/loader_svg.svg" width="40" alt="loadersvg"/>'
};

jQuery(document).ready(function($) {

	/**
	 * ==================== waitUntilExists ======================
	 * 2/11/2016
	 */
	$.fn.waitUntilExists = function(handler, shouldRunHandlerOnce, isChild) {
		var found = 'found';
		var $this = $(this.selector);
		var $elements = $this.not(function() {
			return $(this).data(found);
		}).each(handler).data(found, true);

		if (!isChild) {
			(window.waitUntilExists_Intervals = window.waitUntilExists_Intervals || {})[this.selector] =
				window.setInterval(function() {
					$this.waitUntilExists(handler, shouldRunHandlerOnce, true);
				}, 500)
			;
		}
		else
			if (shouldRunHandlerOnce && $elements.length) {
				window.clearInterval(window.waitUntilExists_Intervals[this.selector]);
			}

		return $this;
	};
	/**
	 * ==================== Helpers ======================
	 * 2/11/2016
	 */
	var alertHolder = function(itemClass, sendedMessage) {
		return "<div class='alert alert-" + itemClass + "'>" +
			"<button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
			"<span aria-hidden='true'>&times;</span>" +
			"</button><h3 class='text-center'>" + sendedMessage + "</h3></div>"
	},
		modalAlert = function(itemClass, msg) {
		return "<div class='alert-backdrop'><div class='alert alert-" + itemClass + "'>" +
			"<p>" + msg + "</p></div></div>"
	},
		elemHasClass = function(el, cls) {
		return el.className && new RegExp("(\\s|^)" + cls + "(\\s|$)").test(el.className);
	},
		capitalize = function (string) {
		return string.charAt(0).toUpperCase() + string.slice(1);
	};

	$('.alert-backdrop').waitUntilExists(function() {
		var that = $(this);
		that.on('click', function(e) {
			e.stopPropagation();
			if (elemHasClass(e.target, 'alert-backdrop')) {
				that.fadeOut(500, function() {
					that.remove();
				});
			}
		});
	});

	/**
	 * ==================== Defaults End ======================
	 * 2/11/2016
	 */


	(function mainThemeAjaxScope() {

		var successMessage ="Your message has been successfully sended",
				errorMessage = "Fill Correct all required fields!",
				wrongCaptcha = "Fill Correct Captha",
				unknownError ="Something Wrong, try again later";

		var formContactProcess = function(act) {

			$('form.alice-ajax-contact-form').on('submit', function() {
				var that = $(this),
					method = that.attr('method'),
					formData = {
						action : act
					};

				that.find('[name]').each(function() {
					formData[$(this).attr('name')] = $(this).val();
				});

				//FormProcessStart
				$.ajax({
					url       : AJAXURL,
					type      : method,
					data      : formData,
					error     : function() {
						alert("Something wrong! Try again later.");
					},
					beforeSend: function() {
						//show loader or u can show loader before ajax and then hide him
						$('body').prepend(Loaders.infiniteSpinner);
						$('body .loader-backdrop').animate({
							opacity: 1
						}, 500);
					},
					success   : function(response) {
						//remove/hide loader
						//do something with response
						var body = $('body'),
							wrapper = $('.ghostly-wrap');
						switch (response) {
							case 'success':
								body.find('.loader-backdrop').remove();
								wrapper.find('.alert').remove();
								that.before(alertHolder('success', successMessage));
								// Clear inputs when success
								that.find('[name]').each(function() {
									$(this).val("");
								});
								break;
							case 'error' :
								body.find('.loader-backdrop').remove();
								wrapper.find('.alert').remove();
								that.before(alertHolder('danger', errorMessage));
								break;
							case 'error captcha' :
								body.find('.loader-backdrop').remove();
								wrapper.find('.alert').remove();
								that.before(alertHolder('warning', wrongCaptcha));
								break;
							default :
								body.find('.loader-backdrop').remove();
								wrapper.find('.alert').remove();
								that.before(alertHolder('info', unknownError));
						}
					},
					complete  : function() {
					}
				});

				return false;
			});

		};
		formContactProcess('aa_contact_form');


		var ajaxLoadPosts = function() {
			var page = 1,
				loading = true,
				$window = $(window),
				$content = $('#ajax-posts-loop');

			page === 1 && progressJs().start();


			var loadPosts = function() {
				$.ajax({
					url       : AJAXURL,
					type      : "POST",
					data      : {
						alice_ajax_posts: true,
						pageNumber      : page,
						action: 'alice_ajax_posts'
					},
					dataType  : "html",
					beforeSend: function() {
						if (page !== 1)
							$content.append(Loaders.bouncingStatic);

						page === 1 && progressJs().set(50);
					},
					success   : function(data) {
						var $data = $(data);
						if ($data.length) {
							$data.hide();
							$content.append($data);
							$data.fadeIn();
							$('#loader-static').remove();
							loading = false;
						} else {
							$('#loader-static').remove();
						}
						page === 1 && progressJs().end();
					},
					error     : function(jqXHR, textStatus, errorThrown) {
						$('#loader-static').remove();
						alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
					}
				});

			};
			$window.on('scroll', function() {
				if (((window.innerHeight + window.scrollY) >= document.body.offsetHeight) && loading === false) {
					loading = true;
					page++;
					loadPosts();
				}
			});
			loadPosts();
		};
		if (document.getElementById('ajax-posts-loop') !== null)
			ajaxLoadPosts();

	})();

	


});
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
	;(function() {

		var openCloseMenu = function(selector, menu) {
			if (selector.hasClass('tcon-transform')) {
				menu.css('display', 'block');
				setTimeout(function(){
					menu.addClass('open-menu');
				}, 10);
			} else {
				menu.removeClass('open-menu');
				setTimeout(function(){
					menu.css('display', 'none');
				}, 300);
			}
		};

		var launcher = $('#mobile-menu-trigger').find('> button'),
			menuContainer = $('#main-alicelf-nav');

		openCloseMenu(launcher, menuContainer);

		launcher.on('click', function(){
			openCloseMenu($(this), menuContainer);
		});


	})();

});