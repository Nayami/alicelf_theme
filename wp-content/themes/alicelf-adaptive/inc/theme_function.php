<?php

use Alicelf\Platform\MobileDetect;
use Alicelf\Helpers\Helper;
use Alicelf\Helpers\Arr;

if ( ! function_exists( 'forget_array_item' ) ) {
	/**
	 * @param $maybe_array
	 * @param $param
	 * @param bool $key
	 *
	 * @return mixed (new array)
	 */
	function forget_array_item( $maybe_array, $param, $key = false )
	{
		return Arr::forget( $maybe_array, $param, $key );
	}
}

if ( ! function_exists( 'is_localhost' ) ) {
	/**
	 * @return bool
	 */
	function is_localhost()
	{
		return Helper::isLocalhost();
	}
}

if ( ! function_exists( 'content_cutter' ) ) {
	/**
	 * Returns number of words
	 *
	 * @param $string
	 * @param null $num_start
	 * @param null $num_end
	 *
	 * @return null|string
	 */
	function content_cutter( $string, $num_start = null, $num_end = null )
	{
		return Helper::contentCutter( $string, $num_start, $num_end );
	}
}

if ( ! function_exists( 'dd' ) ) {
	/**
	 * Simply Output Data
	 *
	 * @param $data
	 */
	function dd( $data )
	{
		Helper::dumpAndDie( $data );
	}
}

if ( ! function_exists( 'trim_data' ) ) {
	/**
	 * Simple trim data
	 *
	 * @param $data
	 *
	 * @return string
	 */
	function trim_data( $data )
	{
		return trim( strip_tags( $data ) );
	}
}

if ( ! function_exists( 'aa_fetch_curl' ) ) {
	/**
	 * Regular curl or Wp curl
	 *
	 * @param $url
	 * @param int $timeout
	 *
	 * @return mixed|string
	 */
	function aa_fetch_curl( $url, $timeout = 20 )
	{
		return Helper::fetchCurl( $url, $timeout );
	}
}

if ( ! function_exists( 'aa_check_plugin' ) ) {
	/**
	 * Check enabled plugins
	 *
	 * @param $plugin
	 *
	 * @return bool
	 */
	function aa_check_plugin( $plugin )
	{
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		return is_plugin_active( $plugin );
	}
}

if ( ! function_exists( 'is_mobile_platform' ) ) {
	/**
	 * Check current platform is phone
	 *
	 * @return bool
	 */
	function is_mobile_platform()
	{
		$detect = new MobileDetect;
		if ( $detect->isMobile() )
			return true;

		return false;
	}
}

if ( ! function_exists( 'browser_info' ) ) {
	/**
	 * Get Browser Info
	 *
	 * @return string
	 */
	function browser_info()
	{
		return Helper::browser();
	}
}

if ( ! function_exists( 'aa_unset_cookies' ) ) {
	/**
	 * Unset All Cookies
	 */
	function aa_unset_cookies()
	{
		Helper::flushCookies();
	}
}

/**
 * Get Navigation
 */
function paged_navigation()
{
	if ( function_exists( 'wp_pagenavi' ) ) {
		wp_pagenavi();
	} else {
		echo "<div class='nav-previous'>" . previous_post_link( '&laquo; %link' ) . "</div><div class='nav-next'>" . next_post_link( '%link &raquo;' ) . "</div>";
	}
}

function al_tags_template()
{
	if ( get_the_tags() ) { ?>
		<div class="alert alert-info tags-info">Tags:
			<?php the_tags( "<i class='glyphicon glyphicon-tag'></i>",
				"<i class='glyphicon glyphicon-tag'></i>", "" ); ?>
		</div>
	<?php }
}

/**
 * Custom Search form
 *
 * @param bool $echo
 *
 * @return mixed|string|void
 */
function al_search_form( $echo = true )
{
	do_action( 'pre_get_search_form' );

	$search_form_template = locate_template( 'searchform.php' );
	if ( '' != $search_form_template ) {
		ob_start();
		require( $search_form_template );
		$form = ob_get_clean();
	} else {
		$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
				<div class="input-group">
				<span class="input-group-addon"><label>' . _x( 'Search for:', 'label' ) . '</label></span>
				<input type="search" class="form-control" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder' ) . '" value="' . get_search_query() . '" name="s" title="' . esc_attr_x( 'Search for:', 'label' ) . '" />
				<span class="input-group-addon"><input type="submit" value="' . esc_attr_x( 'Search', 'submit button' ) . '" /></span>
			</div></form>';
	}
	$result = apply_filters( 'get_search_form', $form );

	if ( null === $result ) {
		$result = $form;
	}

	if ( $echo ) {
		echo $result;
	} else {
		return $result;
	}
	return false;
}

/**
 * Get Brand
 */
