<?php

/**
 * @wordpress-plugin
 * Plugin Name:       PayPal WP Button Manager V2
 * Plugin URI:        http://www.angelleye.com/
 * Description:       Easily create and manage secure PayPal buttons for WordPress
 * Version:           1.0.0
 * Author:            Angell EYE
 * Author URI:        http://www.angelleye.com/
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       paypal-wp-button-manager
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    die;
}

if ( ! defined( 'WBP_PLUGIN_FILE' ) ) {
    define( 'WBP_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'WBP_PLUGIN_URL') ) {
    define( 'WBP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if( !defined( 'WBP_PLUGIN_PATH') ) {
    define('WBP_PLUGIN_PATH', __DIR__ );
}

if( !defined('WBP_IMAGE_PATH' ) ){
    define('WBP_IMAGE_PATH', WBP_PLUGIN_URL . 'assets/backend/images/' );
}

if( !defined('WBP_API_LINK') ){
    define('WBP_API_LINK', 'https://3yjtbtgz0m.execute-api.us-east-2.amazonaws.com/default/PayPalMerchantIntegrationTest/' );
}

if( !defined('WBP_PPCP_LINK') ){
    define('WBP_PPCP_LINK', 'https://3yjtbtgz0m.execute-api.us-east-2.amazonaws.com/default/PayPalMerchantIntegrationTest/ppcp-request' );
}

if( !defined( 'WBP_SANDBOX_PARTNER_MERCHANT_ID') ){
    define('WBP_SANDBOX_PARTNER_MERCHANT_ID', 'B82TS7QWRJ6TS');
}

if( !defined( 'WBP_LIVE_PARTNER_MERCHANT_ID') ){
    define('WBP_LIVE_PARTNER_MERCHANT_ID', 'B82TS7QWRJ6TS');
}

if (!defined('WBP_SANDBOX_PARTNER_CLIENT_ID')) {
    define('WBP_SANDBOX_PARTNER_CLIENT_ID', 'AaYsUf4lXeKOnLmKhDWbak0YYWNk5SW0Lt1lk22gFvsgu74h1Vawg1y6rcmt60f8JIx-x81J5bMA-q7O');
}

if (!defined('WBP_LIVE_PARTNER_CLIENT_ID')) {
    define('WBP_LIVE_PARTNER_CLIENT_ID', 'AaYsUf4lXeKOnLmKhDWbak0YYWNk5SW0Lt1lk22gFvsgu74h1Vawg1y6rcmt60f8JIx-x81J5bMA-q7O');
}

require __DIR__ . '/includes/Autoloader.php';

$wbp_autoloader = new WBP_Autoloader();
if(!$wbp_autoloader->init()) {
    return; 
}

$wbp_autoloader->load();