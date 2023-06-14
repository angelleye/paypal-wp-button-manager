<?php
defined( 'ABSPATH' ) || exit;
/*
 * Class responsible for shortcodes
 */
class PayPal_WP_Button_Manager_Shortcode{
    public function __construct(){
        add_shortcode( PayPal_WP_Button_Manager_Post::$shortcode, array( $this, 'print_button' ) );
        add_action('wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Registers the shortcode script
     * */
    public function enqueue_scripts(){
        wp_register_style("wbp-frontend-button", WBP_PLUGIN_URL . 'assets/frontend/css/wbp-button.css', array(), '1.0.0');
    }

    /**
     * Prints the button and applicable form
     * 
     * @param Array attrs Attributes
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
        $button = new PayPal_WP_Button_Manager_Button( $button_id );
        if( !$button->is_valid_button() ){
            return;
        }
        
        $company_id = $button->get_company_id();
        $company = $wpdb->get_results( "SELECT ID, company_name FROM {$wpdb->prefix}angelleye_paypal_button_manager_companies WHERE ID = $company_id LIMIT 1" );
        
        wp_register_script("wbp-frontend-button", WBP_PLUGIN_URL . 'assets/frontend/js/wbp-button.js', array('jquery', 'wbp-paypal-sdk'), '1.0.0');
        wp_localize_script("wbp-frontend-button", "btn_obj_".$button_id, array(
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
            'amount' => $button->get_total()
        ));
        
        $hidden_method = $button->get_hide_funding_method();
        if( !empty( $hidden_method ) ){
            $hidden = '&disable-funding=' . implode(',', $hidden_method );
        } else {
            $hidden = '';
        }
        wp_enqueue_script('wbp-paypal-sdk', 'https://www.paypal.com/sdk/js?&client-id=' . WBP_SANDBOX_PARTNER_CLIENT_ID . $hidden . '&enable-funding=venmo,paylater&merchant-id=' . $button->get_company_merchant_id() , array(), null);
        wp_enqueue_script("wbp-frontend-button");
        wp_enqueue_style("wbp-frontend-button");
        
        $admin_error_messages = array();
        if( empty( $button->get_company_id() ) ){
            $admin_error_messages[] = __('Please select the company id from button configuration.', 'paypal-wp-button-manager' );
        }

        if( empty( $button->get_price() ) ){
            $admin_error_messages[] = __('Please add the price of the item', 'paypal-wp-button-manager' );
        }

        if( !empty( $admin_error_messages ) && is_user_logged_in() && in_array('administrator',  wp_get_current_user()->roles) ){
            include( WBP_PLUGIN_PATH . '/templates/frontend/wbp-config-errors.php');
            return;
        }

        ob_start();
        include( WBP_PLUGIN_PATH . '/templates/frontend/wbp-button.php' );
        return ob_get_clean();
    }
}