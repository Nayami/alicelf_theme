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

/**
 * ==================== Initialization ======================
 * 21.07.2016
 */
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
	/**
	 * ==================== Donation payment process ======================
	 * 21.07.2016
	 */
	if(isset($_POST['donation_process_20163021033051'])) {
		$data = $_POST;
		$donation_name = $data['donation_name'];
	}
	/**
	 * ==================== Products Payment Process ======================
	 * 21.07.2016
	 */
	if(isset($_POST['paypal_checkout_20164820054847'])) {

		$data = $_POST;
		$product_data = $_SESSION['aa_products_session'];

		$payer = new \PayPal\Api\Payer();
		$payer->setPaymentMethod('paypal');

	}
}