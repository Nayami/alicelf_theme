<?php

function custom_posts_shortcode( $atts )
{
	ob_start();

	$cat          = null;
	$tag          = null;
	$type         = null;
	$limit        = null;
	$order        = null;
	$orderby      = null;
	$columns      = null;
	$ignoresticky = null;

	extract( shortcode_atts( array(
		'type'         => '',
		'order'        => 'DESC',
		'orderby'      => 'date',
		'limit'        => '-1',
		'exclude'      => '',
		'include'      => '',
		'columns'      => '',
		'ignoresticky' => '1',
		'cat'          => '',
		'tag'          => ''

	), $atts ) );

//	global $wpdb, $post, $table_prefix;

	$excludearray = array();
	if ( ! empty( $exclude ) ) {
		$excludearray = explode( ',', $exclude );
	}
	$includearray = array();
	if ( ! empty( $include ) ) {
		$includearray = explode( ',', $include );
	}

	$included_tag = array();
	if ( ! empty( $tag ) ) {
		$included_tag = explode( ',', $tag );
	}

	$default_columns = array( 1, 2, 3, 4, 6, 12 );
	$totcount        = 12;

	if ( ! in_array( $columns, $default_columns ) ) {
		$modulo = 4;
	} else {
		$modulo = 12 / $columns;
	}

	$paged = 1;
	if ( get_query_var( 'paged' ) ) {
		$paged = get_query_var( 'paged' );
	} elseif ( get_query_var( 'page' ) ) {
		$paged = get_query_var( 'page' );
	}

	$args = array(
		'post_type'           => $type,
		'order'               => $order,
		'orderby'             => $orderby,
		'post__not_in'        => $excludearray,
		'post__in'            => $includearray,
		'posts_per_page'      => $limit,
		'ignore_sticky_posts' => $ignoresticky,
		'paged'               => $paged,
		'cat'                 => $cat,
		'tag'                 => $tag
	);
	// rewind
	$posts_query = null;
	$posts_query = new WP_Query( $args );

	if ( $posts_query->have_posts() ) {
		do_action( 'aa_shortcode_posts', $posts_query, $type, $modulo );
	} else {
		echo "no posts found on post type : " . $type;
	}
	wp_reset_query();

	return ob_get_clean();
}

add_action( 'aa_shortcode_posts', 'aa_func_20160420020425', 10, 3 );
function aa_func_20160420020425( $posts_query, $type, $modulo )
{
	?>
	<div class='row posts-shortcode-<?php echo $type ?>'>
		<?php while ( $posts_query->have_posts() ): $posts_query->the_post(); ?>
			<article id='post-<?php the_ID() ?>' class='col-sm-<?php echo $modulo ?>'>
				<?php get_template_part( 'templates/shortcode-single-' . $type ) ?>
			</article>
		<?php endwhile; ?>
	</div>
	<?php
}

function do_the_breadcrumb()
{
	global $post;
	$posttype   = get_post_type( $post );
	$categories = get_the_category();
	$separator  = "";
	$output     = '';
	?>
	<ol class="breadcrumb">
		<?php if ( is_home() ) { ?>
			<li class="active">Home: <?php bloginfo( 'name' ); ?></li>
		<?php } else { ?>
			<li><a href="<?php echo site_url() ?>">Home</a></li>
			<?php if ( is_single() ) { ?>
				<li><a href="<?php echo site_url() ?>?post_type=<?php echo $posttype ?>"><?php echo $posttype ?></a></li>
				<?php if ( $categories ) {
					foreach ( $categories as $category ) {
						$output .= '<li><a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">' . $category->cat_name . '</a></li>' . $separator;
					}
					echo trim( $output, $separator );
				} ?>
				<li class="active"><?php echo the_title() ?></li>
				<?php
			}
			if ( is_page() ) {
				if ( is_page() && $post->post_parent > 0 ) {
					foreach ( array_reverse( get_post_ancestors( $post ) ) as $ancestor ): ?>
						<li><a href="?p=<?php echo $ancestor ?>"><?php echo get_post( $ancestor )->post_title ?></a></li>
					<?php endforeach;
				} ?>
				<li class="active"><?php echo the_title() ?></li>
				<?php
			}
			if ( is_category() ) {
				foreach ( $categories as $category ) {
					$output .= '<li><a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">' . $category->cat_name . '</a></li>' . $separator;
				}
				echo trim( $output, $separator );
			}
			if ( is_archive() ) {
				?>
				<li class="active">Archive</li>
				<?php
			}
		} ?>
	</ol>
	<?php
}

/**
 * Theme Slider
 */
function get_theme_slider()
{
	ob_start();
	global $alicelf;
	$active     = 'active';
	$ac_bullet  = 'active';
	$transition = null;

	switch ( $alicelf[ 'opt-carouseltransition' ] ) {
		case 2 :
			$transition = 'carousel-fade slide';
			break;
		case 3 :
			$transition = 'slick_thumbs';
			break;
		default :
			$transition = 'slide';
	}

	do_action( 'aa_theme_carousel_process', $alicelf, $active, $ac_bullet, $transition );

	return ob_get_clean();
}

// [aa_option opt="opt-someoption"]
function aa_getthmeoption( $option )
{
	ob_start();
	global $alicelf;
	$opt = null;

	extract( shortcode_atts( array( 'opt' => '' ), $option ) );

	echo $alicelf[ $opt ];

	return ob_get_clean();
}

// ============= Aa_img =============
if ( ! function_exists( 'aa_img' ) ) {
	function aa_img( $args )
	{
		ob_start();
		$sha = shortcode_atts( [
			'id'    => $args[ 'id' ],
			'class' => ! empty( $args[ 'class' ] ) ? $args[ 'class' ] : "img-responsive",
		], $args );
		echo "<img src='" . wp_get_attachment_url( $sha[ 'id' ] ) . "' class='{$sha['class']}'>";

		return ob_get_clean();
	}
}
/**
 * Custom Post Types
 * [custom_posts type="recent_projects" order="asc" cat="46,71,52,64"]
 * [custom_posts type="recent_projects" limit="3" columns="2" cat="-46"]
 * [custom_posts type="testimonials" limit="3" columns="2" include="46,71,52,64"]
 * [custom_posts type="" columns="" limit="" include="" exclude="" order="" orderby="" ignoresticky=""]
 */

function release_alicelf_shortcodes()
{
	add_shortcode( 'bcrumb', 'do_the_breadcrumb' );
	add_shortcode( 'custom_posts', 'custom_posts_shortcode' );
	add_shortcode( 'theme_carousel', 'get_theme_slider' );
	add_shortcode( 'aa_option', 'aa_getthmeoption' );
	add_shortcode( 'aa_img', 'aa_img' );
}

add_action( 'init', 'release_alicelf_shortcodes' );