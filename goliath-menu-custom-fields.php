<?php
/*
 *  Plugin Name:    Goliath - Menu Custom Fields
 *  Plugin URI:     https://github.com/studio-goliath/goliath-menu-custom-fields
 *  Description:    Ever wanted custom fields on menu items ?
 *  Version:        1.0.0
 *  Author:         Alain Diart for Studio-Goliath
 *  Author URI:     http://studio-goliath.com
 *  License:        GPLv2 or later
 *  License URI:    http://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH'))
    exit;

if (!class_exists('goliath_menu_custom_fields')) {

    class goliath_menu_custom_fields
    {
        private $args;

        function __construct($args)
        {
            $this->args = $args;

            $GLOBALS['GOLIATH_MENU_CUSTOM_FIELDS_ARGS'] = $this->args;

            add_filter('wp_setup_nav_menu_item', array(&$this, 'hook_wp_setup_nav_menu_item'));
            add_action('wp_update_nav_menu_item', array(&$this, 'hook_wp_update_nav_menu_item'), 10, 3);
            add_filter('wp_edit_nav_menu_walker', array(&$this, 'hook_wp_edit_nav_menu_walker'), 10, 2);
        }

        function hook_wp_setup_nav_menu_item($menu_item)
        {
            foreach($this->args as $location => $fields) {
                foreach($fields as $field_name => $field_attr) {
                    $menu_item->$field_name = get_post_meta($menu_item->ID, '_menu_item_'.$field_name, true);
                }
            }

            return $menu_item;
        }

        function hook_wp_update_nav_menu_item($menu_id, $menu_item_db_id, $args)
        {
            foreach($this->args as $location => $fields) {
                foreach($fields as $field_name => $field_attr) {
                    if (isset($_REQUEST['menu-item_'.$field_name]) && is_array($_REQUEST['menu-item_'.$field_name]) && isset($_REQUEST['menu-item_'.$field_name][$menu_item_db_id])) {
                        $field_value = $_REQUEST['menu-item_'.$field_name][$menu_item_db_id];
                        update_post_meta($menu_item_db_id, '_menu_item_'.$field_name, $field_value);
                    }
                }
            }
        }

        function hook_wp_edit_nav_menu_walker($walker, $menu_id)
        {
            $menu_locations = get_nav_menu_locations();

            foreach($this->args as $location => $fields) {
                if (array_key_exists($location, $menu_locations) && $menu_locations[$location] == $menu_id) {
                    require_once('goliath-walker-nav-menu-edit.php');

                    return 'Goliath_Walker_Nav_Menu_Edit';
                }
            }

            return $walker;
        }
    }
}