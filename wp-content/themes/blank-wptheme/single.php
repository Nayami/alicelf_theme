<?php
/**
 * The Template for displaying single posts.
 */
get_header(); ?>
<!--SINGLE ARTICLE-->
<article id="article-<?php the_ID(); ?>" class="ghostly-wrap">
		<div class="row">
			<?php $class_co_sm = is_active_sidebar( "default-widgetize-sidebar" ) ? 8 : 12; ?>
			<div <?php post_class("col-sm-$class_co_sm single-loop"); ?>>
				<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="entry-title"><?php the_title(); ?></h3>
						</div>
						<div class="panel-body">
							<div class="entry-content"><?php /*GenerateMeta::fetchAllMeta('recent_projects');*/ the_content(); ?></div>
							<div class="label label-default pull-right"> Author: <?php the_author(); ?></div>
							<?php edit_post_link(); al_tags_template(); ?>
							<nav>
								<ul id="nav-below" class="pager clearfix">
									<li class="nav-previous"><?php previous_post_link('&larr; %link'); ?></li>
									<li class="nav-next"><?php next_post_link('%link &rarr;'); ?></li>
								</ul>
							</nav>
							<?php comments_template('/templates/tpl-comment.php'); ?>
						</div>
					</div>
				<?php endwhile; else: echo "No post matched your criteria"; endif; ?>
			</div>
			<?php aa_default_wiget_sidebar(4) ?>
		</div>
</article>
<?php get_footer(); ?>