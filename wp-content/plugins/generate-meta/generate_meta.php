<?php
/*
Plugin Name: Generate Meta
Plugin URI: http://vzazerkalie.com/portf/
Description: Alicelf Generate Metaboxes plugin - Upload and Activate.
Author: Alicelf
Version: 3.0.2
Author URI: http://vzazerkalie.com/portf/
*/
//@Template Todo: create separate sections
//@Template Todo: define user frendly factory
//@Template Todo: removable single image
//@Template Todo: output gallery to frontend
//@Template Todo: datepicker box type
//@Template Todo: extend plugin

require('GenerateMeta.php');
require('ajax_actions.php');

// Include all dynamic custom fields
foreach ( glob( plugin_dir_path(__FILE__) . "/dynamic_metafields/*.php" ) as $filename )
	require_once( $filename );

/**
 * Include styles and scripts
 *
 */
add_action('admin_enqueue_scripts', 'aa_func_20154904124919');
function aa_func_20154904124919()
{
	$plugindir = plugin_dir_url( __DIR__ ) . basename( __DIR__ );
	//jQuery sortable
	wp_enqueue_style( 'jq-ui-sort-core', $plugindir . '/jq-sortable/jquery-ui.min.css' );
	wp_enqueue_style( 'jq-ui-sort-structure', $plugindir . '/jq-sortable/jquery-ui.structure.min.css' );
	wp_enqueue_script( 'jq-ui-sort-js', $plugindir . '/jq-sortable/jquery-ui.min.js', array( 'jquery' ), false, true );

	// Plugin scripts
	wp_enqueue_style( 'alice_font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'GenerateMetaStyle', $plugindir . '/style_script/style.css' );
	wp_enqueue_script( 'GenerateMetaScript', $plugindir . '/style_script/script.js', array( 'jquery' ), false, true );
	$data = array(
		'site_url'     => get_site_url(),
		'ajax_url'     => admin_url( 'admin-ajax.php' ),
		'template_uri' => get_template_directory_uri(),
	);
	wp_localize_script( 'GenerateMetaScript', 'aa_generate_meta_var', $data );
}

$section_template = array(
	array(
		'type' => 'text',
		'name' => 'title',
		'value' => '',
	),
	array(
		'type' => 'image',
		'name' => 'slider-image',
		'value' => '',
	),
	array(
		'type' => 'textarea',
		'name' => 'description',
		'value' => '',
	)
);

$dynamic_metabox = new Repeater( 'pages_repeater_meta', 'Page Accordion', 'page' );
$dynamic_metabox->run( 'repeater', null, 'high', $section_template );
$dynamic_metabox->saveMetadata();