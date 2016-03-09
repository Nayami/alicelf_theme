<?php
/**
 * ==================== Patients ======================
 * 09.03.2016
 */

add_action( 'wp_ajax_nopriv_ajx20164109104142', 'ajx20164109104142' );
add_action( 'wp_ajax_ajx20164109104142', 'ajx20164109104142' );
function ajx20164109104142()
{
	global $wpdb;
	$current_date = time();
	$past_day     = strtotime( '-1 day', $current_date );
	$next_day     = strtotime( '+1 day', $current_date );

	$q = "SELECT {$wpdb->users}.ID, {$wpdb->users}.user_registered, {$wpdb->users}.subscription_plan,
		{$wpdb->usermeta}.user_id, {$wpdb->usermeta}.meta_key, {$wpdb->usermeta}.meta_value
		FROM {$wpdb->users}
		JOIN {$wpdb->usermeta}
		ON {$wpdb->users}.ID={$wpdb->usermeta}.user_id
		WHERE {$wpdb->users}.user_registered > {$past_day}
		AND {$wpdb->users}.user_registered < {$next_day}
		AND {$wpdb->users}.subscription_plan='patient'";

	$result = $wpdb->get_results( $q, ARRAY_A );

	foreach ( $result as $item ) {
		$wpdb->delete( $wpdb->prefix . "users", [ 'ID' => $item[ 'ID' ] ] );
		$wpdb->delete( $wpdb->prefix . "usermeta", [ 'user_id' => $item[ 'ID' ] ] );
		$wpdb->delete( $wpdb->prefix . "bp_xprofile_data", [ 'user_id' => $item[ 'ID' ] ] );
	}
}

/**
 * ==================== Therapists ======================
 * 09.03.2016
 */
