<?php
/**
 * Menu item custom fields
 *
 * @package Menu_Item_Custom_Fields
 * @version 1.0
 * @author Galina Bublik <galinka.fie@gmail.com>
 *
 */


/**
 * Sample menu item metadata
 *
 *
 * @since 1.0
 */
class Menu_Item_Custom_Fields {

	/**
	 * Holds our custom fields
	 *
	 * @var    array
	 * @access protected
	 * 
	 */
	protected static $fields = array();
	protected static $templates = array();


	/**
	 * Initialize plugin
	 */
	public static function init() {
   		add_filter( 'wp_edit_nav_menu_walker', array( __CLASS__, '_filter_walker' ), 100 );
    	add_filter( 'wp_nav_menu_item_custom_fields', array( __CLASS__, '_fields' ), 20, 4 );
		add_action( 'wp_update_nav_menu_item', array( __CLASS__, '_save' ), 10, 3 );
		add_filter( 'manage_nav-menus_columns', array( __CLASS__, '_columns' ), 99 );

    	add_filter( 'wp_nav_menu_args', array( __CLASS__, 'add_menu_walker' ) );
		add_filter( 'wp_nav_menu', array( __CLASS__, '_remove_menu_item_title_filter' ) );

     	add_action( 'admin_enqueue_scripts', array( __CLASS__, 'amcf_scripts_styles' ), 10 );

		self::$fields = get_option( 'menu_custom_fieds' );
		self::$templates = get_option( 'menu_template' );
	}

	public static function amcf_scripts_styles( ) {

		wp_enqueue_script('upload-field', AMCF_RELPATH . '/js/upload_field.js'); 
		wp_enqueue_style('menu-fields-style', AMCF_RELPATH . '/css/menu-options-style.css'); 
		
	}

	/**
	 * Add filter to 'the_title' hook
	 *
	 * We need to filter the menu item title but **not** regular post titles.
	 * Thus, we're adding the filter when `wp_nav_menu()` is called.
	 *
	 * @since   1.0
	 * @wp_hook filter wp_nav_menu_args
	 * @param   array  $args Not used.
	 *
	 * @return array
	 */

	public static function add_menu_walker( $args ) {
		//print_r($args);
		if(self::$templates[ $args['theme_location'] ]['type'] == 'php'){
		//if(false){
			$walker = self::front_walker($args['walker']);
			$args['walker'] = $walker;
		} else {
			add_filter( 'the_title', array( __CLASS__, '_add_fields' ), 999, 2 );
		}
		return $args;
	}


	/**
	 * Remove filter from 'the_title' hook
	 *
	 * Because we don't want to filter post titles, we need to remove our
	 * filter when `wp_nav_menu()` exits.
	 *
	 * @since   1.0
	 * @wp_hook filter wp_nav_menu
	 * @param   array  $nav_menu Not used.
	 * @return  array
	 */
	public static function _remove_menu_item_title_filter( $nav_menu ) {
		remove_filter( 'the_title', array( __CLASS__, '_add_fields' ), 999, 2 );
		
		return $nav_menu;
	}

