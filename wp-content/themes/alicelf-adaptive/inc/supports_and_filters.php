<?php

/**
 * Shortcodes in widgets
 * Post type supports
 * Title Filter
 * Custom excerpt - read more
 * Custom length of excerpt
 * Restrict Image Duplicates
 * Change Num of keeping revisions
 */

add_theme_support( 'post-thumbnails' );
add_theme_support( 'woocommerce' );

add_filter( 'widget_text', 'do_shortcode' );

/**
 * Post Formats
 */
//add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'audio' ) );
//add_action( 'admin_head', 'alice_remove_post_type_support', 10 );
function alice_remove_post_type_support()
{
	remove_post_type_support( 'post', 'post-formats' );
}

/**
 * @param $title
 *
 * @return string
 */
function title_addon( $title )
{
	( is_home() || is_front_page() ) ?
		$title = bloginfo( 'name' ) . " | " . get_bloginfo( 'description', 'display' ) : $title = the_title() . " | " . get_bloginfo( 'name' );
	if ( is_404() ) {
		$title = bloginfo( 'name' ) . ' | .404!';
	}

	return $title;
}

add_filter( 'wp_title', 'title_addon' );


/**
 * Add "Read More" custom text for Recent Projects (if use the_excerpt())
 */
function new_excerpt_more( $more )
{
	global $post;

	return ' ... <a href="' . get_permalink( $post->ID ) . '">Read more <i class="glyphicon glyphicon-arrow-right"></i></a>';
}

add_filter( 'excerpt_more', 'new_excerpt_more' );


function custom_excerpt_length( $length )
{
	return 10;
}

//add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


/**
 * Restict duplicate images with different sizes
 *
 * @param $sizes
 */
add_filter( 'intermediate_image_sizes_advanced', 'add_image_insert_override' );
function add_image_insert_override( $sizes )
{
	unset( $sizes[ 'thumbnail' ] );
	unset( $sizes[ 'medium' ] );
	unset( $sizes[ 'large' ] );
}

add_filter( 'wp_revisions_to_keep', 'custom_revisions_number', 10, 2 );
function custom_revisions_number( $num, $post )
{
	$num = 3; // <-- change this accordingly.
	return $num;
}


function aa_set_favicon() {
	global $alicelf;
	$output = null;
	if(!empty($alicelf['opt-favicon']['url']))
		$output = "<link rel='icon' type='image/png' href='{$alicelf['opt-favicon']['url']}'>";
	else
		$output = "<link rel='icon' type='image/png' href='".get_template_directory_uri()."/img/sitefavicon.png'>";

	echo $output;
}
add_action('wp_head', 'aa_set_favicon');