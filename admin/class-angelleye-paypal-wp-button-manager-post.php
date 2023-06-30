<?php
defined( 'ABSPATH' ) || exit;
/*
 * Class responsible for create paypal button post.
 */
class Angelleye_Paypal_Wp_Button_Manager_Post{

    public static $post_type = 'paypal_button';
    public static $shortcode = 'angelleye_paypal_button';

    public function __construct() {
        add_action( 'init', array($this, 'register_post_type') );
        add_action( 'add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action( 'save_post', array($this, 'save_settings'), 10, 1);
        add_action( 'admin_notices', array( $this, 'check_company_exists') );
        add_action('init', array( $this, 'register_iframe_route') );
        add_action( 'template_redirect', array( $this, 'paypal_button_iframe' ) );
        add_filter('manage_' . self::$post_type . '_posts_columns', array( $this, 'button_post_type_columns'), 10, 1);
        add_filter('manage_edit-' . self::$post_type . '_sortable_columns', array( $this, 'button_post_type_sort_columns'), 10, 1);
        add_action( 'manage_' . self::$post_type . '_posts_custom_column' , array($this,'fill_button_post_type_columns'), 10, 2 );
        add_action( 'pre_get_posts', array( $this, 'handle_custom_column_sorting' ) );
        add_filter('posts_search', array( $this, 'custom_column_search'), 10, 2);
        add_action('wp_ajax_angelleye_paypal_wp_button_manager_admin_paypal_button_check_shortcode_used', array( $this, 'check_shortcode_used') );
        add_filter( 'post_updated_messages', array( $this, 'updated_messages_for_buttons' ) );
    }

    /**
     * Registers the post type
     * */
    public function register_post_type() {
        $labels = array(
            'name' => _x('PayPal Buttons', 'Post type general name', 'angelleye-paypal-wp-button-manager'),
            'singular_name' => _x('PayPal Button','Post type singular name', 'angelleye-paypal-wp-button-manager'),
            'menu_name' => _x('PayPal Buttons', 'Admin Menu text', 'angelleye-paypal-wp-button-manager'),
            'name_admin_bar' => _x('PayPal Button','Add New on Toolbar', 'angelleye-paypal-wp-button-manager'),
            'add_new' => __('Add New Button', 'angelleye-paypal-wp-button-manager'),
            'add_new_item' => __('Add New PayPal Button', 'angelleye-paypal-wp-button-manager'),
            'edit_item' => __('Edit PayPal Button', 'angelleye-paypal-wp-button-manager'),
            'new_item' => __('New PayPal Button', 'angelleye-paypal-wp-button-manager'),
            'view_item' => __('View PayPal Button', 'angelleye-paypal-wp-button-manager'),
            'search_items' => __('Search PayPal Buttons', 'angelleye-paypal-wp-button-manager'),
            'not_found' => __( 'No paypal buttons found', 'angelleye-paypal-wp-button-manager'),
            'not_found_in_trash' => __('No paypal buttons found in trash', 'angelleye-paypal-wp-button-manager'),
            'item_updated' => __('Button updated successfully', 'angelleye-paypal-wp-button-manager'),
            'item_published' => __('Button published successfully', 'angelleye-paypal-wp-button-manager')
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
            'menu_icon' => ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_IMAGE_PATH . 'angelleye-paypal-wp-button-manager-icon.png',
        );
        register_post_type( self::$post_type , $args );
    }

    /**
     * Adds the meta boxes
     * */
    public function add_meta_boxes() {

        add_meta_box(
            'paypal-button-meta-box',
            __('PayPal Button Setting','angelleye-paypal-wp-button-manager'),
            array($this, 'add_settings'),
            self::$post_type,
            'normal',
            'default'
        );

        if( isset( $_GET['post'] ) && get_post_type( sanitize_text_field( $_GET['post'] ) ) == self::$post_type ){
            add_meta_box(
                'shortcode-meta-box',
                __('Button Shortcode', 'angelleye-paypal-wp-button-manager'),
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
        
        $button = new Angelleye_Paypal_Wp_Button_Manager_Button($post->ID);

        $companies =$wpdb->get_results( "SELECT ID, company_name FROM {$wpdb->prefix}angelleye_paypal_button_manager_companies WHERE paypal_merchant_id IS NOT NULL" );

        $currencies = angelleye_paypal_wp_button_manager_currencies();

        include_once( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH .'/admin/partials/angelleye-paypal-wp-button-manager-admin-button-settings.php');
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
            'wbp_hide_funding_method' => $_POST['wbp-button-hide-funding'],
            'wbp_hide_data_fields' => $_POST['hide_data_fields'],
            'wbp_data_fields_left_background_color' => sanitize_text_field( $_POST['left_background_color'] ),
            'wbp_data_fields_right_background_color' => sanitize_text_field( $_POST['right_background_color'] ),
            'wbp_data_fields_left_foreground_color' => sanitize_text_field( $_POST['left_foreground_color'] ),
            'wbp_data_fields_right_foreground_color' => sanitize_text_field( $_POST['right_foreground_color'] ),
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
        include_once(ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH . '/admin/partials/angelleye-paypal-wp-button-manager-admin-shortcode-generator.php');
    }

    /**
     * Checks if the paypal company exists, if not then loads the assistance page to setup one.
     * */
    public function check_company_exists(){
        global $post_type, $pagenow;

        if( $post_type === self::$post_type && $pagenow === 'edit.php' ){
            if( Angelleye_Paypal_Wp_Button_Manager_Companies::record_count() == 0 ){
                include_once(ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH . '/admin/partials/angelleye-paypal-wp-button-manager-admin-button-default.php');
            }
        }
    }

    /**
     * Registers the iframe route
     * */
    public function register_iframe_route(){
        add_rewrite_endpoint( 'angelleye-paypal-button-manager-iframe-preview', EP_ROOT );
    }

    /**
     * Adds the template for iframe route
     * */
    public function paypal_button_iframe(){
        global $wp;
        if ( isset( $wp->query_vars['angelleye-paypal-button-manager-iframe-preview'] ) ) {
            if( get_current_user_id() && current_user_can( 'manage_options') ){

                include_once(ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH . '/admin/partials/angelleye-paypal-wp-button-manager-paypal-button-preview.php');
            }
            exit;
        }
    }

    /**
     * Adds the columns to button posts
     * 
     * @param array     columns     Array of columns
     * 
     * @return array
     * */
    public function button_post_type_columns( $columns ){
        $date = $columns['date'];
        unset( $columns['date'] );

        $columns['item_name'] = __('Item Name','angelleye-paypal-wp-button-manager');
        $columns['item_id'] = __('Item ID','angelleye-paypal-wp-button-manager');
        $columns['price'] = __('Price', 'angelleye-paypal-wp-button-manager');
        $columns['currency'] = __('Currency', 'angelleye-paypal-wp-button-manager');
        $columns['shipping'] = __('Shipping','angelleye-paypal-wp-button-manager');
        $columns['tax'] = __('Tax','angelleye-paypal-wp-button-manager');
        $columns['date'] = $date;
        return $columns;
    }

    /**
     * Adds the sortable to columns of button posts
     * 
     * @param array     columns     Array of columns
     * 
     * @return array
     * */
    public function button_post_type_sort_columns( $columns ){
        $columns['item_name'] = 'item_name';
        $columns['item_id'] = 'item_id';
        $columns['price'] = 'price';
        $columns['currency'] = 'currency';
        $columns['shipping'] = 'shipping';
        $columns['tax'] = 'tax';
        return $columns;
    }

    /**
     * Fills the custom post type columns
     * 
     * @param string    column      column id
     * @param int       post_id     ID of the post
     * 
     * @return void
     * */
    public function fill_button_post_type_columns( $column, $post_id ){
        $button = new Angelleye_Paypal_Wp_Button_Manager_Button( $post_id );
        switch( $column ){
            case 'item_name':
                echo $button->get_item_name();
                break;

            case 'item_id':
                echo $button->get_item_id();
                break;

            case 'price':
                echo $button->get_price();
                break;

            case 'currency':
                echo $button->get_currency();
                break;

            case 'shipping':
                echo $button->get_shipping_amount();
                break;

            case 'tax':
                echo $button->get_tax_rate();
                break;
        }
    }

    /**
     * Handles the custom column sorting
     * 
     * @param WP_Query      query       Query object
     * 
     * @return void
     * */
    public function handle_custom_column_sorting( $query ) {
        if ( ! is_admin() ) {
            return;
        }

        $orderby = $query->get( 'orderby' );

        if ( 'item_name' === $orderby ) {
            $query->set( 'meta_key', 'wbp_product_name' );
            $query->set( 'orderby', 'meta_value' );
        } else if ( 'item_id' === $orderby ) {
            $query->set( 'meta_key', 'wbp_product_id' );
            $query->set( 'orderby', 'meta_value' );
        } else if ( 'price' === $orderby ) {
            $query->set( 'meta_key', 'wbp_item_price' );
            $query->set( 'orderby', 'meta_value' );
        } else if ( 'currency' === $orderby ) {
            $query->set( 'meta_key', 'wbp_item_price_currency' );
            $query->set( 'orderby', 'meta_value' );
        } else if ( 'shipping' === $orderby ) {
            $query->set( 'meta_key', 'wbp_item_shipping_amount' );
            $query->set( 'orderby', 'meta_value' );
        } else if ( 'tax' === $orderby ) {
            $query->set( 'meta_key', 'wbp_item_tax_rate' );
            $query->set( 'orderby', 'meta_value' );
        }
    }

    /**
     * Allows to search in custom column
     * 
     * @param string    search      search query
     * @param WP_Query  wp_query    WP_Query object
     * 
     * @return string
     * */
    public function custom_column_search( $search, $wp_query ){
        global $wpdb;

        if (isset($wp_query->query['s']) && is_admin() && $wp_query->is_main_query() && $wp_query->get('post_type') === self::$post_type ) {
            $search_term = $wp_query->query['s'];
            $search .= " OR ($wpdb->postmeta.meta_key = 'wbp_product_name' AND $wpdb->postmeta.meta_value LIKE '%$search_term%') OR ($wpdb->postmeta.meta_key = 'wbp_product_id' AND $wpdb->postmeta.meta_value LIKE '%$search_term%') OR ($wpdb->postmeta.meta_key = 'wbp_item_price' AND $wpdb->postmeta.meta_value LIKE '%$search_term%') OR ($wpdb->postmeta.meta_key = 'wbp_item_price_currency' AND $wpdb->postmeta.meta_value LIKE '%$search_term%') OR ($wpdb->postmeta.meta_key = 'wbp_item_shipping_amount' AND $wpdb->postmeta.meta_value LIKE '%$search_term%') OR ($wpdb->postmeta.meta_key = 'wbp_item_tax_rate' AND $wpdb->postmeta.meta_value LIKE '%$search_term%')";
        }

        return $search;
    }

    public function check_shortcode_used(){
        if( get_current_user_id() && current_user_can( 'manage_options' ) ){
            global $wpdb;
            $post_id = sanitize_text_field( $_POST['post_id'] );

            $shortcode = '[' . self::$shortcode . ' id="' . $post_id . '"]';

            $posts = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_content LIKE '%%%s%%' AND post_type IN ('post','page')", $shortcode ) );

            $_posts = array();
            foreach ($posts as $post) {
                $_posts[] = array(
                    'title' => $post->post_title,
                    'url' => get_edit_post_link( $post->ID )
                );
            }
            wp_send_json( array('success' => true, 'posts' => $_posts ) );
        }
        die();
    }

    public function updated_messages_for_buttons($messages) {
        $messages['paypal_button'] = array(
            0  => '',
            1  => __( 'Button updated successfully.', 'angelleye-paypal-wp-button-manager' ),
            6  => __( 'Button created successfully.', 'angelleye-paypal-wp-button-manager' ),
            7  => __( 'Button saved successfully.', 'angelleye-paypal-wp-button-manager' ),
            8  => __( 'Button submitted successfully.', 'angelleye-paypal-wp-button-manager' ),
            10 => __( 'Button draft updated.' )
        );

        return $messages;
    }
}