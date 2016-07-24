<?php
add_action( 'single_itemproduct_before', 'aa_func_20163029123051' );
function aa_func_20163029123051()
{
	global $post;
	$product_price = get_field('item_price');
	$product_type = get_field('product_type');
	?>
	<div class="clearfix">
		<?php
		echo "<pre>";
		print_r($product_price);
		echo "</pre>";
		/**
		 * ==================== Size ======================
		 * 29.06.2016
		 */
		if ( have_rows( 'item-size' ) ) {
			while ( have_rows( 'item-size' ) ) { the_row();
				$selected_by_default = get_sub_field( 'selected_by_default' );
				$size_available = get_sub_field( 'available' );
				$size_name = get_sub_field( 'name' );
				$size_price = get_sub_field( 'price' );

			}
		}

		/**
		 * ==================== Color ======================
		 * 29.06.2016
		 */
		echo "<hr>";
		if ( have_rows( 'item-color' ) ) {
			while ( have_rows( 'item-color' ) ) { the_row();
				$_selected = get_sub_field( 'selected_by_default' );
				$color_available = get_sub_field( 'available' );
				$color_name = get_sub_field( 'name' );
				$color_price = get_sub_field( 'price' );
				$color_image = get_sub_field( 'image' );

			}
		}

		?>
	</div>
	<?php
}