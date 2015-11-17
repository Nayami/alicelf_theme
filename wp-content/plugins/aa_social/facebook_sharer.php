<?php

add_filter( 'the_excerpt', 'aa_append_share_button' );
function aa_append_share_button( $excerpt )
{
	global $post;

	$access_token = "CAAXdpOeywxcBAPv10LoQU4vF9EgN9ObG91lxrfW5z6tpmufyJbMvUvXbK5wV5amPt1UAv32ZAKdZAhVcW5EjnlmGb2bNketbupGQzzotQ18YXQOZC4MXnFVfETjhUgJXfxkKBfID6LLxDNNh7mOrpfT8hJonAK9bISGv2fEj7tqiLnIBj5RZAr7eKi8YNFAVGAZCKdnEpBGbLNsJdz0qU";

	$fea_image = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
	$link      = get_permalink( $post->ID );

	$title      = get_the_title( $post->ID );
	$descr      = content_cutter( strip_tags( get_the_content( $post->ID ) ), 0, 30 ) . "...";
	$attributes = "href='{$link}' data-image='{$fea_image}' data-title='{$title}' data-desc='{$descr}'";

	// curl to info
	$fb_post_info = json_decode( aa_fetch_curl( "https://api.facebook.com/method/links.getStats?urls={$link}&format=json", 15 ) );
	$share_like   = $fb_post_info[ 0 ];
	$share_count  = $share_like->share_count;
	$like_count   = $share_like->like_count;

	$uri_id = json_decode( aa_fetch_curl( "https://graph.facebook.com/v2.1/?id=" . $link . "&fields=og_object.fields(id)&access_token=" . $access_token ) );

	$share_btn = "<a {$attributes} class='fb_share btn btn-default'><i class='fa fa-share'></i><i class='fa fa-facebook'></i>{$share_count}</a>";

	$like_btn = "<a data-og-object-id='{$uri_id->og_object->id}' class='fb-like btn btn-default aa-btn-like'
				data-href='{$link}'
        data-action='like'
        data-show-faces='true'><i class='fa fa-thumbs-up'></i><span class='likes-count'>{$like_count}</span></a>";

	$excerpt .= $share_btn . $like_btn;

	return $excerpt;
}

add_action( 'after_theme_footer', 'aa_plugin_social_init', 21 );
function aa_plugin_social_init()
{
	?>
	<script>
		jQuery(document).ready(function($) {

			/**
			 * ==================== Wait Until Exists ======================
			 * 25.10.2015
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

			/**
			 * ==================== Wait For Variable Processor ======================
			 * 25.10.2015
			 */
//			var waiterForFBinitial = setInterval(function(){
//				if(isLoaded === true) {
//					clearInterval(waiterForFBinitial);
//					launchLikeprocessor();
//				}
//			},250);


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


			var fbLikeBtn = $('.fb-like');

			if (fbLikeBtn.length > 0) {
				fbLikeBtn.on('click', function(e) {
					e.preventDefault();
					var that = $(this),
						link = that.attr('data-href'),
						thatCountLikes = that.find('.likes-count');

					that.addClass('in-progress');
					that.prepend("<i class='fa fa-spinner fa-spin'></i>");

					FB.api(
						"/me/og.likes",
						"POST", {"object": link},
						function(response) {
							if (response) {
								if (response.error) {
//									console.log(response);
									switch (response.error.code) {
										case 2500 :
											// @Todo: do the redirect
											console.log("you need to be logget in facebook");
											break;
										case 3501 :

											//@Todo update like count after unlike
//											var objectId = that.attr('data-og-object-id');
//											FB.api(
//												that.attr('data-og-object-id') + "/likes",
//												'GET',
//												function(response) {
//													var userId = response.data[0].id,
//														req = userId + "_" + objectId + "/likes";
//
//													FB.api(req, "DELETE",
//														function(r) {
//															console.log(r);
//														}
//													);
//
//												}
//											);


											break;
										default :
											console.log("unknown error");
									}

								} else {
									thatCountLikes.html(parseInt(thatCountLikes.html()) + 1);
								}
								that.removeClass('in-progress');
								that.find('.fa-spinner').remove();
							}
						}
					);
				});

				/**
				 * ==================== Check Likes ======================
				 * 24.10.2015
				 */
//				var launchLikeprocessor = function() {
//					FB.getLoginStatus(function(response) {
//						if (response.status === "connected") {
//
//							$.each(fbLikeBtn, function() {
//								var that = $(this),
//									link = that.attr('data-href');
//								FB.api(
//									"/me/og.likes",{"object" : link},
//									function(response) {
//										if (response.data.length > 0){
//											that.prepend('<i data-object-id="'+response.data[0].id+'" class="fa fa-remove"></i>');
//											that.addClass('active-like-by-current-user');
//										}
//									}
//								);
//							});
//						}
//					});
//				};
			}

		});
	</script>
	<?php
}