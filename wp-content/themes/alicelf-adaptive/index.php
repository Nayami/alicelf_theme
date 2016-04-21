<?php get_header(); ?>

	<!--INDEX LOOP-->
	<div class="main-loop">
		<div class="ghostly-wrap">
			<div class="row">
				<?php
					$class_co_sm = is_active_sidebar( "default-widgetize-sidebar" ) ? 8 : 12;
					$post_type = get_post_type(get_the_ID());
				?>
				<div class="col-sm-<?php echo $class_co_sm ?> front-loop">
					<?php get_template_part( 'templates/loop-post' ); ?>
					<?php paged_navigation(); ?>
				</div>
				<?php aa_default_wiget_sidebar( 4 ); ?>
			</div>
		</div>
	</div>

<?php get_footer(); ?>