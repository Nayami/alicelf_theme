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
//add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'audio','gallery' ) );
//add_action( 'admin_head', 'alice_remove_post_type_support', 10 );
function alice_remove_post_type_support()
{
	remove_post_type_support( 'post', 'post-formats' );
}


add_action('aa_backend_theme_setup_head', 'aa_func_20161101071136');
function aa_func_20161101071136()
{
	global $alicelf;
	$_logo = $alicelf['opt-logo'];
	if(!empty($_logo))
		echo "<img src='{$_logo['url']}'>";
}


add_filter('wp_title', 'aa_func_20164019094058', 10, 1);
function aa_func_20164019094058($title)
{
	$woo_presets = __woo_options();
	if ( is_home() || is_front_page() ) {
		$title = get_bloginfo( 'name' ) . " | " . get_bloginfo( 'description', 'display' );
	} else if ( is_404() ) {
		$title = get_bloginfo( 'name' ) . ' | .404!';
	} else if ( is_search() ) {
		$title = get_bloginfo( 'name' ) . " | Search " . get_search_query();
	} else {
		$title = get_the_title() . " | " . get_bloginfo( 'name' );
	}

	if(function_exists('is_shop')) {
		if ( is_shop() ) {
			$title = get_the_title( $woo_presets[ 'woocommerce_shop_page_id' ] ) . " | " . get_bloginfo( 'name' );
		}
	}

	return $title;
}

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
//	unset( $sizes[ 'thumbnail' ] );
	unset( $sizes[ 'medium' ] );
	unset( $sizes[ 'large' ] );

	return $sizes;
}

add_filter( 'wp_revisions_to_keep', 'custom_revisions_number', 10, 2 );
function custom_revisions_number( $num, $post )
{
	$num = 3;
	return $num;
}

add_action( 'admin_head', 'aa_set_favicon' );
add_action( 'wp_head', 'aa_set_favicon' );
function aa_set_favicon()
{
	global $alicelf;
	$output = null;
	if ( ! empty( $alicelf[ 'opt-favicon' ][ 'url' ] ) )
		$output = "<link rel='icon' type='image/png' href='{$alicelf['opt-favicon']['url']}'>";
	else
		$output = "<link rel='icon' type='image/png' href='" . get_template_directory_uri() . "/img/sitefavicon.png'>";

	echo $output;
}


if ( ! function_exists( 'opt_snippet_html' ) ) {
	function opt_snippet_html()
	{
		ob_start();
		global $alicelf;
		echo $alicelf[ 'opt-snippet-html' ];

		return ob_get_clean();
	}

	add_shortcode( 'opt_snippet_html', 'opt_snippet_html' );
}

add_action('wp_head', 'aa_func_20163901013945', 30);
function aa_func_20163901013945()
{
	global $alicelf;
	if(!empty($alicelf['opt-snippet-css']))
		echo "<style>{$alicelf['opt-snippet-css']}</style>";
}
/**
 * ==================== JS after body tag ======================
 * 01.06.2016
 */
add_action( 'aa_afterbodystart', 'aa_func_20165314065329', 10 );
function aa_func_20165314065329()
{
	global $alicelf;
	if ( ! empty( $alicelf[ 'opt-snippet-js' ] ) )
		echo "<script>{$alicelf['opt-snippet-js']}</script>";

	echo "<div id='global-load-line'></div>";
}


/**
 * ==================== Render System Messages ======================
 * 15.04.2016
 */
add_action( 'render_system_messages', 'aa_func_20165722125737', 10 );
function aa_func_20165722125737()
{
	if ( isset( $_SESSION[ 'aa_alert_messages' ] ) ) {
		foreach ( $_SESSION[ 'aa_alert_messages' ] as $alert ) {
			?>
			<div class="alert alert-<?php echo $alert['type'] ?>">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<?php echo $alert['message'] ?>
			</div>
			<?php
		}
		unset( $_SESSION[ 'aa_alert_messages' ] );
	}
}

/**
 * ==================== Customize Login Page ======================
 * 15.04.2016
 */
add_filter('login_headerurl', 'aa_func_20160715060748',10, 1);
function aa_func_20160715060748($url)
{
	return get_site_url();
}
add_filter('login_headertitle', 'aa_func_20160815060854', 10, 1);
function aa_func_20160815060854($title)
{
	return get_bloginfo('name');
}

/**
 * ==================== Editor tags ======================
 * 04.06.2016
 */
add_action( 'admin_print_footer_scripts', 'aa_func_20163004123014' );
function aa_func_20163004123014()
{
	if ( wp_script_is( 'quicktags' ) ) {
		?>
		<script type="text/javascript">
			QTags.addButton('ghostly_wrap', 'Wrap', '<div class="ghostly-wrap">', '</div>', 'p', 'Wrap', 140);
			QTags.addButton('eg_pre', 'Pre', '<pre>', '</pre>', 'q', 'Preformatted text tag', 141);
		</script>
		<?php
	}
}


/**
 * ==================== SEARCH ======================
 * 19.04.2016
 */
add_filter('aa_search_hook', 'aa_func_20164919094939', 10, 2);
function aa_func_20164919094939($query, $per_page)
{
	isset( $_GET[ 'page' ] ) && $query .= "&paged={$_GET['page']}";

	return $query;
}