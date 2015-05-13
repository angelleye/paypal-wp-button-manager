<?php

/**
 * Fired when the plugin is uninstalled.
  */
// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}
global $wpdb;
$paypal_companies = $wpdb->prefix . 'paypal_wp_button_manager_companies';
$wpdb->query("DROP TABLE IF EXISTS $paypal_companies");
