<?php

/**
 * Load ajax posts
 */
add_action( 'wp_ajax_nopriv_alice_ajax_posts', 'alice_ajax_posts' );
add_action( 'wp_ajax_alice_ajax_posts', 'alice_ajax_posts' );
function alice_ajax_posts()
{
	$numPosts = get_option( 'posts_per_page' );
	$page     = ( isset( $_POST[ 'pageNumber' ] ) ) ? $_POST[ 'pageNumber' ] : 0;
	query_posts( array(
		'posts_per_page' => $numPosts,
		'paged'          => $page
	) );
	get_template_part( 'templates/tpl-index-loop' );
	die();
}

/**
 * Contact form
 */
add_action( 'wp_ajax_nopriv_aa_contact_form', 'aa_contact_form' );
add_action( 'wp_ajax_aa_contact_form', 'aa_contact_form' );
function aa_contact_form()
{
	if ( isset( $_POST[ 'alice_form_with_captcha' ] ) ) {
		require_once ABSPATH . '/wp-content/plugins/custom_captcha/securimage/securimage.php';
		$securimage = new Securimage();
		if ( $securimage->check( $_POST[ 'captcha_code' ] ) == false ) {
			echo "error captcha";
			die();
		} else {
			form_process_to_send();
			die();
		}
	}

	if ( isset( $_POST[ 'alice_default_form' ] ) ) {
		form_process_to_send();
		die();
	}
}

/**
 * Convert tables to utf8 encoding
 */
add_action( 'wp_ajax_aa_func_20150827030852', 'aa_func_20150827030852' );
function aa_func_20150827030852()
{
	if ( isset( $_POST[ 'do_the_conversion' ] ) ) {
		global $wpdb;
		$set_encoding = $_POST['set_encoding'];
		$tables   = $wpdb->get_results( "SHOW TABLES" );
		$method   = "Tables_in_" . $wpdb->dbname;
		$messages = "";
		foreach ( $tables as $table ) {
			$wpdb->query( "ALTER TABLE {$table->$method} DEFAULT CHARACTER SET utf8 COLLATE {$set_encoding};" );
			$wpdb->query( "ALTER TABLE {$table->$method} CONVERT TO CHARACTER SET utf8 COLLATE {$set_encoding};" );
			$messages .= "Table " . $table->$method . " has been converted to {$set_encoding}<br>";
		}
		$messages .= "<hr><div class='alert alert-success'>Conversion complete.</div>";
		echo $messages;
		die;
	}
}