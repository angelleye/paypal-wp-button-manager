<?php

/**
 * This class defines all code necessary to button generator interface
 * @class       AngellEYE_PayPal_Button_Manager_for_WordPress_button_interface
 * @version	1.0.0
 * @package		paypal-button-manager-for-wordpress/includes
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_Button_Manager_for_WordPress_button_generator {

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public static function init() {
        global $post, $post_ID;

        add_action('paypal_button_manager_button_generator', array(__CLASS__, 'paypal_button_manager_button_interface_generator'));
    }

    public static function paypal_button_manager_button_interface_generator() {

        // Create PayPal object.
        global $post, $post_ID;

        $payapal_helper = new AngellEYE_PayPal_Button_Manager_for_WordPress_PayPal_Helper();
        $PayPalConfig = $payapal_helper->paypal_button_manager_for_wordpress_get_paypalconfig();
        $PayPal = new PayPal($PayPalConfig);
        $paypal_buttontype = $payapal_helper->paypal_button_manager_for_wordpress_get_button_type();

        $BMButtonVars = array();
        $BMButtonVars = $payapal_helper->paypal_button_manager_for_wordpress_get_buttonvars();
        $PayPalRequestData = $payapal_helper->paypal_button_manager_for_wordpress_get_dropdown_values();

        $PayPalResult = $PayPal->BMCreateButton($PayPalRequestData);


        // Write the contents of the response array to the screen for demo purposes.
        if (isset($PayPalResult['ERRORS']) && !empty($PayPalResult['ERRORS'])) {
            global $post, $post_ID;
            $paypal_button_manager_notice = get_option('paypal_button_manager_notice');
            $notice[$post_ID] = $PayPalResult['ERRORS'][0]['L_LONGMESSAGE'];
            $notice_code[$post_ID] = $PayPalResult['ERRORS'][0]['L_ERRORCODE'];
            self::paypal_button_manager_write_error_log($PayPalResult['ERRORS']);
            update_option('paypal_button_manager_notice', $notice);
            update_option('paypal_button_manager_error_code', $notice_code);
            unset($_POST);
        } else if ($PayPalResult['RAWRESPONSE'] == false) {
            global $post, $post_ID;
            $timeout_notice[$post_ID] = 'Internal server error occured';
            update_option('paypal_button_manager_timeout_notice', $timeout_notice);
            self::paypal_button_manager_write_error_log($PayPalResult['RAWRESPONSE']);
            unset($_POST);
        } else if (isset($PayPalResult['WEBSITECODE']) && !empty($PayPalResult['WEBSITECODE'])) {
            global $post, $post_ID;
            update_post_meta($post_ID, 'paypal_button_response', $PayPalResult['WEBSITECODE']);
            
        }
    }

    public static function paypal_button_manager_write_error_log($error) {
        $debug = (get_option('log_enable_button_manager') == 'yes') ? 'yes' : 'no';
        if ('yes' == $debug) {
            $log_write = new AngellEYE_PayPal_Button_Manager_for_WordPress_Logger();
        }

        if ('yes' == $debug) {
            $log_write->add('paypal_button_manager', 'PayPal button manager response: ' . print_r($error, true));
        }
    }

}

AngellEYE_PayPal_Button_Manager_for_WordPress_button_generator::init();