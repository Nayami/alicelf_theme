<?php
add_action( 'wp_loaded', 'aa_func_20163615043638', 1 );
function aa_func_20163615043638()
{
	/**
	 * ==================== Login Form ======================
	 * 15.04.2016
	 */
	if ( isset( $_POST[ 'aa-login-username' ] ) ) {
		$data  = $_POST;
		$email = $data[ 'aa-login-username' ];
		$pass  = $data[ 'aa-login-password' ];

		if ( get_user_by( 'email', $email ) ) {
			$user = get_user_by( 'email', $email )->data;
			// User successfully logget in
			if ( wp_check_password( $pass, $user->user_pass, $user->ID ) ) {

				wp_set_auth_cookie( $user->ID );

				$_SESSION[ 'aa_alert_messages' ][ "successfully_login" ] = [
					'type'    => "success",
					'message' => "You successfully logget in"
				];

			} else {
				// Wrong password
				$_SESSION[ 'aa_alert_messages' ][ 'successfully_login' ] = [
					'type'    => 'warning',
					'message' => 'Wrong password'
				];
			}
		} else {
			// User with this email isn't exists
			$_SESSION[ 'aa_alert_messages' ][ 'successfully_login' ] = [
				'type'    => 'danger',
				'message' => 'Wrong Email'
			];

		}
		wp_redirect( $_SERVER[ 'HTTP_REFERER' ] );
		die;
	}
}