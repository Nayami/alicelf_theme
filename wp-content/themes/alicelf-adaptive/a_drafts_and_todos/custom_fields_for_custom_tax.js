jQuery(document).ready(function ($){

	var doTheUploadMediaForCustomTaxonony = function() {
		var btnHolder = $('.uploader-for-media-image-cat-prod');

		btnHolder.on('click', function(e) {
			e.preventDefault();
			var prntId = $(this).parent('.upl_media_custom_box').attr('id'),
				actualParentNodeEleMedia = $('#' + prntId);
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
				actualParentNodeEleMedia.find('input:text').val(imageArray.join(","));
				if (imageArray.length > 0) {
					actualParentNodeEleMedia.find('.img-holder-for-this').empty();
					actualParentNodeEleMedia.find('.img-holder-for-this').append('<img src="' + imageArray + '">');
				}
			});
			frame.open();

		});
	};
	doTheUploadMediaForCustomTaxonony();

});