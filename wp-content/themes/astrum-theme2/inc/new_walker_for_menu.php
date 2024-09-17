<?php 
if( is_admin() ){

	if( ! class_exists('Walker_Nav_Menu_Edit') )
		require_once ABSPATH . 'wp-admin/includes/class-walker-nav-menu-edit.php';

	## Наш новый волкер, который подправляет родителя. 
	## Вылеживаемся, потому что нет подходящего хука...
	class Custom_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {

		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$item_output = '';

			parent::start_el( $item_output, $item, $depth, $args, $id );

			$output .= preg_replace(
				// может со временем отвалиться - надо првоерять...
				'/(?=<(fieldset|p)[^>]+class="[^"]*field-move)/', 
				Menu_Item_Custom_Fields_Example::_fields( $item->ID, $item, $depth, $args ),
				$item_output
			);
		}

	}

	/**
	 * Sample menu item metadata
	 *
	 * This class demonstrate the usage of Menu Item Custom Fields in plugins/themes.
	 *
	 * @since 0.1.0
	 */
	class Menu_Item_Custom_Fields_Example {
		/**
		 * Holds our custom fields
		 *
		 * @var    array
		 * @access protected
		 * @since  Menu_Item_Custom_Fields_Example 0.2.0
		 */
		protected static $fields = array(
			'checkbox_collapse' => 'Collapse',
		);

		/**
		 * Initialize plugin
		 */
		public static function init() {

			add_action( 'wp_update_nav_menu_item', array( __CLASS__, '_save' ), 10, 3 );
			add_filter( 'manage_nav-menus_columns', array( __CLASS__, '_columns' ), 99 );

			## заменим базовый волкер
			add_filter( 'wp_edit_nav_menu_walker', function(){
				return 'Custom_Walker_Nav_Menu_Edit';
			}, 99 );
		}
		/**
		 * Save custom field value
		 *
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
				if ( !empty( $_POST[ $key ] ) ) {
					// Do some checks here...
					$index_arr = (int)$menu_item_db_id;
					$value = $_POST[ $key ][$index_arr ];
					if ( $value == on) {
						$value = 'checked';
					}
				} else {
					$value = null;
				}
				// Update
				if ( ! is_null( $value ) ) {
					update_post_meta( $menu_item_db_id, $key, $value );
				} else {			
					delete_post_meta( $menu_item_db_id, $key );
				}
			}

		}
		/**
		 * Print field
		 *
		 * @param object $item  Menu item data object.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args  Menu item args.
		 * @param int    $id    Nav menu ID.
		 *
		 * @return string Form fields
		 */
		public static function _fields( $id, $item, $depth, $args ) {
			ob_start();

			foreach ( self::$fields as $_key => $label ){
				$key   = sprintf( 'menu-item-%s', $_key );
				$id    = sprintf( 'edit-%s-%s', $key, $item->ID );
				$name  = sprintf( '%s[%s]', $key, $item->ID );
				$value = get_post_meta( $item->ID, $key, true );
				$class = sprintf( 'field-%s', $_key );
				?>
				<p class="description description-wide <?php echo esc_attr( $class ) ?>">
					<?php printf(
						'<label for="%1$s"><input type="checkbox" id="%1$s" class="widefat %1$s" name="%3$s"  %4$s/>%2$s</label>',
						esc_attr( $id ),
						esc_html( $label ),
						esc_attr( $name ),
						esc_attr( $value )
					) ?>
				</p>
				<?php
			}

			return ob_get_clean();
		}
		/**
		 * Add our fields to the screen options toggle
		 *
		 * @param array $columns Menu item columns
		 * @return array
		 */
		public static function _columns( $columns ) {
			$columns = array_merge( $columns, self::$fields );
			return $columns;
		}
		
		public static function getKeyFields(): array {
			if (empty(self::$fields)) {
				return [];
			}
			return array_keys(self::$fields);
		}
		
	}
	Menu_Item_Custom_Fields_Example::init();
}



class New_Astrum_Custom_Menu_Walker extends Walker_Nav_Menu {
	
	function start_el(&$output, $item, $depth=0, $args=[], $id=0) {
		$this_checked = get_post_meta( $item->ID, 'menu-item-checkbox_collapse', true);
		if ((int)$depth == 0 ) {
			
			if ($this_checked == 'checked' && $item->current_item_ancestor == false && $item->current_item_parent == false) {				
				$icon_caret = '<span class="sf-sub-indicator collapse-item-btn" depth="'.$depth.'" data-item-close="close"><i class="icon-angle-down collapse-i-btn"></i></span>';
			} else {
				$icon_caret = '<span class="sf-sub-indicator collapse-item-btn" depth="'.$depth.'" data-item-close="open" data-params="'.$item->current_item_ancestor.$item->current_item_parent.$item->current.'"><i class="icon-angle-down icon-angle-desktop"></i><i class="icon-angle-up collapse-i-btn icon-angle-mobile"></i></span>';
			}
			
		} else {
			
			if ($this_checked == 'checked' && $item->current_item_ancestor == false && $item->current_item_parent == false /*&& $item->current == false*/) {
				$icon_caret = '<span class="sf-sub-indicator collapse-item-btn" data-item-close="close"><i class="icon-angle-down collapse-i-btn icon-angle-mobile"></i><i class="icon-angle-right icon-angle-desktop"></i></span>';
			} else {
				$icon_caret = '<span class="sf-sub-indicator collapse-item-btn" data-item-close="open"><i class="icon-angle-up collapse-i-btn icon-angle-mobile"></i><i class="icon-angle-right icon-angle-desktop"></i></span>';
			}
			
		}

		if ( $this_checked == 'checked' && $item->current_item_ancestor == false && $item->current_item_parent == false) {
			$collapse_class = 'collapse-menu-item';
		} else {
			$collapse_class = '';
		}

		if (strpos($item->url, '/portfolio/') !== false && strpos(get_permalink(), $item->url) !== false) {
				$output .= '<li id="'.$checked.' menu-item-'.$item->ID.'" class="'.$collapse_class . implode(' ', $item->classes) . ' current-menu-item">';			
		} else {
			$output .= '<li id="'.$checked.' menu-item-'.$item->ID.'" class="'.$collapse_class .  implode(' ', $item->classes) . '">';
		}
		
		if ( $item->current || strpos($item->url, '/portfolio/') !== false && strpos(get_permalink(), $item->url) !== false) {
			 $output .= '<a aria-current="page" class="for-mobile-link menu-link-active-current" href="'.$item->url.'" data-wpel-link="internal" aria-current="page">';
		}
		else {
			$output .= '<a class="for-mobile-link" href="'.$item->url.'" data-wpel-link="internal">';
		}
       
        $output .= $item->title;
        if ($args->walker->has_children) {
			$output .= $icon_caret.'</a>';
		} else {
        	$output .= '</a>';
		}			
    }
	
}