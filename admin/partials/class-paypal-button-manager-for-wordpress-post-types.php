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
        add_action('init', array(__CLASS__, 'paypal_button_manager_remove_paypal_buttons_editor'), 10);
        add_action('save_post', array(__CLASS__, 'paypal_button_manager_button_interface_display'));
        add_filter('gettext', array(__CLASS__, 'paypal_button_manager_change_publish_button'), 10, 2);
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
                        'name' => __('PayPal Button Manager', 'paypal_button_manager_for_wordpress'),
                        'singular_name' => __('PayPal Button', 'paypal_button_manager_for_wordpress'),
                        'menu_name' => _x('PayPal Button Manager', 'Admin menu name', 'paypal_button_manager_for_wordpress'),
                        'add_new' => __('Add PayPal Button', 'paypal_button_manager_for_wordpress'),
                        'add_new_item' => __('Add New PayPal Button', 'paypal_button_manager_for_wordpress'),
                        'edit' => __('Edit', 'paypal_button_manager_for_wordpress'),
                        'edit_item' => __('View PayPal Button', 'paypal_button_manager_for_wordpress'),
                        'new_item' => __('New PayPal Button', 'paypal_button_manager_for_wordpress'),
                        'view' => __('View PayPal Button', 'paypal_button_manager_for_wordpress'),
                        'view_item' => __('View PayPal Button', 'paypal_button_manager_for_wordpress'),
                        'search_items' => __('Search PayPal Buttons', 'paypal_button_manager_for_wordpress'),
                        'not_found' => __('No PayPal buttons found', 'paypal_button_manager_for_wordpress'),
                        'not_found_in_trash' => __('No PayPal buttons found in trash', 'paypal_button_manager_for_wordpress'),
                        'parent' => __('Parent PayPal Button', 'paypal_button_manager_for_wordpress')
                    ),
                    'description' => __('This is where you can create new PayPal buttons.', 'paypal_button_manager_for_wordpress'),
                    'public' => false,
                    'show_ui' => true,
                    'capability_type' => 'post',
                    'capabilities' => array(
                        'create_posts' => true, // Removes support for the "Add New" function
                    ),
                    'map_meta_cap' => true,
                    'publicly_queryable' => false,
                    'exclude_from_search' => true,
                    'hierarchical' => false, // Hierarchical causes memory issues - WP loads all records!
                    'rewrite' => array('slug' => 'paypal_buttons'),
                    'query_var' => true,
                    'menu_icon' => BMW_PLUGIN_URL . 'admin/images/paypal-button-manager-for-wordpress-icon.png',
                    'supports' => array('title'),
                    'has_archive' => true,
                    'show_in_nav_menus' => true
                        )
                )
        );
    }

    public static function paypal_button_manager_change_publish_button($translation, $text) {

        global $post;

        if (isset($post->post_type) && !empty($post->post_type)) {
            if ('paypal_buttons' == $post->post_type) {
                if ($text == 'Publish') {
                    return 'Create Button';
                } else if ($text == 'Update') {
                    return 'Update Button';
                }
            }
        }

        return $translation;
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

    public static function paypal_button_manager_remove_paypal_buttons_editor() {
        remove_post_type_support('paypal_buttons', 'editor');
    }

    public static function my_paypal_buttons_columns($column, $post_id) {
        global $post;
        switch ($column) {
            case 'shortcodes' :
                $shortcode_avalabilty = get_post_meta($post_id, 'paypal_button_response', true);
                if (isset($shortcode_avalabilty) && !empty($shortcode_avalabilty)) {
                    echo '[paypal_button_manager id=' . $post_id . ']';
                } else {
                    echo "Not Available";
                }

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
        global $post, $post_ID;
        $paypal_button_html = get_post_meta($post_ID, 'paypal_button_response', true);
        if (isset($paypal_button_html) && !empty($paypal_button_html)) {
            ?>
            <h3>Paste the button code in your post or page editor:</h3><br/>
            <textarea id="txtarea_response" cols="70" rows="10"><? echo $paypal_button_html; ?></textarea>
            <br/><br/>
            <h3>Paste the below wordpress shortcode in your post or page editor:</h3><br/>
            <lable class='h3padding'><?php echo '[paypal_button_manager id=' . $post_ID . ']'; ?></lable>			

            <?php
        } else {
            if (get_option('enable_sandbox') == 'yes') {

                $APIUsername = get_option('paypal_api_username_sandbox');
                $APIPassword = get_option('paypal_password_sandbox');
                $APISignature = get_option('paypal_signature_sandbox');
            } else {

                $APIUsername = get_option('paypal_api_username_live');
                $APIPassword = get_option('paypal_password_live');
                $APISignature = get_option('paypal_signature_live');
            }

            if ((isset($APIUsername) && !empty($APIUsername)) && (isset($APIPassword) && !empty($APIPassword)) && (isset($APISignature) && !empty($APISignature))) {
                do_action('paypal_button_manager_interface');
            } else {
                echo "Please fill your API credentials properly. &nbsp;&nbsp;<a href='/wp-admin/options-general.php?page=paypal-button-manager-for-wordpress-option'> Go to API Settings </a>";
            }
        }
    }

    public static function paypal_button_manager_button_interface_display() {

        global $post, $post_ID;

        $paypal_button_html = get_post_meta($post_ID, 'paypal_button_response', true);


        if (((isset($_POST['publish'])) || isset($_POST['save'])) && ($post->post_type == 'paypal_buttons')) {
            if (empty($paypal_button_html)) {
                do_action('paypal_button_manager_button_generator');
            } else {
                update_post_meta($post_ID, 'paypal_button_manager_success_notice', '');
            }
        }
    }

}

Paypal_button_Manager_For_Wordpress_Post_types::init();