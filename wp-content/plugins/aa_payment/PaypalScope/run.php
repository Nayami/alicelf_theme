<?php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

// ============= Get paypal credentials =============
if ( ! function_exists( 'get_paypal_credentials' ) ) {
	function get_paypal_credentials()
	{
		global $aa_payment;

		return $aa_payment->getOptions( 'paypa_credentials' );
	}
}
$paypal_credentials = get_paypal_credentials();

$aa_paypal = new ApiContext(
	new OAuthTokenCredential(
		$paypal_credentials[ 'client_id' ],
		$paypal_credentials[ 'secret' ]
	)
);


/**
 * ==================== Checkout Action ======================
 * 20.07.2016
 */
add_action('wp_loaded', 'aa_func_20164820054804');
function aa_func_20164820054804()
{
	if(isset($_POST['paypal_checkout_20164820054847'])) {

		$data = $_POST;

	}
}