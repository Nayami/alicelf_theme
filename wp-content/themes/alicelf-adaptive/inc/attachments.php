<?php

/**
 * ==================== Attachments ======================
 * 17.04.2016
 */
use Alicelf\Helpers\Helper;

if ( ! function_exists( 'aa_delete_attachment' ) ) {
	/**
	 * Delete Attachment record and unlink related files
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	function aa_delete_attachment( $id )
	{
		return Helper::deleteAttachment( $id );
	}
}

if ( ! function_exists( 'upload_user_file' ) ) {
	/**
	 *
	 * Handle Upload single file from frontend and returns id or false
	 *
	 * @param array $file
	 *
	 * @return bool|int identifier attachment
	 */
	function upload_user_file( $file = array() )
	{
		require_once( ABSPATH . 'wp-admin/includes/admin.php' );
		$file_return = wp_handle_upload( $file, array( 'test_form' => false ) );
		if ( isset( $file_return[ 'error' ] ) || isset( $file_return[ 'upload_error_handler' ] ) ) {
			return false;
		} else {
			$filename = $file_return[ 'file' ];

			$attachment    = array(
				'post_mime_type' => $file_return[ 'type' ],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
				'guid'           => $file_return[ 'url' ]
			);
			$attachment_id = wp_insert_attachment( $attachment, $file_return[ 'url' ] );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );
			if ( 0 < intval( $attachment_id ) ) {
				return $attachment_id;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'upload_frontend_files' ) ) {
	/**
	 *
	 * Handle Uploads from frontend and returns array of uploaded id's or false
	 *
	 * @param int $size 50kb for default
	 * @param array $allowed_types
	 * @param bool $ajax
	 *
	 * @return array|bool
	 */
	function upload_frontend_files( $size = 50000, $allowed_types = [ 'image/png', 'image/jpeg' ], $ajax = false )
	{
		$ids              = [ ];
		$filetypes_string = implode( ', ', $allowed_types );

		if ( ! empty( $_FILES ) ) {

			foreach ( $_FILES as $file ) {
				if ( is_array( $file ) ) {

					if ( array_search( $file[ 'type' ], $allowed_types ) !== false ) {

						if ( $file[ 'size' ] > $size ) {
							$_SESSION[ 'aa_alert_messages' ][ "filesize-error" ] = [
								'type'    => "danger",
								'message' => "The File is to large"
							];
							wp_redirect( $_SERVER[ 'HTTP_REFERER' ] );
							die;
						} else {
							$attachment_id = upload_user_file( $file );
							$ids[]         = $attachment_id;
						}

					} else {
						$_SESSION[ 'aa_alert_messages' ][ "filetype-error" ] = [
							'type'    => "warning",
							'message' => "Notice: Wrong filetype. (You should use {$filetypes_string})"
						];

						wp_redirect( $_SERVER[ 'HTTP_REFERER' ] );
						die;
					}

				}
			}
		}

		return $ids;
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