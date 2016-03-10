<?php 
/*
Plugin Name: Menu custom fields
Description: Custom Fields for Menu Item
Version: 1.0.0
Author: ArtMyWeb
Author URI: http://artmyweb.com
License: GPL v2
Plugin URI: 
*/


    define( 'AMCF_ABSPATH', dirname( __FILE__ ) );
	define( 'AMCF_RELPATH', plugins_url() .'/'. basename(dirname(__FILE__))  );


	register_deactivation_hook( __FILE__, 'amcf_deactivation_hook' );
	register_activation_hook( __FILE__, 'amcf_activation_hook' );


	// init hook for module manager
	add_action( 'init', 'amcf_wp_init' );
	function amcf_wp_init(){
		require_once dirname( __FILE__ ) . '/menu-fields.php';

	}

	/**
	 * Deactivation hook.
	 *
	 * Reset some of data.
	 */
	function amcf_deactivation_hook()
	{
	    // Delete messages
	    //delete_option( 'menu_custom_fieds' );
	    //delete_option( 'menu_custom_fieds' );
	}

	/**
	 * Activation hook.
	 *
	 * Add default options.
	 */
	function amcf_activation_hook()
	{
	    $default_fields = array(
				'field-01' => array(
					'label'=> __( 'Some text', 'amcf' ),
					'type'=> 'text',
					'value' => ''
				)
		);
		$locs = get_nav_menu_locations(); 
			if($locs){
				foreach ($locs as $k => $m) {
					$default_template[$k] = array(
					'type' => 'title',
					'php_template' => '',
					'menu_template' => '%title%',
					);
				} 
			}
		
	    add_option( 'menu_custom_fieds', $default_fields, '', 'no' );
	    add_option( 'menu_template', $default_template, '', 'no' );

	}

	/**
	 * Admin menu hook.
	 *
	 * Add default options.
	 */

	function amcf_add_menu_page (){
		//add_menu_page("Menu custom fields", "Menu custom fields", 10, "menu_custom_fields_options", "amcf_menu_page");
		add_submenu_page('options-general.php', "Menu custom fields", "Menu custom fields", 10, "menu_custom_fields_options", "amcf_menu_page");
	}

	add_action("admin_menu","amcf_add_menu_page");

	function amcf_menu_page(){
		wp_enqueue_script('jquery-ui-sortable');
		include 'options_page.php';
	}
