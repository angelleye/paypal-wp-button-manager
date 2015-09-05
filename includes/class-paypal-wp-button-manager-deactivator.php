<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    paypal-wp-button-manager
 * @subpackage paypal-wp-button-manager/includes
 * @author     Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_Deactivator {

    /**
     * @since    0.1.0
     */
    public static function deactivate() {

        // Log activation in Angell EYE database via web service.
        // @todo need to add option to enable this
        //$log_url = $_SERVER['HTTP_HOST'];
        //$log_plugin_id = 9;
        //$log_activation_status = 0;
        //wp_remote_request('http://www.angelleye.com/web-services/wordpress/update-plugin-status.php?url='.$log_url.'&plugin_id='.$log_plugin_id.'&activation_status='.$log_activation_status);
    	
    }

}
