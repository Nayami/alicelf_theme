<?php
/**
 * Register Site styles and Scripts
 */
add_action( 'wp_enqueue_scripts', 'invoke_scripts' );
function invoke_scripts()
{
	global $alicelf;
	$theme_path = get_template_directory_uri();
	$t_dir = get_template_directory_uri() . '/style-parts';

	// Font Icons
	wp_enqueue_style( 'alice-font_awesome', $t_dir . '/font-awesome/css/font-awesome.min.css' );
	wp_enqueue_style( 'alice-icons', $t_dir . '/alicelf-font-icons/styles.css' );

	// Plugins Css
	wp_enqueue_style( 'progressjs-css', $theme_path . '/partials/progressjs.min.css' );

	// Styles
	wp_enqueue_style( 'bootstrap-base-styles', $theme_path . '/bootstrap/bootstrap.css' );
	wp_enqueue_style( 'template-base-styles', get_bloginfo( 'stylesheet_url' ) );

	// Theme jQuery 2.1
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', $theme_path . '/js_prod/jquery2.1.js', array(), false, true );
	wp_enqueue_script( 'jquery' );

	wp_enqueue_script( 'aa-compiled-scripts', $theme_path . '/js_prod/uglify.js', array(), false, true );
	$data = array(
		'site_url'     => get_site_url(),
		'ajax_url'     => admin_url( 'admin-ajax.php' ),
		'template_uri' => $theme_path,
	);
	wp_localize_script( 'aa-compiled-scripts', 'aa_ajax_var', $data );

	// Slick Slider
	if($alicelf[ 'opt-carouseltransition' ] === '3') {
		wp_enqueue_style( 'slick-20155007025030', $theme_path . '/partials/slick/slick.css' );
		wp_enqueue_script( 'slick-script-20155307025349', $theme_path . '/partials/slick/slick.min.js', array(), false, true );
	}
}

// Admin Scripts
add_action( 'admin_enqueue_scripts', 'theme_alicelf_admin_styles_restore_scripts' );
function theme_alicelf_admin_styles_restore_scripts()
{
	$t_dir = get_template_directory_uri() . '/style-parts';
	wp_enqueue_style( 'alice_font_awesome', $t_dir . '/font-awesome/css/font-awesome.min.css' );
	wp_enqueue_style( 'alice_icons', $t_dir . '/alicelf-font-icons/styles.css' );
	wp_enqueue_style( 'alice_style_admin', $t_dir . '/admin-scripts/admin-style.css' );
	wp_enqueue_script( 'alice_bootstrap_admin', $t_dir . '/admin-scripts/admin-bootstrap/bootstrap-min.js', array( 'jquery' ), false, true );

	wp_enqueue_script( 'alice_script_admin', $t_dir . '/admin-scripts/script.js', array(), false, true );
	$data = array(
		'site_url'     => get_site_url(),
		'template_uri' => get_template_directory_uri(),
	);
	wp_localize_script( 'alice_script_admin', 'aa_admin_ajax_var', $data );
}