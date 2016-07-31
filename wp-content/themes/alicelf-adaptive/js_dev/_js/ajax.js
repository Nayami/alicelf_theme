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

		var successMessage = "Your message has been successfully sended",
			errorMessage = "Fill Correct all required fields!",
			wrongCaptcha = "Fill Correct Captha",
			unknownError = "Something Wrong, try again later";

		var formContactProcess = function(act) {

			$('form.alice-ajax-contact-form').on('submit', function() {
				var that = $(this),
					method = that.attr('method'),
					formData = {
						action: act
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