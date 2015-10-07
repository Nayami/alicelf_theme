/**
 * Define the globals
 */
var SITE_URL = aa_ajax_var.site_url,
	AJAXURL = aa_ajax_var.ajax_url,
	THEME_URI = aa_ajax_var.template_uri,
	IMG_DIR = THEME_URI + '/img/';

jQuery(document).ready(function($) {

	var processRemoveNotice = function() {
		var container = $('.aa-plugin-notice-container');

		$.each(container, function() {
			var that = $(this),
				buttonTrigger = that.find('button'),
				dataUser = that.attr('data-current-user'),
				dataNotice = that.attr('id'),
				dataNoticeDescriptor = that.attr('data-plugin-notice');

			buttonTrigger.on('click', function(e) {
				$.ajax({
					url       : ajaxurl,
					type      : "POST",
					data      : {
						aa_notice_descriptor: dataNoticeDescriptor,
						aa_data_user        : dataUser,
						aa_data_notice      : dataNotice,
						action              : 'ajx20150506060531'
					},
					//dataType : "html",
					beforeSend: function() {
					},
					success   : function(data) {
						console.log(data);
					},
					error     : function(jqXHR, textStatus, errorThrown) {
						alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
					}
				});

			});

		});

	};
	processRemoveNotice();

});