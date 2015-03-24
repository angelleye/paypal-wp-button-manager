<?php

/**
 *
 * Registers post types and taxonomies
 *
 * @class       Paypal_button_Manager_For_Wordpress_Post_types
 * @version		1.0.0
 * @package		paypal-button-manager-for-wordpress
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class Paypal_button_Manager_For_Wordpress_Post_types {

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public static function init() {
        add_action('admin_print_scripts', array(__CLASS__, 'disable_autosave'));
        add_action('init', array(__CLASS__, 'paypal_button_manager_for_wordpress_register_post_types'), 5);
        add_action('add_meta_boxes', array(__CLASS__, 'paypal_button_manager_for_wordpress_add_meta_boxes'), 10);
        add_filter('manage_edit-paypal_buttons_columns', array(__CLASS__, 'my_edit_paypal_buttons_columns'));
        add_action('manage_paypal_buttons_posts_custom_column', array(__CLASS__, 'my_paypal_buttons_columns'), 10, 2);

        add_action('save_post', array(__CLASS__, 'paypal_button_manager_button_interface_display'));
    }

    /**
     * Disable the auto-save functionality for Paypal buttons.
     * @since    1.0.0
     * @access   public
     * @return void
     */
    public static function disable_autosave() {
        global $post;

        if ($post && get_post_type($post->ID) === 'paypal_button_manager') {
            wp_dequeue_script('autosave');
        }
    }

    /**
     * paypal_button_manager_for_wordpress_register_post_types function
     * @since    1.0.0
     * @access   public
     */
    public static function paypal_button_manager_for_wordpress_register_post_types() {
        global $wpdb;
        if (post_type_exists('paypal_button_manager_for_wordpress')) {
            return;
        }


        do_action('paypal_button_manager_for_wordpress_register_post_types');

        register_post_type('paypal_buttons', apply_filters('paypal_button_manager_for_wordpress_register_post_types', array(
                    'labels' => array(
                        'name' => __('PayPal buttons', 'paypal_button_manager_for_wordpress'),
                        'singular_name' => __('PayPal buttons', 'paypal_button_manager_for_wordpress'),
                        'menu_name' => _x('PayPal buttons', 'Admin menu name', 'paypal_button_manager_for_wordpress'),
                        'add_new' => __('Add PayPal buttons', 'paypal_button_manager_for_wordpress'),
                        'add_new_item' => __('Add New PayPal buttons', 'paypal_button_manager_for_wordpress'),
                        'edit' => __('Edit', 'paypal_button_manager_for_wordpress'),
                        'edit_item' => __('View PayPal buttons', 'paypal_button_manager_for_wordpress'),
                        'new_item' => __('New PayPal buttons', 'paypal_button_manager_for_wordpress'),
                        'view' => __('View PayPal buttons', 'paypal_button_manager_for_wordpress'),
                        'view_item' => __('View PayPal buttons', 'paypal_button_manager_for_wordpress'),
                        'search_items' => __('Search PayPal buttons', 'paypal_button_manager_for_wordpress'),
                        'not_found' => __('No PayPal buttons found', 'paypal_button_manager_for_wordpress'),
                        'not_found_in_trash' => __('No PayPal buttons found in trash', 'paypal_button_manager_for_wordpress'),
                        'parent' => __('Parent PayPal buttons', 'paypal_button_manager_for_wordpress')
                    ),
                    'description' => __('This is where you can add new PayPal buttons.', 'paypal_button_manager_for_wordpress'),
                    'public' => true,
                    'show_ui' => true,
                    'capability_type' => 'post',
                    'capabilities' => array(
                        'create_posts' => true, // Removes support for the "Add New" function
                    ),
                    'map_meta_cap' => true,
                    'publicly_queryable' => true,
                    'exclude_from_search' => false,
                    'hierarchical' => false, // Hierarchical causes memory issues - WP loads all records!
                    'rewrite' => array('slug' => 'paypal_buttons'),
                    'query_var' => true,
                    'menu_icon' => BMW_PLUGIN_URL . 'admin/images/paypal-button-manager-for-wordpress-icon.png',
                    'supports' => array('title', ''),
                    'has_archive' => true,
                    'show_in_nav_menus' => true
                        )
                )
        );
    }

    public static function my_edit_paypal_buttons_columns($columns) {

        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Button Name'),
            'shortcodes' => __('Shortcodes'),
            'date' => __('Date')
        );

        return $columns;
    }

    public static function my_paypal_buttons_columns($column, $post_id) {
        global $post;
        switch ($column) {
            case 'shortcodes' :
                echo '[paypal_button_manager id=' . $post_id . ']';
                break;
            case 'publisher' :
                echo get_post_meta($post_id, 'publisher', true);
                break;
        }
    }

    public static function paypal_button_manager_for_wordpress_add_meta_boxes() {
        add_meta_box('paypal-buttons-meta-id', 'Paypal Button Generator', array(__CLASS__, 'paypal_button_manager_for_wordpress_metabox'), 'paypal_buttons', 'normal', 'high');
    }

    public static function paypal_button_manager_for_wordpress_metabox() {

        do_action('paypal_button_manager_interface');
    }

    public static function paypal_button_manager_button_interface_display() {
        if (isset($_POST['button_submit'])) {
            do_action('paypal_button_manager_button_generator');
        }
    }

}

Paypal_button_Manager_For_Wordpress_Post_types::init();