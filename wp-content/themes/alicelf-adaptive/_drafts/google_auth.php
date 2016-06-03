<?php

use GuzzleHttp\Client;

add_action( 'aa_afterbodystart', 'aa_func_20162003112035' );
function aa_func_20162003112035()
{
	global $alicelf;
	$client_id     = $alicelf[ 'google-api-client-id' ];
	$client_secret = $alicelf[ 'google-api-client-secret' ];
	$redirect_uri  = $alicelf[ 'google-api-redirect-url' ];
	$auth_url      = $alicelf[ 'google-api-auth-url' ];
	$token_url     = $alicelf[ 'google-api-token-url' ];
	$userinfo_url  = $alicelf[ 'google-api-userinfo-url' ];

	$get_params = [
		'client_id'     => $client_id,
		'redirect_uri'  => $redirect_uri,
		'response_type' => 'code',
		'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
	];
	$link       = $auth_url . '?' . urldecode( http_build_query( $get_params ) );
	echo "<a class='btn btn-default' href='{$link}'>Google Auth</a>";

	if ( isset( $_GET[ 'code' ] ) ) {
		$post_params = [
			'client_id'     => $client_id,
			'client_secret' => $client_secret,
			'redirect_uri'  => $redirect_uri,
			'grant_type'    => 'authorization_code',
			'code'          => $_GET[ 'code' ]
		];
		$tokenInfo   = null;

//		$curl = curl_init();
//		curl_setopt($curl, CURLOPT_URL, $token_url);
//		curl_setopt($curl, CURLOPT_POST, 1);
//		curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($post_params)));
//		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//		$result = curl_exec($curl);
//		curl_close($curl);
//		$tokenInfo = json_decode($result, true);

		$client    = new Client( [ 'verify' => false ] );
		$request   = $client->request( 'POST', $token_url, [ 'form_params' => $post_params ] );
		$tokenInfo = json_decode( $request->getBody(), true );

		if ( isset( $tokenInfo[ 'access_token' ] ) ) {
			$get_params[ 'access_token' ] = $tokenInfo[ 'access_token' ];

			$request = $client->get( $userinfo_url, [ 'query' => $get_params ] );

			$userInfo = json_decode( $request->getBody(), true );
			echo "<pre>";
			print_r( $userInfo );
			echo "</pre>";
		}

	}

}