<?php

get_header(); ?>
<?php if ( have_posts() ) : ?>
	<div class="ghostly-wrap">
	<!--	Search Page -->
<?php $search_count = 0; $search = new WP_Query("s=$s & showposts=-1");

	if($search->have_posts())
		while($search->have_posts()) { $search->the_post(); $search_count++; } ?>
			<h5 class="page-title"> Search result for: <?php echo get_search_query();?></h5>
			<p>Num of matched results: <?php echo $search_count; ?></p>
			<div class="row">
				<?php $class_co_sm = is_active_sidebar( "base-widgeted-sidebar" ) ? 8 : 12; ?>
				<div class="col-sm-<?php echo $class_co_sm ?> front-loop">
					<?php get_template_part( 'templates/tpl-index-loop' ); ?>
				</div>
				<?php aa_default_wiget_sidebar( 4 ); paged_navigation(); ?>
			</div>
	<?php else:
	include get_404_template();
	; endif; ?>
</div>
<?php get_footer() ?>