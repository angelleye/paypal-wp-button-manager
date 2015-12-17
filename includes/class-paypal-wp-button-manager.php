<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    paypal-wp-button-manager
 * @subpackage paypal-wp-button-manager/includes
 * @author     Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    0.1.0
     * @access   protected
     * @var      AngellEYE_PayPal_WP_Button_Manager_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    0.1.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    0.1.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the Dashboard and
     * the public-facing side of the site.
     *
     * @since    0.1.0
     */
    public function __construct() {

        $this->plugin_name = 'paypal-wp-button-manager';
        $this->version = '1.0.3';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

        /**
         * Add action links
         * http://stackoverflow.com/questions/22577727/problems-adding-action-links-to-wordpress-plugin
         */
        $prefix = is_network_admin() ? 'network_admin_' : '';
        add_filter("{$prefix}plugin_action_links_" . PAYPAL_WP_BUTTON_MANAGER_PLUGIN_BASENAME, array($this, 'plugin_action_links'), 10, 4);
    }

    /**
     * Return the plugin action links.  This will only be called if the plugin
     * is active.
     *
     * @since 1.0.0
     * @param array $actions associative array of action names to anchor tags
     * @return array associative array of plugin action links
     */
    public function plugin_action_links($actions, $plugin_file, $plugin_data, $context) {
        $custom_actions = array(
            'configure' => sprintf('<a href="%s">%s</a>', admin_url('options-general.php?page=paypal-wp-button-manager-option'), __('Configure', 'paypal-wp-button-manager')),
            'docs' => sprintf('<a href="%s" target="_blank">%s</a>', 'http://www.angelleye.com/category/docs/paypal-wp-button-manager/?utm_source=paypal_wp_button_manager&utm_medium=docs_link&utm_campaign=paypal_wp_button_manager', __('Docs', 'paypal-wp-button-manager')),
            'support' => sprintf('<a href="%s" target="_blank">%s</a>', 'http://wordpress.org/support/plugin/paypal-wp-button-manager/', __('Support', 'paypal-wp-button-manager')),
            'review' => sprintf('<a href="%s" target="_blank">%s</a>', 'http://wordpress.org/support/view/plugin-reviews/paypal-wp-button-manager', __('Write a Review', 'paypal-wp-button-manager')),
        );

        // add the links to the front of the actions list
        return array_merge($custom_actions, $actions);
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - AngellEYE_PayPal_WP_Button_Manager_Loader. Orchestrates the hooks of the plugin.
     * - AngellEYE_PayPal_WP_Button_Manager_i18n. Defines internationalization functionality.
     * - AngellEYE_PayPal_WP_Button_Manager_Admin. Defines all hooks for the dashboard.
     * - AngellEYE_PayPal_WP_Button_Manager_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    0.1.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-paypal-wp-button-manager-loader.php';

        /**
         * The class responsible for writing log in log file.
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-paypal-wp-button-manager-logger.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-paypal-wp-button-manager-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the Dashboard.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-paypal-wp-button-manager-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-paypal-wp-button-manager-public.php';

        /**
         * Custom functions returns in file
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/paypal-wp-button-manager-functions.php';
        /**
         * PayPal button generator interface code written
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/paypal-wp-button-manager-html-format.php';

        /**
         * PayPal button generator file included.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/BMCreateButton.php';

        /**
         * PayPal button generator custom functions define in this file.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/paypal-wp-button-manager-paypal-helper.php';
        /**
         * Autoload file included for paypal intigrate paypal library.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/autoload.php';

        /**
         * PayPal php class file included.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/Angelleye_PayPal.php';

        /**
         * Included for inherit wordpress table style.
         */
        require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

        $this->loader = new AngellEYE_PayPal_WP_Button_Manager_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the AngellEYE_PayPal_WP_Button_Manager_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    0.1.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new AngellEYE_PayPal_WP_Button_Manager_i18n();
        $plugin_i18n->set_domain($this->get_plugin_name());

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the dashboard functionality
     * of the plugin.
     *
     * @since    0.1.0
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new AngellEYE_PayPal_WP_Button_Manager_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_notices', $plugin_admin, 'paypal_wp_button_manager_notice_display');
        $this->loader->add_filter('post_updated_messages', $plugin_admin, 'paypal_wp_button_manager_success_notice_display');
        $this->loader->add_action('admin_init', $plugin_admin, 'paypal_wp_button_manager_shortcode_button_init');
        $this->loader->add_action('wp_trash_post', $plugin_admin, 'paypal_wp_button_manager_wp_trash_post');
        $this->loader->add_action('wp_ajax_checkconfig', $plugin_admin, 'paypal_wp_button_manager_checkconfig');
        $this->loader->add_action('wp_ajax_delete_paypal_button', $plugin_admin, 'paypal_wp_button_manager_before_delete_post');
        $this->loader->add_action('wp_ajax_checkhosted_button', $plugin_admin, 'paypal_wp_button_manager_checkhosted_button');
        $this->loader->add_action('wp_ajax_delete_post_own', $plugin_admin, 'paypal_wp_button_manager_delete_post_own');
        $this->loader->add_action('wp_ajax_del_all_hostedbutton', $plugin_admin, 'paypal_wp_button_manager_del_all_hostedbutton');
        $this->loader->add_action('wp_ajax_cancel_donate', $plugin_admin, 'paypal_wp_button_manager_cancel_donate');
        $this->loader->add_action('admin_menu', $plugin_admin, 'paypal_wp_button_manager_welcome_page');
        $this->loader->add_filter('admin_head', $plugin_admin, 'paypal_wp_button_manager_print_emptytrash');
        $is_cancel = get_option('paypal_wp_button_cancel');
       	if (isset($is_cancel) && empty($is_cancel)) {
            $this->loader->add_filter('add_meta_boxes', $plugin_admin, 'paypal_wp_button_manager_beer_metabox');
        }
        $this->loader->add_action('paypal_wp_button_manager_pbm_about', $plugin_admin, 'paypal_wp_button_manager_pbm_about');
        $this->loader->add_action('paypal_wp_button_manager_pbm_credits', $plugin_admin, 'paypal_wp_button_manager_pbm_credits');
        $this->loader->add_action('paypal_wp_button_manager_pbm_translators', $plugin_admin, 'paypal_wp_button_manager_pbm_translators');
        $this->loader->add_action('admin_head', $plugin_admin, 'paypal_wp_button_manager_remove_wcpage_link');
        $this->loader->add_action( 'admin_init', $plugin_admin, 'paypal_wp_button_manager_ignore_update_notice');
        $this->loader->add_action( 'upgrader_process_complete', 'paypal_wp_button_manager_upgrader_process_complete', 10, 2 );
		
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    0.1.0
     * @access   private
     */
    private function define_public_hooks() {
        $plugin_public = new AngellEYE_PayPal_WP_Button_Manager_Public($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    0.1.0
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
     * @return    AngellEYE_PayPal_WP_Button_Manager_Loader    Orchestrates the hooks of the plugin.
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