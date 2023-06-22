<?php
defined( 'ABSPATH' ) || exit;
/*
 * Class responsible for paypal company listing, add and viewing the same.
 */
class Angelleye_Paypal_Wp_Button_Manager_Company {

    private $signup_url;
    private $ppcp_url;
    private $sandbox_merchant_id;
    private $live_merchant_id;
    public $paypal_companies;
    public static $paypal_button_company_slug = 'paypal-buttons';
    private $logger;

    public function __construct(){
        $this->signup_url = ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_API_LINK . 'generate-signup-link';
        $this->ppcp_url = ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_API_LINK . 'ppcp-request';
        $this->sandbox_merchant_id = ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_SANDBOX_PARTNER_MERCHANT_ID;
        $this->live_merchant_id = ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_LIVE_PARTNER_MERCHANT_ID;
        $this->logger = new Angelleye_PayPal_WP_Button_Manager_Logger( 'ppcp-seller-onboard' );
        add_action( 'admin_menu', array( $this, 'admin_menu') );
        add_action('admin_post_angelleye_paypal_wp_button_manager_admin_add_company', array( $this, 'create_company') );
        add_action('admin_post_angelleye_paypal_wp_button_manager_admin_added_company', array( $this, 'update_company') );
        add_filter( 'set-screen-option', array($this, 'save_listing_page_option' ), 10, 3 );
    }

    /**
     * Adds the menu page
     * */
    public function admin_menu(){
        $companies_page = add_submenu_page( 'edit.php?post_type=paypal_button', __('PayPal Accounts','angelleye-paypal-wp-button-manager'), __('PayPal Accounts','angelleye-paypal-wp-button-manager'), 'manage_options', self::$paypal_button_company_slug, array( $this, 'paypal_button_manager_admin') );
        add_action("load-$companies_page", array( $this, 'companies_screen_options') );
    }

