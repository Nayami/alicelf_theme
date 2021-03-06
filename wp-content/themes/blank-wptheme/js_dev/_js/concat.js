/**
 * Ajax dynamic theme script
 */

var SITE_URL = aa_ajax_var.site_url,
	AJAXURL = aa_ajax_var.ajax_url,
	THEME_URI = aa_ajax_var.template_uri,
	IMG_DIR = THEME_URI + '/img/',
	LOAD_LINE = "#global-load-line",
	_COLORS = {
		purple: "#684fb6",
		red   : "#d9534f"
	};

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
		launchModalAlert = function(itemClass, msg) {
			var bodyselector = $('body');
			bodyselector.prepend(modalAlert(itemClass, msg));
			setTimeout(function() {
				bodyselector.find('.alert-backdrop').addClass('show');
			}, 100);
		},
		elemHasClass = function(el, cls) {
			return el.className && new RegExp("(\\s|^)" + cls + "(\\s|$)").test(el.className);
		},
		capitalize = function(string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		};


	$('.alert-backdrop').waitUntilExists(function() {
		var that = $(this);
		that.on('click', function(e) {
			e.stopPropagation();
			if (elemHasClass(e.target, 'alert-backdrop')) {
				that.removeClass('show');
				setTimeout(function() {
					that.remove();
				}, 300);
			}
		});
	});

	$('.modal-backdrop[itemscope="aa-modal"]').waitUntilExists(function() {
		var that = $(this);
		that.on('click', function(e) {
			if (elemHasClass(e.target, 'modal-backdrop')) {
				that.removeClass('show');
				$(window).trigger('aaModalClosed');
				setTimeout(function() {
					that.css({'display': 'none'});
				}, 400);
			}
		});
	});


	/**
	 * ==================== Defaults End ======================
	 * 2/11/2016
	 */
	(function mainThemeAjaxScope() {

		var ajaxLoadPosts = function() {
			var page = 1,
				loading = true,
				$window = $(window),
				$content = $('#ajax-posts-loop');

			var loadPosts = function() {
				$(LOAD_LINE).empty();
				$(LOAD_LINE).fadeIn();
				var line = new ProgressBar.Line(LOAD_LINE, {color: _COLORS.purple});

				line.animate(0.2);

				$.ajax({
					url       : AJAXURL,
					type      : "POST",
					data      : {
						pageNumber      : page,
						action          : 'alice_ajax_posts'
					},
					dataType  : "html",
					beforeSend: function() {
						if (page !== 1)
							$content.append(Loaders.bouncingStatic);

						line.animate(0.5);
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
						line.animate(1);

						setTimeout(function() {
							$(LOAD_LINE).fadeOut();
						}, 500);

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


	/**
	 * =========================================================
	 * =========================================================
	 * ==================== Login Process ======================
	 * =========================================================
	 * =========================================================
	 */
	var loginAjaxHandler = function(clickLauncher, parentContainer) {
		$(clickLauncher).on('click', function(e) {

			var that = $(this),
				container = $(parentContainer),
				progressLine = container.find('[data-progress]').attr('id'),
				login = container.find('[name=login]'),
				pass = container.find('[name=pass]'),
				progress = $("#" + progressLine);

			progress.empty();
			progress.fadeIn();

			var line = new ProgressBar.Line("#" + progressLine, {color: _COLORS.red});
			line.animate(0.2);

			$.ajax({
				url       : AJAXURL,
				type      : "POST",
				data      : {
					action: "ajx20161223091233",
					login : login.val(),
					pass  : pass.val()
				},
				//dataType : "html",
				beforeSend: function() {
					line.animate(0.7);
				},
				success   : function(data) {
					container.find('.alert').remove();
					if (data) {
						switch (data) {
							case "wrong-pass":
								container.prepend(alertHolder('warning', 'Wrong pass'));
								setTimeout(function() {
									container.find('.alert').fadeOut();
								}, 4000);

								break;
							case "not-found":
								container.prepend(alertHolder('danger', 'User not found'));
								setTimeout(function() {
									container.find('.alert').fadeOut();
								}, 4000);

								break;
							case "success":
								container.prepend(alertHolder('success', 'Success'));
								setTimeout(function() {
									location.reload();
								}, 1000);

								break;
							default:
								console.log('unknown');
						}
					}
					line.animate(1);
					setTimeout(function() {
						progress.fadeOut();
					}, 500);
				},
				error     : function(jqXHR, textStatus, errorThrown) {
					alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
				}
			});
		});
	};
	loginAjaxHandler('#login-trigger', '#aa-loginform-container');

	/**
	 * =========================================================
	 * =========================================================
	 * ==================== Register Process ===================
	 * =========================================================
	 * =========================================================
	 */
	var registerAjaxHandler = function(clickLauncher, parentContainer) {
		$(clickLauncher).on('click', function(e) {
			var that = $(this),
				container = $(parentContainer),
				progressLine = container.find('[data-progress]').attr('id'),
				progress = $("#" + progressLine),
				first_name = container.find('[name=first_name]'),
				last_name = container.find('[name=last_name]'),
				email = container.find('[name=email]'),
				pass = container.find('[name=pass]'),
				pass_confirm = container.find('[name=pass_confirm]');

			progress.empty();
			progress.fadeIn();

			var line = new ProgressBar.Line("#" + progressLine, {color: _COLORS.red});
			line.animate(0.2);

			$.ajax({
				url       : AJAXURL,
				type      : "POST",
				data      : {
					action      : "ajx20161023111004",
					first_name  : first_name.val(),
					last_name   : last_name.val(),
					email       : email.val(),
					pass        : pass.val(),
					pass_confirm: pass_confirm.val()
				},
				//dataType : "html",
				beforeSend: function() {
					line.animate(0.7);
				},
				success   : function(data) {
					container.find('.alert').remove();
					// alicelfdev@gmail.com
					if(data) {
						console.log(data);

						switch (data) {
							case "user-exists":
								container.prepend(alertHolder('danger', 'User already exists'));
								setTimeout(function() {
									container.find('.alert').fadeOut();
								}, 4000);
								break;

							case "password-missmatch":
								container.prepend(alertHolder('warning', 'Password Mismatch or empty'));
								setTimeout(function() {
									container.find('.alert').fadeOut();
								}, 4000);
								break;

							case "email-error":
								container.prepend(alertHolder('warning', 'Not valid email'));
								setTimeout(function() {
									container.find('.alert').fadeOut();
								}, 4000);
								break;

							case "success":
								container.prepend(alertHolder('success', 'Check your email'));

								break;

							default :
								console.log("unknown");
						}

					}

					line.animate(1);
					setTimeout(function() {
						progress.fadeOut();
					}, 500);
				},
				error     : function(jqXHR, textStatus, errorThrown) {
					alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
				}
			});
		});
	};
	registerAjaxHandler('#register-trigger', '#aa-register-container');


	/**
	 * ===================================================================
	 * ==================== User Gallery Ajax Scope ======================
	 * ===================================================================
	 * 25.04.2016
	 */
	// Test media
	$('[data-trig]').on('click', function(e) {
		e.preventDefault();

		var that = $(this);
		var frame = wp.media({
			title   : 'Add your title here',
			frame   : 'post',
			multiple: true,
			library : {type: 'image'},
			button  : {text: 'Add Image'}
		});
		frame.open();

	});

});
/**
 * Base Theme Script ver 0.1.0
 */

var _BODY = document.body,
	_HTML = document.documentElement,
	_DOCUMENT_HEIGHT = Math.max(_BODY.scrollHeight, _BODY.offsetHeight,
		_HTML.clientHeight, _HTML.scrollHeight, _HTML.offsetHeight),
	_TOP_OFFSET = document.documentElement.scrollTop || document.body.scrollTop,
	_LEFT_OFFSET = document.documentElement.scrollLeft || document.body.scrollLeft;


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

};

jQuery(document).ready(function($) {

	$(window).on('scroll', function(){
		var _TOP_OFFSET = document.documentElement.scrollTop || document.body.scrollTop,
			header = $('header.site-header'),
			hh = header.height();
		_TOP_OFFSET > hh ? header.addClass('header-with-bg') : header.removeClass('header-with-bg');

	});

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


	//$(window).on('scroll',function(){
	//	if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
	//		console.log('sdfsdfs');
	//	}
	//});


});
+function($) {

	/**
	 * ==================== Popup ======================
	 * 29.04.2016
	 */
	$('.popup-holder').on('click', function(e) {

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
+function($) {
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
		$(relatedModal).trigger('aaModalOpened', [type, relatedModal]);

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
	 * ==================== Remove Modal ======================
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
				$(window).trigger('aaModalClosed');
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
			$(window).trigger('aaModalClosed');
		}, 300);
	});

	$(window).on('aaModalOpened', function(e) {
		$(_BODY).addClass('aa-modal-overlay');
	});

	$(window).on('aaModalClosed', function(e) {
		$(_BODY).removeClass('aa-modal-overlay');
	});

	//raizeModalEvent("#login-modal");
	//setTimeout(function(){
	//	detachModalEvent("#login-modal");
	//}, 3000);
	//$(window).on('aaModalOpened', function(e, type, relatedModal){
	//	console.log(e, type, relatedModal);
	//});
}(jQuery);