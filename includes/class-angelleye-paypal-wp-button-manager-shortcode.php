<?php
defined( 'ABSPATH' ) || exit;
/*
 * Class responsible for shortcodes
 */
class Angelleye_Paypal_Wp_Button_Manager_Shortcode{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    public function __construct( $plugin_name, $version ){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_shortcode( Angelleye_Paypal_Wp_Button_Manager_Post::$shortcode, array( $this, 'print_button' ) );
    }

    /**
     * Prints the button and applicable form
     * 
     * @param Array attrs Attributes
     * 
     * @return string
     * */
    public function print_button( $attrs ){
        global $wpdb;
        if( is_admin() ){
            return;
        }

        if( !isset( $attrs['id'] ) ){
            return;
        }

        $button_id = sanitize_text_field( $attrs['id'] );
        $button = new Angelleye_Paypal_Wp_Button_Manager_Button( $button_id );
        if( !$button->is_valid_button() ){
            return;
        }

        if( $button->get_button_type() == 'subscription' && !is_user_logged_in() ){
            include( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH . '/public/partials/angelleye-paypal-wp-button-manager-subscription-non-loggedin-error.php');
            return;
        }

        wp_register_script( $this->plugin_name . "-frontend-button", ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL . 'public/js/angelleye-paypal-wp-button-manager-button.js', array('jquery', $this->plugin_name . '-paypal-sdk'), $this->version);
        
        $company_id = $button->get_company_id();
        $company = $wpdb->get_results( "SELECT ID, company_name FROM {$wpdb->prefix}angelleye_paypal_button_manager_companies WHERE ID = $company_id LIMIT 1" );
        
        wp_localize_script( $this->plugin_name . "-frontend-button", "btn_obj_".$button_id, array(
            'api_url' => get_site_url() . '/wp-json/angelleye-paypal-button-manager/create-order',
            'capture_url' => get_site_url() . '/angelleye-capture-order',
            'layout' => $button->get_button_layout(),
            'color' => $button->get_button_color(),
            'shape' => $button->get_button_shape(),
            'size' => $button->get_button_size(),
            'height' => $button->get_button_height(),
            'label' => $button->get_button_label(),
            'tagline' => $button->get_button_tagline(),
            'merchant_id' => $button->get_company_merchant_id(),
            'amount' => $button->get_total(),
            'type' => $button->get_button_type(),
            'general_error' => __('Something went wrong on our end. We apologize for any inconvenience this may have caused. Please try again later.','angelleye-paypal-wp-button-manager')
        ));
        
        $hidden_method = $button->get_hide_funding_method();
        if( !empty( $hidden_method ) ){
            $hidden = '&disable-funding=' . implode(',', $hidden_method );
        } else {
            $hidden = '';
        }
        
        if( $button->is_company_test_mode() ){
            wp_enqueue_script( $this->plugin_name . '-paypal-sdk', 'https://www.paypal.com/sdk/js?&client-id=' . ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_SANDBOX_PARTNER_CLIENT_ID . $hidden . '&enable-funding=venmo,paylater&merchant-id=' . $button->get_company_merchant_id() , array(), null);
        } else {
            wp_enqueue_script( $this->plugin_name . '-paypal-sdk', 'https://www.paypal.com/sdk/js?&client-id=' . ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_LIVE_PARTNER_CLIENT_ID . $hidden . '&enable-funding=venmo,paylater&merchant-id=' . $button->get_company_merchant_id() , array(), null);
        }
        
        wp_enqueue_script( $this->plugin_name . "-frontend-button");
        wp_enqueue_style( $this->plugin_name . "-frontend-button");
        
        $admin_error_messages = array();
        if( empty( $button->get_company_id() ) ){
            $admin_error_messages[] = __('Please select the company id from button configuration.', 'angelleye-paypal-wp-button-manager' );
        }

        if( empty( $button->get_price() ) ){
            $admin_error_messages[] = __('Please add the price of the item', 'angelleye-paypal-wp-button-manager' );
        }

        ob_start();
        if( !empty( $admin_error_messages ) && is_user_logged_in() && in_array('administrator',  wp_get_current_user()->roles) ){
            include( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH . '/public/partials/angelleye-paypal-wp-button-manager-public-config-errors.php');
            return;
        }
        
        include( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH . '/public/partials/angelleye-paypal-wp-button-manager-public-button.php' );
        return ob_get_clean();
    }
}