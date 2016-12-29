<?php get_header(); ?>
<div class="main-loop">
	<div class="ghostly-wrap">
		<div class="row">
			<?php if (is_active_sidebar( 'base-widgeted-sidebar' )): ?>
			<div class="col-sm-8 front-loop">
				<?php else: ?>
				<div class="ghostly-wrap front-loop">
					<?php endif; ?>

					<?php
					if ( have_posts() ) :?>
						<h3>Posts by Category: <?php echo get_cat_name(get_query_var('cat'));?></h3>
						<?php while ( have_posts() ) : the_post(); ?>
						<article id="recent-item-<?php the_ID() ?>">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								</div>

								<div class="panel-body">
									<?php if (has_post_thumbnail()): ?>
									<div class="col-sm-4">
										<a class="post-img" href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
									</div>
									<div class="col-sm-8">
										<?php else: ?>
										<div class="col-sm-12">
											<?php endif; ?>
											<small><?php the_time( 'F jS, Y' ); ?></small>
											<div class="entry"><?php the_excerpt(); ?></div>
										</div>
									</div>
								</div>
						</article>

					<?php endwhile;
					else:  include( get_404_template() ); ?>
					<?php endif;
					wp_reset_postdata();
					paged_navigation();
					?>

				</div>

				<?php if (is_active_sidebar( 'base-widgeted-sidebar' )): ?>
				<aside class="col-sm-4 main-sidebar">
					<?php else: ?>
					<aside class="hidden">
						<?php endif; ?>
						<?php get_sidebar(); ?>
					</aside>
			</div>
		</div>
	</div>

<?php get_footer(); ?>