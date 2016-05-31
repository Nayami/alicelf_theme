<?php

// @TODO: Create fabric fields class

if ( ! function_exists( 'get_menu_loc_by_id' ) ) {
	function get_menu_loc_by_id( $nav_menu_selected_id )
	{
		$_menu           = wp_get_nav_menu_object( $nav_menu_selected_id )->term_id;
		$theme_locations = get_nav_menu_locations();

		return array_search( $_menu, $theme_locations );
	}
}

add_action( 'admin_head', 'aa_func_20165429115424' );
function aa_func_20165429115424()
{
	$screen = get_current_screen();
	if ( $screen->id === 'nav-menus' )
		wp_enqueue_media();
}

add_action( 'aa_custom_menu_edit_before', 'aa_func_20163629113604', 10, 2 );
function aa_func_20163629113604( $item_id, $item )
{
	global $nav_menu_selected_id;
	$value = get_post_meta( $item_id, '_menu_item_custom', true );

	if ( get_menu_loc_by_id( $nav_menu_selected_id ) === 'primary' ) {
		?>
		<p class="upload-menu-item upload-menu-item-wide">
			<label for="upload-menu-item-<?php echo $item_id; ?>">
				<?php _e( 'Menu Image' ); ?><br/>
				<?php
				$image = null;
				if ( ! empty( $value ) ) {
					$item = json_decode( $value );
					$image .= "<div class='img-wrap'>";
					$image .= "<img src='{$item->url}'><i data-id-src='{$item->id}' class='fa fa-remove'></i>";
					$image .= "</div>";
				}
				echo "<div class='backend-uploader-handler' data-type='single'>";
				echo "<div class='image-holder'>{$image}</div>";
				echo "<button data-fragment='aa-upload-backend' class='button button-small'>Change Image</button>";
				echo "<input type='hidden' name='aa-menu-item-custom[{$item_id}]' value='{$value}'>";
				echo "</div>";
				?>
			</label>
		</p>
		<?php
	}

}

add_filter( 'aa_menu_afrer_link_open', 'aa_func_20161629121611', 10, 1 );
function aa_func_20161629121611( $item )
{
	$value = get_post_meta( $item, '_menu_item_custom', true );

	if ( ! empty( $value ) ) {
		$v = json_decode( $value );

		return "<img src='{$v->url}'>";
	}

	return null;
}

/**
 * ==================== Update menu item values ======================
 * 29.05.2016
 */
add_action( 'wp_update_nav_menu_item', 'aa_func_20163329103314', 10, 3 );
function aa_func_20163329103314( $menu_id, $menu_item_db_id, $args )
{
	if ( isset( $_REQUEST[ 'aa-menu-item-custom' ] ) ) {
		$custom_value = $_REQUEST[ 'aa-menu-item-custom' ][ $menu_item_db_id ];
		update_post_meta( $menu_item_db_id, '_menu_item_custom', $custom_value );
	}
}

// Fully replace walker
add_filter( 'wp_edit_nav_menu_walker', 'custom_nav_edit_walker', 10, 2 );
function custom_nav_edit_walker( $walker, $menu_id )
{
	return 'CustomFieldsMenu';
}