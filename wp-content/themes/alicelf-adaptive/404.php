<?php
/**
 * The template for displaying 404 pages (Not Found)
 */
?>
<div class="not-found-loop row">
	<div class="ghostly-wrap">
		<h2 class="entry-title"><?php _e('.404 !'); ?></h2>
		<div class="entry-content">
			<p><?php _e( 'Nothing matched your search criteria. Keep search: '); ?></p>
			<?php al_search_form(); ?>
		</div>
	</div>
</div>