<?php
/**
 * Theme Regular Metaboxes
 */
if ( class_exists( 'GenerateMeta' ) ) {
	$radiolist_sidebar = new GenerateMeta( 'aa_theme_sidebar_options', 'Sidebar Options', 'page' );
	$rd_args           = array(
		'option_one'  => array(
			'label' => 'L Sidebar',
			'value' => 'aa_left_sidebar'
		),
		'option_two'  => array(
			'label' => 'R Sidebar',
			'value' => 'aa_right_sidebar'
		),
		'option_tree' => array(
			'label' => 'No Sidebar',
			'value' => 'aa_nosidebar'
		),
	);
	$radiolist_sidebar->run( 'radio', 'side', 'high', $rd_args );
	$radiolist_sidebar->saveMetadata();

	add_action( 'init', 'initialize_sidebars_from_admin' );
	function initialize_sidebars_from_admin()
	{
		global $wp_registered_sidebars;
		$aa_get_sidebars     = new GenerateMeta( 'aa_select_registred_sidebar', 'Select sidebar', 'page' );
		$option_sidebar_args = array();
		foreach ( $wp_registered_sidebars as $sidebar ) {
			$option_sidebar_args[ ] = $sidebar[ 'id' ] = array(
				'label' => $sidebar[ 'name' ],
				'value' => $sidebar[ 'id' ],
			);
		}
		$aa_get_sidebars->run( 'select', 'side', 'high', $option_sidebar_args );
		$aa_get_sidebars->saveMetadata();
	}

}