<?php
/*
Plugin Name: Admin Search
Description: Quick launcher for WordPress admin pages, just press /
Plugin Version: 0.1
Author: Nikolay Bachiyski
Author URI: http://nikolay.bg/

TODO:
	* Better looks, probably move as part of the menu
	* Add icons (tricky, because the information is in CSS only)
	* More liberal search for multiple words: "media new", "new media"
	* TextMate style first-word-letter-search : nm matches "new media", "media new",
	* Safer removal of count bubbles, see the comment in remove_spans()
	* Add jQuery UI Autocomplete to core
	* Further away: search for text inside settings pages
*/

class WP_Admin_Search {

	function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	function remove_spans( $text ) {
		// Yeah, I know that this won't work for <span>Blah</span> CRUCIAL TEXT <span>xxx</span>,
		// but I don't care for now
		return preg_replace( '|<span.*</span>|', '', $text );
	}

	function build_menu_items() {
		global $menu, $submenu;
		$menu_items = array();
		foreach( $menu as $item ) {
			if ( !$item[0] ) continue;
			// Add top-level menus only if they have no sub-menus,
			// because always there is a sub-menu item, which links to the top-level menu
			if ( !isset( $submenu[$item[2]] ) ) {
				$menu_items[] = array( 'name' => $item[0], 'parent_name' => null, 'url' => admin_url( $item[2] ) );
				continue;
			}
			foreach( $submenu[$item[2]] as $sub_item ) {
				$url = strpos( $sub_item[2], '.php' ) === false? admin_url( $item[2] . '?page=' . $sub_item[2] ) : admin_url( $sub_item[2] );
				$menu_items[] = array( 'name' => $sub_item[0], 'parent_name' => $item[0], 'url' => $url );
			}
		}
		foreach( $menu_items as &$menu_item ) {
			// remove count bubbles, which are in spans
			$menu_item['name'] = $this->remove_spans( $menu_item['name'] );
			$menu_item['parent_name'] = $this->remove_spans( $menu_item['parent_name'] );
			
			$menu_item['label'] = '<span class="name">'.$menu_item['name'].'</span>';
			if ( $menu_item['parent_name'] ) {
				$menu_item['label'] = '<span class="parent">'.$menu_item['parent_name'].'</span> &rarr; '.$menu_item['label'];
			} 
		}
		return $menu_items;
	}

	function admin_init() {
		$this->menu_items = $this->build_menu_items();
		wp_enqueue_script( 'jquery-ui-autocomplete', plugins_url( basename( dirname( __FILE__ ) ) ) . '/ui.autocomplete.js', array('jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-position'), '1.8.2' );
		wp_enqueue_script( 'admin-search', plugins_url( basename( dirname( __FILE__ ) ) ) . '/admin-search.js', array('jquery-hotkeys', 'jquery-ui-autocomplete'), mt_rand() );
		wp_localize_script( 'admin-search', 'adminMenuItems', $this->menu_items);
		wp_enqueue_style( 'admin-search', plugins_url( basename( dirname( __FILE__ ) ) ) . '/admin-search.css', array(), mt_rand() );
	}
}

new WP_Admin_Search;