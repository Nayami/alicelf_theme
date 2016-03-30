<?php

if ( !has_nav_menu('primary') ) {
	$menu_id = wp_create_nav_menu("Default Alicelf Menu");

	wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  __('Home'),
			'menu-item-classes' => 'home',
			'menu-item-url' => home_url( '/' ),
			'menu-item-status' => 'publish'));
	wp_nav_menu(array(
			'show_home'      => true,
			'menu_class'     => 'nav navbar-nav default-alicelf-navbar',
			'container'      => false,
			'walker'         => new AliceNavigator()
	));
} else {

	// @TODO: swith between several menu types
	?>
	<nav id="main-alicelf-nav" class="main-navigation" role="navigation">
		<?php
		$args = array(
			'show_home'      => true,
			'menu_class'     => 'nav navbar-nav',
			'theme_location' => 'primary',
			'container'      => false,
			'walker'         => new AliceNavigator()
		);
		wp_nav_menu( $args );
		?>
	</nav>

<?php } ?>