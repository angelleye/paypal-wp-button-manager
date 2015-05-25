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

        global $post, $post_ID;

        extract(shortcode_atts(array(
                    'id' => ''), $atts));

        $paypal_button_response = get_post_meta($id, 'paypal_button_response', true);
        $post_status = get_post_status($id);
        if ((!empty($paypal_button_response)) && (($post_status == 'publish')) && ($post_status !='auto-draft')) {
            return $paypal_button_response;
        } else {
            return '[paypal_wp_button_manager id=' . $id . ']';
        }
    }

}

AngellEYE_PayPal_WP_Button_Manager_Public_Display::init();