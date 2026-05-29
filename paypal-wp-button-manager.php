<?php

/**
 * @wordpress-plugin
 * Plugin Name:       PayPal WP Button Manager
 * Plugin URI:        http://www.angelleye.com/
 * Description:       Easily create and manage secure PayPal buttons for WordPress
 * Version:           2.0.4
 * Author:            Angell EYE
 * Author URI:        http://www.angelleye.com/
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       paypal-wp-button-manager
 * Domain Path:       /languages
 * Requires at least: 6.2
 * Tested up to:      7.0
 * Requires PHP:      7.4
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
/**
 *  define PIW_PLUGIN_DIR constant for global use
 */
if (!defined('BMW_PLUGIN_DIR'))
    define('BMW_PLUGIN_DIR', dirname(__FILE__));

/**
 * define BMW_PLUGIN_URL constant for global use
 */
if (!defined('BMW_PLUGIN_URL'))
    define('BMW_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 *  define log file path
 */
if (!defined('PAYPAL_WP_BUTTON_MANAGER_LOG_DIR')) {
    define('PAYPAL_WP_BUTTON_MANAGER_LOG_DIR', ABSPATH . 'wp-content/uploads/paypal-wp-button-manager-logs/');
}

/**
 * define plugin basename
 */
if (!defined('PAYPAL_WP_BUTTON_MANAGER_PLUGIN_BASENAME')) {
    define('PAYPAL_WP_BUTTON_MANAGER_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

if (!defined('AEU_ZIP_URL')) {
    define('AEU_ZIP_URL', 'https://updates.angelleye.com/ae-updater/angelleye-updater/angelleye-updater.zip');
}

/**
 * Required functions
 */
if (!function_exists('angelleye_queue_update')) {
    require_once( 'includes/angelleye-functions.php' );
}

angelleye_queue_update(plugin_basename(__FILE__), '101', 'paypal-wp-button-manager');

/**
 * Shared AngellEYE push-notifications class. Self-contained — keep this file in
 * sync with the copy in paypal-for-woocommerce; the class_exists guard inside
 * ensures only one copy loads if multiple AngellEYE plugins are active.
 */
require_once plugin_dir_path(__FILE__) . 'includes/notifications/class-angelleye-push-notifications.php';
add_action('plugins_loaded', function () {
    if (!is_admin() || !class_exists('AngellEYE_Push_Notifications')) {
        return;
    }
    (new AngellEYE_Push_Notifications(array(
        'plugin_slug' => 'paypal-wp-button-manager',
        'applies_to'  => function ($notification, $plugin_slug) {
            if (empty($notification->ans_plugins) || !is_array($notification->ans_plugins)) {
                // No targeting metadata — show to all AE plugins (legacy behavior).
                return true;
            }
            return in_array($plugin_slug, $notification->ans_plugins, true);
        },
    )))->register();
}, 25);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-paypal-wp-button-manager-activator.php
 */
function activate_paypal_wp_button_manager($networkwide) {
	
    require_once plugin_dir_path(__FILE__) . 'includes/class-paypal-wp-button-manager-activator.php';
    AngellEYE_PayPal_WP_Button_Manager_Activator::activate($networkwide);
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-paypal-wp-button-manager-deactivator.php
 */
function deactivate_paypal_wp_button_manager() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-paypal-wp-button-manager-deactivator.php';
    AngellEYE_PayPal_WP_Button_Manager_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_paypal_wp_button_manager');
register_deactivation_hook(__FILE__, 'deactivate_paypal_wp_button_manager');

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-paypal-wp-button-manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_paypal_wp_button_manager() {

    $plugin = new AngellEYE_PayPal_WP_Button_Manager();
    $plugin->run();
}

run_paypal_wp_button_manager();
