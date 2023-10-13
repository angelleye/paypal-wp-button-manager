<?php
defined( 'ABSPATH' ) || exit;
/*
 * Class responsible for paypal subscription management.
 */
class Angelleye_Paypal_Wp_Button_Manager_Subscription_Management {

	private $plugin_name;
	private $version;
	private $paypal_subscriptions;
	public static $paypal_button_subscription_slug = 'paypal_subscription';

	public function __construct( $plugin_name, $version ){
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'admin_menu', array( $this, 'admin_menu') );
		add_filter( 'set-screen-option', array($this, 'save_listing_page_option' ), 10, 3 );
	}

	/**
     * Adds the menu page
     * */
	public function admin_menu(){
		$subscriptions_page = add_submenu_page( 'edit.php?post_type=paypal_button', __('Subscriptions','angelleye-paypal-wp-button-manager'), __('Subscriptions','angelleye-paypal-wp-button-manager'), 'manage_options', self::$paypal_button_subscription_slug, array( $this, 'paypal_button_manager_admin_subscriptions') );
        add_action("load-$subscriptions_page", array( $this, 'subscriptions_screen_options') );
	}

	/**
	 * Shows the subscriptions list page
	 * 
	 * @return void
	 * */
	public function paypal_button_manager_admin_subscriptions(){

		include_once( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH .'/admin/partials/angelleye-paypal-wp-button-manager-admin-subscription-list.php');
	}

	/**
     * Creates the screen options for the subscriptions listings
     * 
     * @return void
     * */
	public function subscriptions_screen_options(){
		$option = 'per_page';
        $args   = [
            'label'   => __('Subscriptions','angelleye-paypal-wp-button-manager'),
            'default' => 20,
            'option'  => 'paypal_subscriptions_per_page'
        ];

        add_screen_option( $option, $args );

        $this->paypal_subscriptions = new Angelleye_Paypal_Wp_Button_Manager_Subscription_Management_List_Table();
	}

	/**
     * Allows the users to set the pagination records per page
     *
     * @param string $status current status of the option
     * @param string $option option
     * @param mixed $value value of the option
     * 
     * @return mixed
     * */
    public function save_listing_page_option( $status, $option, $value ) {
        if( $option == 'paypal_subscriptions_per_page' ){
            return $value;
        }
        return $status;
    }
}