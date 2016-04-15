<?php
/**
 * Template name: Profile
 */
get_header();

if ( is_user_logged_in() ) {
	// Render user info if logget in
	$user      = new WP_User( get_current_user_id() );
	$user_meta = get_user_meta( $user->ID );
	// @TODO: check if user activation key is acceptable and make else
	do_action('aa_userprofile_action', $user, $user_meta);

	// @TODO: render user stuff

} else {
	login_register_form();
}

get_footer();

?>