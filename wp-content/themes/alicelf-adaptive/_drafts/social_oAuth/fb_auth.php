<?php
use GuzzleHttp\Client;

// ============= Fb_vars =============
if ( ! function_exists( 'fb_vars' ) ) {
	function fb_vars()
	{
		global $alicef;

		return [
			'client_id'     => '1651075215180567',
			'client_secret' => '747b7b816b16144ea5608cd010f2f5ab',
			'redirect_uri'  => 'http://localhost/redux',
			'scope'         => 'email,public_profile,user_friends',
			'auth_url'      => 'https://www.facebook.com/dialog/oauth',
			'token_url'     => 'https://graph.facebook.com/v2.5/oauth/access_token',
			'me_url'        => 'https://graph.facebook.com/v2.5/me'
		];
	}
}

add_action( 'aa_afterbodystart', 'aa_func_20163106113113' );
function aa_func_20163106113113()
{
	$settings   = fb_vars();
	$get_params = [
		'client_id'    => $settings[ 'client_id' ],
		'redirect_uri' => $settings[ 'redirect_uri' ],
		'scope'        => $settings[ 'scope' ]
	];
	$link       = $settings[ 'auth_url' ] . '?' . urldecode( http_build_query( $get_params ) );

	echo "<a class='btn btn-default' href='{$link}'>Fb Auth</a>";
	if ( isset( $_GET[ 'code' ] ) ) {

		$post_params = [
			'client_id'     => $settings[ 'client_id' ],
			'response_type' => 'token',
			'redirect_uri'  => $settings[ 'redirect_uri' ],
			'client_secret' => $settings[ 'client_secret' ],
			'code'          => $_GET[ 'code' ]
		];

		$client  = new Client( [ 'verify' => false ] );
		$request = $client->get( $settings[ 'token_url' ],
			[ 'query' => $post_params ]
		);

		$tokenInfo = json_decode( $request->getBody(), true );

		if ( $tokenInfo[ 'access_token' ] ) {
			$request   = $client->get( $settings['me_url'], [
				'query' => [
					'access_token' => $tokenInfo[ 'access_token' ],
					'fields'       => 'id,first_name,last_name,picture.type(large),email,cover',
				]
			] );
			$small_pic = $client->get( $settings['me_url'], [
				'query' => [
					'access_token' => $tokenInfo[ 'access_token' ],
					'fields'       => 'picture',
				]
			] );
			echo "<pre>";
			print_r( json_decode( $request->getBody(), true ) );
			echo "</pre>";
			echo "<pre>";
			print_r( json_decode( $small_pic->getBody(), true ) );
			echo "</pre>";
		}

	}
}