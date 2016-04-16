<?php
/**
 * Template name: Profile
 */
get_header();

if ( is_user_logged_in() ) {
	// Render user info if logget in
	// @TODO: check if user activation key is acceptable and make else

	do_action('aa_userprofile_action');// @TODO: render user stuff

} else {
	login_register_form();
}

get_footer();

?>