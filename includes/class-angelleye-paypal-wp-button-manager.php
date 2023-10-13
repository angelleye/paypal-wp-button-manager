<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://angelleye.com
 * @since      1.0.0
 *
 * @package    Angelleye_Paypal_Wp_Button_Manager
 * @subpackage Angelleye_Paypal_Wp_Button_Manager/includes
 */
class Angelleye_Paypal_Wp_Button_Manager {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Angelleye_Paypal_Wp_Button_Manager_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_VERSION' ) ) {
			$this->version = ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'angelleye-paypal-wp-button-manager';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Angelleye_Paypal_Wp_Button_Manager_Loader. Orchestrates the hooks of the plugin.
	 * - Angelleye_Paypal_Wp_Button_Manager_i18n. Defines internationalization functionality.
	 * - Angelleye_Paypal_Wp_Button_Manager_Admin. Defines all hooks for the admin area.
	 * - Angelleye_Paypal_Wp_Button_Manager_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The file responsible for general functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-angelleye-paypal-wp-button-manager-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-angelleye-paypal-wp-button-manager-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-angelleye-paypal-wp-button-manager-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-angelleye-paypal-wp-button-manager-public.php';

		/**
		 * The class responsible for all loging.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-angelleye-paypal-wp-button-manager-logger.php';

		/**
		 * The class responsible for defining all actions of company signup flow
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-angelleye-paypal-wp-button-manager-company.php';
		new Angelleye_Paypal_Wp_Button_Manager_Company();

		/**
		 * The class responsible for defining all actions of company list
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-angelleye-paypal-wp-button-manager-companies.php';


		/**
		 * The class responsible for button manager posts.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-angelleye-paypal-wp-button-manager-post.php';
		new Angelleye_Paypal_Wp_Button_Manager_Post();

		/**
		 * The class responsible for button manager button.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-angelleye-paypal-wp-button-manager-button.php';

		/**
		 * The class responsible for button manager orders.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-angelleye-paypal-wp-button-manager-order.php';
		new Angelleye_Paypal_Wp_Button_Manager_Order();

		/**
		 * The class responsible for button manager paypal apis.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-angelleye-paypal-wp-button-manager-paypal-api.php';

		/**
		 * The class responsible for button manager shortcode.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-angelleye-paypal-wp-button-manager-shortcode.php';
		new Angelleye_Paypal_Wp_Button_Manager_Shortcode( $this->plugin_name, $this->version );

		/**
		 * The class responsible for button manager block.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-angelleye-paypal-wp-button-manager-block.php';
		new Angelleye_Paypal_Wp_Button_Manager_Block( $this->plugin_name, $this->version );

		/**
		 * The class responsible for button manager subscription admin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-angelleye-paypal-wp-button-manager-subscription-management-list-table.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-angelleye-paypal-wp-button-manager-subscription-management.php';
		new Angelleye_Paypal_Wp_Button_Manager_Subscription_Management( $this->plugin_name, $this->version );

		/**
		 * The class responsible for subscription management.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-angelleye-paypal-wp-button-manager-subscription.php';

		/**
		 * The class responsible for subscription management.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-angelleye-paypal-wp-button-manager-subscription-renewal.php';
		new Angelleye_Paypal_Wp_Button_Manager_Subscription_Renewal();

		$this->loader = new Angelleye_Paypal_Wp_Button_Manager_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Angelleye_Paypal_Wp_Button_Manager_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Angelleye_Paypal_Wp_Button_Manager_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Angelleye_Paypal_Wp_Button_Manager_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Angelleye_Paypal_Wp_Button_Manager_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Angelleye_Paypal_Wp_Button_Manager_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
