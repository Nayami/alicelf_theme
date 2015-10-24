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
	function aa_fetch_curl( $url, $timeout = null )
	{
		$tm = $timeout !== null ? $timeout : 10;
		if ( is_callable( 'curl_init' ) ) {
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_TIMEOUT, $tm );
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

//add_action('after_theme_footer', 'aa_func_20155324055302',25);
function aa_func_20155324055302()
{
	$app_id       = "1651075215180567";
	$my_url       = "http://vzazerkalie.com/?p=17";
	$app_secret   = "47b7b816b16144ea5608cd010f2f5ab";
	$access_token = "CAAXdpOeywxcBABTl9ATh71Omm2ZBfZB42ImWJm0RETSnpevXcWJ1UsW5HjLY4JJZBYyNNs8xvsea2Ww7ZCKrLSRZBZAy71kp8kUcN3IL6lQyhfFyHZCvZCxOTeXhtBVX3XE5IIZChsROlX70rahZCGjpnS8B48IicikaUkLV1sl4gMSkQjRW3Ug3A0a43k8kooiZBj7MEZBHMyOTIVcxZCvgLNSez";

	$url = "https://graph.facebook.com/" .
	       "fql?q=SELECT+user_id+FROM+url_like+WHERE+user_id=me()+and url='{$my_url}'" .
	       "&access_token=" . $access_token;

	$somevar = aa_fetch_curl( $url );

	echo "<pre>";
	print_r( $somevar );
	echo "</pre>";
}

/**
 * Add Fb Root to site
 * Define the app
 */
add_action( 'aa_afterbodystart', 'aa_func_20155923115930', 1 );
function aa_func_20155923115930()
{
	?>
	<div id="fb-root"></div><?php
}

add_action( 'aa_afterbodystart', 'aa_fbrootinitiator', 2 );
function aa_fbrootinitiator()
{
	global $aa_plugin_social;
	$fb = $aa_plugin_social->getOptions( 'facebook_credentials' );
	?>
	<script>
		var isLoaded = false;
		window.fbAsyncInit = function() {

			FB.init({
				appId  : "<?php echo $fb['app_id'] ?>",
				version: 'v2.5',
				status : true,
				cookie : true,
				xfbml  : true
			});

		};

		(function(d, debug) {
			var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			if (d.getElementById(id)) {
				return;
			}
			js = d.createElement('script');
			js.id = id;
			js.async = true;
			js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
			ref.parentNode.insertBefore(js, ref);
		}(document, /*debug*/ false));

		isLoaded = true;
	</script>
	<?php
}