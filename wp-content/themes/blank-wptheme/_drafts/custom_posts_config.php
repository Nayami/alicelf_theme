<?php

/**
 * Creating custom posts by plugin
 */
$labels = array(
	'name'               => __( 'Recent Projects' ),
	'singular_name'      => __( 'Project' ),
	'add_new'            => __( 'Add new' ),
	'add_new_item'       => 'Add new recent project',
	'edit_item'          => 'Edit recent project',
	'new_item'           => 'New recent project',
	'view_item'          => 'View recent project',
	'search_items'       => 'Search recent project',
	'not_found'          => 'recent project not found',
	'not_found_in_trash' => 'Empty basket recent projects',
	'parent_item_colon'  => '',
	'menu_name'          => 'Projects'

);
$args   = array(
	'labels'             => $labels,
	'public'             => true,
	'publicly_queryable' => true,
	'show_ui'            => true,
	'show_in_menu'       => true,
	'query_var'          => true,
	'rewrite'            => array( 'slug' => 'recent_projects' ),
	'capability_type'    => 'post',
	'has_archive'        => true,
	'hierarchical'       => true,
	'menu_position'      => null,
	'taxonomies'         => array( 'category', 'post_tag' ),
	'supports'           => array(
		'title',
		'editor',
		'author',
		'thumbnail',
		'excerpt',
		'comments',
		'custom-fields',
		'page-attributes'
	)
);
if ( class_exists( 'GenerateCPosts' ) ) {
	$new_post = new GenerateCPosts( 'recent_projects', $labels, $args );
	// can set menu position for example ->run(65)
	$new_post->run();
	$new_post->taxonomy('category_recent', 'Cat Taxonomy', 'slug_tax_recent');
	$new_post->createField('text','customarray', 'customtext');
//	$new_post->addContextualHelp('Some Contextual text');
//	$new_post->postFormatSupport();
}