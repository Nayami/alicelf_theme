<?php
function al_thumb( $type = null, $col_class = 4 )
{
	//sun-gradient
	//blade-shine
	//Default: none effect
	if ( has_post_thumbnail() ){
		switch ( $type ) {
			case 'sun-gradient': ?>
				<figure class="col-sm-<?php echo $col_class ?> generated-figure <?php echo $type ?>">
					<a class="post-img" href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail(); ?>
						<figcaption>
							<h3><?php echo content_cutter( get_the_title(), 0, 3 ); ?></h3>
						</figcaption>
					</a>
				</figure>
				<?php ;
				break;
			case 'blade-shine': ?>
				<figure class="col-sm-<?php echo $col_class ?> generated-figure <?php echo $type ?>">
					<a class="post-img" href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail(); ?>
						<figcaption>
							<p><?php echo content_cutter( get_the_title(), 0, 3 ); ?></p>

							<p><?php echo strip_tags(content_cutter( get_the_content(), 0, 10 )); ?></p>
						</figcaption>
					</a>
				</figure>
				<?php ;
				break;
			default: ?>
				<figure class="col-sm-<?php echo $col_class ?> generated-figure">
					<a class="post-img" href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail(); ?>
					</a>
				</figure>
				<?php ;
		}
	} else {
		?>
		<figure class="col-sm-<?php echo $col_class ?> generated-figure">
			<a class="post-img" href="<?php the_permalink(); ?>">
				<img class="img-responsive" src="http://placehold.it/150x150" alt="alt">
			</a>
		</figure>
		<?php
	}
}