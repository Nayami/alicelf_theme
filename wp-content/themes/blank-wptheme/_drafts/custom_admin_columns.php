<?php

if ( ! function_exists( '__cc_config' ) ) {
	function __cc_config()
	{
		return [
			'label'  => __( "Event Tags" ),
			'c_name' => 'ai1ec_event',
			'tax'    => 'events_tags'
		];
	}
}
$_c_config = __cc_config();

/**
 * ==================== Register column ======================
 */
add_filter( "manage_" . $_c_config[ 'c_name' ] . "_posts_columns", 'aa_func_20160211020209', 9, 1 );
function aa_func_20160211020209( $columns )
{
	$config = __cc_config();

	$columns[ 'bws_events_tags_info' ] = $config[ 'label' ];

	return $columns;
}

/**
 * ==================== Sortable ======================
 */
add_filter( "manage_edit-" . $_c_config[ 'c_name' ] . "_sortable_columns", 'aa_func_20160703070704' );
function aa_func_20160703070704( $columns )
{
	$_c_config = __cc_config();

	$columns[ 'bws_events_tags_info' ] = $_c_config[ 'tax' ];

	return $columns;
}

/**
 * ==================== Column Content ======================
 * 03.06.2016
 */
add_filter( "manage_" . $_c_config[ 'c_name' ] . "_posts_custom_column", 'aa_func_20160011020038', 10, 2 );
function aa_func_20160011020038( $column, $event_id )
{
	$_c_config = __cc_config();
	if ( 'bws_events_tags_info' === $column ) {
		$events_tags = get_the_terms( $event_id, $_c_config[ 'tax' ] );
		echo "<ul class='list-inline'>";
		foreach ( $events_tags as $events_tag ) {
			$tag_id   = $events_tag->term_id;
			$tag_name = $events_tag->name;
			$tag_slug = $events_tag->slug;
			$link     = "?post_type=" . $_c_config[ 'c_name' ] . "&" . $_c_config[ 'tax' ] . "={$tag_slug}";
			?>
			<li>
				<a href="<?php echo $link ?>">
					#<?php echo $tag_id ?> <i class='fa fa-tag'></i> <?php echo $tag_name ?>
				</a>
			</li>
			<?php
		}
		echo "</ul>";
	}
}