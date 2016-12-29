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
	?>
	<div class="ghostly-wrap">
		<div class="col-sm-6">
			<?php echo do_shortcode('[aa_login_form_shortcode]'); ?>
		</div>
		<div class="col-sm-6">
			<?php echo do_shortcode('[aa_registrationform_shortcode]'); ?>
		</div>
	</div>
	<hr>
	<?php
}

get_footer();

?>