<?php
/*
Plugin Name: AA Payment
Plugin URI: http://www.upwork.com/fl/olegtsibulnik
Description: AA Payment plugin - Upload and Activate. PHP version 5.4+ required
Author: Alicelf WebArtisan
Version: 1.0.1
Author URI: http://www.upwork.com/fl/olegtsibulnik
*/

// Dependencies
require_once( 'AAPluginInitial.php' );
$aa_payment = new AAPluginInitial( "AA Payment", null, null, null, 99 );
// Subpage
$aa_payment->addSubpage( 'Donation Forms' );

require_once( 'vendor/autoload.php' );

// Wrap to wp_loaded for get user and set him notice
add_action( 'plugins_loaded', 'aa_func_20150406060442', 1 );
function aa_func_20150406060442()
{
	global $aa_payment;
	// Notice content. Can has hrefs or other html
	$aa_payment->setPluginNotice( 'aa_payment_welcome', "Plugin {$aa_payment->_plugin_name} is enabled" );

	$aa_payment->setOption( 'paypa_credentials',
		[
			'email'        => '',
			'client_id'    => '',
			'secret'       => '',
			'redirect_url' => '',
		]
	);
}

// Change Plugin title
add_filter( 'aa_payment_basetitle', 'aa_func_20150506060501', 10, 1 );
function aa_func_20150506060501( $title )
{
	$title .= " (payment gateway credentials)";
	return $title;
}

/**
 * Plugin page content
 */
add_action( 'aa_payment_content', 'aa_func_20150506060514' );
function aa_func_20150506060514()
{
	global $aa_payment;
	$opt = $aa_payment->getOptions( 'paypa_credentials' );
	?>
	<h3>PayPal Credentials</h3>
	<form action="" method="post" class="aa-pluginsloader-holder">
		<div class="clearfix">
			<table class="table table-striped table-bordered">
				<tr>
					<th><label for="paypal-email">PayPal Email</label></th>
					<td><input value="<?php echo $opt[ 'email' ] ?>" class="form-control" type="email" placeholder="PayPal Email" name="aa_pp_payment[paypal_email]" id="paypal-email"></td>
				</tr>
				<tr>
					<th><label for="paypal-client-id">PayPal Client ID</label></th>
					<td><input value="<?php echo $opt[ 'client_id' ] ?>" class="form-control" type="text" placeholder="PayPal Client ID" name="aa_pp_payment[paypal_client_id]" id="paypal-client-id"></td>
				</tr>
				<tr>
					<th><label for="paypal-secret">PayPal Secret</label></th>
					<td><input value="<?php echo $opt[ 'secret' ] ?>" class="form-control" placeholder="PayPal Secret" type="text" name="aa_pp_payment[paypal_secret]" id="paypal-secret"></td>
				</tr>
			</table>
		</div>
		<button id="paypal-credentials-submit" type="submit" class="button button-primary">Save Options</button>
	</form>
	<?php
}

