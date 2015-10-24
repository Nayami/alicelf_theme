/**
 * Define the globals
 */
var SITE_URL = aa_ajax_var.site_url,
	AJAXURL = aa_ajax_var.ajax_url,
	THEME_URI = aa_ajax_var.template_uri,
	IMG_DIR = THEME_URI + '/img/';

var Loaders = {
	bouncingAbsolute : '<div id="loader-absolute" class="loader-absolute"><div class="preview-area"><div class="spinner-jx"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>',
	bouncingStatic : '<div id="loader-static"><div class="preview-area"><div class="spinner-jx"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>',
	infiniteSpinner : '<div class="loader-backdrop"><div class="loader-infinite-spinner"><div class="lt"></div><div class="rt"></div><div class="lb"></div><div class="rb"></div></div></div>'
};

var alertHolder = function(itemClass, sendedMessage) {
	return "<div class='alert alert-" + itemClass + "'>" +
		"<button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
		"<span aria-hidden='true'>&times;</span>" +
		"</button><h3 class='text-center'>" + sendedMessage + "</h3></div>"
};

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
						action              : 'ajx20151209121256'
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

	/**
	 * ==================== Main Form Social Credentials Submittion ======================
	 * 24.10.2015
	 */
	var formSubmittion = function(){
		var container = $('#social-form-20152424112452'),
			submitButton = container.find(':submit'),
			appId = $('#facebook-app-id');

		submitButton.on('click', function(e){
			e.preventDefault();

			container.find('.alert').remove();
			container.prepend(Loaders.bouncingAbsolute);

			$.ajax({
				url : ajaxurl,
				type : "POST",
				data : {
					action : "ajx20153324113320",
					facebook : {
						appId : appId.val()
					}
				},
				//dataType : "html",
				beforeSend : function(){},
				success : function(data) {
					container.find('.loader-absolute').remove();
					if(data === "success") {
						container.prepend(alertHolder('success', 'Your credentials has been saved'));
					}
				},
				error     : function(jqXHR, textStatus, errorThrown) {
					alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
				}
			});

		});

	};
	formSubmittion();

});