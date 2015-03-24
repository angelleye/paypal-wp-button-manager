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

        add_action('paypal_button_manager_button_generator', array(__CLASS__, 'paypal_button_manager_button_interface_generator'));
    }

    public function paypal_button_manager_button_interface_generator() {

        // Create PayPal object.
        $payapal_helper = new AngellEYE_PayPal_Button_Manager_for_WordPress_PayPal_Helper();
        $PayPalConfig = $payapal_helper->paypal_button_manager_for_wordpress_get_paypalconfig();
        $PayPal = new PayPal($PayPalConfig);
        $paypal_buttontype = $payapal_helper->paypal_button_manager_for_wordpress_get_button_type();

        $BMButtonVars = array();
        $BMButtonVars = $payapal_helper->paypal_button_manager_for_wordpress_get_buttonvars();
        $PayPalRequestData = $payapal_helper->paypal_button_manager_for_wordpress_get_dropdown_values();

        $PayPalResult = $PayPal->BMCreateButton($PayPalRequestData);
        //$PayPalResult;
        echo "Test";

        // Write the contents of the response array to the screen for demo purposes.
        if (isset($PayPalResult['ERRORS']) && !empty($PayPalResult['ERRORS'])) {
            //$PayPalResult['ERRORS'][0]['L_LONGMESSAGE']
            global $post, $post_ID;
            $paypal_button_manager_notice = get_option('paypal_button_manager_notice');
            $notice[$post_ID] = $PayPalResult['ERRORS'][0]['L_LONGMESSAGE'];
            update_option('paypal_button_manager_notice', $notice);
        } else if (isset($PayPalResult['WEBSITECODE']) && !empty($PayPalResult['WEBSITECODE'])) {
            global $post, $post_ID;
            update_post_meta($post_ID, 'paypal_button_response', $PayPalResult['WEBSITECODE']);


            //return $PayPalResult;
        }
    }

}

AngellEYE_PayPal_Button_Manager_for_WordPress_button_generator::init();