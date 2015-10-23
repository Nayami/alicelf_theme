<?php

add_filter( 'the_excerpt', 'aa_append_share_button' );
function aa_append_share_button( $excerpt )
{
	global $post;
	$fea_image  = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
	$link       = get_permalink( $post->ID );
	$title      = get_the_title( $post->ID );
	$descr      = content_cutter( strip_tags( get_the_content( $post->ID ) ), 0, 30 ) . "...";
	$attributes = "href='{$link}' data-image='{$fea_image}' data-title='{$title}' data-desc='{$descr}'";
	$share_btn  = "<a {$attributes} class='fb_share btn btn-default'><i class='fa fa-share'></i><i class='fa fa-facebook'></i></a>";

	$excerpt .= $share_btn;

	return $excerpt;
}

add_action( 'after_theme_footer', 'aa_plugin_social_init', 21 );
function aa_plugin_social_init()
{
	?>
	<script>
		jQuery(document).ready(function($) {

			(function(d, debug) {
				var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
				if (d.getElementById(id)) { return; }
				js = d.createElement('script');
				js.id = id;
				js.async = true;
				js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
				ref.parentNode.insertBefore(js, ref);
			}(document, /*debug*/ false));

			function postToFeed(title, desc, url, image) {
				var noimage = '<?php echo get_template_directory_uri() ?>/img/alicelf-brand.png';
				if (image.length === 0) {
					image = noimage;
				}
				var obj = {
					method     : 'feed',
					link       : url,
					picture    : image,
					name       : title,
					description: desc
				};

				function callback(response) {
					// Cancel : null
					// Close  : undefined
//					console.log(response);
				}

				FB.ui(obj, callback);
			}


			var fbShareBtn = $('.fb_share');

			if (fbShareBtn.length > 0) {
				$.each(fbShareBtn, function() {
					var that = $(this);
					that.on('click', function(e) {
						e.preventDefault();

						var title = that.attr('data-title'),
							desc = that.attr('data-desc'),
							url = that.attr('href'),
							image = that.attr('data-image');

						postToFeed(title, desc, url, image);

						return false;
					})
				});
			}

		});
	</script>
	<?php
}