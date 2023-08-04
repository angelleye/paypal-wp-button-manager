<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://angelleye.com
 * @since      1.0.0
 *
 * @package    Angelleye_Paypal_Wp_Button_Manager
 * @subpackage Angelleye_Paypal_Wp_Button_Manager/admin
 */
class Angelleye_Paypal_Wp_Button_Manager_Admin {

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
	 * @since      1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		global $post_type, $pagenow;
		if( ( isset( $_GET['page'] ) && $_GET['page'] == Angelleye_Paypal_WP_Button_Manager_Company::$paypal_button_company_slug ) || ( $post_type === Angelleye_Paypal_Wp_Button_Manager_Post::$post_type && $pagenow === 'edit.php' ) ){
            wp_enqueue_style( $this->plugin_name . '-company', ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL . 'admin/css/angelleye-paypal-wp-button-manager-company.css', array(), $this->version, 'all' );
        }

        if( ( isset( $_GET['post_type'] ) && $_GET['post_type'] == Angelleye_Paypal_Wp_Button_Manager_Post::$post_type ) || ( isset( $_GET['post'] ) && get_post_type( sanitize_text_field( $_GET['post'] ) ) == Angelleye_Paypal_Wp_Button_Manager_Post::$post_type ) ){
        	wp_enqueue_style( $this->plugin_name . '-button', ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL . 'admin/css/angelleye-paypal-wp-button-manager-button.css', array(), $this->version, 'all' );
        	wp_enqueue_style( $this->plugin_name . '-select2', ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL . 'admin/css/select2.min.css', array(), $this->version, 'all' );
        	wp_enqueue_style( $this->plugin_name . '-jquery-ui', ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL . 'admin/css/jquery-ui.min.css', array(), $this->version, 'all');
        	wp_enqueue_style("wp-color-picker");
        }
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if( isset( $_GET['page'] ) && $_GET['page'] == Angelleye_Paypal_WP_Button_Manager_Company::$paypal_button_company_slug ){
			wp_enqueue_script( $this->plugin_name, ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL . 'admin/js/angelleye-paypal-wp-button-manager-company.js', array( 'jquery' ), $this->version, false );
		}

		if( ( isset( $_GET['post_type'] ) && $_GET['post_type'] == Angelleye_Paypal_Wp_Button_Manager_Post::$post_type ) || ( isset( $_GET['post'] ) && get_post_type( sanitize_text_field( $_GET['post'] ) ) == Angelleye_Paypal_Wp_Button_Manager_Post::$post_type ) ){
            
            if( isset( $_GET['post'] ) ){
                $button = new Angelleye_Paypal_Wp_Button_Manager_Button( sanitize_text_field( $_GET['post'] ) );
                if( !empty( $button->get_hide_funding_method() ) ){
                    $hide_method = '&disable-funding=' . implode(',', $button->get_hide_funding_method() );
                } else {
                    $hide_method = '';
                }
            } else {
                $hide_method = '';
            }

            wp_register_script( $this->plugin_name . '-select2', ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL . 'admin/js/select2.min.js', array('jquery'), $this->version, false );
            wp_localize_script( $this->plugin_name . '-select2', 'wbp_select2', array( 'placeholder' => __('Please Select','angelleye-paypal-wp-button-manager') ) );
            wp_enqueue_script( $this->plugin_name . '-select2' );

            wp_enqueue_script( $this->plugin_name . '-paypal-sdk', 'https://www.paypal.com/sdk/js?&client-id=' . ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_SANDBOX_PARTNER_CLIENT_ID . '&enable-funding=venmo,paylater' . $hide_method, array(), null );

            wp_register_script( $this->plugin_name . '-button', ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL . 'admin/js/angelleye-paypal-wp-button-manager-paypal-button.js', array( $this->plugin_name . '-paypal-sdk', 'jquery'), '1.0.0' );
            wp_localize_script( $this->plugin_name . '-button', 'angelleye_paypal_wp_button_manager_admin_paypal_button', array( 
	            	'copy_text' => __('Copy Shortcode','angelleye-paypal-wp-button-manager'), 
	            	'copied_text' => __('Shortcode successfully copied!','angelleye-paypal-wp-button-manager' ), 
	            	'delete_caution' => __('Caution', 'angelleye-paypal-wp-button-manager'),
	            	'delete_caution_2' => __('This PayPal button is being used in your site content.','angelleye-paypal-wp-button-manager'),
	            	'delete_caution_3' => __('Deleting this button will remove it from your site, and any content or shortcodes referencing this button will no longer render.', 'angelleye-paypal-wp-button-manager'),
	            	'delete_caution_4' => __('Please ensure you have removed references to this button from your content before deleting it.', 'angelleye-paypal-wp-button-manager'),
	            	'delete_caution_5' => __('Do you wish to proceed with deleting this button?', 'angelleye-paypal-wp-button-manager'),
	            	'delete_caution_wait_message' => __('Please Wait', 'angelleye-paypal-wp-button-manager'),
	            	'ajaxurl' => admin_url( 'admin-ajax.php' ),
	            	'preview_tax_label' => __('Tax (%s%)', 'angelleye-paypal-wp-button-manager')
	        	) 
       		);
            wp_enqueue_script( $this->plugin_name . '-button' );

            wp_enqueue_script( $this->plugin_name . '-jquery-ui', ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL . 'admin/js/jquery-ui.min.js', array(), $this->version, 'all');
        	wp_enqueue_script("wp-color-picker");

        }
	}

}
