<?php
/**
 * Register Site styles and Scripts
 */
add_action( 'wp_enqueue_scripts', 'invoke_scripts' );
function invoke_scripts()
{
	global $alicelf;
	$t_dir = get_template_directory_uri() . '/style-parts';
	wp_enqueue_style( 'alice-font_awesome', $t_dir . '/font-awesome/css/font-awesome.min.css' );
	wp_enqueue_style( 'alice-icons', $t_dir . '/alicelf-font-icons/styles.css' );

	$theme_path = get_template_directory_uri();

	/**
	 * Theme jQuery 2.1
	 */
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', $theme_path . '/js/jquery2.1.js', array(), false, true );
	wp_enqueue_script( 'jquery' );

	wp_enqueue_style( 'bootstrap-base-styles', $theme_path . '/bootstrap/bootstrap.css' );
	wp_enqueue_style( 'template-base-styles', get_bloginfo( 'stylesheet_url' ) );

	/**
	 * Compiled Scripts
	 * alert
	 * carousel
	 * collapse
	 * transition
	 *
	 * smooth-scroll
	 * progerssjs
	 */
	wp_enqueue_script( 'aa-compiled-scripts', $theme_path . '/js/uglify.js', array(), false, true );

	if($alicelf[ 'opt-carouseltransition' ] === '3') {
		wp_enqueue_style( 'slick-20155007025030', $theme_path . '/partials/slick/slick.css' );
		wp_enqueue_script( 'slick-script-20155307025349', $theme_path . '/partials/slick/slick.min.js', array(), false, true );
	}

// Progress js
	wp_enqueue_style( 'progressjs-style', $theme_path . '/partials/progressjs.min.css' );

	// Theme ajax script
	wp_register_script( 'ajax-theme-scripts', $theme_path . '/js/ajax.js', array(), false, true );
	wp_enqueue_script( 'ajax-theme-scripts' );
	$data = array(
		'site_url'     => get_site_url(),
		'ajax_url'     => admin_url( 'admin-ajax.php' ),
		'template_uri' => $theme_path,
	);
	wp_localize_script( 'ajax-theme-scripts', 'aa_ajax_var', $data );

	// Theme regular script
	wp_enqueue_script( 'site-theme-script', $theme_path . '/js/script.js', array(), false, true );
}

/**
 * Parallax Scripts
 */
//add_action( 'wp_enqueue_scripts', 'aa_invoke_parallax' );
function aa_invoke_parallax()
{
	$path = get_template_directory_uri() . '/js/scrollmagic/';
	// Scrollmagic
	wp_enqueue_script( 'tweenmax', $path . 'tween-max.1.14.2.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'scrollmagic', $path . 'scrollmagic.2.0.5.js', array( 'jquery' ), false, true );
//	wp_enqueue_script( 'addIndicators', $path . 'indications.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'animation-gasp', $path . 'animation.gsap.js', array( 'jquery' ), false, true );
}

/**
 * Admin Scripts
 */
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