<footer id="footer" class="navbar navbar-default row">
	<?php
		global $alicelf;
		do_action('aa-before-footerstart');
	?>
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
	<div class="ghostly-wrap text-center">
		<?php echo nl2br($alicelf['opt-company-copyright']) ?>
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