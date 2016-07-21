<?php
$theme_opt_name = 'alicelf_theme_setup';
add_filter('redux/options/'.$theme_opt_name.'/sections', 'aa_func_20164921104955',999, 1);
function aa_func_20164921104955($sections)
{
	$sections[] = [
		'icon'       => 'el el-home',
		'title'      => __( 'Bws Adds', 'alicelf-adaptive' ),
		'customizer' => false,
		'desc'       => esc_html__( "Custom adds", "alicelf-adaptive" ),
		'fields'     => [
			[
				'id'       => 'fallback_free_lvel',
				'type'     => 'text',
				'title'    => __( 'Memebership Lvl Fallback', 'alicelf-adaptive' ),
				'subtitle' => '',
			],
		]
	];
	return $sections;
}