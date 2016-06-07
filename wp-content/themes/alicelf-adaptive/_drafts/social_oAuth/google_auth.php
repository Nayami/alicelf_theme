<?php

use GuzzleHttp\Client;


// https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token=[YOUR_TOKEN]

if ( ! function_exists( 'google_vars' ) ) {
	function google_vars()
	{
		global $alicelf;

		return [
			'client_id'     => $alicelf[ 'google-api-client-id' ],
			'client_secret' => $alicelf[ 'google-api-client-secret' ],
			'redirect_uri'  => $alicelf[ 'google-api-redirect-url' ],
			'auth_url'      => $alicelf[ 'google-api-auth-url' ],
			'token_url'     => $alicelf[ 'google-api-token-url' ],
			'userinfo_url'  => $alicelf[ 'google-api-userinfo-url' ]
		];
	}
}

add_action( 'aa_afterbodystart', 'aa_func_20162003112035' );
function aa_func_20162003112035()
{
	$settings = google_vars();

	$scopes = [
		'https://www.googleapis.com/auth/userinfo.profile',
		'https://www.googleapis.com/auth/userinfo.email',
		'https://www.googleapis.com/auth/plus.me',
	];
	$scopes = implode(" ", $scopes);

	$get_params = [
		'client_id'     => $settings['client_id'],
		'redirect_uri'  => $settings['redirect_uri'],
		'response_type' => 'code',
		'scope'         => $scopes
	];
	$link       = $settings['auth_url'] . '?' . urldecode( http_build_query( $get_params ) );
	echo "<a class='btn btn-default' href='{$link}'>Google Auth</a>";

	if ( isset( $_GET[ 'code' ] ) ) {
		$post_params = [
			'client_id'     => $settings['client_id'],
			'client_secret' => $settings['client_secret'],
			'redirect_uri'  => $settings['redirect_uri'],
			'grant_type'    => 'authorization_code',
			'code'          => $_GET[ 'code' ]
		];

		$client    = new Client( [ 'verify' => false ] );
		$request   = $client->request( 'POST', $settings['token_url'], [ 'form_params' => $post_params ] );
		$tokenInfo = json_decode( $request->getBody(), true );

		if ( isset( $tokenInfo[ 'access_token' ] ) ) {
			$get_params[ 'access_token' ] = $tokenInfo[ 'access_token' ];

			$request = $client->get( $settings['userinfo_url'], [ 'query' => $get_params ] );

			$userInfo = json_decode( $request->getBody(), true );
			$userInfo[ 'access_token' ] = $get_params[ 'access_token' ];
			echo "<pre>";
			print_r( $userInfo );
			echo "</pre>";
		}

	}

}