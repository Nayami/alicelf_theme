<?php
/*
Plugin Name: AA Social
Plugin URI: http://vzazerkalie.com/portf/
Description: Alicelf AA Social plugin - Upload and Activate.
Author: Alicelf
Version: 2.0
Author URI: http://vzazerkalie.com/portf/
*/

$plugin_temp_vars = array(
	'facebook_app_id'       => '1651075215180567',
	'facebook_app_token'    => '1651075215180567|MfENeTLsrBxAGgGunabspN0tal0',
	'facebook_app_secret'   => '747b7b816b16144ea5608cd010f2f5ab',
);

include_once( 'helpers_and_processors.php' );
//@Template Todo: setup vars for further includes
require_once( 'facebook_sharer.php' );
require_once( 'AAPluginInitialStart.php' );
include( 'ajax.php' );
// $name $pagetitle $menutitle $menuslug $position
$aa_plugin_social = new AAPluginInitialStart();

add_action('admin_notices', 'aa_func_20154113024145');
function aa_func_20154113024145()
{
	global $aa_plugin_social;

}
// Wrap to wp_loaded for get user and set him notice
add_action( 'plugins_loaded', 'aa_func_20155206095228', 1 );
function aa_func_20155206095228()
{
	global $aa_plugin_social;
	// Notice content. Can has hrefs or other html
	$aa_plugin_social->setPluginNotice( 'aa_plugin_welcome', 'Plugin is enabled' );
	// $aa_plugin_social->setPluginNotice('some_another_notice', 'some notice content');
	$aa_plugin_social->setOption('facebook_credentials', array());
}

// Change Plugin title
add_filter( 'aa_plugin_basetitle', 'aa_func_20153606023600', 10, 1 );
function aa_func_20153606023600( $title )
{
	return "Socials Plugin";
}

/**
 * Plugin page content
 */
add_action( 'aa_plugin_content', 'aa_func_20152413112415' );
function aa_func_20152413112415()
{
	global $aa_plugin_social;
	$fb = $aa_plugin_social->getOptions('facebook_credentials');
	?>
	<form id="social-form-20152424112452" action="" method="post">
		<label for="facebook-app-id">Facebook App Id:</label>
		<input value="<?php echo $fb['app_id'] ?>" placeholder="Facebook App Id" type="text" id="facebook-app-id">
		<button type="submit" class="button button-primary">Save Options</button>
	</form>
	<?php
}