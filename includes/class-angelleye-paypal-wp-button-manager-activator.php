<?php

/**
 * Fired during plugin activation
 *
 * @link       https://angelleye.com
 * @since      1.0.0
 *
 * @package    Angelleye_Paypal_Wp_Button_Manager
 * @subpackage Angelleye_Paypal_Wp_Button_Manager/includes
 */
class Angelleye_Paypal_Wp_Button_Manager_Activator {

	/**
	 * Creates the database table for companies.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        global $wpdb;
        $table_name = $wpdb->prefix . "angelleye_paypal_button_manager_companies";
        $charset_collate = $wpdb->get_charset_collate();
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
            `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
            `company_name` mediumtext  NULL,
            `paypal_person_name` mediumtext  NULL,
            `country` varchar(2) NULL,
            `paypal_mode` tinytext  NULL,
            `paypal_merchant_id` text  NULL,
            `tracking_id` text NULL,
            `products` text NULL,
            PRIMARY KEY ID (ID)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        flush_rewrite_rules();
	}

}
