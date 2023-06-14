<?php
defined( 'ABSPATH' ) || exit;
/*
 * Class responsible for create paypal button post.
 */
class PayPal_WP_Button_Manager_Post{

    public static $post_type = 'paypal_button';
    public static $shortcode = 'angelleye_paypal_button';

    public function __construct() {
        add_action( 'init', array($this, 'register_post_type') );
        add_action( 'add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action( 'save_post', array($this, 'save_settings'), 10, 1);
        add_action('admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Enqueues the required styling and scripts
     * */
    public function enqueue_scripts(){
        if( ( isset( $_GET['post_type'] ) && $_GET['post_type'] == self::$post_type ) || ( isset( $_GET['post'] ) && get_post_type( sanitize_text_field( $_GET['post'] ) ) == self::$post_type ) ){
            if( isset( $_GET['post'] ) ){
                $button = new PayPal_WP_Button_Manager_Button( sanitize_text_field( $_GET['post'] ) );
                if( !empty( $button->get_hide_funding_method() ) ){
                    $hide_method = '&disable-funding=' . implode(',', $button->get_hide_funding_method() );
                } else {
                    $hide_method = '';
                }
            } else {
                $hide_method = '';
            }


            wp_enqueue_style( 'wbp-button', WBP_PLUGIN_URL . 'assets/backend/css/wbp-button.css', array(), '1.0.0' );
            wp_enqueue_style( 'wbp-select2', WBP_PLUGIN_URL . 'assets/backend/css/select2.min.css', array(), '1.0.0' );
            wp_enqueue_style('jquery-ui-core');

            wp_enqueue_script('jquery-ui-core');
            wp_register_script( 'wbp-select2', WBP_PLUGIN_URL . 'assets/backend/js/select2.min.js', array('jquery'), '1.0.0');
            wp_localize_script( 'wbp-select2', 'wbp_select2', array( 'placeholder' => __('Please Select','paypal-wp-button-manager') ) );
            wp_enqueue_script( 'wbp-select2' );
            wp_enqueue_script( 'wbp-paypal-sdk', 'https://www.paypal.com/sdk/js?&client-id=' . WBP_SANDBOX_PARTNER_CLIENT_ID . '&enable-funding=venmo,paylater' . $hide_method, array(), null );
            wp_enqueue_script( 'wbp-paypal-button', WBP_PLUGIN_URL . 'assets/backend/js/wbp-paypal-button.js', array('wbp-paypal-sdk', 'jquery'), '1.0.0' );
        }
    }

    /**
     * Registers the post type
     * */
    public function register_post_type() {
        $labels = array(
            'name' => _x('PayPal Buttons', 'Post type general name', 'paypal-wp-button-manager'),
            'singular_name' => _x('PayPal Button','Post type singular name', 'paypal-wp-button-manager'),
            'menu_name' => _x('PayPal Buttons', 'Admin Menu text', 'paypal-wp-button-manager'),
            'name_admin_bar' => _x('PayPal Button','Add New on Toolbar', 'paypal-wp-button-manager'),
            'add_new' => __('Add New Button', 'paypal-wp-button-manager'),
            'add_new_item' => __('Add New PayPal Button', 'paypal-wp-button-manager'),
            'edit_item' => __('Edit PayPal Button', 'paypal-wp-button-manager'),
            'new_item' => __('New PayPal Button', 'paypal-wp-button-manager'),
            'view_item' => __('View PayPal Button', 'paypal-wp-button-manager'),
            'search_items' => __('Search PayPal Buttons', 'paypal-wp-button-manager'),
            'not_found' => __( 'No paypal buttons found', 'paypal-wp-button-manager'),
            'not_found_in_trash' => __('No paypal buttons found in trash', 'paypal-wp-button-manager'),
        );
    
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => true,
            'has_archive' => true,
            'menu_position' => 5,
            'supports' => array( 'title' ),
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => self::$post_type ),
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_icon' => WBP_IMAGE_PATH . 'paypal-wp-button-manager-icon.png',
        );
        register_post_type( self::$post_type , $args );
    }

    /**
     * Adds the meta boxes
     * */
    public function add_meta_boxes() {

        add_meta_box(
            'paypal-button-meta-box',
            __('PayPal Button Setting','paypal-wp-button-manager'),
            array($this, 'add_settings'),
            self::$post_type,
            'normal',
            'default'
        );

        if( isset( $_GET['post'] ) && get_post_type( sanitize_text_field( $_GET['post'] ) ) == self::$post_type ){
            add_meta_box(
                'shortcode-meta-box',
                __('Button Shortcode', 'paypal-wp-button-manager'),
                array( $this, 'print_shortcode'),
                self::$post_type,
                'side',
                'default'
            );
        }
    
    }

    /**
     * Adds the settings for button
     * 
     * @param WP_Post post post object
     * */
    public function add_settings( $post ) {
        global $wpdb;
        
        $button = new PayPal_WP_Button_Manager_Button($post->ID);

        $companies =$wpdb->get_results( "SELECT ID, company_name FROM {$wpdb->prefix}angelleye_paypal_button_manager_companies WHERE paypal_merchant_id IS NOT NULL" );

        $currencies = angelleye_paypal_wp_button_manager_currencies();

        // Form and java script. 
        include_once( WBP_PLUGIN_PATH .'/templates/admin/wbp-paypal-button-settings.php');
    }

    /**
     * Saves the settings of the button
     * 
     * @param int post_id id of the post
     * */
    function save_settings( $post_id ) {
        global $wpdb;
        // Check if our nonce is set.
        if ( ! isset( $_POST['paypal_button_settings_nonce'] ) ) {
            return;
        }
        
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['paypal_button_settings_nonce'], 'paypal_button_settings' ) ) {
            return;
        }
        
