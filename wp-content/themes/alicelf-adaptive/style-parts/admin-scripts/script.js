jQuery(document).ready(function($) {

	var SITE_URL = aa_admin_ajax_var.site_url,
		THEME_URI = aa_admin_ajax_var.template_uri;

	var Loaders = {
		bouncingAbsolute: '<div id="loader-absolute"><div class="preview-area"><div class="spinner-jx"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>',
		bouncingStatic  : '<div id="loader-static"><div class="preview-area"><div class="spinner-jx"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>',
		infiniteSpinner : '<div class="loader-backdrop"><div class="loader-infinite-spinner"><div class="lt"></div><div class="rt"></div><div class="lb"></div><div class="rb"></div></div></div>',
		svgLoader       : '<img id="svg-loader-process" src="' + THEME_URI + '/svg/loader_svg.svg" width="40" alt="loadersvg"/>'

	};


	/**
	 *
	 * @param handler
	 * @param shouldRunHandlerOnce
	 * @param isChild
	 * @returns {*|jQuery|HTMLElement}
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

	// @Template Todo: Move to plugin custom posts
	/**
	 * Uploader for custom field of custom taxonomy of custom post
	 */
	$('.al-tax-custom-field button').on('click', function(e) {
		e.preventDefault();
		var that = $(this),
			parentElem = that.parents('.al-tax-custom-field'),
			textInput = parentElem.find('input:text'),
			imageChild = parentElem.find('img');

		var frame = wp.media({
			title   : 'Add your title here',
			frame   : 'post',
			multiple: false,
			library : {type: 'image'},
			button  : {text: 'Add Image'}
		});
		frame.on('close', function(data) {
			var imageArray = [],
				images = frame.state().get('selection');
			images.each(function(image) {
				imageArray.push(image.attributes.url);
			});
			textInput.val(imageArray.join(","));
			if (imageArray.length > 0) {
				imageChild.attr('src', imageArray);
			}
		});
		frame.open();
	});


	$('.invoke-conversion').on('click', function(e) {

		var that = $(this),
			container = $('#convert-tables-section'),
			selectEncoding = $('#select-encoding-conversion').val();

		if (confirm("Are you sure? (This can take a while)") !== false) {
			container.append(Loaders.bouncingStatic);
			$.ajax({
				url    : ajaxurl,
				type   : "POST",
				data   : {
					do_the_conversion: true,
					action           : 'aa_func_20150827030852',
					set_encoding : selectEncoding
				},
				success: function(data) {
					container.empty();
					container.append(data);
				},
				error  : function(jqXHR, textStatus, errorThrown) {
					$('#loader-static').remove();
					alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
				}
			});
		} else {
			return false;
		}
	});

});