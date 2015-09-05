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
	public static function activate() {
		/**
         *  call create_files function when plugin active
         */
		self::create_files();

		global $wpdb;

		// Log activation in Angell EYE database via web service.
		// @todo need to add option for people to enable this.
		//$log_url = $_SERVER['HTTP_HOST'];
		//$log_plugin_id = 9;
		//$log_activation_status = 1;
		//wp_remote_request('http://www.angelleye.com/web-services/wordpress/update-plugin-status.php?url='.$log_url.'&plugin_id='.$log_plugin_id.'&activation_status='.$log_activation_status);


		$table_name = $wpdb->prefix . "paypal_wp_button_manager_companies";
		$charset_collate = $wpdb->get_charset_collate();

		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
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
			dbDelta( $sql );
		} else if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
		$row_paypal_merchant_id = $wpdb->get_var("SHOW COLUMNS FROM `$table_name` LIKE 'paypal_merchant_id'");
		$row_paypal_account_mode =$wpdb->get_var("SHOW COLUMNS FROM `$table_name` LIKE 'paypal_account_mode'");
		
			
		if(!$row_paypal_merchant_id){
				$wpdb->query("ALTER TABLE $table_name ADD paypal_merchant_id text NULL");
			}
		if(!$row_paypal_account_mode){
				$wpdb->query("ALTER TABLE $table_name ADD paypal_account_mode tinytext NULL");
			}
			
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

}
