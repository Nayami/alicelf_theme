<?php
/**
 * The loop that displays a page.
 */
?>
<?php get_header(); ?>
<!--DEFAULT PAGE LOOP START-->
<div id="page-<?php the_ID(); ?>"  <?php post_class( 'default-page-loop ghostly-wrap' ); ?>>
	<div class="row">
		<?php
		$page_id =  get_queried_object_id();
		aa_dynamic_sidebar_view( $page_id, 4 );

		$sidebar_position = get_post_meta( $page_id, 'aa_theme_sidebar_options', true );
		if ( is_plugin_active( 'generate-meta/generate_meta.php' ) )
			$class_co_sm = $sidebar_position[ 0 ] === 'aa_nosidebar' ? 12 : 8;
		else
			$class_co_sm = is_active_sidebar( 'default-widgetize-sidebar' ) ? 8 : 12;
		?>
		<div class="col-sm-<?php echo $class_co_sm ?>">
			<?php get_template_part( 'templates/tpl-page-loop' ); ?>
		</div>
		<?php
		if ( ! is_plugin_active( 'generate-meta/generate_meta.php' ) )
			aa_default_wiget_sidebar( 4 );
		?>
	</div>

</div>
<!--DEFAULT PAGE LOOP  END-->
<?php get_footer(); ?>
