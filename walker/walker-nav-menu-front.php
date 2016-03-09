<?php

/**
 * Custom Walker for Nav Menu Editor
 *
 * We're separating this class from the plugin file because Walker_Nav_Menu_Edit
 * is only loaded on the wp-admin/nav-menus.php page.
 *
 * @package Menu_Item_Custom_Fields
 * @version 1.0
 * @author Galina Bublik <galinka.fie@gmail.com>
 */

/**
 * Menu item custom fields front walker
 *
 *
 * @since 1.0
 */

class Menu_Item_Front_Walker extends Walker_Nav_Menu {

	/**
	 * Start the element output.
	 *
	 * We're injecting our custom fields after the div.submitbox
	 *
	 * @see Walker_Nav_Menu::start_el()
	 * @since 1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   Menu item args.
	 */
	function start_el(&$output, $item, $depth, $args) {
        $location = $args->theme_location;
		$menu = get_option( 'menu_template' );
        //print_r($menu);
        /*
		$class_names = ' class="' . esc_attr( implode(' ', $item->classes) ) . '"';

        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

        $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : '';

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';

        $item_output .= $args->link_before;

        //START Display the image field
          if(get_post_meta($item->ID, 'menu-item-field-02', true) ){
	          $img = image_downsize( get_post_meta($item->ID, 'menu-item-field-02', true), 'thumbnail');
	          $item_output .= '<img src="'. $img[0].'" alt="">';
	      }
	    //END Display the image field

          $item_output .= '<span>'.$item->title.'</span>';

        //START Use checkbox field
        if( get_post_meta($item->ID, 'menu-item-field-04', true) ){
        	$item_output .= '<span>'.$item->description.'</span>';
    	}
        //END Use checkbox field

        //START Display text or select field
    	if( get_post_meta($item->ID, 'menu-item-field-03', true) ){
        	$item_output .= '<span>'.get_post_meta($item->ID, 'menu-item-field-03', true).'</span>';
    	}
        //END Display text or select field

        $item_output .= $args->link_after;

        $item_output .= '</a>';
        $item_output .= $args->after;
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

   		*/
		eval( stripslashes(trim($menu[$location]['php_template'])) );		
		
		return $output;
	}

}
