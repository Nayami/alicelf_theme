<?php

register_sidebar( array(
		'name'          => 'Default sidebar',
		'id'            => 'default-widgetize-sidebar',
		'before_widget' => '<div id="%1$s" class="widget list-group %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="wdg_title list-group-item">',
		'after_title'   => '</h4>'
	)
);

register_sidebar( array(
		'name'          => 'Alternative sidebar',
		'id'            => 'alternative-widgetize-sidebar',
		'before_widget' => '<div id="%1$s" class="widget list-group %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="wdg_title list-group-item">',
		'after_title'   => '</h4>'
	)
);

/**
 * Output Sidebars
 */

function aa_dynamic_sidebar_view( $page_id, $col_sm = 3 )
{
	if ( is_page() ) {
		$sidebar          = get_post_meta( $page_id, 'aa_select_registred_sidebar', true );
		$sidebar_position = get_post_meta( $page_id, 'aa_theme_sidebar_options', true );

		if ( ! empty( $sidebar_position ) && $sidebar_position[ 0 ] !== 'aa_nosidebar' ) {
			if ( is_active_sidebar( $sidebar ) ) {
				switch ( $sidebar_position[ 0 ] ) {
					case 'aa_left_sidebar' :
						?>
					<aside class="aa-pull-left col-sm-<?php echo $col_sm . " " . $sidebar_position[ 0 ] . " " . $sidebar ?>">
						<?php dynamic_sidebar( $sidebar ); ?>
						</aside><?php
						;
						break;
					case 'aa_right_sidebar' :
						?>
					<aside class="aa-pull-right col-sm-<?php echo $col_sm . " " . $sidebar_position[ 0 ] . " " . $sidebar ?>">
						<?php dynamic_sidebar( $sidebar ); ?>
						</aside><?php
						;
						break;
				}
			}
		}
	}
}

function aa_default_wiget_sidebar( $col_sm = 3 )
{
	if ( is_active_sidebar( 'default-widgetize-sidebar' ) ) { ?>
		<aside class="col-sm-<?php echo $col_sm ?> main-sidebar">
			<?php dynamic_sidebar( 'default-widgetize-sidebar' ); ?>
		</aside>
	<?php }
}

function aa_alternative_wiget_sidebar( $col_sm = 3 )
{
	if ( is_active_sidebar( 'alternative-widgetize-sidebar' ) ) { ?>
		<aside class="col-sm-<?php echo $col_sm ?> left-sidebar">
			<?php dynamic_sidebar( 'alternative-widgetize-sidebar' ); ?>
		</aside>
	<?php }
}