	/**
	 * Add fields to menu item title
	 *
	 * @since   1.0
	 * @wp_hook filter  the_title
	 * @param   string  $title     Menu item title.
	 * @param   int     $id        Menu item ID.
	 *
	 * @return string
	 */
	public static function _add_fields( $title, $item ) {
		global $menu;
		$locations = get_nav_menu_locations();
		$term = get_the_terms($item, 'nav_menu');
		$loc = array_search($term[0]->term_id, $locations);
		$template = stripslashes(self::$templates[$loc]['menu_template']);
		$parts = explode('%', $template);
		//print_r($parts);
		$output = $template;

		if($parts){
			foreach ($parts as $key => $value) {
					$number = explode('-', $value);
				if( preg_match('/^image\-\d{2}$/', $value)){
					if(get_post_meta($item, 'menu-item-field-'.$number[1], true)){
				        $img = image_downsize( get_post_meta($item, 'menu-item-field-'.$number[1], true), 'thumbnail');
						$image .= '<img src="'. $img[0].'" alt="!!!">';
						$output = preg_replace('/%image\-'.$number[1].'%/', $image, $output);
					} else {
						$output = preg_replace('/%image\-'.$number[1].'%/', '', $output);
					}
				} else if( preg_match('/^text\-\d{2}$/', $value) ){
					if(get_post_meta($item, 'menu-item-field-'.$number[1], true)){
				        $meta = get_post_meta($item, 'menu-item-field-'.$number[1], true);
						$output = preg_replace('/%text\-'.$number[1].'%/', $meta, $output);
					} else {
						$output = preg_replace('/%text\-'.$number[1].'%/', '', $output);
					}
				} else if( preg_match('/^select\-\d{2}$/', $value) ){
					if(get_post_meta($item, 'menu-item-field-'.$number[1], true)){
				        $meta = get_post_meta($item, 'menu-item-field-'.$number[1], true);
						$output = preg_replace('/%select\-'.$number[1].'%/', $meta, $output);
					} else {
						$output = preg_replace('/%select\-'.$number[1].'%/', '', $output);
					}
				} else if( preg_match('/^radio\-\d{2}$/', $value) ){
					if(get_post_meta($item, 'menu-item-field-'.$number[1], true)){
				        $meta = get_post_meta($item, 'menu-item-field-'.$number[1], true);
						$output = preg_replace('/%radio\-'.$number[1].'%/', $meta, $output);
					} else {
						$output = preg_replace('/%radio\-'.$number[1].'%/', '', $output);
					}

				} else if( preg_match('/^checkbox\-\d{2}.{0,50}$/', $value) ){
					if(get_post_meta($item, 'menu-item-field-'.$number[1], true)){
				        $meta = $number[2];
						$output = preg_replace('/%checkbox\-'.$number[1].'(\-'.$number[2].')?(\-'.$number[3].')?%/', $meta, $output);
					} else {
						$output = preg_replace('/%checkbox\-'.$number[1].'(\-'.$number[2].')?(\-'.$number[3].')?%/', $number[3], $output);
					}

				}
			}
		}
			
			$output = preg_replace('/%title%/', $title, $output);

		return $output;
	}




	/**
	 * Save custom field value
	 * @since   1.0
	 * @wp_hook action wp_update_nav_menu_item
	 *
	 * @param int   $menu_id         Nav menu ID
	 * @param int   $menu_item_db_id Menu item ID
	 * @param array $menu_item_args  Menu item data
	 */
	public static function _save( $menu_id, $menu_item_db_id, $menu_item_args ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

		foreach ( self::$fields as $_key => $label ) {
			$key = sprintf( 'menu-item-%s', $_key );

			// Sanitize
			if ( ! empty( $_POST[ $key ][ $menu_item_db_id ] ) ) {
				// Do some checks here...
				$value = $_POST[ $key ][ $menu_item_db_id ];
			}
			else {
				$value = null;
			}

			// Update
			if ( ! is_null( $value ) ) {
				update_post_meta( $menu_item_db_id, $key, $value );
			}
			else {
				delete_post_meta( $menu_item_db_id, $key );
			}
		}
	}


