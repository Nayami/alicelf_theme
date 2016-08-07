<?php
$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
$_height = $image[2];
$style = "height: {$image[2]}px;";
?>

<header class='image-overlap' style='<?php echo $style ?>'>
	<hgroup class="inner-elem">
		<h2><?php the_title() ?></h2>
		<h3><?php echo get_post_meta(get_the_ID(),'page_subtitle', true) ?></h3>
	</hgroup>
	<?php if($image) echo "<img id='toppage-image' src='{$image[0]}' data-mediabreakpoint='{$image[1]}'>";  ?>
</header>