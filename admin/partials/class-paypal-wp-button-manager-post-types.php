<?php

/**
 *
 * Registers post types and taxonomies
 *
 * @class       AngellEYE_PayPal_WP_Button_Manager_Post_types
 * @version		1.0.0
 * @package		paypal-wp-button-manager
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_Post_types {

    /**
     * Hook in methods
     * @since    0.1.0
     * @access   static
     */
    public static function init() {

        add_action('admin_print_scripts', array(__CLASS__, 'disable_autosave'));
        add_action('init', array(__CLASS__, 'paypal_wp_button_manager_register_post_types'), 5);
        add_action('init', array(__CLASS__, 'paypal_wp_button_manager_register_taxonomy'), 5);
        add_action('add_meta_boxes', array(__CLASS__, 'paypal_wp_button_manager_add_meta_boxes'), 10);
        add_action('restrict_manage_posts', array(__CLASS__, 'paypal_wp_button_manager_restrict_manage_posts'));
        add_action('parse_query', array(__CLASS__, 'paypal_wp_button_manager_parse_query'));
        add_filter('manage_edit-paypal_buttons_columns', array(__CLASS__, 'paypal_wp_button_manager_edit_paypal_buttons_columns'));
        add_action('manage_paypal_buttons_posts_custom_column', array(__CLASS__, 'paypal_wp_button_manager_paypal_buttons_columns'), 10, 2);
        add_action('init', array(__CLASS__, 'paypal_wp_button_manager_remove_paypal_buttons_editor'), 10);
        add_action('save_post', array(__CLASS__, 'paypal_wp_button_manager_button_interface_display'));
        add_filter('gettext', array(__CLASS__, 'paypal_wp_button_manager_change_publish_button'), 10, 2);
    }

    /**
     * Disable the auto-save functionality for Paypal buttons.
     * @since    0.1.0
     * @access   public
     * @return void
     */
    public static function disable_autosave() {
        global $post;

        if ($post && get_post_type($post->ID) === 'paypal_wp_button_manager') {
            wp_dequeue_script('autosave');
        }
    }

    /**
     * paypal_wp_button_manager_register_post_types function is user for register custom post type
     * @since    0.1.0
     * @access   public
     */
    public static function paypal_wp_button_manager_register_post_types() {
        global $wpdb;
        if (post_type_exists('paypal_wp_button_manager')) {
            return;
        }


        do_action('paypal_wp_button_manager_register_post_types');

        register_post_type('paypal_buttons', apply_filters('paypal_wp_button_manager_register_post_types', array(
                    'labels' => array(
                        'name' => __('PayPal Buttons', 'paypal_wp_button_manager'),
                        'singular_name' => __('PayPal Button', 'paypal_wp_button_manager'),
                        'menu_name' => _x('PayPal Buttons', 'Admin menu name', 'paypal_wp_button_manager'),
                        'add_new' => __('Add PayPal Button', 'paypal_wp_button_manager'),
                        'add_new_item' => __('Add New PayPal Button', 'paypal_wp_button_manager'),
                        'edit' => __('Edit', 'paypal_wp_button_manager'),
                        'edit_item' => __('View PayPal Button', 'paypal_wp_button_manager'),
                        'new_item' => __('New PayPal Button', 'paypal_wp_button_manager'),
                        'view' => __('View PayPal Button', 'paypal_wp_button_manager'),
                        'view_item' => __('View PayPal Button', 'paypal_wp_button_manager'),
                        'search_items' => __('Search PayPal Buttons', 'paypal_wp_button_manager'),
                        'not_found' => __('No PayPal buttons found', 'paypal_wp_button_manager'),
                        'not_found_in_trash' => __('No PayPal buttons found in trash', 'paypal_wp_button_manager'),
                        'parent' => __('Parent PayPal Button', 'paypal_wp_button_manager')
                    ),
                    'description' => __('This is where you can create new PayPal buttons.', 'paypal_wp_button_manager'),
                    'public' => false,
                    'show_ui' => true,
                    'capability_type' => 'post',
                    'map_meta_cap' => true,
                    'publicly_queryable' => false,
                    'exclude_from_search' => true,
                    'hierarchical' => false, // Hierarchical causes memory issues - WP loads all records!
                    'rewrite' => array('slug' => 'paypal_buttons'),
                    'query_var' => true,
                    'menu_icon' => BMW_PLUGIN_URL . 'admin/images/paypal-wp-button-manager-icon.png',
                    'supports' => array('title'),
                    'has_archive' => true,
                    'show_in_nav_menus' => true
                   

                        )
                )
        );
    }

    public static function paypal_wp_button_manager_register_taxonomy() {
        if (taxonomy_exists('paypal_button_types')) {
            return;
        }

        do_action('paypal_wp_button_manager_register_taxonomy');

        register_taxonomy('paypal_button_types', apply_filters('paypal-wp-button-manager-cat', array('paypal_buttons')), apply_filters('paypal-wp-button-manager-cat', array(
                    'hierarchical' => true,
                    'label' => __('PayPal Button Types', 'paypal-button'),
                    'labels' => array(
                        'name' => __('PayPal Button Types', 'paypal-button'),
                        'singular_name' => __('PayPal Button Types', 'paypal-button'),
                        'menu_name' => _x('PayPal Button Types', 'Admin menu name', 'paypal-button'),
                        'search_items' => __('Search PayPal Button Types', 'paypal-button'),
                        'all_items' => __('All PayPal Button Types', 'paypal-button'),
                        'parent_item' => __('Parent PayPal Button Types', 'paypal-button'),
                        'parent_item_colon' => __('Parent PayPal Button Types:', 'paypal-button'),
                        'edit_item' => __('Edit PayPal Button Types', 'paypal-button'),
                        'update_item' => __('Update PayPal Button Types', 'paypal-button'),
                        'add_new_item' => __('Add New PayPal Button Types', 'paypal-button'),
                        'new_item_name' => __('New PayPal Button Types Name', 'paypal-button')
                    ),
                    'show_ui' => false,
                    'query_var' => true,
                    'rewrite' => array('slug' => 'paypal_buttons'),
                    'update_count_callback' => '_update_post_term_count'
                ))
        );
    }

    public static function paypal_wp_button_manager_restrict_manage_posts() {
        global $typenow;
        global $wp_query;
        if ($typenow == 'paypal_buttons') {
            $taxonomy = 'paypal_button_types';
            $term = isset($wp_query->query['paypal_button_types']) ? $wp_query->query['paypal_button_types'] : '';
            $business_taxonomy = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' => __("Show All PayPal Buttons"),
                'taxonomy' => $taxonomy,
                'name' => 'paypal_button_types',
                'orderby' => 'name',
                'selected' => $term,
                'hierarchical' => true,
                'depth' => 3,
                'show_count' => true, // Show # listings in parens
                'hide_empty' => true,
            ));
        }
    }

    public static function paypal_wp_button_manager_parse_query($query) {
        global $pagenow;
        $qv = &$query->query_vars;
        if ($pagenow == 'edit.php' && isset($qv['paypal_button_types']) && is_numeric($qv['paypal_button_types'])) {
            $term = get_term_by('id', $qv['paypal_button_types'], 'paypal_button_types');
            $qv['paypal_button_types'] = ($term ? $term->slug : '');
        }
    }

    public static function paypal_wp_button_manager_status() {
        global $wpdb;

        return $wpdb->get_col($wpdb->prepare("SELECT DISTINCT post_status FROM {$wpdb->posts} WHERE post_type = %s AND post_status != %s  ORDER BY post_status", 'paypal_buttons', 'auto-draft'));
    }

    /**
     * paypal_wp_button_manager_change_publish_button function is for 
     * change publish text to create button in custom post type
     * @global type $post returns the globle post values.
     * @param type $translation returns the translated text.
     * @param type $text string which needs to change
     * @since 1.0.0
     * @access public
     */
    public static function paypal_wp_button_manager_change_publish_button($translation, $text) {

        global $post;
        $post_id = isset($post->ID) ? $post->ID : '';
        if (isset($post_id) && !empty($post_id)) {
            $paypal_button_response = get_post_meta($post_id, 'paypal_button_response', true);
            if (isset($post->post_type) && !empty($post->post_type)) {
                if ('paypal_buttons' == $post->post_type) {

                    if ($text == 'Publish') {
                        return __('Create PayPal Button');
                    } else if ($text == 'Update') {
                        if (isset($post_id) && empty($paypal_button_response)) {
                            return __('Create PayPal Button');
                        } else {
                            return __('Update PayPal Button');
                        }
                    }
                }
            }
        }

        return $translation;
    }

    /**
     * paypal_wp_button_manager_edit_paypal_buttons_columns function
     * is use for register button shortcode column.
     * @param type $columns returns attribute for custom column.
     * @since 1.0.0
     * @access public
     */
    public static function paypal_wp_button_manager_edit_paypal_buttons_columns($columns) {

        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Button Name'),
            'shortcodes' => __('Shortcodes'),
            'date' => __('Date')
        );

        return $columns;
    }

    /**
     * paypal_wp_button_manager_remove_paypal_buttons_editor function
     * is use for remove editor for custom post type.
     * @since 1.0.0
     * @access public
     */
    public static function paypal_wp_button_manager_remove_paypal_buttons_editor() {
        remove_post_type_support('paypal_buttons', 'editor');
    }

    /**
     * paypal_wp_button_manager_paypal_buttons_columns function is use
     * for write content in custom registered column.
     * @global type $post returns the post variable values.
     * @param type $column Column name in which we want to write content.
     * @param type $post_id Post id of post in which content will be written for
     * the column.
     * @since 1.0.0
     * @access public
     */
    public static function paypal_wp_button_manager_paypal_buttons_columns($column, $post_id) {
        global $post;
        switch ($column) {
            case 'shortcodes' :
                $shortcode_avalabilty = get_post_meta($post_id, 'paypal_button_response', true);
                if (isset($shortcode_avalabilty) && !empty($shortcode_avalabilty)) {
                    echo '[paypal_wp_button_manager id=' . $post_id . ']';
                } else {
                    echo __('Not Available');
                }

                break;
            case 'publisher' :
                echo get_post_meta($post_id, 'publisher', true);
                break;
        }
    }

    /**
     * paypal_wp_button_manager_add_meta_boxes function is use for
     * register metabox for paypal_buttons custom post type.
     * @since 1.0.0
     * @access public
     */
    public static function paypal_wp_button_manager_add_meta_boxes() {
        add_meta_box('paypal-buttons-meta-id', __('PayPal Button Generator'), array(__CLASS__, 'paypal_wp_button_manager_metabox'), 'paypal_buttons', 'normal', 'high');
    }

    /**
     * paypal_wp_button_manager_metabox function is use for write data
     * in metabox.
     * @global type $post returns the post variable values.
     * @global type $post_ID returns the post id of a post.
     * @since 1.0.0
     * @access public
     */
    public static function paypal_wp_button_manager_metabox() {
        global $post, $post_ID;
        $paypal_button_html = get_post_meta($post_ID, 'paypal_button_response', true);
        $paypal_button_id = get_post_meta($post_ID, 'paypal_wp_button_manager_button_id', true);
        if (isset($paypal_button_id) && !empty($paypal_button_id)) {
            $button_id_text = $paypal_button_id;
        } else {
            $button_id_text = 'Not Available - Because this button is not saved at PayPal...';
        }

        $paypal_email_link = get_post_meta($post_ID, 'paypal_wp_button_manager_email_link', true);
        if (isset($paypal_button_html) && !empty($paypal_button_html)) {
            ?>
            <table class="tbl_shortcode">
                <tr>
                    <td class="td_title"><?php echo _e('You can easily place this button in your pages and posts using this tool....', 'paypal-wp-button-manager'); ?></td>
                </tr>
                <tr>
                    <td class="td_shortcode text-center-align"><img src="<?php echo BMW_PLUGIN_URL; ?>/admin/images/paypal_tools.png" width="75%"/></td>
                </tr>
                <tr>
                <tr>
                    <td colspan="2" class="center-text">OR</td>
                </tr>
                <tr>
                    <td class="td_title"><?php echo _e('You may copy and paste this shortcode into any Page or Post to place the PayPal button where you would like it to be displayed.', 'paypal-wp-button-manager'); ?></td>
                </tr>
                <tr>
                    <td class="td_shortcode"><input type="text"  value="<?php echo '[paypal_wp_button_manager id=' . $post_ID . ']'; ?>" readonly="readonly" class="wp-ui-text-highlight code large-text large-text-own txtarea_response"></td>
                </tr>
                <tr>
                    <td colspan="2" class="center-text">OR</td>
                </tr>
                <tr>
                    <td class="td_title"><?php echo _e('If you would prefer to use the HTML directly use this snippet.', 'paypal-wp-button-manager'); ?></td>
                </tr>
                <tr>
                    <td><textarea  readonly="readonly" class="wp-ui-text-highlight code txtarea_response" cols="70" rows="10"><?php echo $paypal_button_html; ?></textarea></td>
                </tr>
                <?php if (isset($paypal_email_link) && !empty($paypal_email_link)) { ?>
                    <tr>
                        <td colspan="2" class="center-text">OR</td>
                    </tr>
                    <tr>
                        <td class="td_title"><?php echo _e('If you plan to use this button within an email or basic link of any kind you can use this URL.', 'paypal-wp-button-manager'); ?></td>
                    </tr>
                    <tr>
                        <td class="td_shortcode"><input type="text"  value="<?php echo isset($paypal_email_link) ? $paypal_email_link : ''; ?>" readonly="readonly" class="wp-ui-text-highlight code large-text large-text-own txtarea_response"></td>
                    </tr>
                <?php } ?>

            </table>

            <?php
        } else {

            global $wpdb;
            $tbl_company_name = $wpdb->prefix . 'paypal_wp_button_manager_companies'; // do not forget about tables prefix
            $results_companis = $wpdb->get_row("SELECT count(*) as cnt_totalcompany FROM $tbl_company_name");
            if (isset($results_companis) && $results_companis->cnt_totalcompany <= 0) {
                ?>

                <div id="div_no_company">You have not set up any account, please add an account for create button <a href='<?php echo admin_url() . "admin.php?page=paypal-wp-button-manager-option&tab=company" ?>'>Add Company</a></a> </div>

                <?php
            } else {
                do_action('paypal_wp_button_manager_before_interface');
                do_action('paypal_wp_button_manager_interface');
                echo '<div id="go_to_settings"></div>';
            }
        }
    }

    /**
     * paypal_wp_button_manager_button_interface_display is use for display
     * paypal button generator interface.
     * @global type $post returns the post variable values.
     * @global type $post_ID returns the post id of a post.
     * @since 1.0.0
     * @access public
     */
    public static function paypal_wp_button_manager_button_interface_display() {

        global $post, $post_ID, $wpdb;
        $paypal_button_html = get_post_meta($post_ID, 'paypal_button_response', true);
        $paysuccessnotice = get_post_meta($post_ID, 'paypal_wp_button_manager_success_notice', true);
        if (((isset($_POST['publish'])) || isset($_POST['save'])) && ($post->post_type == 'paypal_buttons')) {
            if (empty($paypal_button_html)) {
                do_action('paypal_wp_button_manager_button_generator');
            } else {
                if (isset($_POST['button_type']) && !empty($_POST['button_type'])) {
                    update_post_meta($post_ID, 'paypal_wp_button_manager_success_notice', 'Button Created Successfully.');
                } else {
                    update_post_meta($post_ID, 'paypal_wp_button_manager_success_notice', '');
                }
            }
        }
    }

}

AngellEYE_PayPal_WP_Button_Manager_Post_types::init();