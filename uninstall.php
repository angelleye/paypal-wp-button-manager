<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://angelleye.com
 * @since      1.0.0
 *
 * @package    Angelleye_Paypal_Wp_Button_Manager
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
global $wpdb;
$paypal_companies = $wpdb->prefix . 'angelleye_paypal_button_manager_companies';
$wpdb->query("DROP TABLE IF EXISTS $paypal_companies");
