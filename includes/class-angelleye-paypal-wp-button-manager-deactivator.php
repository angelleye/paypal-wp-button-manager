<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://angelleye.com
 * @since      1.0.0
 *
 * @package    Angelleye_Paypal_Wp_Button_Manager
 * @subpackage Angelleye_Paypal_Wp_Button_Manager/includes
 */
class Angelleye_Paypal_Wp_Button_Manager_Deactivator {

	/**
	 * Flushes the rewrite rules when plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        flush_rewrite_rules();
	}

}
