<?php

/**
 * Bootstrap Slider transitons
 * Slide | Fade
 */
add_action('aa_theme_carousel_process', 'aa_func_20154807014848',10, 4);
function aa_func_20154807014848( $alicelf, $active, $ac_bullet, $transition )
{
	$slider_type = $alicelf[ 'opt-carouseltransition' ];

	if($slider_type === '1' || $slider_type === '2') {

		?>
		<div id="aa-main-site-slider" class="carousel <?php echo $transition ?>" data-ride="carousel">
			<!-- Indicators -->
			<ol class="carousel-indicators">
				<?php for ( $indicator = 0; $indicator < count( $alicelf[ 'opt-slides' ] ); $indicator ++ ): ?>
					<li data-target="#aa-main-site-slider" data-slide-to="<?php echo $indicator ?>" class="<?php echo $ac_bullet ?>"></li>
					<?php $ac_bullet = null; endfor; ?>
			</ol>

			<div class="carousel-inner" role="listbox">
				<?php foreach ( $alicelf[ 'opt-slides' ] as $slide ): ?>
					<div class="item <?php echo $active ?> attachment-<?php echo $slide[ 'attachment_id' ] ?>">
						<img src="<?php echo $slide[ 'image' ] ?>" alt="<?php echo $slide[ 'title' ] ?>" data-aa-image="slider-image">
						<div class="carousel-caption">
							<h4><?php echo $slide[ 'description' ] ?></h4>
							<p class="text-center">
								<?php if ( ! empty( $slide[ 'url' ] ) ): ?>
									<a class="btn btn-default" href="<?php echo $slide[ 'url' ] ?>"></a>
								<?php endif; ?>
							</p>
						</div>
					</div>
					<?php $active = null; endforeach; ?>
			</div>

			<a class="left carousel-control" href="#aa-main-site-slider" role="button" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="right carousel-control" href="#aa-main-site-slider" role="button" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
		<?php
	} elseif ($slider_type === '3') { ?>

		<?php
			echo "<pre>";
			print_r($alicelf[ 'opt-slides' ]);
			echo "</pre>";
		?>

		<div class="slick-slider-container">
			<div class="slider-for">
			<?php foreach ( $alicelf[ 'opt-slides' ] as $slide ): ?>
				<div class="attachment-<?php echo $slide[ 'attachment_id' ] ?>">
					<img class="img-responsive" src="<?php echo $slide[ 'image' ] ?>" alt="<?php echo $slide[ 'title' ] ?>">
				</div>
			<?php endforeach; ?>
			</div>

			<div class="slider-nav">
				<?php
					for ( $indicator = 0; $indicator < count( $alicelf[ 'opt-slides' ] ); $indicator ++ ):
					$thumb = $alicelf[ 'opt-slides' ][$indicator]['thumb'];
					$title = $alicelf[ 'opt-slides' ][$indicator]['title'];
					?>
					<div class="<?php echo $ac_bullet ?>">
						<img class="img-responsive" src="<?php echo $thumb ?>" alt="<?php echo $title?>">
					</div>
					<?php endfor; ?>
			</div>

		</div>

		<?php

	} else {
		echo "Unknown Slider type";
	}
}