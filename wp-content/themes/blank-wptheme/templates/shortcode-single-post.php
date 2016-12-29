<div class="panel panel-default">
	<div class="panel-heading">
		<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
	</div>

	<div class="panel-body">
		<div class="row">
			<?php if( has_post_thumbnail()): ?>
			<div class="col-sm-4">
				<a class="post-img" href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
			</div>
			<div class="col-sm-8">
				<?php else: ?>
				<div class="col-sm-12">
					<?php endif; ?>
					<small><?php the_time('F jS, Y'); ?></small>
					<div class="entry"><?php the_content(); ?></div>
				</div>
		</div>
	</div>
</div>