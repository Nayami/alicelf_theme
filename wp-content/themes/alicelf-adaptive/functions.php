<?php
//session_start();

// plugins dependences pack and redux core
$init_dir = dirname(__FILE__) . '/theme_init/';
require_once ($init_dir.'plugins_dependences.php');
require_once ($init_dir.'ReduxCore/framework.php');
require_once ($init_dir.'config.php');

// Walkers
foreach ( glob( get_template_directory() . "/walkers/*.php" ) as $filename )
	require_once( $filename );

// Theme Shortcodes, functions, supports, filters, metaboxes and custom posts
foreach ( glob( get_template_directory() . "/inc/*.php" ) as $filename )
	require_once( $filename );

// Theme partials
foreach ( glob( get_template_directory() . "/partials/*.php" ) as $filename )
	require_once( $filename );

// Ajax content include
require_once("ajax.php");