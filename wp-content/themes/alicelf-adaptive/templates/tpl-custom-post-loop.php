<?php
$post_type = 'custom_post';

$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$args = array( 'post_type' =>$post_type, 'posts_per_page' => 0, 'paged' => $paged );
$query = query_posts( $args );

if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<article>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			</div>
			<div class="row">
				<div class="panel-body">
					<?php al_thumb('blade-shine'); ?>
					<?php $has_thumb = has_post_thumbnail() ? 8 : 12; ?>
					<div class="col-sm-<?php echo $has_thumb ?>">
						<small><?php the_time('F jS, Y'); ?></small>
						<div class="entry"><?php the_excerpt(); ?></div>
					</div>
				</div>
			</div>

		</div>
	</article>

<?php endwhile; else: include( get_404_template() ); endif; wp_reset_postdata(); paged_navigation(); ?>