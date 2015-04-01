<?php

/**
 * @class       AngellEYE_PayPal_Button_Manager_for_WordPress_Public_Display
 * @version		1.0.0
 * @package		paypal-button-manager-for-wordpress
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_Button_Manager_for_WordPress_Public_Display {

    public static function init() {
        add_shortcode('paypal_button_manager', array(__CLASS__, 'create_paypal_button_manager_shortcode'));
    }

    public static function create_paypal_button_manager_shortcode($atts, $content = null) {

        global $post, $post_ID;

        extract(shortcode_atts(array(
                    'id' => ''), $atts));

        $paypal_button_response = get_post_meta($id, 'paypal_button_response', true);
        $post_status = get_post_status($id);
        if (!empty($paypal_button_response) && ($post_status == 'publish')) {
            return $paypal_button_response;
        } else {
            return '[paypal_button_manager id=' . $id . ']';
        }
    }

}

AngellEYE_PayPal_Button_Manager_for_WordPress_Public_Display::init();