<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    paypal-wp-button-manager
 * @subpackage paypal-wp-button-manager/includes
 * @author     Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_Activator {

    /**
     * @since    0.1.0
     */
    public static function activate($network_wide = true) {
        /**
         *  call create_files function when plugin active
         */
        global $wpdb;
        self::create_files();
        if (is_multisite() && $network_wide) {
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_ids as $blog_id) {
                switch_to_blog($blog_id);
                self::create_table();
                restore_current_blog();
            }
        } else {
            self::create_table();
        }
    }

    /**
     * Create files/directories
     */
    public static function create_files() {
        // Install files and folders for uploading files and prevent hotlinking
        $upload_dir = wp_upload_dir();
        $files = array(
            array(
                'base' => PAYPAL_WP_BUTTON_MANAGER_LOG_DIR,
                'file' => '.htaccess',
                'content' => 'deny from all'
            ),
            array(
                'base' => PAYPAL_WP_BUTTON_MANAGER_LOG_DIR,
                'file' => 'index.html',
                'content' => ''
            )
        );

        foreach ($files as $file) {
            if (wp_mkdir_p($file['base']) && !file_exists(trailingslashit($file['base']) . $file['file'])) {
                if ($file_handle = @fopen(trailingslashit($file['base']) . $file['file'], 'w')) {
                    fwrite($file_handle, $file['content']);
                    fclose($file_handle);
                }
            }
        }
    }

    public static function create_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . "paypal_wp_button_manager_companies";
        $charset_collate = $wpdb->get_charset_collate();
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
		`ID` mediumint(9) NOT NULL AUTO_INCREMENT,
		`title` mediumtext  NULL,
		`paypal_person_name` mediumtext  NULL,
		`paypal_person_email` text  NULL,
		`paypal_api_username` text  NULL,
		`paypal_api_password` text  NULL,
		`paypal_api_signature` text  NULL,
		`paypal_mode` tinytext  NULL,
		`paypal_merchant_id` text  NULL,
		`paypal_account_mode` tinytext  NULL,
		UNIQUE KEY ID (ID)
		) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        } else if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
            $row_paypal_merchant_id = $wpdb->get_var("SHOW COLUMNS FROM `$table_name` LIKE 'paypal_merchant_id'");
            $row_paypal_account_mode = $wpdb->get_var("SHOW COLUMNS FROM `$table_name` LIKE 'paypal_account_mode'");
            if (!$row_paypal_merchant_id) {
                $wpdb->query("ALTER TABLE $table_name ADD paypal_merchant_id text NULL");
            }
            if (!$row_paypal_account_mode) {
                $wpdb->query("ALTER TABLE $table_name ADD paypal_account_mode tinytext NULL");
            }
        }
    }

}
