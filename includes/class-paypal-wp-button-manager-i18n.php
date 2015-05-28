<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    paypal-wp-button-manager
 * @subpackage paypal-wp-button-manager/includes
 * @author     Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_i18n {

    /**
     * The domain specified for this plugin.
     *
     * @since    0.1.0
     * @access   private
     * @var      string    $domain    The domain identifier for this plugin.
     */
    private $domain;

    /**
     * Load the plugin text domain for translation.
     *
     * @since    0.1.0
     */
    public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), $this->domain );
       /* load_plugin_textdomain(
                $this->domain, false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );*/
        
       
		load_textdomain( $this->domain, BMW_PLUGIN_URL . 'languages/' . $this->domain . '-' . $locale . '.po' );
		load_plugin_textdomain( $this->domain, FALSE, BMW_PLUGIN_URL . '/languages/' );
    }

    /**
     * Set the domain equal to that of the specified domain.
     *
     * @since    0.1.0
     * @param    string    $domain    The domain that represents the locale of this plugin.
     */
    public function set_domain($domain) {
        $this->domain = $domain;
    }

}
