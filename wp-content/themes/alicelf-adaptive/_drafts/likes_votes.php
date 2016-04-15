<?php
/**
 * ======== do_action('likes_html', $post_id)  ==========
 * ======================================================
 * ==================== Likes html ======================
 * ======================================================
 * ============== Font Awesome marked ===================
 */
add_action( 'likes_html', 'aa_func_20160017090021', 10, 1 );
function aa_func_20160017090021( $post_id )
{
	?>
	<span class="likes <?php echo user_liked_this( $post_id ) ? 'active' : '' ?>" data-post-id="<?php echo $post_id ?>">
		<i class="fa fa-heart-o"></i>
		<span class="likes-counter"><?php echo post_like_count( $post_id ); ?>
		</span>
	</span>
	<?php
}

/**
 * ==================== Css ======================
 * 03.04.2016
 */
add_action('wp_head', 'aa_func_20160903110912');
function aa_func_20160903110912()
{
	?>
	<style type="text/css">span.likes{cursor:pointer}span.likes.active i{color:#00a68e}span.likes:hover i{color:#00a68e}</style>
	<?php
}

add_action('wp_footer', 'aa_func_20161003111058');
function aa_func_20161003111058()
{
	?>
	<script>
		jQuery(document).ready(function ($){
			var btn = $('span.likes');

				btn.on('click', function() {
					var that = $(this),
						postId = that.attr('data-post-id'),
						likesCount = that.find('.likes-counter'),
						likesNumber = parseInt(likesCount.text());

					that.find('i').removeClass('fa-heart-o');
					that.find('i').addClass('fa-spin fa-spinner');

					$.ajax({
						url       : AJAXURL, // must be existing
						type      : "POST",
						data      : {
							action : 'ajx20161216111200',
							post_id: postId
						},
						//dataType : "html",
						beforeSend: function() {
						},
						success   : function(data) {
							if (data === 'like') {
								that.addClass('active');
								likesNumber += 1;
								likesCount.text(likesNumber);
							} else {
								that.removeClass('active');
								likesNumber -= 1;
								likesCount.text(likesNumber);
							}
							that.find('i').addClass('fa-heart-o');
							that.find('i').removeClass('fa-spin fa-spinner');
						},
						error     : function(jqXHR, textStatus, errorThrown) {
							alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
						}
					});
				});

		});
	</script>
	<?php
}




// ================== Set Idenity for non logget ==================
add_action( 'init', 'aa_func_20163016103030' );
function aa_func_20163016103030()
{
	$user_hash = null;

	if ( ! is_user_logged_in() ) {
		$user_hash = sha1( time() );
		if ( ! isset( $_COOKIE[ 'current_user_hash' ] ) ) {
			setcookie( "current_user_hash", $user_hash, strtotime( '+30 days' ) );
		}
	}
}


/**
 * Get Current User Idenity
 *
 * @return null|string
 */
function aa_get_current_user_hash()
{
	$user_hash = null;
	if ( is_user_logged_in() ) {
		$user_hash = sha1( wp_get_current_user()->data->user_email );
	} else {
		if ( ! isset( $_COOKIE[ 'current_user_hash' ] ) ) {
			$user_hash = sha1( time() );
		} else {
			$user_hash = $_COOKIE[ 'current_user_hash' ];
		}

	}

	return $user_hash;
}

/**
 * Get post likes count
 *
 * @param $id
 *
 * @return int|mixed
 */
function post_like_count( $id )
{
	$meta       = get_post_meta( $id, 'likes_container', true );
	$array_meta = unserialize( $meta );

	if ( empty( $array_meta ) || ( count( $array_meta ) === 0 ) ) {
		$meta = 0;
	} else {
		$meta = count( unserialize( $meta ) );
	}

	return $meta;
}

/**
 * User liked this post?
 *
 * @param $post_id
 *
 * @return bool
 */
function user_liked_this( $post_id )
{
	$user_hash = aa_get_current_user_hash();
	$likes_arr = unserialize( get_post_meta( $post_id, 'likes_container', true ) );
	if ( is_array( $likes_arr ) ) {
		$position = array_search( $user_hash, $likes_arr );

		return $position !== false;
	}

	return false;
}

/**
 * =========================================================
 * =========================================================
 * ==================== Likes Process ======================
 * =========================================================
 * =========================================================
 */
add_action( 'wp_ajax_nopriv_ajx20161216111200', 'ajx20161216111200' );
add_action( 'wp_ajax_ajx20161216111200', 'ajx20161216111200' );
function ajx20161216111200()
{
	$user_hash = aa_get_current_user_hash();
	$post_id   = $_POST[ 'post_id' ];
	$likes_arr = [ ];
	// ================== First Like ==================
	if ( post_like_count( $post_id ) === 0 ) {
		$likes_arr[] = $user_hash;
		update_post_meta( $post_id, 'likes_container', serialize( $likes_arr ) );
		echo 'like';
	} else {

		$likes_arr = unserialize( get_post_meta( $post_id, 'likes_container', true ) );
		$position  = array_search( $user_hash, $likes_arr );
		// ================== Unlike ==================
		if ( $position !== false ) {
			unset( $likes_arr[ $position ] );
			update_post_meta( $post_id, 'likes_container', serialize( $likes_arr ) );
			echo 'unlike';
		} else {
			// ================== Add Like ==================
			$likes_arr[] = $user_hash;
			update_post_meta( $post_id, 'likes_container', serialize( $likes_arr ) );
			echo 'like';
		}

	}

	die;
}