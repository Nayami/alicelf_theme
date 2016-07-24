<?php
add_action( 'woocommerce_after_add_to_cart_button', 'aa_func_20163029123051' );
function aa_func_20163029123051()
{
	global $product;
	$id = $product->id;
	?>
	<div class="clearfix">
		<?php
		if ( have_rows( 'product_options_group' ) ) {
			while ( have_rows( 'product_options_group' ) ) {
				the_row();
				if ( get_row_layout() == 'product_size_and_colors' ) {

					/**
					 * ==================== Size ======================
					 * 29.06.2016
					 */
					if ( have_rows( 'size' ) ) {
						while ( have_rows( 'size' ) ) { the_row();
							$selected_by_default = get_sub_field( 'selected_by_default' );
							$size_available = get_sub_field( 'available' );
							$size_name = get_sub_field( 'size_name' );
							$size_price = get_sub_field( 'size_price' );

							echo "<pre>";
							print_r($selected_by_default);
							echo "</pre>";
						}
					}

					/**
					 * ==================== Color ======================
					 * 29.06.2016
					 */
					if ( have_rows( 'color' ) ) {
						while ( have_rows( 'color' ) ) { the_row();
							$selected_by_default = get_sub_field( 'selected_by_default' );
							$color_available = get_sub_field( 'available' );
							$color_name = get_sub_field( 'color_name' );
							$color_price = get_sub_field( 'color_price' );

							echo "<pre>";
							print_r($color_name);
							echo "</pre>";
						}
					}

				}
			}
		}

		?>
	</div>
	<?php
}