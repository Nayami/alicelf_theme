<?php
/*
Plugin Name: AA Payment
Plugin URI: http://vzazerkalie.com/portf/
Description: AA Payment plugin - Upload and Activate.
Author: Alicelf
Version: 0.0.1
Author URI: http://vzazerkalie.com/portf/
*/

require_once( 'AAPaymentInitial.php' );
include( 'ajax.php' );

$aa_payment = new AAPaymentInitial("AA Payment");

// Wrap to wp_loaded for get user and set him notice
add_action( 'plugins_loaded', 'aa_func_20150406060442', 1 );
function aa_func_20150406060442()
{
	global $aa_payment;
	// Notice content. Can has hrefs or other html
	$aa_payment->setPluginNotice( 'aa_payment_welcome', "Plugin {$aa_payment->_plugin_name} is enabled" );

	// Set Options
	$paypal_credentials = array(
		'email'=>'',
		'client_id'=>'',
		'secret'=>''
	);
	$aa_payment->setOption('paypa_credentials', $paypal_credentials);
}

// Change Plugin title
add_filter( 'aa_payment_basetitle', 'aa_func_20150506060501', 10, 1 );
function aa_func_20150506060501( $title )
{
	$title = $title .= " (test mode)";
	return $title;
}

/**
 * Plugin page content
 */
add_action( 'aa_payment_content', 'aa_func_20150506060514' );
function aa_func_20150506060514()
{
	global $aa_payment;
	?>
	<form action="" method="post">
		<div class="clearfix">
<?php
	echo "<pre>";
	print_r($aa_payment->getOptions());
	echo "</pre>";
?>	</div>
		<button type="submit" class="button button-primary">Save Options</button>
	</form>
	<?php
}