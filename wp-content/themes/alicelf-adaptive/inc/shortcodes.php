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

	global $wpdb, $post, $table_prefix;

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
		// Exclude posts by id
		'post__in'            => $includearray,
		'posts_per_page'      => $limit,
		// Number of posts to display.
		'ignore_sticky_posts' => $ignoresticky,
		'paged'               => $paged,
		'cat'                 => $cat,
		'tag'                 => $tag
	);
	// rewind
	$my_query = null;
	$my_query = new WP_Query( $args );

	if ( $my_query->have_posts() ) { ?>
		<div class="row posts-shortcode-<?php echo $type ?>">
			<?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
				<article id="post-<?php the_ID(); ?>" class="col-sm-<?php echo $modulo ?> shortcode-single-<?php echo $type ?>">
					<?php get_template_part( 'templates/tpl-shortcode-single' ); ?>
				</article>
			<?php endwhile; ?>
		</div>
	<?php } else {
		echo "no posts found on post type : " . $type;
	}
	wp_reset_query();

	return ob_get_clean();

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
	global $alicelf;
	$active    = 'active';
	$ac_bullet = 'active';
	$transition = $alicelf[ 'opt-carouseltransition' ] > 1 ? 'carousel-fade slide' : 'slide';
	?>
	<div id="aa-main-site-slider" class="carousel <?php echo $transition ?>" data-ride="carousel">
		<!-- Indicators -->
		<ol class="carousel-indicators">
			<?php for ( $indicator = 0; $indicator < count( $alicelf[ 'opt-slides' ] ); $indicator ++ ): ?>
				<li data-target="#aa-main-site-slider" data-slide-to="<?php echo $indicator ?>" class="<?php echo $ac_bullet ?>"></li>
				<?php $ac_bullet = null; endfor; ?>
		</ol>

		<div class="carousel-inner" role="listbox">
			<?php foreach ( $alicelf[ 'opt-slides' ] as $slide ): ?>
				<div class="item <?php echo $active ?> attachment-<?php echo $slide[ 'attachment_id' ] ?>">
					<img src="<?php echo $slide[ 'image' ] ?>" alt="<?php echo $slide[ 'title' ] ?>" data-aa-image="slider-image">
					<div class="carousel-caption">
						<h4><?php echo $slide[ 'description' ] ?></h4>
						<p class="text-center">
							<?php if ( ! empty( $slide[ 'url' ] ) ): ?>
								<a class="btn btn-default" href="<?php echo $slide[ 'url' ] ?>"></a>
							<?php endif; ?>
						</p>
					</div>
				</div>
				<?php $active = null; endforeach; ?>
		</div>

		<a class="left carousel-control" href="#aa-main-site-slider" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#aa-main-site-slider" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
<?php
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
}

add_action( 'init', 'release_alicelf_shortcodes' );