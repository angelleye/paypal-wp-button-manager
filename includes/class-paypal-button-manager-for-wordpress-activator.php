<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    paypal-button-manager-for-wordpress
 * @subpackage paypal-button-manager-for-wordpress/includes
 * @author     Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_Button_Manager_for_WordPress_Activator {

    /**
     * @since    1.0.0
     */
    public static function activate() {
       /**
         *  call create_files function when plugin active
         */
        self::create_files();
    }
    /**
     * Create files/directories
     */
    public static function create_files() {
        // Install files and folders for uploading files and prevent hotlinking
        $upload_dir = wp_upload_dir();

        $files = array(
            array(
                'base' => PAYPAL_BUTTONS_FOR_WORDPRESS_LOG_DIR,
                'file' => '.htaccess',
                'content' => 'deny from all'
            ),
            array(
                'base' => PAYPAL_BUTTONS_FOR_WORDPRESS_LOG_DIR,
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
