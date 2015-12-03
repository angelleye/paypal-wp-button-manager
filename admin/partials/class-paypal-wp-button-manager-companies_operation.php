<?php

/**
 * This class defines all code necessary to add edit delete company
 * @class       AngellEYE_PayPal_WP_Button_Manager_Company_Operations
 * @version	1.0.0
 * @package		paypal-wp-button-manager/admin/partials
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_Company_Operations {

    /**
     * Hook in methods
     * @since    0.1.0
     * @access   static
     */
    function __construct() {
        
    }

    public function paypal_wp_button_manager_add_company() {
        global $wpdb;
        $table_name = $wpdb->prefix . "paypal_wp_button_manager_companies";


        if ((isset($_POST['paypal_mode']) && !empty($_POST['paypal_mode'])) && (isset($_POST['paypal_api_username']) && !empty($_POST['paypal_api_username'])) && (isset($_POST['paypal_api_password']) && !empty($_POST['paypal_api_password'])) && (isset($_POST['paypal_api_signature']) && !empty($_POST['paypal_api_signature']))) {
            $flag_mode = '';
            if (isset($_POST['paypal_mode']) && !empty($_POST['paypal_mode'])) {
                if ($_POST['paypal_mode'] == 'Sandbox') {
                    $flag_mode = true;
                } else if ($_POST['paypal_mode'] == 'Live') {
                    $flag_mode = false;
                } else {
                    $flag_mode = '';
                }
            }

            $PayPalConfig = array(
                'Sandbox' => $flag_mode,
                'APIUsername' => isset($_POST['paypal_api_username']) ? trim($_POST['paypal_api_username']) : '',
                'APIPassword' => isset($_POST['paypal_api_password']) ? trim($_POST['paypal_api_password']) : '',
                'APISignature' => isset($_POST['paypal_api_signature']) ? trim($_POST['paypal_api_signature']) : '',
                'PrintHeaders' => isset($print_headers) ? $print_headers : '',
                'LogResults' => isset($log_results) ? $log_results : '',
                'LogPath' => isset($log_path) ? $log_path : '',
            );



            $PayPal = new Angelleye_PayPal($PayPalConfig);
            $PayPalResult = $PayPal->GetPalDetails();

            if (isset($PayPalResult['ACK']) && $PayPalResult['ACK'] == 'Success') {
                if (isset($PayPalResult['PAL']) && !empty($PayPalResult['PAL'])) {
                    $merchant_account_id = $PayPalResult['PAL'];
                }
            }
        }




        $add_result = $wpdb->insert($table_name, array('title' => isset($_POST['company_title']) ? trim($_POST['company_title']) : '',
            'paypal_person_name' => isset($_POST['paypal_person_name']) ? trim($_POST['paypal_person_name']) : '',
            'paypal_person_email' => isset($_POST['paypal_person_email']) ? trim($_POST['paypal_person_email']) : '',
            'paypal_api_username' => isset($_POST['paypal_api_username']) ? trim($_POST['paypal_api_username']) : '',
            'paypal_api_password' => isset($_POST['paypal_api_password']) ? trim($_POST['paypal_api_password']) : '',
            'paypal_api_signature' => isset($_POST['paypal_api_signature']) ? trim($_POST['paypal_api_signature']) : '',
            'paypal_mode' => isset($_POST['paypal_mode']) ? $_POST['paypal_mode'] : '',
            'paypal_account_mode' => isset($_POST['paypal_account_mode']) ? $_POST['paypal_account_mode'] : '',
            'paypal_merchant_id' => isset($merchant_account_id) ? $merchant_account_id : '',
                ));

        unset($PayPalConfig);
        return $add_result;
    }

    public function paypal_wp_button_manager_edit_company() {
        global $wpdb;
        $table_name = $wpdb->prefix . "paypal_wp_button_manager_companies";
        $id = $_GET['cmp_id'];


        if ((isset($_POST['paypal_mode']) && !empty($_POST['paypal_mode'])) && (isset($_POST['paypal_api_username']) && !empty($_POST['paypal_api_username'])) && (isset($_POST['paypal_api_password']) && !empty($_POST['paypal_api_password'])) && (isset($_POST['paypal_api_signature']) && !empty($_POST['paypal_api_signature']))) {
            $flag_mode = '';
            if (isset($_POST['paypal_mode']) && !empty($_POST['paypal_mode'])) {
                if ($_POST['paypal_mode'] == 'Sandbox') {
                    $flag_mode = true;
                } else if ($_POST['paypal_mode'] == 'Live') {
                    $flag_mode = false;
                } else {
                    $flag_mode = '';
                }
            }

            $PayPalConfig = array(
                'Sandbox' => $flag_mode,
                'APIUsername' => isset($_POST['paypal_api_username']) ? trim($_POST['paypal_api_username']) : '',
                'APIPassword' => isset($_POST['paypal_api_password']) ? trim($_POST['paypal_api_password']) : '',
                'APISignature' => isset($_POST['paypal_api_signature']) ? trim($_POST['paypal_api_signature']) : '',
                'PrintHeaders' => isset($print_headers) ? $print_headers : '',
                'LogResults' => isset($log_results) ? $log_results : '',
                'LogPath' => isset($log_path) ? $log_path : '',
            );



            $PayPal = new Angelleye_PayPal($PayPalConfig);
            $PayPalResult = $PayPal->GetPalDetails();

            if (isset($PayPalResult['ACK']) && $PayPalResult['ACK'] == 'Success') {
                if (isset($PayPalResult['PAL']) && !empty($PayPalResult['PAL'])) {
                    $merchant_account_id = $PayPalResult['PAL'];
                }
            }
        }



        $edit_result = $wpdb->update($table_name, array('title' => isset($_POST['company_title']) ? trim($_POST['company_title']) : '',
            'paypal_person_name' => isset($_POST['paypal_person_name']) ? trim($_POST['paypal_person_name']) : '',
            'paypal_person_email' => isset($_POST['paypal_person_email']) ? trim($_POST['paypal_person_email']) : '',
            'paypal_api_username' => isset($_POST['paypal_api_username']) ? trim($_POST['paypal_api_username']) : '',
            'paypal_api_password' => isset($_POST['paypal_api_password']) ? trim($_POST['paypal_api_password']) : '',
            'paypal_api_signature' => isset($_POST['paypal_api_signature']) ? trim($_POST['paypal_api_signature']) : '',
            'paypal_mode' => isset($_POST['paypal_mode']) ? $_POST['paypal_mode'] : '',
            'paypal_account_mode' => isset($_POST['paypal_account_mode']) ? $_POST['paypal_account_mode'] : '',
            'paypal_merchant_id' => isset($merchant_account_id) ? $merchant_account_id : ''), array('ID' => $id), array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'), array('%d'));

        unset($PayPalConfig);
        return $edit_result;
    }

    public function paypal_wp_button_manager_delete_company() {
        global $wpdb;
        $table_name = $wpdb->prefix . "paypal_wp_button_manager_companies";
        $nonce = $_REQUEST['_wpnonce'];
        $ID = isset($_GET['cmp_id']) ? $_GET['cmp_id'] : 0;
        if (wp_verify_nonce($nonce, 'delete_company' . $ID)) {
            $delete_item = $wpdb->delete($table_name, array('ID' => $ID));
        }

        return $delete_item;
    }

}

