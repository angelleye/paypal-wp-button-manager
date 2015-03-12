<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    paypal-button-manager-for-wordpress
 * @subpackage paypal-button-manager-for-wordpress/admin
 * @author     Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_Button_Manager_for_WordPress_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the Dashboard.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/paypal-button-manager-for-wordpress-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the dashboard.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/paypal-button-manager-for-wordpress-admin.js', array('jquery'), $this->version, false);
    }

}
