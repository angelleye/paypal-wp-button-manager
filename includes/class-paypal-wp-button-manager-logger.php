<?php
/*
 * Class responsible for generating the logs.
 */
class PayPal_WP_Button_Manager_Logger {
    private $log_file;
    private static $log_dir = 'angelleye-wp-button-manager-logs';

    public function __construct( $type=null ) {
        $log_file_name = is_null( $type ) ? 'log-' . date( 'Y-m-d' ) . '.log' : 'log-' . date( 'Y-m-d' ) . '-' . $type . '.log';
        $upload_dir = wp_upload_dir();
        $log_dir = $upload_dir['basedir'] . '/' . self::$log_dir;
        wp_mkdir_p( $log_dir );
        $this->log_file = $log_dir . '/' . $log_file_name;

        // Create the .htaccess file with the appropriate protection rules
        $htaccess_file_path = $log_dir . '/.htaccess';
        if ( ! file_exists( $htaccess_file_path ) ) {
            $htaccess_file_content = "Order deny,allow\nDeny from all";
            file_put_contents( $htaccess_file_path, $htaccess_file_content );
        }
    }

    /**
     * Logs the error information
     * 
     * @param string message error message
     * @param mixed data error data
     * */
    public function error( $message, $data = null ) {
        $this->log( $message, 'ERROR', $data );
    }

    /**
     * Logs the debug information
     * 
     * @param string message debug message
     * @param mixed data debug data
     * */
    public function debug( $message, $data = null ) {
        $this->log( $message, 'DEBUG', $data );
    }

    /**
     * Logs the info information
     * 
     * @param string message info message
     * @param mixed data info data
     * */
    public function info( $message, $data = null ) {
        $this->log( $message, 'INFO', $data );
    }

    /**
     * Actually logs the information
     * 
     * @param string message message string
     * @param string type type of log
     * @param mixed data data that needs to be logged
     * */
    private function log( $message, $type, $data = null ) {
        // Append the type prefix to the message
        $prefixed_message = "[$type] $message";

        // Append the data to the message, if provided
        if ( ! is_null( $data ) ) {
            $prefixed_message .= "\n" . print_r( $data, true );
        }

        // Open the log file for writing (create if it doesn't exist)
        $handle = fopen( $this->log_file, 'a' );
        
        chmod( $this->log_file, 0600);

        // Write the message to the log file
        fwrite( $handle, date( 'Y-m-d H:i:s' ) . ': ' . $prefixed_message . "\n" );

        // Close the file handle
        fclose( $handle );
    }

    /**
     * Remove the logs generated before 30 days
     * */
    public static function delete_old_logs(){
        $upload_dir = wp_upload_dir();
        $log_directory = $upload_dir['basedir'] . '/' . self::$log_dir;
        $files = scandir($log_directory);
        $today = strtotime('today midnight');
        foreach ($files as $file) {
            if (preg_match('/^log-\d{4}-\d{2}-\d{2}\.txt$/', $file)) {
                $date = strtotime(substr($file, 4, 10));
                if ($date < ($today - (30 * 24 * 60 * 60))) {
                    unlink($log_directory . '/' . $file);
                }
            }
        }
    }
}