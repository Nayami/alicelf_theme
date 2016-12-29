<?php

namespace Alicelf\Helpers;

use WP_Http;

class Helper {

	/**
	 * @return bool
	 */
	public static function isLocalhost()
	{
		return ( $_SERVER[ 'REMOTE_ADDR' ] === '127.0.0.1'
		         || $_SERVER[ 'REMOTE_ADDR' ] === 'localhost' )
		       || $_SERVER[ 'REMOTE_ADDR' ] === "::1"
			? true : false;
	}

	/**
	 * @param $string
	 * @param null $num_start
	 * @param null $num_end
	 *
	 * @return null|string
	 */
	public static function contentCutter( $string, $num_start = null, $num_end = null )
	{
		settype( $string, "string" );
		if ( is_int( $num_start ) && is_int( $num_end ) ) {
			$array_of_strings = explode( " ", $string );
			$sliced           = array_slice( $array_of_strings, $num_start, $num_end );
			$new_string       = implode( " ", $sliced );

			return $new_string;
		}

		return null;
	}

	/**
	 * @param $data
	 */
	public static function dumpAndDie( $data )
	{
		echo "<pre>";
		var_dump( $data );
		echo "</pre>";
		die;
	}

	/**
	 * @return string
	 */
	public static function browser()
	{
		if ( strpos( $_SERVER[ 'HTTP_USER_AGENT' ], 'MSIE' ) !== false
		     || strpos( $_SERVER[ 'HTTP_USER_AGENT' ], 'Trident' ) !== false
		) {
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

	/**
	 * Remove all cookies
	 */
	public static function flushCookies()
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

	/**
	 * ==================== Current Request Is ajax ? ======================
	 */
	public static function is_ajax()
	{
		return is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX;
	}

}