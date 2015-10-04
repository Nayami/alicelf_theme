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
	'facebook_app_id'       => '790092147756606',
	'facebook_app_token'    => '790092147756606|KJDAyRtS9JbQADwfq_2QKMggRdg',
	'facebook_app_secret'   => 'f5bfcccade71c0bed60e3d4cd619b2c9',
);

include_once( 'helpers_and_processors.php' );
//@Template Todo: setup vars for further includes
require_once( 'facebook_sharer.php' );
require_once( 'AAPluginInitialStart.php' );
include( 'ajax.php' );
// $name $pagetitle $menutitle $menuslug $position
$aa_plugin = new AAPluginInitialStart();

add_action('admin_notices', 'aa_func_20154113024145');
function aa_func_20154113024145()
{
	global $aa_plugin;
}
// Wrap to wp_loaded for get user and set him notice
add_action( 'plugins_loaded', 'aa_func_20155206095228', 1 );
function aa_func_20155206095228()
{
	global $aa_plugin;
	// Notice content. Can has hrefs or other html
	$aa_plugin->setPluginNotice( 'aa_plugin_welcome', 'Plugin is enabled' );
//	$aa_plugin->setPluginNotice('some_another_notice', 'some notice content');
}

// Change Plugin title
add_filter( 'aa_plugin_basetitle', 'aa_func_20153606023600', 10, 1 );
function aa_func_20153606023600( $title )
{
	return $title;
}

/**
 * Plugin page content
 */
add_action( 'aa_plugin_content', 'aa_func_20152413112415' );
function aa_func_20152413112415()
{
	?>
	<form action="" method="post">
		<?php //@Template Todo: do the form stuff
		?>
		<button type="submit" class="button button-primary">Save Options</button>
	</form>
	<?php
}