<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://angelleye.com
 * @since      1.0.0
 *
 * @package    Angelleye_Paypal_Wp_Button_Manager
 * @subpackage Angelleye_Paypal_Wp_Button_Manager/public
 */
class Angelleye_Paypal_Wp_Button_Manager_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_register_style( $this->plugin_name . '-thankyou', ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL . 'public/css/angelleye-paypal-wp-button-manager-thankyou.css', array(), $this->version, 'all' );

		wp_register_style( $this->plugin_name . '-frontend-button', ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL . 'public/css/angelleye-paypal-wp-button-manager-frontend-button.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
	}

}
