<?php

/**
 * @class       AngellEYE_PayPal_WP_Button_Manager_Public_Display
 * @version		1.0.0
 * @package		paypal-wp-button-manager
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_Public_Display {

    public static function init() {
        add_shortcode('paypal_wp_button_manager', array(__CLASS__, 'paypal_wp_button_manager_create_shortcode'));
    }

    /**
     * paypal_wp_button_manager_create_shortcode function is for generate
     * @since 1.0.0
     * @access public
     */
    public static function paypal_wp_button_manager_create_shortcode($atts, $content = null) {

        extract(shortcode_atts(array(
                    'id' => ''), $atts));

        if( !empty($id) ) {
            $post = get_post($id);
            if(!empty($post->post_type) && $post->post_type == 'paypal_buttons' && $post->post_status == 'publish') {
                $post_date = strtotime( $post->post_date );
                $current_date = strtotime(gmdate('Y-m-d H:i:s'));
                if( ($current_date - $post_date) > 90 ) { 
                    $button_host_id = get_post_meta($post->ID, 'paypal_wp_button_manager_button_id', true);
                    if( !empty($button_host_id) ) {
                        $payapal_helper = new AngellEYE_PayPal_WP_Button_Manager_PayPal_Helper();
                        $company_id = get_post_meta($post->ID, 'paypal_wp_button_manager_company_rel', true);
                        $PayPalConfig = $payapal_helper->paypal_wp_button_manager_get_paypalconfig($company_id);
                        $PayPal = new Angelleye_PayPal($PayPalConfig);
                        $PayPalResult = $PayPal->BMGetButtonDetails($button_host_id);
                        if (isset($PayPalResult['EMAILLINK']) && !empty($PayPalResult['EMAILLINK'])) {
                            update_post_meta($post->ID, 'paypal_wp_button_manager_email_link', $PayPalResult['EMAILLINK']);
                        } else {
                            update_post_meta($post->ID, 'paypal_wp_button_manager_email_link', '');
                        }
                        if (isset($PayPalResult['WEBSITECODE']) && !empty($PayPalResult['WEBSITECODE'])) {
                            update_post_meta($post->ID, 'paypal_button_response', $PayPalResult['WEBSITECODE']);
                            return $PayPalResult['WEBSITECODE'];
                        } else {
                            update_post_meta($post->ID, 'paypal_button_response', '');
                        }
                    }
                }
                $paypal_button_response = get_post_meta($post->ID, 'paypal_button_response', true);
                if( !empty($paypal_button_response) ) {
                    return $paypal_button_response;
                } else {
                    return '[paypal_wp_button_manager id=' . $id . ']';
                }
            }
        }
    }

}

AngellEYE_PayPal_WP_Button_Manager_Public_Display::init();