<?php


if ( ! function_exists( 'mailchimp_vars' ) ) {
	function mailchimp_vars()
	{
		global $alicelf;
		return [
			'api_key'          => $alicelf[ 'mailchimp-api-key' ],
			'client_id'        => $alicelf[ 'mailchimp-client_id' ],
			'client_secret'    => $alicelf[ 'mailchimp-client_secret' ],
			'list_id'          => $alicelf[ 'mailchimp-list-id' ],
			'redirect_uri'     => $alicelf[ 'mailchimp-redirect_uri' ],
			'authorize_uri'    => 'https://login.mailchimp.com/oauth2/authorize',
			'access_token_uri' => 'https://login.mailchimp.com/oauth2/token',
			'metadata'         => 'https://login.mailchimp.com/oauth2/metadata',
		];

	}
}

function syncMailchimp($data) {
	$settings = mailchimp_vars();
	$apiKey = $settings['api_key'];
	$listId = $settings['list_id'];

	$memberId = md5(strtolower($data['email']));
	$dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
	$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

	$json = json_encode([
		'email_address' => $data['email'],
		'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
		'merge_fields'  => [
			'FNAME'     => $data['firstname'],
			'LNAME'     => $data['lastname']
		]
	]);

	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

	$result = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	return $result;
}
//5fac64ac54a7dce698a24a3f08da938a
add_action( 'wp_loaded', 'aa_func_20163707033756' );
function aa_func_20163707033756()
{
	if ( isset( $_POST[ 'mc-email' ] ) ) {
		$data = [
			'email'     => $_POST[ 'mc-email' ],
			'status'    => 'subscribed',
			'firstname' => 'john',
			'lastname'  => 'doe'
		];

		echo "<pre>";
		print_r( syncMailchimp( $data ) );
		echo "</pre>";
	}
}
add_action( 'aa_afterbodystart', 'aa_func_20164708014740' );
function aa_func_20164708014740()
{
	?>
	<div class="ghostly-wrap">
		<form role="form" method="POST" id="subscribe">
			<input type="email" name="mc-email" placeholder="you@yourself.com" class="form-control">
			<button type="submit" class="btn btn-default btn-sm">Subscribe</button>
		</form>
	</div>
	<?php

}