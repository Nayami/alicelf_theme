<?php get_header(); ?>
<!--ARCHIVE LOOP-->
    <div class="ghostly-wrap">
			<div class="row">
				<?php aa_alternative_wiget_sidebar(); ?>

				<div class="col-sm-6 archive-loop">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						<article class="row">
							<div class="col-sm-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
									</div>
									<div class="panel-body">
										<small><?php the_time('F jS, Y'); ?></small>
										<div class="entry"><?php the_excerpt(); ?></div>
									</div>
								</div>
							</div>
						</article>

					<?php endwhile; else: ?><p>Sorry, no posts matched your criteria.</p>
					<?php endif; paged_navigation(); ?>
				</div>
				<?php aa_default_wiget_sidebar(); ?>
			</div>
    </div>
<?php get_footer(); ?>