<?php
/**
 * is_localhost
 * Content cutter
 * Fetch CUrl
 * Paged Navigation
 * Tags template
 * Custom Search Form
 * Check Plugin aa_check_plugin( $plugin ) aa_check_plugin( 'custom_captcha/custom_captcha.php' );
 * Dump and die _dd($data)
 * Get browser info aa_browser_info()
 * Unset All Cookies aa_unset_cookies()
 * Detect Mobile device aa_is_mobile_platform()
 */

if ( ! function_exists( 'is_localhost' ) ) {
	function is_localhost()
	{
		return ( $_SERVER[ 'REMOTE_ADDR' ] === '127.0.0.1'
		         || $_SERVER[ 'REMOTE_ADDR' ] === 'localhost' )
		         || $_SERVER[ 'REMOTE_ADDR' ] === "::1"
			? true : false;
	}
}

if ( ! function_exists( 'content_cutter' ) ) {
	function content_cutter( $string, $num_start = null, $num_end = null )
	{
		settype( $string, "string" );
		if ( is_int( $num_start ) && is_int( $num_end ) ) {
			$array_of_strings = explode( " ", $string );
			$sliced           = array_slice( $array_of_strings, $num_start, $num_end );
			$new_string       = implode( " ", $sliced );

			return $new_string;
		}

		return "num_start or num_end must be Integer";
	}
}

// Simple trim
function trimData( $data )
{
	return trim( strip_tags( $data ) );
}

if ( ! function_exists( 'aa_fetch_curl' ) ) {
	/* cURL fetch*/
	function aa_fetch_curl( $url )
	{
		if ( is_callable( 'curl_init' ) ) {
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 20 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
//		set headers if need
//		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
//			'Content-type: text/xml'
//		) );
			$feedData = curl_exec( $ch );
			curl_close( $ch );
			//If not then use file_get_contents
		} elseif ( ini_get( 'allow_url_fopen' ) == 1 || ini_get( 'allow_url_fopen' ) === true ) {
			$feedData = file_get_contents( $url );
			//Or else use the WP HTTP AP
		} else {
			if ( ! class_exists( 'WP_Http' ) )
				include_once( ABSPATH . WPINC . '/class-http.php' );
			$request  = new WP_Http;
			$result   = $request->request( $url );
			$feedData = $result[ 'body' ];
		}

		return $feedData;
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
 */
function al_search_form( $echo = true )
{
	do_action( 'pre_get_search_form' );

	$format = current_theme_supports( 'html5', 'search-form' ) ? 'html5' : 'xhtml';
	$format = apply_filters( 'search_form_format', $format );

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
}

/**
 * Check plugins
 */
if ( ! function_exists( 'aa_check_plugin' ) ) {
	function aa_check_plugin( $plugin )
	{
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		return is_plugin_active( $plugin );
	}
}

/**
 * Show globals
 */
if ( ! function_exists( 'show_globals' ) ) {
	function show_globals()
	{
		global $alicelf;
		echo "<pre>";
		print_r( $alicelf );
		echo "</pre>";
	}
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
		<div class="ghostly-wrap social-list">
			<div class="col-sm-12"><?php echo aa_social_list( 'list-inline pull-right' ) ?></div>
		</div>
		<div class='ghostly-wrap hidden-on-phones'><?php echo get_brand() ?></div>
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
		<div class='col-sm-3 hidden-on-phones top-line'>
			<div class='row'><?php echo get_brand() ?></div>
		</div>
		<div class='col-sm-9'>
			<div class="row">
				<div class="ghostly-wrap social-list"><?php echo aa_social_list( 'list-inline pull-right' ) ?></div>
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

		$name      = trimData( $_POST[ 'visitor_name' ] );
		$to        = trimData( $_POST[ 'to_admin' ] );
		$sitename  = $_POST[ 'bloginfo_name_field' ];
		$subject   = 'Email From ' . $sitename . ' sended by ' . $name;
		$email     = trimData( $_POST[ 'visitor_email' ] );
		$mail_body = trimData( $_POST[ 'visitor_message' ] );

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

if ( ! function_exists( '_dd' ) ) {
	function _dd( $data )
	{
		echo "<pre>";
		print_r( $data );
		echo "</pre>";
		die;
	}
}

/**
 * Get Browser Info
 */
if ( ! function_exists( 'aa_browser_info' ) ) {
	function aa_browser_info()
	{
		if ( strpos( $_SERVER[ 'HTTP_USER_AGENT' ], 'MSIE' ) !== false || strpos( $_SERVER[ 'HTTP_USER_AGENT' ], 'Trident' ) !== false ) {
			$browser = 'ugly-iexplorer';
		} elseif ( strpos( $_SERVER[ 'HTTP_USER_AGENT' ], 'Chrome' ) !== false ) {
			$browser = 'google-chrome';
		} elseif ( strpos( $_SERVER[ 'HTTP_USER_AGENT' ], 'Firefox' ) !== false ) {
			$browser = 'mozilla-firefox';
		} elseif ( strpos( $_SERVER[ 'HTTP_USER_AGENT' ], 'Opera' ) !== false ) {
			$browser = 'opera';
		} elseif ( strpos( $_SERVER[ 'HTTP_USER_AGENT' ], 'Safari' ) !== false ) {
			$browser = 'apple-safari';
		} else {
			$browser = 'unknown-browser';
		}

		return $browser;
	}
}

/**
 * Unset All Cookies
 */

if ( ! function_exists( 'aa_unset_cookies' ) ) {
	function aa_unset_cookies()
	{
		if ( isset( $_SERVER[ 'HTTP_COOKIE' ] ) ) {
			$cookies = explode( ';', $_SERVER[ 'HTTP_COOKIE' ] );
			foreach ( $cookies as $cookie ) {
				$parts = explode( '=', $cookie );
				$name  = trim( $parts[ 0 ] );
				setcookie( $name, '', time() - 1000 );
				setcookie( $name, '', time() - 1000, '/' );
			}
		}
	}
}

if ( ! function_exists( 'aa_is_mobile_platform' ) ) {
	/**
	 * @return bool
	 */
	function aa_is_mobile_platform()
	{
		$detect = new Mobile_Detect;
		if ( $detect->isMobile() )
			return true;

		return false;
	}
}