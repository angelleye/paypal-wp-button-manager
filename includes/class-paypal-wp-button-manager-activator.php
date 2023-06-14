<?php
defined( 'ABSPATH' ) || exit;
/*
 * Fired during the plugin activation.
 */
class PayPal_WP_Button_Manager_Activator{

    public function __construct(){
        register_activation_hook( WBP_PLUGIN_FILE, array( $this, 'create_database_tables' ) );
        register_deactivation_hook( WBP_PLUGIN_FILE, 'flush_rewrite_rules' );
    }

    /**
     * Creates the database table for companies
     * */
    public function create_database_tables(){
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