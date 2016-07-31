<?php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

/**
 * ==================== Initialization ======================
 * 21.07.2016
 */
$aa_paypal = new ApiContext(
	new OAuthTokenCredential(
		PP_CLIENT_ID,
		PP_CLIENT_SECRET
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
		$product_data = $_SESSION['aa_products_session'];

		$payer = new \PayPal\Api\Payer();
		$payer->setPaymentMethod('paypal');

	}
}