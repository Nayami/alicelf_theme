<?php

/*
 * Walker Of  Main Menu
 */

class AliceNavigator extends Walker_Nav_Menu {

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"sub-menu dropdown-menu dropdown-onhover\">\n";
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent     = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$value      = '';
		$classes    = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[ ] = 'menu-item-' . $item->ID;
		/* If this item has a dropdown menu, add the 'dropdown' class for Bootstrap */

		$item->hasChildren && $classes[ ] = 'dropdown';
		in_array( 'current-menu-item', $classes ) && $classes[ ] = 'active ';

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names . '>';

		$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

		// new addition for active class on the a tag
		// Add attributes for active link in menu
		in_array( 'current-menu-item', $classes ) && $attributes .= 'class="al-active"';

		$item_output = '<span class="title-icons glyphicon ' . esc_attr( $item->attr_title ) . ' "></span><a' . $attributes . '>';
		$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
		if ( $item->hasChildren && $depth == 0 ) {
			$item_output .= '<span class="caret"></span></a>';
		} elseif ( $item->hasChildren && $depth >= 1 ) {
			$item_output .= '<span class="caret right-caret"></span></a>';
		} else {
			$item_output .= '</a>';
		}

		/* Output the actual caret for the user to click on to toggle the menu */
		if ( $item->description ) {
			$item_output .= "<span>$item->description</span>";
		}

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
		// check whether this item has children, and set $item->hasChildren accordingly
		$element->hasChildren = isset( $children_elements[ $element->ID ] ) && ! empty( $children_elements[ $element->ID ] );

		// continue with normal behavior
		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}

// Allow HTML descriptions in WordPress Menu
remove_filter( 'nav_menu_description', 'strip_tags' );
add_filter( 'wp_setup_nav_menu_item', 'al_allow_html' );
function al_allow_html( $menu_item ) {
	$menu_item->description = apply_filters( 'nav_menu_description', $menu_item->post_content );

	return $menu_item;
}