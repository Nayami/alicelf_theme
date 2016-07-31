<?php

// ============= Paypal_donations_form =============
if ( ! function_exists( 'paypal_donations_form' ) ) {
	function paypal_donations_form()
	{
		ob_start();
		global $aa_payment;
		// $aa_payment->get_images_dir_url()
		?>

		<?php
		return ob_get_clean();
	}
}




/**
 * ==================== Shortcodes definition ======================
 * 21.07.2016
 */
add_action('wp_loaded', 'aa_func_20163321033351');
function aa_func_20163321033351()
{
	add_shortcode( 'paypal_donations_form', 'paypal_donations_form' );
}