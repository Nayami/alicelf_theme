<?php

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
 * Add Fb Root to site
 * Define the app
 */
add_action('aa_afterbodystart', 'aa_func_20155923115930');
function aa_func_20155923115930()
{
	?><div id="fb-root"></div><?php
}
add_action( 'after_theme_footer', 'aa_fbrootinitiator', 20 );
function aa_fbrootinitiator()
{
	global $plugin_temp_vars;
	?>
	<script>
		$.ajaxSetup({cache: false});
		$.getScript('//connect.facebook.net/en_US/sdk.js', function() {
			FB.init({
				appId  : "<?php echo $plugin_temp_vars["facebook_app_id"] ?>",
				status: true, cookie: true, xfbml: true,
			});
//			console.log(FB);
		});
	</script>
	<?php
}