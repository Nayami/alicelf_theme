<footer id="footer" class="navbar navbar-default row">
	<div class="ghostly-wrap">
		<?php
		if ( has_nav_menu( 'footer_custom_menu' ) ) {
			$args = array(
				'show_home'      => true,
				'menu_class'     => 'nav navbar-nav',
				'theme_location' => 'footer_custom_menu',
				'container'      => false,
				'walker'         => new AliceNavigator()
			);
			wp_nav_menu( $args );
		}

		?>
	</div>
	<div class="ghostly-wrap">
		<p class="text-center"><?php bloginfo( 'name' ); ?> <strong> Theme: <?php echo wp_get_theme(); ?></strong></p>

	</div>
	<a href="#scroll-trigger-top" id="footer-angle-arrow" class="smooth-scrolled-item">
		<i class="glyphicon glyphicon-arrow-up"></i>
	</a>
</footer>
</div><!--END CONTAINER-->
</div><!--END MAIN-CONTENT-SITE-->
<?php
wp_footer();
do_action('after_theme_footer');
?>
</body>
</html>