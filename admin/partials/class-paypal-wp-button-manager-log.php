<?php

/**
 * This class defines all code necessary to Companies Setting from admin side
 * @class       AngellEYE_PayPal_WP_Button_Manager_Company_Setting
 * @version	1.0.0
 * @package		paypal-wp-button-manager/admin/partials
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_Log {

    public static function init() {
        add_action('paypal_wp_button_manager_logs_setting', array(__CLASS__, 'paypal_wp_button_manager_logs_setting'));
    }

    public static function paypal_wp_button_manager_logs_setting() {

        $logs = self::scan_log_files();

        if (!empty($_REQUEST['log_file']) && isset($logs[sanitize_title($_REQUEST['log_file'])])) {
            $viewed_log = $logs[sanitize_title($_REQUEST['log_file'])];
        } elseif (!empty($logs)) {
            $viewed_log = current($logs);
        }

        if ($logs) :
            ?>
            <div id="log-viewer-select">
                <div class="alignleft">
                    <h2><?php printf(__('Log file: %s (%s)', 'paypal-wp-button-manager'), esc_html($viewed_log), date_i18n(get_option('date_format') . ' ' . get_option('time_format'), filemtime(PAYPAL_WP_BUTTON_MANAGER_LOG_DIR . $viewed_log))); ?></h2>
                </div>
                <div class="alignright">
                    <form action="<?php echo admin_url('admin.php?page=paypal-wp-button-manager-option&tab=logs'); ?>" method="post">
                        <select name="log_file">
                            <?php foreach ($logs as $log_key => $log_file) : ?>
                                <option value="<?php echo esc_attr($log_key); ?>" <?php selected(sanitize_title($viewed_log), $log_key); ?>><?php echo esc_html($log_file); ?> (<?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), filemtime(PAYPAL_WP_BUTTON_MANAGER_LOG_DIR . $log_file)); ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <input type="submit" class="button" value="<?php esc_attr_e('View', 'paypal-wp-button-manager'); ?>" />
                    </form>
                </div>
                <div class="clear"></div>
            </div>
            <div id="log-viewer">
                <textarea cols="70" rows="25"><?php echo esc_textarea(file_get_contents(PAYPAL_WP_BUTTON_MANAGER_LOG_DIR . $viewed_log)); ?></textarea>
            </div>
        <?php else : ?>
            <div class="updated woocommerce-message inline"><p><?php _e('There are currently no logs to view.', 'paypal-wp-button-manager'); ?></p></div>
        <?php
        endif;
    }

    /**
     * Scan the log files.
     * @return array
     */
    public static function scan_log_files() {
        $files = @scandir(PAYPAL_WP_BUTTON_MANAGER_LOG_DIR);
        $result = array();

        if (!empty($files)) {

            foreach ($files as $key => $value) {

                if (!in_array($value, array('.', '..'))) {
                    if (!is_dir($value) && strstr($value, '.log')) {
                        $result[sanitize_title($value)] = $value;
                    }
                }
            }
        }

        return $result;
    }

}

AngellEYE_PayPal_WP_Button_Manager_Log::init();
