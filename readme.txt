=== Goliath - Menu Custom Fields ===
Contributors: Alain Diart for Studio-Goliath
Tags: menu, custom fields
Requires at least: 3.5
Tested up to: 3.8.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Ever wanted custom fields on wordpress menu items ?


== Description ==

With this developer oriented plugin you can add custom fields to menu items.

This plugin has been done for a custom project we're working on, so it may not suit all your needs. It's been greatly inspired from http://www.wpexplorer.com/adding-custom-attributes-to-wordpress-menus/ but does more than adding a single field.

With this plugin :

* you can have different fields for each menu locations
* you can use two kind of controls in the admin : simple text input and textarea, it shouldn't be that hard to add more controls (checkboxes, ...)

But no,

* You can't define your custom fields in the admin, you have to write code for that
* It does not work in WordPress customizer, only menu edition admin page.
* It does not include any walker for the frontend.


== Installation ==

1. Copy the `goliath-menu-custom-fields` folder into your `wp-content/plugins` folder
2. Activate the `Goliath - Menu Custom Fields` plugin via the plugins admin page
3. Edit your functions.php file to instanciate the plugin's class :

// You should have this kind of code already present in your theme :
function site_register_nav_menus()
{
    register_nav_menus(
        array(
            'main'        => __('Main menu'),
            'lang'        => __('Language menu'),
            'foot'        => __('Footer menu'),
        )
    );
}
add_action('after_setup_theme', 'site_register_nav_menus');

// Then you must add and configure this code
if (class_exists('goliath_menu_custom_fields')) {
    $menu_custom_fields = array(
        'main' => array( // The key here has to match with the locations defined before
            'custom-field-01' => array( // The key should not have spaces nor special characters (think slug)
                'label' => __('Custom Field 1'), // The label in admin for this field
                'type' => 'input', // The type : input or textarea
            ),
            'custom-field-02' => array(
                'label' => __('Custom Field 2'),
                'type' => 'textarea',
            ),
        ),
        'foot' => array(
            'custom-field-01' => array( // If your menu is set for both main and foot locations, then this field will not be replicated
                'label' => __('Custom Field 1'),
                'type' => 'textarea',
            ),
        ),
    );
    new goliath_menu_custom_fields($menu_custom_fields);
}


== Changelog ==

= 1.0.0 =
* Initial Release.