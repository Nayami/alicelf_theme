<?php
/**
 * Template name: Profile
 */
get_header();

if ( is_user_logged_in() ) {
	// Render user info if logget in
	$user      = new WP_User( get_current_user_id() );
	$user_meta = get_user_meta( $user->ID );

	// @TODO: render user stuff
	// @TODO: check if email exists

} else {
	login_register_form();
}

get_footer();

?>