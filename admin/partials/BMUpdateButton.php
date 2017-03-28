<?php
/**
 * This class defines all code necessary to button generator interface
 * @class       AngellEYE_PayPal_WP_Button_Manager_button_interface
 * @version	1.0.0
 * @package		paypal-wp-button-manager/includes
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_button_updater {
    /**
     * Hook in methods
     * @since    0.1.0
     * @access   static
     */
    public static function init() {
        global $post, $post_ID;
        add_action('paypal_wp_button_manager_button_updater', array(__CLASS__, 'paypal_wp_button_manager_button_interface_updater'));
    }
    public static function paypal_wp_button_manager_button_interface_updater() {
        // Create PayPal object.
        global $post, $post_ID, $wpdb;
        $payapal_helper = new AngellEYE_PayPal_WP_Button_Manager_PayPal_Helper();
        $PayPalConfig = $payapal_helper->paypal_wp_button_manager_get_paypalconfig();
        $PayPal = new Angelleye_PayPal($PayPalConfig);
        $meta = get_post_meta(get_the_ID());
        $edit_hosted_button_id=$meta['paypal_wp_button_manager_button_id'][0];        
        $BMButtonVars = array();
        $BMButtonVars = $payapal_helper->paypal_wp_button_manager_get_buttonvars();
        $PayPalRequestData = $payapal_helper->paypal_wp_button_manager_get_dropdown_values();        
        $PayPalResult = $PayPal->BMUpdateButton($PayPalRequestData,$edit_hosted_button_id);        
        
        // Write the contents of the response array to the screen for demo purposes.
        if (isset($PayPalResult['ERRORS']) && !empty($PayPalResult['ERRORS'])) {            
            global $post, $post_ID;
            $paypal_wp_button_manager_notice = get_option('paypal_wp_button_manager_notice');
            $notice[$post_ID] = $PayPalResult['ERRORS'][0]['L_LONGMESSAGE'];
            $notice_code[$post_ID] = $PayPalResult['ERRORS'][0]['L_ERRORCODE'];
            
            $PayPalRequest = isset($PayPalResult['RAWREQUEST']) ? $PayPalResult['RAWREQUEST'] : '';
            $PayPalResponse = isset($PayPalResult['RAWRESPONSE']) ? $PayPalResult['RAWRESPONSE'] : '';
            $PayPalResult['RAWREQUEST'] = $PayPal->NVPToArray($PayPal->MaskAPIResult($PayPalRequest));
            $PayPalResult['RAWRESPONSE'] = $PayPal->NVPToArray($PayPal->MaskAPIResult($PayPalResponse));
            
            self::paypal_wp_button_manager_write_error_log($PayPalResult);
            update_option('paypal_wp_button_manager_notice', $notice);
            update_option('paypal_wp_button_manager_error_code', $notice_code);
            update_post_meta($post_ID, 'paypal_wp_button_manager_success_notice', '');
            delete_option('paypal_wp_button_manager_timeout_notice');
            // Update the post into the database
            unset($_POST);
            unset($post);
        } else if ($PayPalResult['RAWRESPONSE'] == false) {           
            global $post, $post_ID;
            $timeout_notice[$post_ID] = 'Internal server error occured';
            update_option('paypal_wp_button_manager_timeout_notice', $timeout_notice);
            
            $PayPalRequest = isset($PayPalResult['RAWREQUEST']) ? $PayPalResult['RAWREQUEST'] : '';
            $PayPalResponse = isset($PayPalResult['RAWRESPONSE']) ? $PayPalResult['RAWRESPONSE'] : '';
            $PayPalResult['RAWREQUEST'] = $PayPal->NVPToArray($PayPal->MaskAPIResult($PayPalRequest));
            $PayPalResult['RAWRESPONSE'] = $PayPal->NVPToArray($PayPal->MaskAPIResult($PayPalResponse));
                    
            self::paypal_wp_button_manager_write_error_log($PayPalResult);
            update_post_meta($post_ID, 'paypal_wp_button_manager_success_notice', '');
            delete_option('paypal_wp_button_manager_notice');
            delete_option('paypal_wp_button_manager_error_code');
            unset($_POST);
            unset($post);
        } else if (isset($PayPalResult['WEBSITECODE']) && !empty($PayPalResult['WEBSITECODE'])) {            
            global $post, $post_ID;
            global $wp;
            update_post_meta($post_ID, 'paypal_button_response', $PayPalResult['WEBSITECODE']);
            
            $PayPalRequest = isset($PayPalResult['RAWREQUEST']) ? $PayPalResult['RAWREQUEST'] : '';
            $PayPalResponse = isset($PayPalResult['RAWRESPONSE']) ? $PayPalResult['RAWRESPONSE'] : '';
            $PayPalResult['RAWREQUEST'] = $PayPal->NVPToArray($PayPal->MaskAPIResult($PayPalRequest));
            $PayPalResult['RAWRESPONSE'] = $PayPal->NVPToArray($PayPal->MaskAPIResult($PayPalResponse));
            
            self::paypal_wp_button_manager_write_error_log($PayPalResult);
            update_post_meta($post_ID, 'paypal_wp_button_manager_success_notice', 'Button Updated Successfully.');
            delete_option('paypal_wp_button_manager_notice');
            delete_option('paypal_wp_button_manager_error_code');
            delete_option('paypal_wp_button_manager_timeout_notice');
            if (isset($PayPalResult['HOSTEDBUTTONID']) && !empty($PayPalResult['HOSTEDBUTTONID'])) {
                update_post_meta($post_ID, 'paypal_wp_button_manager_button_id', $PayPalResult['HOSTEDBUTTONID']);
            }
            if (isset($_POST['ddl_companyname']) && !empty($_POST['ddl_companyname'])) {
                update_post_meta($post_ID, 'paypal_wp_button_manager_company_rel', $_POST['ddl_companyname']);
            }
            if (isset($PayPalResult['EMAILLINK']) && !empty($PayPalResult['EMAILLINK'])) {
                update_post_meta($post_ID, 'paypal_wp_button_manager_email_link', $PayPalResult['EMAILLINK']);
            }            
            if (isset($PayPalResult['HOSTEDBUTTONID']) && !empty($PayPalResult['HOSTEDBUTTONID'])) {
                if ((isset($_POST['enable_inventory']) && !empty($_POST['enable_inventory'])) || (isset($_POST['enable_profit_and_loss']) && !empty($_POST['enable_profit_and_loss']))) {
                    $PayPalRequestData_Inventory = $payapal_helper->paypal_wp_button_manager_set_inventory();                     
                    $PayPalSet_InventoryResult = $PayPal->BMSetInventory($PayPalRequestData_Inventory);
                    self::paypal_wp_button_manager_write_error_log($PayPalSet_InventoryResult);
                    if (isset($PayPalSet_InventoryResult['ERRORS']) && !empty($PayPalSet_InventoryResult['ERRORS'])) {
                        global $post, $post_ID;
                        $paypal_wp_button_manager_notice = get_option('paypal_wp_button_manager_notice');
                        $notice[$post_ID] = $PayPalSet_InventoryResult['ERRORS'][0]['L_LONGMESSAGE'];
                        $notice_code[$post_ID] = $PayPalSet_InventoryResult['ERRORS'][0]['L_ERRORCODE'];
                        update_option('paypal_wp_button_manager_notice', $notice);
                        update_option('paypal_wp_button_manager_error_code', $notice_code);
                        update_post_meta($post_ID, 'paypal_wp_button_manager_success_notice', '');
                        delete_option('paypal_wp_button_manager_timeout_notice');
                    }
                }
            }
            unset($post);
            unset($_POST);
        }
    }
    /**
     * This function is for write log in error log folder
     * @param type $error The error returns the string of a error.
     */
    public static function paypal_wp_button_manager_write_error_log($error) {
        $debug = (get_option('log_enable_button_manager') == 'yes') ? 'yes' : 'no';
        if ('yes' == $debug) {
            $log_write = new AngellEYE_PayPal_WP_Button_Manager_Logger();
        }
        if ('yes' == $debug) {
            $log_write->add('paypal_wp_button_manager', 'PayPal WP Button Manager response: ' . print_r($error, true));
        }
    }
}
AngellEYE_PayPal_WP_Button_Manager_button_updater::init();