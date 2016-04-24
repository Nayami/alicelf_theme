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

/**
 * ==================== Update user settings ======================
 * 24.04.2016
 */
add_action( 'wp_loaded', 'aa_func_20165024035015' );
function aa_func_20165024035015()
{
	if ( isset( $_POST[ 'aa-edit-mypersonalinfo' ] ) ) {

		$data    = $_POST;
		$user_id = get_current_user_id();
		unset( $data[ 'aa-edit-mypersonalinfo' ] );

		if ( ! empty( $data[ 'pass' ] ) ) {
			if ( $data[ 'pass' ] !== $data[ 'pass_confirm' ] ) {
				$_SESSION[ 'aa_alert_messages' ][ 'pass_not_matches' ] = [
					'type'    => 'danger',
					'message' => 'Password and confirmation not matches!'
				];
				wp_redirect( $_SERVER[ 'HTTP_REFERER' ] );
				die;
			} else {
				wp_set_password( $data[ 'pass' ], $user_id );
				wp_set_auth_cookie( $user_id );
			}
		}
		unset( $data[ 'pass' ] );
		unset( $data[ 'pass_confirm' ] );

		foreach ( $data as $item_k => $item_v ) {
			update_user_meta( $user_id, $item_k, $item_v );
		}
		$_SESSION[ 'aa_alert_messages' ][ 'settings_updated' ] = [
			'type'    => 'success',
			'message' => 'Your settings is updated'
		];
		wp_redirect( $_SERVER[ 'HTTP_REFERER' ] );
		die;
	}
}