<?php

/**
 * Delete notice for current user
 */
add_action( 'wp_ajax_ajx20150506060531', 'ajx20150506060531' );
function ajx20150506060531()
{
	if ( isset( $_POST[ 'aa_notice_descriptor' ] ) ) {
		$notice_option = get_option( $_POST[ 'aa_notice_descriptor' ] );
		$user          = $_POST[ 'aa_data_user' ];
		$notice_arr    = $notice_option[ $_POST[ 'aa_data_notice' ] ];

		$userisnotyet_excluded = array_search( $user, $notice_arr[ 'excluded_users' ] );

		// If user not found in array of excluded users
		if ( $userisnotyet_excluded === false ) {
			$notice_arr[ 'excluded_users' ][]            = $user;
			$notice_option[ $_POST[ 'aa_data_notice' ] ] = $notice_arr;
			update_option( $_POST[ 'aa_notice_descriptor' ], $notice_option );
		}
	}
	exit;
}

/**
 * Submit PayPal Credentials
 */
add_action( 'wp_ajax_ajx20150207050244', 'ajx20150207050244' );
function ajx20150207050244()
{
	global $aa_payment;
	$p = $_POST[ 'aa_pp_payment' ];
	if ( isset( $p ) ) {
		$options = $aa_payment->getOptions();
		$aa_payment->setOption( 'paypa_credentials', array(
				'email'     => $p[ 'email' ],
				'client_id' => $p[ 'client_id' ],
				'secret'    => $p[ 'secret' ]
			), true
		);
		echo "success";
		die;
	}
	die;
}