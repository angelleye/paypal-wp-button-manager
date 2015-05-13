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
        $add_result = $wpdb->insert($table_name, array('title' => isset($_POST['company_title']) ? $_POST['company_title'] : '',
            'paypal_person_name' => isset($_POST['paypal_person_name']) ? $_POST['paypal_person_name'] : '',
            'paypal_person_email' => isset($_POST['paypal_person_email']) ? $_POST['paypal_person_email'] : '',
            'paypal_api_username' => isset($_POST['paypal_api_username']) ? $_POST['paypal_api_username'] : '',
            'paypal_api_password' => isset($_POST['paypal_api_password']) ? $_POST['paypal_api_password'] : '',
            'paypal_api_signature' => isset($_POST['paypal_api_signature']) ? $_POST['paypal_api_signature'] : '',
            'paypal_mode' => isset($_POST['paypal_mode']) ? $_POST['paypal_mode'] : ''
                ));
        return $add_result;
    }

    public function paypal_wp_button_manager_edit_company() {
        global $wpdb;
        $table_name = $wpdb->prefix . "paypal_wp_button_manager_companies";
        $id = $_GET['cmp_id'];
        $edit_result = $wpdb->update($table_name, array('title' => isset($_POST['company_title']) ? $_POST['company_title'] : '',
            'paypal_person_name' => isset($_POST['paypal_person_name']) ? $_POST['paypal_person_name'] : '',
            'paypal_person_email' => isset($_POST['paypal_person_email']) ? $_POST['paypal_person_email'] : '',
            'paypal_api_username' => isset($_POST['paypal_api_username']) ? $_POST['paypal_api_username'] : '',
            'paypal_api_password' => isset($_POST['paypal_api_password']) ? $_POST['paypal_api_password'] : '',
            'paypal_api_signature' => isset($_POST['paypal_api_signature']) ? $_POST['paypal_api_signature'] : '',
            'paypal_mode' => isset($_POST['paypal_mode']) ? $_POST['paypal_mode'] : ''), array('ID' => $id), array('%s', '%s', '%s', '%s', '%s', '%s'), array('%d'));
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
