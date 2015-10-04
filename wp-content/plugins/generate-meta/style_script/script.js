jQuery(document).ready(function ($){

	var SITE_URL = aa_generate_meta_var.site_url,
		THEME_URI = aa_generate_meta_var.template_uri;

	var Loaders = {
		bouncingAbsolute: '<div id="loader-absolute" class="labsolut"><div class="preview-area"><div class="spinner-jx"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>',
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


	$('.al-meta-img-container-wrap').each(function() {
		var thisCheckbox = $('input[type="checkbox"]', this);
		var thisLabel = $('label', this);
		thisCheckbox.on('change', function() {
			if ($(this).is(':checked')) {
				$(this).parent().append('<div class="image-curtain"><p>Image will be removed after saving post</p></div>');
			} else {
				$(this).parent().find('.image-curtain').remove();
			}
		});
	});

	$('.al-filemeta-upload-wrap').each(function() {
		$('input:file', this).on('change', function(e) {
			var file = this.files;
			$(this).parent().find('.uploaded-file-information').remove();
			$('.label-meta-upload-cp').after('<div class="uploaded-file-information">New Image: <b>' + file[0]['name'] + '</b></div>')
		});
	});


	/**
	 * Gallery uploader for cusom meta fields
	 */
	var doTheGallery = function() {

		var btn = $('.btn-trigger-gallery');

		$('.clear-gallery-bt').on('click', function(e) {
			e.preventDefault();
			var prnt = $(this).parent('.al-gal-holder').attr('id'), parentEm = $('#' + prnt);

			if (confirm('Are You sure wanna clear your gallery')) {
				parentEm.find('input:text').val('');
				parentEm.find('.al-gal-holder')
					.append('<div class="delete-message"><i class="change-mind">Return Gallery</i>Gallery will be deleted after saving post</div>');

				$('.change-mind').on('click', function() {
					var prntId = $(this).parents('.al-gal-holder').attr('id'), prntElem = $('#' + prntId);
					prntElem.find('input[type="text"]').val(prntElem.find('input[type="hidden"]').val());
					prntElem.find('.delete-message').remove();
				});
			}

		});


		$.each(btn, function() {
			var that = $(this), parentElemId = that.parent('.al-gal-holder').attr('id');

			that.on('click', function(e) {
				e.preventDefault();
				frame = wp.media({
					title   : 'Select your media',
					frame   : 'post',
					multiple: true, // set to false if you want only one image
					library : {type: 'image'},
					button  : {text: 'Add Image'}
				});

				frame.on('close', function(data) {
					var imageArray = [], parentBlock = $('#' + parentElemId);

					images = frame.state().get('selection');
					images.each(function(image) {
						imageArray.push(image.attributes.url); // want other attributes? Check the available ones with console.log(image.attributes);
					});

					parentBlock.find('input:text').val(imageArray.join(",")); // Adds all image URL's comma seperated to a text input

					var imgHldre = parentBlock.find('.gallery-existing-image-holder');
					if (imageArray.length > 0) {
						imgHldre.empty();
						for (var i = imageArray.length; i--;) {
							imgHldre.append("<div class='img-holder-gal'><img class='img-responsive' src='" + imageArray[i] + "'></div>");
						}
					}
				});
				frame.open();

			});
		});

	};
	doTheGallery();


	/**
	 * Repeater Metabox
	 * @param selector
	 */
	var repeaterMetaboxProcess = function(selector) {
		var elem = $(selector);

		$.each(elem, function() {
			var metabox = $(this),
				dataHandler = metabox.find('>[data-handler]'),
				iteratorsWrapper = metabox.find('.data-page-repeater'),
				section = iteratorsWrapper.find('>section:first-of-type'),
				sectionTemlate = metabox.find('[data-template]').attr('data-template'),
				submitRepeater = metabox.find('button[data-repeater-submit]'),
				postIdentificator = metabox.attr('data-post-id'),
				metaDescriptor = dataHandler.attr('name');

			// Add a section
			metabox.find('[data-add-section]').on('click', function(e) {
				e.preventDefault();
				iteratorsWrapper.append(sectionTemlate);
			});

			// Delete section (For appeared buttons)
			$('button[data-stored-template="btn-destroy"]').waitUntilExists(function() {
				$("button[data-destroy='destroy']").on('click', function(e) {
					e.preventDefault();
					var that = $(this), thatParent = that.parents('section');
					if(confirm('Are you sure?') !== false)
						thatParent.remove();
				});

			});
			// Delete section (For existing buttons)
			$("button[data-destroy='destroy']").on('click', function(e) {
				e.preventDefault();
				var that = $(this), thatParent = that.parents('section');
				if(confirm('Are you sure?') !== false)
					thatParent.remove();
			});


			// Submit Process
			submitRepeater.on('click', function(e) {
				e.preventDefault();
				var that = $(this),
					thatParentHolder = that.parents('.generate-repeater-metabox'),
					sectionsCollection = thatParentHolder.find('.data-page-repeater > section'),
					collector = {};

				// Each Section
				for (var i = 0; i < sectionsCollection.length; i++) {
					var cSection = sectionsCollection.eq(i),
						inputs = cSection.find('[data-input]'),
						sectionNameDescriptor = cSection.find('[data-section-name]'),
						sectionName = sectionNameDescriptor.val(),
						slug = sectionName.replace(' ', '_').toLowerCase();

					collector[slug] = {
						sectionName : sectionName
					};
					// Each item in section
					for (var j = 0; j < inputs.length; j++) {
						var thatInput = inputs.eq(j);

						collector[slug][thatInput.attr('data-input')] = {
							field_type: thatInput.attr('data-type'),
							value     : thatInput.val()
						};
					}
				}

				metabox.append(Loaders.bouncingAbsolute);
				$.ajax({
					url    : ajaxurl,
					type   : "POST",
					data   : {
						repeater_req : true,
						post_id      : postIdentificator,
						meta_key     : metaDescriptor,
						repeater_data: collector,
						action       : "aa_func_20152903032931"
					},
					success: function(data) {
						dataHandler.val(data);

						metabox.find('.labsolut').remove();
					},
					error  : function(jqXHR, textStatus, errorThrown) {
						$('#loader-static').remove();
						alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
					}
				});

			});

		});

	};
	repeaterMetaboxProcess('.generate-repeater-metabox');


});