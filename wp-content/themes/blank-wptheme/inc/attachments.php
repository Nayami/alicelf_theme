<?php

use Alicelf\Helpers\AmAttachment;

if ( ! function_exists( 'aa_get_attachment' ) ) {

	/**
	 * @param $id
	 * @param bool $meta
	 *
	 * @return array
	 */
	function aa_get_attachment( $id, $meta = false )
	{
		return AmAttachment::get_attachment($id, $meta);
	}
}


if ( ! function_exists( 'aa_get_userallfiles' ) ) {
	/**
	 * @param $user_id
	 *
	 * @return array|bool
	 */
	function aa_get_userallfiles( $user_id )
	{
		if ( ! is_numeric( $user_id ) )
			return false;

		global $wpdb;
		$files   = [ ];
		$results = $wpdb->get_results(
			"SELECT ID, post_title, post_author FROM {$wpdb->posts}
			WHERE post_author={$user_id} AND post_type='attachment'" );

		if ( ! empty( $results ) ) {
			foreach ( $results as $result ) {
				$files [] = [
					'_id'     => $result->ID,
					'_author' => $result->post_author,
					'_title'  => $result->post_title,
					'_url'    => wp_get_attachment_url( $result->ID ),
					'_thumb'  => wp_get_attachment_image_src( $result->ID )
				];
			}
		}

		return $files;
	}
}