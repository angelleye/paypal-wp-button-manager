<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    paypal-wp-button-manager
 * @subpackage paypal-wp-button-manager/admin
 * @author     Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    0.1.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    0.1.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    0.1.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_dependencies();
    }

    /**
     * Register the stylesheets for the Dashboard.
     *
     * @since    0.1.0
     */
    public function enqueue_styles() {
        wp_enqueue_style('thickbox'); // call to media files in wp
        wp_enqueue_style($this->plugin_name . 'one', plugin_dir_url(__FILE__) . '/css/paypal-wp-button-manager-global.css', array(), $this->version, false);
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/paypal-wp-button-manager-master.css', array(), $this->version, false);
        wp_enqueue_style($this->plugin_name . 'two', plugin_dir_url(__FILE__) . '/css/paypal-wp-button-manager-coreLayout.css', array(), $this->version, false);
        wp_enqueue_style($this->plugin_name . 'three', plugin_dir_url(__FILE__) . '/css/paypal-wp-button-manager-me2.css', array(), $this->version, false);
        wp_enqueue_style($this->plugin_name . 'four', plugin_dir_url(__FILE__) . '/css/paypal-wp-button-manager-print.css', array(), $this->version, false);
        wp_enqueue_style($this->plugin_name . 'five', plugin_dir_url(__FILE__) . 'css/paypal-wp-button-manager-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . 'seven', plugin_dir_url(__FILE__) . 'css/webkit/fontello.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the dashboard.
     *
     * @since    0.1.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('jquery-ui-tooltip');

        wp_enqueue_script($this->plugin_name . 'one', plugin_dir_url(__FILE__) . 'js/paypal-wp-button-manager-global.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . 'three', plugin_dir_url(__FILE__) . 'js/paypal-wp-button-manager-pa.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . 'five', plugin_dir_url(__FILE__) . 'js/paypal-wp-button-manager-widgets.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . 'four', plugin_dir_url(__FILE__) . 'js/paypal-wp-button-manager-pp_jscode_080706.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/paypal-wp-button-manager-admin.js', array('jquery'), $this->version, false);

        if (wp_script_is($this->plugin_name)) {
            wp_localize_script($this->plugin_name, 'paypal_wp_button_manager_plugin_url', apply_filters('paypal_wp_button_manager_plugin_url_filter', array(
                        'plugin_url' => plugin_dir_url(__FILE__)
                    )));
        }
        global $post;
        $args = array('post_type' => 'paypal_buttons', 'posts_per_page' => '100', 'post_status' => 'publish');
        $paypal_buttons_posts = get_posts($args);
        $shortcodes = array();
        $shortcodes_values = array();
        foreach ($paypal_buttons_posts as $key_post => $paypal_buttons_posts_value) {
            $shortcodes[$paypal_buttons_posts_value->ID] = $paypal_buttons_posts_value->post_title;
        }

        if (empty($shortcodes)) {

            $shortcodes_values = array('0' => 'No shortcode Available');
        } else {
            $shortcodes_values = $shortcodes;
        }
        wp_localize_script('paypal-wp-button-manager', 'shortcodes_button_array', apply_filters('paypal_wp_button_manager_shortcode', array(
                    'shortcodes_button' => $shortcodes_values
                )));
    }

    private function load_dependencies() {

        /**
         * The class responsible for defining all actions that occur in the Dashboard for Paypal buttons.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/class-paypal-wp-button-manager-post-types.php';

        /**
         * The class responsible for defining all actions that occur in the Dashboard
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/paypal-wp-button-manager-admin-display.php';

        /**
         * The class responsible for defining function for display Html element
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/class-paypal-wp-button-manager-html-output.php';

        /**
         * The class responsible for defining function for display general setting tab
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/class-paypal-wp-button-manager-general-setting.php';
    }

    /**
     * paypal_wp_button_manager_notice_display function is use for display
     * error of paypal response.
     * @global type $post returns the post values.\
     * @since 1.0.0
     * @access public
     */
    public function paypal_wp_button_manager_notice_display() {
        global $post;
        $errors_notice = get_option('paypal_wp_button_manager_notice');
        $error_code = get_option('paypal_wp_button_manager_error_code');
        $timeout_notice = get_option('paypal_wp_button_manager_timeout_notice');
        $success_notice = get_option('paypal_wp_button_manager_success_notice');
        if ((isset($errors_notice) && !empty($errors_notice)) && (isset($error_code) && !empty($error_code)) && (empty($timeout_notice))) {
            echo _e('<div class="error"><p>Error Code:&nbsp;' . $error_code[$post->ID] . '<br/>Error Details:&nbsp;' . $errors_notice[$post->ID] . '</p></div>', 'paypal-wp-button-manager');
            echo "<style>.updated{display:none;}</style>";
            unset($errors_notice[$post->ID]);
            unset($error_code[$post->ID]);
            update_option('paypal_wp_button_manager_notice', $errors_notice);
            update_option('paypal_wp_button_manager_error_code', $error_code);
        } else if (isset($timeout_notice) && !empty($timeout_notice)) {
            echo _e('<div class="error"><p>Error Details:&nbsp;' . $timeout_notice[$post->ID] . '</p></div>', 'paypal-wp-button-manager');
            echo "<style>.updated{display:none;}</style>";
            unset($timeout_notice[$post->ID]);
            update_option('paypal_wp_button_manager_timeout_notice', $timeout_notice);
        }
    }

    /**
     * paypal_wp_button_manager_success_notice_display function is use for
     * change paypal_buttons post update message.
     * @param type $messages returns the custom message.
     * @since 1.0.0
     * @access public
     */
    public function paypal_wp_button_manager_success_notice_display($messages) {

        global $post, $post_ID;
        $paypal_button_html = get_post_meta($post_ID, 'paypal_button_response', true);
        $success_message = get_post_meta($post_ID, 'paypal_wp_button_manager_success_notice', true);
        if (isset($success_message) && !empty($success_message)) {
            $custom_message = $success_message;
        } else {
            $custom_message = 'Button Updated Successfully.';
        }
        $messages['paypal_buttons'] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf(__('Button Updated Successfully')),
            2 => __('Custom field updated.'),
            3 => __('Custom field deleted.'),
            4 => __($custom_message),
            /* translators: %s: date and time of the revision */
            5 => isset($_GET['revision']) ? sprintf(__('Button restored to revision from %s'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
            6 => sprintf(__('Button Created Successfully')),
            7 => __('Button saved.'),
            8 => sprintf(__('Button submitted. <a target="_blank" href="%s">Preview Button</a>'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
            9 => sprintf(__('Button scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Button</a>'),
                    // translators: Publish box date format, see http://php.net/date
                    date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
            10 => sprintf(__('Button draft updated. <a target="_blank" href="%s">Preview Button</a>'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
        );
        return $messages;
    }

    /**
     *  paypal_wp_button_manager_shortcode_button_init function process for registering our button.
     *
     */
    public function paypal_wp_button_manager_shortcode_button_init() {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
            return;

        //Add a callback to regiser our tinymce plugin
        add_filter('mce_external_plugins', array($this, 'paypal_wp_button_manager_register_tinymce_plugin'));

        // Add a callback to add our button to the TinyMCE toolbar
        add_filter('mce_buttons', array($this, 'paypal_wp_button_manager_add_tinymce_button'));
    }

    public function paypal_wp_button_manager_register_tinymce_plugin($plugin_array) {
        $plugin_array['pushortcodes'] = plugin_dir_url(__FILE__) . 'js/paypal-wp-button-manager-admin.js';
        return $plugin_array;
    }

    public function paypal_wp_button_manager_add_tinymce_button($buttons) {
        array_push($buttons, 'separator', 'pushortcodes');
        return $buttons;
    }

    public function paypal_wp_button_manager_print_shortcodes_in_js() {
        
    }

    public function paypal_wp_button_manager_print_mynote() {
        global $typenow, $pagenow, $wpdb, $post, $post_ID;
        $table_name = $wpdb->prefix . "posts";
        $postmeta_table = $wpdb->prefix . "postmeta";

        $viecart_post_id = get_option('paypal_wp_button_manager_viewcart_button_postid');
        if (isset($viecart_post_id) && !empty($viecart_post_id)) {
            $view_cart_postid_status = $wpdb->get_row("SELECT count(*) as cnt_viewcart_postid  from  $table_name where post_status='publish' and post_type='paypal_buttons' and ID='$viecart_post_id'");
        }
        $viewcart_post = $wpdb->get_row("SELECT COUNT(*)as cnt_viewcart from  $table_name where post_status='publish' and post_type='paypal_buttons'");
        $is_shopping_button_count_obj = $wpdb->get_row("SELECT COUNT(*)as cnt_is_shopping from  $postmeta_table where meta_key='paypal_wp_button_manager_is_shopping' and meta_value='1'");



        $is_shopping_button_count = $is_shopping_button_count_obj->cnt_is_shopping;
        if (isset($view_cart_postid_status) && !empty($view_cart_postid_status)) {
            if ($view_cart_postid_status->cnt_viewcart_postid <= 0) {
                delete_option('paypal_wp_button_manager_viewcart_button');
            }
        }
        if ($viewcart_post->cnt_viewcart <= 0) {
            delete_option('paypal_wp_button_manager_view_cart_status');
            delete_option('paypal_wp_button_manager_viewcart_button');
            delete_option('paypal_wp_button_manager_viewcart_post_id');
            $wpdb->query($wpdb->prepare("DELETE from $wpdb->postmeta  WHERE meta_key  = %s", 'paypal_wp_button_manager_shopping_post'));
        }
        $shopping_cart_post = get_post_meta($post_ID, 'paypal_wp_button_manager_shopping_post');
        if (isset($shopping_cart_post) && !empty($shopping_cart_post)) {
            $shopping_cart_post_value = $shopping_cart_post;
        }
        $view_cart_button_status = get_option('paypal_wp_button_manager_view_cart_status');
        $paypal_wp_button_manager_viewcart_button = get_option('paypal_wp_button_manager_viewcart_button');
        if (isset($is_shopping_button_count) && $is_shopping_button_count <= 0) {
            $view_cart_button_status_value = '';
        } else if ((isset($view_cart_button_status) && !empty($view_cart_button_status)) && (empty($paypal_wp_button_manager_viewcart_button))) {
            $view_cart_button_status_value = "1";
        } else {
            $view_cart_button_status_value = '';
        }
         $screen = get_current_screen();
         if ($screen->post_type == 'paypal_buttons') {
		        if (((in_array($pagenow, array('edit.php')) && ('paypal_buttons' == 'paypal_buttons' )) && !empty($view_cart_button_status_value)) || ((!empty($view_cart_button_status_value)) && in_array($pagenow, array('post.php', 'post-new.php')) && !empty($shopping_cart_post_value))) {
		            ?>
		            <script>
		                jQuery( document ).ready(function() {
		
		                    jQuery(".wrap").find("h2").after('<div class="updated below-h2 msg_div"><p class="msg_text">You should probably create a view cart button </p>&nbsp;&nbsp;<p class="btn_para"><span class="button button-primary button-large btn_viewcart">Create View Cart Button</span></p> <img src="<?php echo plugin_dir_url(__FILE__) ?>images/ajax-loader.gif" id="gifimg"/></div>');
		                    jQuery( ".btn_viewcart" ).click(function() {
		                        jQuery('#gifimg').css('visibility','visible');
		                        jQuery('#gifimg').css('display','inline');
		                        var data = {
		                            'action': 'create_viewcart_action'
		
		                        };
		
		                        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		                        jQuery.post(ajaxurl, data, function(response) {
		                            jQuery(".msg_div").remove();
		                            jQuery(".wrap").find("h2").after('<div class="updated below-h2 msg_div"><p class="msg_text">View Cart button created successfully. <a href="<?php echo admin_url('edit.php?post_type=paypal_buttons'); ?>">Refresh Data</a></p></div>');
		
		                            jQuery('#gifimg').css('display','none');
		                        });
		
		                    });
		
		                });
		            </script>
		            <?
		        }
         }
    }

    public function paypal_wp_button_manager_create_viewcart_action() {
        global $post, $post_ID;
        $payapal_helper = new AngellEYE_PayPal_WP_Button_Manager_PayPal_Helper();
        $PayPalConfig = $payapal_helper->paypal_wp_button_manager_get_paypalconfig();
        $PayPal = new PayPal($PayPalConfig);
        $BMCreateButtonFields_viewcart = array
            (
            'buttoncode' => 'CLEARTEXT', // The kind of button code to create.  It is one of the following values:  HOSTED, ENCRYPTED, CLEARTEXT, TOKEN
            'buttontype' => 'VIEWCART', // Required.  The kind of button you want to create.  It is one of the following values:  BUYNOW, CART, GIFTCERTIFICATE, SUBSCRIBE, DONATE, UNSUBSCRIBE, VIEWCART, PAYMENTPLAN, AUTOBILLING, PAYMENT
            'buttonsubtype' => '', // The use of button you want to create.  Values are:  PRODUCTS, SERVICES
        );


        $PayPalRequestData_viewcart = array(
            'BMCreateButtonFields' => $BMCreateButtonFields_viewcart,
            'BMButtonVars' => ''
        );

        $PayPalResult_viewcart = $PayPal->BMCreateButton($PayPalRequestData_viewcart);

        if (isset($PayPalResult_viewcart['WEBSITECODE']) && !empty($PayPalResult_viewcart['WEBSITECODE'])) {
            // Create post object
            $view_cart_post = array(
                'post_title' => 'View Cart',
                'post_content' => $PayPalResult_viewcart['WEBSITECODE'],
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => 'paypal_buttons'
            );

            // Insert the post into the database
            $post_id = wp_insert_post($view_cart_post);
            update_post_meta($post_id, 'paypal_button_response', $PayPalResult_viewcart['WEBSITECODE']);
            update_option('paypal_wp_button_manager_viewcart_button', '1');
            update_option('paypal_wp_button_manager_viewcart_button_postid', $post_id);
        }
    }

    public function paypal_wp_button_manager_wp_trash_post($post_id) {
        $post_type = get_post_type($post_id);
        $post_status = get_post_status($post_id);
        if ($post_type == 'paypal_buttons' && in_array($post_status, array('publish', 'draft'))) {
            delete_post_meta($post_id, 'paypal_wp_button_manager_is_shopping');
        }
    }
    
  
}
