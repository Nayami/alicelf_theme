/**
 * Ajax dynamic theme script
 */

var SITE_URL = aa_ajax_var.site_url,
		AJAXURL = aa_ajax_var.ajax_url,
		THEME_URI = aa_ajax_var.template_uri,
		IMG_DIR = THEME_URI + '/img/';

jQuery(document).ready(function($) {

	var Loaders = {
		bouncingAbsolute : '<div id="loader-absolute"><div class="preview-area"><div class="spinner-jx"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>',
		bouncingStatic : '<div id="loader-static"><div class="preview-area"><div class="spinner-jx"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>',
		infiniteSpinner : '<div class="loader-backdrop"><div class="loader-infinite-spinner"><div class="lt"></div><div class="rt"></div><div class="lb"></div><div class="rb"></div></div></div>',
		svgLoader : '<img id="svg-loader-process" src="' + THEME_URI + '/svg/loader_svg.svg" width="40" alt="loadersvg"/>'

	};

	(function mainThemeAjaxScope() {

		var successMessage ="Your message has been successfully sended",
				errorMessage = "Fill Correct all required fields!",
				wrongCaptcha = "Fill Correct Captha",
				unknownError ="Something Wrong, try again later",
				alertHolder = function(itemClass, sendedMessage) {
					return "<div class='alert alert-" + itemClass + "'>" +
						"<button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
						"<span aria-hidden='true'>&times;</span>" +
						"</button><h3 class='text-center'>" + sendedMessage + "</h3></div>"
				};


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