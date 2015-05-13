<?php

/**
 * Fired during plugin uninstall.
 *
 * This class defines all code necessary to run during the plugin's uninstall.
 *
 * @since      1.0.0
 * @package    paypal-wp-button-manager
 * @subpackage paypal-wp-button-manager/includes
 * @author     Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_Uninstall {

    /**
     * @since    0.1.0
     */
    public static function uninstall() {
        //if uninstall not called from WordPress exit
		if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	   		 exit();
		}
				
		//drop a custom db table
		global $wpdb;
		
        $paypal_companies = $wpdb->prefix . 'paypal_wp_button_manager_companies';
		$wpdb->query( "DROP TABLE IF EXISTS $paypal_companies" );
		    	
    }

}
