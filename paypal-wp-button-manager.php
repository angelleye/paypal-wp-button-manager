<?php

/**
 * @wordpress-plugin
 * Plugin Name:       PayPal WP Button Manager
 * Plugin URI:        http://www.angelleye.com/
 * Description:       Easily create and manage secure PayPal buttons for WordPress
 * Version:           1.0.3
 * Author:            Angell EYE
 * Author URI:        http://www.angelleye.com/
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       paypal-wp-button-manager
 * Domain Path:       /languages
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

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-paypal-wp-button-manager-activator.php
 */
function activate_paypal_wp_button_manager() {
	
    require_once plugin_dir_path(__FILE__) . 'includes/class-paypal-wp-button-manager-activator.php';
    AngellEYE_PayPal_WP_Button_Manager_Activator::activate();
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
