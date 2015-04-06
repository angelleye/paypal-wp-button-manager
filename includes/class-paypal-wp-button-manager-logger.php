<?php

/**
 * Allows log files to be written to for debugging purposes.
 *
 * @class       AngellEYE_PayPal_WP_Button_Manager_Logger
 * @version		1.0.0
 * @package		paypal-wp-button-manager/includes
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_Logger {

    /**
     * @var array Stores open file _handles.
     * @access private
     */
    private $_handles;

    /**
     * Constructor for the logger.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->_handles = array();
    }

    /**
     * Destructor.
     *
     * @access public
     * @return void
     */
    public function __destruct() {
        foreach ($this->_handles as $handle) {
            @fclose(escapeshellarg($handle));
        }
    }

    /**
     * Open log file for writing.
     *
     * @access private
     * @param mixed $handle
     * @return bool success
     */
    private function open($handle) {
        if (isset($this->_handles[$handle])) {
            return true;
        }

        if ($this->_handles[$handle] = @fopen($this->paypal_button_manager_for_wordpress_wordpress_get_log_file_path($handle), 'a')) {
            return true;
        }

        return false;
    }

    /**
     * Add a log entry to chosen file.
     *
     * @access public
     * @param mixed $handle
     * @param mixed $message
     * @return void
     */
    public function add($handle, $message) {
        if ($this->open($handle) && is_resource($this->_handles[$handle])) {
            $time = date_i18n('m-d-Y @ H:i:s -'); // Grab Time
            @fwrite($this->_handles[$handle], $time . " " . $message . "\n");
        }
    }

    /**
     * Clear entries from chosen file.
     *
     * @access public
     * @param mixed $handle
     * @return void
     */
    public function clear($handle) {
        if ($this->open($handle) && is_resource($this->_handles[$handle])) {
            @ftruncate($this->_handles[$handle], 0);
        }
    }

    /**
     * Get file path
     *
     * @access public
     * @param mixed $handle
     * @return void
     */
    public function paypal_button_manager_for_wordpress_wordpress_get_log_file_path($handle) {
        return trailingslashit(PAYPAL_WP_BUTTON_MANAGER_LOG_DIR) . $handle . '-' . sanitize_file_name(wp_hash($handle)) . '.log';
    }

}
