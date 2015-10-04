<div id="plugin-admin-container" class="clearfix">
	<h1><?php echo apply_filters('aa_plugin_basetitle', ucfirst(str_replace('_', ' ', $_GET['page']))) ?></h1>
	<?php do_action('aa_before_plugin_content') ?>
	<div class="aa-wrap clearfix">
		<?php do_action('aa_plugin_content') ?>
	</div>
	<?php do_action('aa_after_plugin_content') ?>
</div>