	/**
	 * Print field
	 * @since   1.0
	 *
	 * @param object $item  Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args  Menu item args.
	 * @param int    $id    Nav menu ID.
	 *
	 * @return string Form fields
	 */
	public static function _fields( $id, $item, $depth, $args ) {
		wp_enqueue_media();

		if( !empty(self::$fields) ){ 
		foreach ( self::$fields as $_key => $field ) :
			$key   = sprintf( 'menu-item-%s', $_key );
			$id    = sprintf( 'edit-%s-%s', $key, $item->ID );
			$name  = sprintf( '%s[%s]', $key, $item->ID );
			$label = $field['label'];
			$value = $field['value'];
		
			$class = sprintf( 'field-%s', $_key );
			?>
				<p class="description description-wide <?php echo esc_attr( $class ) ?>">
					<?php 
					switch ($field['type']) {
						
						case 'checkbox':
							if(get_post_meta( $item->ID, $key, true )){
								$value = 'checked';	
							}
							printf(
								'<label for="%1$s"><input type="checkbox" id="%1$s" class="widefat %1$s" name="%3$s" %4$s />%2$s</label>',
								esc_attr( $id ),
								esc_html( $label ),
								esc_attr( $name ),
								$value 
							);
							break;
						case 'select':
							$selected = get_post_meta( $item->ID, $key, true );
							?>
							<label for="<?php echo esc_attr( $id ); ?>">
						    	<?php echo esc_html( $label ); ?>
						    	<select name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>">
						    		<option value="">Select</option>
						    		<?php $values = explode(',', $value);
						    		foreach ($values as $k => $val) { ?>
						    			<option value="<?php echo trim($val); ?>" <?php selected(trim($val), $selected); ?> ><?php echo trim($val); ?></option>
						    		<?php } ?>
						    	</select>
							</label>
							<?php break;
						case 'image':
							$url= '';
							$value = get_post_meta( $item->ID, $key, true );
							if($value){
								$img = image_downsize( $value, 'thumbnail' ); 
						        $url= $img[0]; 
						    } ?>
						    <label for="<?php echo esc_attr( $id ); ?>">
						    	<?php echo esc_html( $label ); ?>
						    	<img src="<?php echo esc_attr( $url ); ?>" id="<?php echo esc_attr( $id ); ?>-img" width="100">
						    	<input type="hidden" id="<?php echo esc_attr( $id ); ?>" class="widefat <?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
							<input id="<?php echo esc_attr( $id ); ?>-button" type="button" class="icon-button button" value="Download" <?php if($url){ ?>style="display: none;"<?php } ?> /><a href="#<?php echo esc_attr( $id ); ?>" class="remove-icon button" 
							<?php if(!$url){ ?>style="display: none;"<?php } ?>
							><span class="dashicons dashicons-no"></span></a> </label>
						<?php
							break;
						case 'radio':
							$selected = get_post_meta( $item->ID, $key, true );
							?>
							<label for="">
						    	<?php echo esc_html( $label ); ?>
							</label>
				    		<?php if( preg_match('/\|/', $value)){
					    		$values = explode('|', $value);
				    		} else {
					    		$values = explode(',', $value);
					    	}
				    		foreach ($values as $k => $val) { ?>
				    			<input type="radio" name="<?php echo esc_attr( $name ); ?>" value="<?php echo trim($val); ?>" <?php checked(trim($val), $selected); ?>  /><?php echo trim($val); ?>
				    		<?php } ?>
						<?php
							break;
						default:
							if(get_post_meta( $item->ID, $key, true )){
								$value = get_post_meta( $item->ID, $key, true );
							}
							printf('<label for="%1$s">%2$s<input type="text" id="%1$s" class="widefat %1$s" name="%3$s" value="%4$s" /></label>',
							esc_attr( $id ),
							esc_html( $label ),
							esc_attr( $name ),
							esc_attr( $value )
						);
							break;
					}?>
				</p>
			<?php
		endforeach;
		}
	}


	/**
	 * Add our fields to the screen options toggle
	 * @since   1.0
	 *
	 * @param array $columns Menu item columns
	 * @return array
	 */
	public static function _columns( $columns ) {
		$columns = array_merge( $columns, self::$fields );

		return $columns;
	}


	/**
	 * Custom walker
	 *
	 * @since   1.0
	 * @access  protected
	 * @wp_hook filter    wp_edit_nav_menu_walker
	 */
	public static function _filter_walker( $walker ) {
			$walker = 'Menu_Item_Custom_Fields_Walker';
			if ( ! class_exists( $walker ) ) {
				require_once AMCF_ABSPATH . '/walker/walker-nav-menu-edit.php';
			}

			return $walker;
		}

	/**
	 * Custom front walker
	 *
	 * @since   1.0
	 * @access  protected
	 * @wp_hook filter    wp_nav_menu_walker
	 */
	public static function front_walker( $walker ) {
		// Load menu item custom fields plugin
		if ( ! class_exists( 'Menu_Item_Front_Walker' ) ) {
			require_once AMCF_ABSPATH . '/walker/walker-nav-menu-front.php';
		}
		$walker = new Menu_Item_Front_Walker;

		return $walker;
	}
}

Menu_Item_Custom_Fields::init();
