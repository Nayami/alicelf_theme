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


	/**
	 * ==================================================================
	 * ==================================================================
	 * ==================== Backend global uploads ======================
	 * ==================================================================
	 * ==================================================================
	 * 15.04.2016
	 */
	$('button[data-fragment="aa-upload-backend"]').on('click', function(e) {

		e.preventDefault();
		var that = $(this),
			thatParent = that.parents('.backend-uploader-handler'),
			uploadMultiple = thatParent.attr('data-type') === 'multiple',
			imageContainer = thatParent.find('.image-holder'),
			textInput = thatParent.find(':hidden'),
			inputValues = [],
			newHtmlValues = "";

		if (textInput.attr('value') !== undefined && textInput.attr('value') !== "")
			inputValues = JSON.parse(textInput.attr('value'));

		var frame = wp.media({
			title   : 'Add your title here',
			frame   : 'post',
			multiple: uploadMultiple,
			library : {type: 'image'},
			button  : {text: 'Add Image'}
		});
		frame.on('close', function(data) {
			var imagesObj = [],
				images = frame.state().get('selection');

			images.each(function(image) {
				var singleImage = image.attributes;

				imagesObj.push(
					{
						id : singleImage.id,
						url: singleImage.url
					}
				);
			});

			if (imagesObj.length > 0) {
				imageContainer.empty();
				if (uploadMultiple) {
					var newArrayCombine;

					// @todo: combine 2 arrays
					if (inputValues.length > 0)
						newArrayCombine = imagesObj.concat(inputValues);
					else
						newArrayCombine = imagesObj;

					newArrayCombine.forEach(function(elemIndex) {
						newHtmlValues += "<div class='img-wrap'>";
						newHtmlValues += "<img src='" + elemIndex.url + "'>";
						newHtmlValues += "<i data-id-src='" + elemIndex.id + "' class='fa fa-remove'></i>";
						newHtmlValues += "</div>";
					});
					imageContainer.append(newHtmlValues);
					textInput.attr('value', JSON.stringify(newArrayCombine));

				} else {
					newHtmlValues = "<div class='img-wrap'>";
					newHtmlValues += "<img src='" + imagesObj[0].url + "'>";
					newHtmlValues += "<i data-id-src='" + imagesObj[0].id + "' class='fa fa-remove'></i>";
					newHtmlValues += "</div>";

					textInput.attr('value', JSON.stringify(imagesObj[0]));
					imageContainer.append(newHtmlValues);
				}

			}


		});
		frame.open();
	});

	/**
	 * ==================== Remove Image from input ======================
	 * 15.04.2016
	 */
	$('.backend-uploader-handler').find('i.fa-remove').waitUntilExists(function() {
		var that = $(this);
		that.on('click', function(e) {

			var parent = that.parents('.backend-uploader-handler'),
				relaredIdSrc = that.attr('data-id-src'),
				wrapper = that.parents('.img-wrap'),
				input = parent.find(':hidden'),
				parentType = parent.attr('data-type');

			if (parentType === 'single') {
				input.attr('value', '');
				wrapper.fadeOut(200, function() {
					wrapper.remove();
				});
			} else {
				// Multiple removements
				var inputValues = JSON.parse(input.attr('value'));

				if (inputValues.length > 0) {
					for (var obj in inputValues) {
						if (parseInt(relaredIdSrc) === inputValues[obj].id) {
							inputValues.splice(obj, 1);
						}
					}

					input.attr('value', JSON.stringify(inputValues));
					wrapper.fadeOut(200, function() {
						wrapper.remove();
					});

				}

			}
			// End ifelse

		});
	});
	/**
	 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	 * ==================== Backend Default Uploads fields END ======================
	 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	 */



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
					set_encoding     : selectEncoding
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