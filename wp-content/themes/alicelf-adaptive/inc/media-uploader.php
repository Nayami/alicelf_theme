<?php
/**
 * ==================== Init WpEnque ======================
 * In frontend and for profile only
 * 29.05.2016
 */
add_action( 'wp_head', 'aa_func_20164829084834' );
function aa_func_20164829084834()
{
	if ( aa_is_profile( get_the_ID() ) ) {
		wp_enqueue_media();
	}
}

add_filter( 'media_view_strings', 'aa_func_20160031110053', 30, 1 );
function aa_func_20160031110053( $strings )
{
	if ( ! is_admin() ) {
		unset( $strings[ 'createGalleryTitle' ] );
		unset( $strings[ 'insertFromUrlTitle' ] );
	}

	return $strings;
}

//function custom_media_upload_tab_name( $tabs ) {
//	$newtab = array( 'tab_slug' => 'Your Tab Name' );
//	return array_merge( $tabs, $newtab );
//}
//add_filter( 'media_upload_tabs', 'custom_media_upload_tab_name' );
//function custom_media_upload_tab_content() {
//	// Add you content here.
//}
//add_action( 'media_upload_tab_slug', 'custom_media_upload_tab_content' );
/**
 * ==================== Add filter for non admins ======================
 * 29.05.2016
 */
add_filter( 'ajax_query_attachments_args', 'aa_func_20161529091520', 10, 1 );
function aa_func_20161529091520( $query )
{
	if ( ! current_user_can( 'manage_options' ) ) {
		$query[ 'author' ] = get_current_user_id();
	}

	return $query;
}

// @TODO: create button container with config options
if ( ! function_exists( 'aa_native_wp_mediabutton' ) ) {
	function aa_native_wp_mediabutton( $config = [ ] )
	{
		?>
		<a href="" class="btn btn-sm btn-default" data-trig="glrtrig">Trigger</a>
		<?php
	}
}