function get_brand()
{
	global $alicelf;
	$output = null;
	if ( ! empty( $alicelf[ 'opt-logo' ][ 'url' ] ) ) {
		$output = "<a class='site-logo' href='" . get_site_url() . "'>";
		$output .= "<img alt='logo' title='" . esc_attr( get_bloginfo( 'name', 'display' ) ) . "' src='" . $alicelf[ 'opt-logo' ][ 'url' ] . "'>";
		$output .= "</a>";
	} else {
		$output = "<hgroup><h1 class='site-title'><a href='" . esc_url( home_url( '/' ) ) . "' title='" . esc_attr( get_bloginfo( 'name', 'display' ) ) . "' rel='home'>" . get_bloginfo( 'name' ) . "</a></h1>";
		$output .= "<h2 id='site-description' class='site-description'>" . get_bloginfo( 'description' ) . "</h2></hgroup>";
	}

	return $output;
}

// Header type 1
function get_header_first()
{
	?>
	<div class='clearfix top-line header-type-1'>
		<div class='ghostly-wrap'><?php echo get_brand() ?></div>
	</div>
	<div class='navigator-wrapper clearfix header-type-1'>
		<div class='ghostly-wrap'><?php get_template_part( 'main-navigator' ) ?></div>
	</div>
	<?php
}

// Header type 2
function get_header_second()
{
	?>
	<div class="ghostly-wrap header-type-2">
		<div class='col-sm-3 top-line'>
			<div class='row'><?php echo get_brand() ?></div>
		</div>
		<div class='col-sm-9 pos-static-tablet'>
			<div class="row">
				<div class="navigator-wrapper"><?php get_template_part( 'main-navigator' ) ?></div>
			</div>
		</div>
	</div>
	<?php
}


// Switch headers
function header_type()
{
	global $alicelf;
	$header_type = $alicelf[ 'opt-header-type' ];

	?>
	<div id="mobile-menu-trigger" class="hidden-on-desktop">
		<button type="button" class="tcon tcon-menu--xcross" aria-label="toggle menu">
			<span class="tcon-menu__lines" aria-hidden="true"></span>
			<span class="tcon-visuallyhidden">toggle menu</span>
		</button>
	</div>
	<?php

	switch ( $header_type ) {
		case 2 :
			get_header_second();
			break;
		default :
			get_header_first();
	}
}

// Social list
function aa_social_list( $classes = null )
{
	global $alicelf;
	$output = "<ul class='list-unstyled {$classes}'>";
	foreach ( $alicelf as $k => $v ) {
		if ( ! strstr( $k, 'social' ) )
			continue;
		$name = str_replace( 'opt-social-', '', $k );
		if ( ! empty( $v ) )
			$output .= "<li><a title='{$name}' href='{$v}'><i class='fa fa-{$name}'></i></a></li>";
	}
	$alicelf[ 'opt-check-rss' ] === '1' && $output .= "<li><a href='" . get_bloginfo( 'rss2_url' ) . "'><i class='fa fa-rss'></i></a></li>";

	return $output . "</ul>";
}

function form_process_to_send()
{
	// $regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
	if (
		isset( $_POST[ 'visitor_name' ], $_POST[ 'visitor_email' ], $_POST[ 'visitor_message' ] ) &&
		( ! empty( $_POST[ 'visitor_name' ] ) && ! empty( $_POST[ 'visitor_email' ] ) && ! empty( $_POST[ 'visitor_message' ] ) )
	) {

		$name      = trim_data( $_POST[ 'visitor_name' ] );
		$to        = trim_data( $_POST[ 'to_admin' ] );
		$sitename  = $_POST[ 'bloginfo_name_field' ];
		$subject   = 'Email From ' . $sitename . ' sended by ' . $name;
		$email     = trim_data( $_POST[ 'visitor_email' ] );
		$mail_body = trim_data( $_POST[ 'visitor_message' ] );

		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= "From: {$email}\r\n";
		$headers .= "Reply-To: {$email}\r\n";
		$headers .= "Return-Path: {$email}\r\n";
		$headers .= "\r\n";

		$message = "<!doctype html><html lang='en-US'><head><title>$subject</title></head><body><div id='message-container'><h2>$subject</h2><p>email: $email</p><p>Message: <br/></p><p>$mail_body</p></div></body></html>
	";
		mail( $to, $subject, $message, $headers );
		echo 'success';
	} else {
		echo "error";
	}
}


if ( ! function_exists( 'login_register_form' ) ) {
	function login_register_form(  )
	{
		?>
		<div class="clearfix" id="aa-login-register-forms">
			<div id="aa-login-container" class="col-sm-6 col-sm-offset-3">
				<h1 class="text-center">Login</h1>
				<form action="" method="POST">

					<div class="form-group">
						<label for="aa--username">Your Email:</label>
						<input type="email" name="aa-login-username" id="aa--username" class="form-control">
					</div>
					<div class="form-group">
						<label for="aa-password">Password:</label>
						<input type="password" name="aa-login-password" id="aa-password" class="form-control">
					</div>

					<div class="btn-group btn-group-justified" role="group">
						<div class="btn-group">
							<a href="<?php echo wp_registration_url() ?>" class="btn btn-default btn-sm">Registration</a>
						</div>
						<div class="btn-group">
							<a href="<?php echo wp_lostpassword_url() ?>" class="btn btn-default btn-sm">Forgot Password?</a>
						</div>
						<div class="btn-group">
							<button type="submit" class="btn btn-default btn-sm">Login</button>
						</div>
					</div>

				</form>

			</div>
		</div>
		<hr>
		<?php
	}
}
