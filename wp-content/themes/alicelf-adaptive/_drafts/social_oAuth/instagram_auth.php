<?php
use GuzzleHttp\Client;

if ( ! function_exists( 'insta_vars' ) ) {
	function insta_vars()
	{
		global $alicelf;

		return [
			'client_id'     => $alicelf['instagram-api-client-id'],
			'client_secret' => $alicelf['instagram-api-client-secret'],
			'redirect_uri'  => $alicelf['instagram-api-redirect-url'],
			'auth_url'      => 'https://api.instagram.com/oauth/authorize/',
			'token_url'     => 'https://api.instagram.com/oauth/access_token'
		];

	}
}

//https://api.instagram.com/oauth/authorize/?client_id=CLIENT-ID&redirect_uri=REDIRECT-URI&response_type=code

add_action('aa_afterbodystart', 'aa_func_20164708014740');
function aa_func_20164708014740()
{
	$settings   = insta_vars();
	$get_params = [
		'client_id'     => $settings[ 'client_id' ],
		'redirect_uri'  => $settings[ 'redirect_uri' ],
		'response_type' => 'code',
	];
	$link       = $settings['auth_url'] . '?' . urldecode( http_build_query( $get_params ) );
	echo "<a class='btn btn-default' href='{$link}'>Instagramm auth</a>";

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

		echo "<pre>";
		print_r($tokenInfo);
		echo "</pre>";

	}

}