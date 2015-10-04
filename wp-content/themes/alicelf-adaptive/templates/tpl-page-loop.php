<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<div class="panel panel-default">
		<div class="panel-heading">
				<h3 class="entry-title"><?php the_title(); ?></h3>
		</div>

	    <div class="entry-content panel-body">
	        <?php the_content();  wp_link_pages();  edit_post_link(); the_tags(""," / "); ?>
		    <nav>
			    <ul id="nav-below" class="pager clearfix">
				    <li class="nav-previous"><?php previous_post_link('&larr; %link'); ?></li>
				    <li class="nav-next"><?php next_post_link('%link &rarr;'); ?></li>
			    </ul>
		    </nav>

	        <?php comments_template('/templates/tpl-comment.php'); ?>
	    </div>
	</div>
<?php endwhile; ?>