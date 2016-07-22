<?php

// ============= Paypal_donations_form =============
if ( ! function_exists( 'paypal_donations_form' ) ) {
	function paypal_donations_form()
	{
		ob_start();
		global $aa_payment;
		?>
		<form action="" method="post">
			<div class="text-center img-center">
				<div class="space-x-10"></div>
				<img class="img-responsive" src="<?php echo $aa_payment->get_images_dir_url() ?>paypal-logo.png" alt="paypal-logo">
				<div class="space-x-10"></div>
			</div>

			<input type="hidden" name="donation_process_20163021033051" value="donation"/>
			<input placeholder="Describe your funds" value="Donation Name" type="text" name="donation_name" class="form-control">
			<input type="number" name="donation_price" placeholder="Donation Price" value="7" class="form-control">
			<hr>
			<div class="donation-footer text-right">
				<button type="submit" class="btn btn-primary btn-sm">Donate now!</button>
			</div>
		</form>
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