    /**
     * Includes the responsible template for menu page
     * */
    public function paypal_button_manager_admin(){
        global $wpdb;

        if( isset( $_GET['company_id'] ) ){
            $company_id = esc_sql( $_GET['company_id'] );

            $company = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}angelleye_paypal_button_manager_companies WHERE ID = %d", $company_id ) );

            if( isset( $company->paypal_merchant_id ) && !empty( $company->paypal_merchant_id ) ){

                $products = $this->get_onboarding_status( $company->paypal_merchant_id, $company->paypal_mode );

                if( !is_wp_error( $products ) ){
                    $wpdb->update( $wpdb->prefix . 'angelleye_paypal_button_manager_companies', array('products' => serialize( $products ) ), array( 'ID' => $company_id ) );
                } else {
                    $products = $products->get_error_message();
                }

                include_once( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH .'/admin/partials/angelleye-paypal-wp-button-manager-admin-company-information.php');
            } else {
                $redirect_url = $this->get_signup_url( $company_id, $company->tracking_id, $company->country, $company->paypal_mode );
                
                include_once( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH .'/admin/partials/angelleye-paypal-wp-button-manager-admin-company.php');
            }
        } else {
            if( isset( $_GET['type'] ) && $_GET['type'] == 'new' ){
                include_once( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH .'/admin/partials/angelleye-paypal-wp-button-manager-admin-company.php');
            } else {
                $companies = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}angelleye_paypal_button_manager_companies" );
                if( $companies > 0 ){
                    include_once( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH .'/admin/partials/angelleye-paypal-wp-button-manager-admin-company-list.php');
                } else {
                    include_once( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH .'/admin/partials/angelleye-paypal-wp-button-manager-admin-add-new-button.php');
                }
            }
        }
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
        if( $option == 'paypal_companies_per_page' ){
            return $value;
        }
        return $status;
    }

    /**
     * Creates the screen options for the componies listings
     * */
    public function companies_screen_options(){
        $option = 'per_page';
        $args   = [
            'label'   => __('PayPal Companies','angelleye-paypal-wp-button-manager'),
            'default' => 20,
            'option'  => 'paypal_companies_per_page'
        ];

        add_screen_option( $option, $args );

        $this->paypal_companies = new Angelleye_Paypal_Wp_Button_Manager_Companies();
    }

    /**
     * Allows to create company
     * */
    public function create_company(){
        if( isset( $_POST['save_paypal_ac_type'] ) ){
            global $wpdb;

            $company_name = sanitize_text_field( $_POST['company_name'] );
            $contact_name = sanitize_text_field( $_POST['contact_name'] );
            $country = sanitize_text_field( $_POST['country'] );

            if( isset( $_POST['paypal_sandbox'] ) ){
                $paypal_mode = 'sandbox';
            } else {
                $paypal_mode = 'live';
            }

            $wpdb->insert( $wpdb->prefix . 'angelleye_paypal_button_manager_companies', array( 'company_name' => $company_name, 'paypal_person_name' => $contact_name, 'country' => $country, 'paypal_mode' => $paypal_mode, 'tracking_id' => strtoupper( bin2hex( random_bytes(10) ) ) ) );

            $company_id = $wpdb->insert_id;
            wp_redirect( admin_url('admin.php?page=' . self::$paypal_button_company_slug . '&type=new&company_id=' . $company_id ) );
        }
    }

    /**
     * Saves the company information
     * */
    public function update_company(){
        if( isset( $_GET['merchantIdInPayPal'] ) && isset( $_GET['company_id'] ) ){
            global $wpdb;
            $company_id = esc_sql( $_GET['company_id'] );
            $merchant_id = esc_sql( $_GET['merchantIdInPayPal'] );

            $wpdb->update( $wpdb->prefix . 'angelleye_paypal_button_manager_companies', array( 'paypal_merchant_id' => $merchant_id ), array( 'ID' => $company_id ) );
            wp_redirect( admin_url('admin.php?page=' . self::$paypal_button_company_slug . '&company_id=' . $company_id ) );
        }
    }

    /**
     * Returns the paypal signup url
     * 
     * @param int company_id id of the company
     * @param string tracking_id tracking id
     * @param string mode mode of paypal
     * 
     * @return mixed
     * */
    private function get_signup_url( $company_id, $tracking_id, $country=null, $mode='live' ){
        $testmode = 'sandbox' === $mode ? 'yes' : 'no';
        $products = array(
            'PPCP'
        );
        $capabilities = array();
        if( $country == 'US' ){
            $products[] = 'ADVANCED_VAULTING';
            $capabilities[] = 'PAYPAL_WALLET_VAULTING_ADVANCED';
        }
        $request_body = array(
            'testmode' => $testmode,
            'return_url' => admin_url('admin-post.php?action=angelleye_paypal_wp_button_manager_admin_added_company&company_id=' . $company_id),
            'return_url_description' => __('Return to your shop', 'angelleye-paypal-wp-button-manager'),
            'products' => $products,
            'tracking_id' => $tracking_id
        );

        if( !empty( $capabilities ) ){
            $request_body['capabilities'] = $capabilities;
        }

        $request = array(
            'body' => json_encode( $request_body ),
            'headers' => array(
                'Content-Type' => 'application/json',
                'User-Agent' => 'PFW_PPCP',
                'Timeout' => 60,
                'Content-Length' => strlen( json_encode( $request_body ) )
            )
        );

        $this->logger->info('Signup URL generate started', array( 'signup_url' => $this->signup_url, 'request' => $request ) );
        
        $response = wp_remote_post( $this->signup_url, $request );
        if( is_wp_error( $response ) ){
            $this->logger->error( 'Signup URL generation error', $response->get_error_messages() );
            return new WP_Error( 'signup_url_error', $response->get_error_message() );
        }

        if( $response['response']['code'] != 200 ){
            $this->logger->error('Signup URL generation error', $response );
            return new WP_Error( 'signup_url_error', $response );
        }

        $response = json_decode( $response['body'] );
        foreach( $response->body->links as $link ){
            if( $link->rel == 'action_url' ){
                $this->logger->info('Signup URL generated successfully. URL: ' . $link->href );
                return $link->href;
            }
        }

        $this->logger->error('Unknown Error');
        return new WP_Error('signup_url_error', __('Unknown Error', 'angelleye-paypal-wp-button-manager') );
    }

    /**
     * Returns the merchant onboarding status
     * 
     * @param int merchant_id id of the merchant (company)
     * @param string mode mode of paypal
     * 
     * @return mixed
     * */
    private function get_onboarding_status( $merchant_id, $mode='live' ){
        if( 'sandbox' === $mode ){
            $testmode = 'yes';
            $paypal_url = 'https://api-m.sandbox.paypal.com/v1/customer/partners/' . $this->sandbox_merchant_id . '/merchant-integrations/' . $merchant_id;
        } else {
            $testmode = 'no';
            $paypal_url = 'https://api-m.paypal.com/v1/customer/partners/' . $this->live_merchant_id . '/merchant-integrations/' . $merchant_id;
        }

        $request_body = array(
            'testmode' => $testmode,
            'paypal_url' => $paypal_url,
            'paypal_header' => array(
                'Content-Type' => 'application/json'
            ),
            'paypal_method' => 'GET',
            'paypal_body' => null,
            'action_name' => 'seller_onboarding_status'
        );

        $request = array(
            'body' => json_encode( $request_body ),
            'headers' => array(
                'Content-Type' => 'application/json',
                'User-Agent' => 'PFW_PPCP',
                'Timeout' => 70,
                'Content-Length' => strlen( json_encode( $request_body ) )
            )
        );

        $this->logger->info('Onboarding status check started', array( 'ppcp_url' => $this->ppcp_url, 'request' => $request ) );
        $response = wp_remote_post( $this->ppcp_url, $request );
        
        if( is_wp_error( $response ) ){
            $this->logger->error( 'Onboarding status check error', $response->get_error_messages() );
            return $response;
        }

        if( $response['response']['code'] != 200 ){
            $this->logger->error( 'Onboarding status check error', $response );
            return new WP_Error('seller-onboarding-status-error', __('Internal Server Error', 'angelleye-paypal-wp-button-manager') );
        }

        $response = json_decode( $response['body'] );
        $products = array();
        foreach( $response->body->products as $product ){
            if( isset( $product->vetting_status ) ){
                $vetting_status = $product->vetting_status;
            } else if ( isset( $product->status ) ){
                $vetting_status = $product->status;
            } else {
                $vetting_status = '';
            }

            if( isset( $product->capabilities ) ){
                $capabilities = $product->capabilities;
            } else {
                $capabilities = array();
            }

            $products[] = array(
                'name' => $product->name,
                'vetting_status' => $vetting_status,
                'capabilities' => $capabilities
            );
        }
        $this->logger->info( 'Onboarding status check successful', $products );
        return $products;
    }
}