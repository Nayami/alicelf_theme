<?php
$settings = google_vars();

$post_params = [
	'client_id'     => $settings[ 'client_id' ],
	'client_secret' => $settings[ 'client_secret' ],
	'redirect_uri'  => $settings[ 'redirect_uri' ],
	'grant_type'    => 'authorization_code',
	'code'          => $_GET[ 'code' ]
];

$curl = curl_init();
curl_setopt( $curl, CURLOPT_URL, $settings[ 'token_url' ] );
curl_setopt( $curl, CURLOPT_POST, 1 );
curl_setopt( $curl, CURLOPT_POSTFIELDS, urldecode( http_build_query( $post_params ) ) );
curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
$result = curl_exec( $curl );
curl_close( $curl );
$tokenInfo = json_decode( $result, true );