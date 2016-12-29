<?php

add_action('wp_head', 'aa_dynamic_styles', 11);
function aa_dynamic_styles() {
	global $alicelf;
	$width = $alicelf['opt-site-width'];
	?>
	<style type="text/css">

		/*initial*/
		@media (min-width : <?php echo $width ?>px) {
			.ghostly-wrap {
				max-width : <?php echo $width ?>px;
				width : 100%;
				margin-left : auto;
				margin-right : auto;
			}
		}
		@media (max-width : <?php echo $width -1 ?>px) {
			.ghostly-wrap {
				padding-left : 15px;
				padding-right : 15px;
			}
		}
		/*Desktop*/
		@media (min-width : 768px) {
			.container {
				width : 100%;
			}
		}
		/*Mobile*/
		@media (max-width : 767px) {

		}
	</style>
	<?php
}