        // Check if the user has permission to edit the post.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Sanitize user input.
        $meta_values = array(
            'wbp_company_id' =>  sanitize_text_field( $_POST['company_id'] ),
            'wbp_button_type' =>  sanitize_text_field( $_POST['button_type']),
            'wbp_product_name' =>  sanitize_text_field( $_POST['product_name'] ),
            'wbp_product_id' =>  sanitize_text_field( $_POST['product_id'] ) ,
            'wbp_item_price' =>  isset( $_POST['item_price'] ) ? sanitize_text_field( $_POST['item_price'] ) : null ,
            'wbp_item_price_currency' =>  isset( $_POST['item_price_currency'] ) ? sanitize_text_field( $_POST['item_price_currency'] ) : null,
            'wbp_button_layout' =>  sanitize_text_field( $_POST['wbp-button-layout'] ) ,
            'wbp_button_color' =>  sanitize_text_field( $_POST['wbp-button-color'] ) ,
            'wbp_button_shape' =>  sanitize_text_field( $_POST['wbp-button-shape'] ),
            'wbp_button_size' =>  sanitize_text_field( $_POST['wbp-button-size'] ),
            'wbp_button_height' =>  sanitize_text_field( $_POST['wbp-button-height'] ),
            'wbp_button_label' => sanitize_text_field( $_POST['wbp-button-label'] ),
            'wbp_button_tagline' => sanitize_text_field( $_POST['wbp-button-tagline'] ),
            'wbp_item_shipping_amount' =>  sanitize_text_field( $_POST['item_shipping_amount'] ),
            'wbp_item_tax_rate' =>  sanitize_text_field( $_POST['item_tax_rate'] ),
            'wbp_hide_funding_method' => $_POST['wbp-button-hide-funding']
        );
               
        foreach ( $meta_values as $key => $value ) {
            update_post_meta( $post_id, $key, $value );
        }
    }

    /**
     * Prints the shortcode within admin
     * 
     * @param WP_Post post post object
     * */
    public function print_shortcode( $post ){
        include_once(WBP_PLUGIN_PATH . '/templates/admin/wbp-shortcode-generator.php');
    }
}