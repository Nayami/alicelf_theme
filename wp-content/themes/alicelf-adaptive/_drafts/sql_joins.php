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
add_action('wp_ajax_nopriv_ajx20161009111025', 'ajx20161009111025');
add_action('wp_ajax_ajx20161009111025', 'ajx20161009111025');
function ajx20161009111025()
{
	global $wpdb;
	$current_date = time();
	$past_day     = strtotime( '-1 day', $current_date );
	$next_day     = strtotime( '+1 day', $current_date );

	$q = "SELECT {$wpdb->users}.ID, {$wpdb->users}.user_registered, {$wpdb->users}.subscription_plan, {$wpdb->users}.user_nicename,
		{$wpdb->usermeta}.user_id, {$wpdb->usermeta}.meta_key, {$wpdb->usermeta}.meta_value
		FROM {$wpdb->users}
		JOIN {$wpdb->usermeta}
		ON {$wpdb->users}.ID={$wpdb->usermeta}.user_id
		WHERE {$wpdb->users}.user_registered > {$past_day}
		AND {$wpdb->users}.user_registered < {$next_day}
		AND {$wpdb->users}.subscription_plan='Therapist / Counselor'
		AND LENGTH({$wpdb->users}.user_nicename) > 2";

	$result = $wpdb->get_results( $q, ARRAY_A );

	foreach ( $result as $item ) {
		$wpdb->delete( $wpdb->prefix . "users", [ 'ID' => $item[ 'ID' ] ] );
		$wpdb->delete( $wpdb->prefix . "usermeta", [ 'user_id' => $item[ 'ID' ] ] );
		$wpdb->delete( $wpdb->prefix . "bp_xprofile_data", [ 'user_id' => $item[ 'ID' ] ] );
	}
}

/**
 * ==================== Three tables joined ======================
 * 16.06.2016
 */
$results    = $wpdb->get_results(
	"SELECT {$wpdb->commentmeta}.comment_id, {$wpdb->commentmeta}.meta_key, {$wpdb->commentmeta}.meta_value,
			{$wpdb->comments}.comment_ID, {$wpdb->comments}.comment_post_ID, {$wpdb->comments}.comment_approved,
			{$wpdb->posts}.*
			FROM {$wpdb->commentmeta}
			JOIN {$wpdb->comments}
			ON {$wpdb->commentmeta}.comment_id={$wpdb->comments}.comment_ID
			JOIN {$wpdb->posts}
			ON {$wpdb->posts}.ID={$wpdb->comments}.comment_post_ID
			WHERE {$wpdb->comments}.comment_approved='1'
			AND {$wpdb->commentmeta}.meta_key='__rating'
			AND {$wpdb->posts}.post_status='publish'",
	ARRAY_A );