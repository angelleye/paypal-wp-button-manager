<?php
defined( 'ABSPATH' ) || exit;
/*
 * Class responsible for shortcodes
 */
class Angelleye_Paypal_Wp_Button_Manager_Button{

    private $id;

    protected $data = array();
    
    public function __construct( $id ){
        $this->id = $id;
    }

    /**
     * Returns if the button is valid or not
     * 
     * @return boolean
     * */
    public function is_valid_button(){
        $valid_post_type = ( Angelleye_Paypal_Wp_Button_Manager_Post::$post_type === get_post_type( $this->id ) );
        $valid_post_status = ( 'publish' === get_post_status( $this->id ) );
        return ( $valid_post_type && $valid_post_status );
    }

    /**
     * Returns the paypal company ID of button
     * 
     * @param string context context of function i.e. view or edit
     * 
     * @return mixed
     * */
    public function get_company_id( $context='view' ){
        return $this->get_prop( 'wbp_company_id', $context );
    }

    /**
     * Returns the button type
     *  
     * @param string context context of function i.e. view or edit
     * 
     * @return mixed
     * */
    public function get_button_type( $context='view' ){
        return $this->get_prop( 'wbp_button_type', $context );
    }

    /**
     * Returns the item name
     *
     * @param string context context of function i.e. view or edit
     * 
     * @return mixed
     * */
    public function get_item_name( $context='view' ){
        return $this->get_prop( 'wbp_product_name', $context );
    }

    /**
     * Returns the item id
     *
     * @param string context context of function i.e. view or edit
     * 
     * @return mixed
     * */
    public function get_item_id( $context='view' ){
        return $this->get_prop( 'wbp_product_id', $context );
    }

    /**
     * Returns the price if applicable
     * 
     * @param string context context of function i.e. view or edit
     *
     * @return mixed
     * */
    public function get_price( $context='view' ){
        return $this->get_prop( 'wbp_item_price', $context );
    }

    /**
     * Returns the currency if applicable
     *
     * @param string context context of function i.e. view or edit
     *
     * @return mixed
     * */
    public function get_currency( $context='view' ){
        return $this->get_prop( 'wbp_item_price_currency', $context );
    }

    /**
     * Returns the button layout
     *
     * @param string context context of function i.e. view or edit
     *
     * @return mixed
     * */
    public function get_button_layout( $context='view' ){
        return $this->get_prop( 'wbp_button_layout', $context );
    }

    /**
     * Returns button color
     *
     * @param string context context of function i.e. view or edit
     *
     * @return mixed
     * */
    public function get_button_color( $context='view' ){
        return $this->get_prop( 'wbp_button_color', $context );
    }

    /**
     * Returns button shape
     *
     * @param string context context of function i.e. view or edit
     *
     * @return mixed
     * */
    public function get_button_shape( $context='view' ){
        return $this->get_prop( 'wbp_button_shape', $context );
    }

    /**
     * Returns the button size
     *
     * @param string context context of function i.e. view or edit
     *
     * @return mixed
     * */
    public function get_button_size( $context='view' ){
        return $this->get_prop( 'wbp_button_size', $context );
    }

    /**
     * Returns button height
     *
     * @param string context context of function i.e. view or edit
     *
     * @return mixed
     * */
    public function get_button_height( $context='view' ){
        return $this->get_prop( 'wbp_button_height', $context );
    }

    /**
     * Returns button label
     *
     * @param string context context of function i.e. view or edit
     *
     * @return mixed
     * */
    public function get_button_label( $context='view' ){
        return $this->get_prop( 'wbp_button_label', $context );
    }

    /**
     * Returns button tagline
     *
     * @param string context context of function i.e. view or edit
     *
     * @return mixed
     * */
    public function get_button_tagline( $context='view' ){
        return $this->get_prop( 'wbp_button_tagline', $context );
    }

    /**
     * Returns shipping amount
     *
     * @param string context context of function i.e. view or edit
     *
     * @return mixed
     * */
    public function get_shipping_amount( $context='view' ){
        return $this->get_prop( 'wbp_item_shipping_amount', $context );
    }

    /**
     * Returns tax rate
     *
     * @param string context context of function i.e. view or edit
     *
     * @return mixed
     * */
    public function get_tax_rate( $context='view' ){
        return $this->get_prop( 'wbp_item_tax_rate', $context );
    }

    /**
     * Returns the hidden funding method
     * 
     * @param string context context of function i.e. view or edit
     * 
     * @return array
     * */
    public function get_hide_funding_method( $context='view' ){
        $hidden_methods = $this->get_prop( 'wbp_hide_funding_method', $context );
        if( empty( $hidden_methods ) ){
            return array();
        }
        return $hidden_methods;
    }

    /**
     * Returns the property
     * 
     * @param string prop propery name or key
     * @param string context context of function i.e. view or edit
     * 
     * @return mixed
     * */
    private function get_prop( $prop, $context ){
        if( array_key_exists( $prop, $this->data ) && !empty( $this->data[$prop] ) ){
            $value = $this->data[$prop];
        }

        if( array_key_exists( $prop, $this->data ) && is_null( $this->data[$prop] ) ){
            $value = '';
        }

        $value = get_post_meta( $this->id, $prop, true );
        if( $context === 'view' ){
            $this->data[$prop] = apply_filters( 'angelleye_paypal_wp_button_manager_button_get_' . $prop, $value, $this->id );
        } else {
            $this->data[$prop] = $value;
        }
        return $this->data[$prop];
    }

    /**
     * Provides the tax total of the button
     * 
     * @return mixed
     * */
    public function get_tax_total(){
        $tax = !empty( $this->get_tax_rate() ) ? $this->get_tax_rate() : 0;
        if( $tax == 0 ){
            return 0;
        }

        $price = $this->get_price();
        if( empty( $price ) ){
            $price = 0;
        }

        $shipping = !empty( $this->get_shipping_amount() ) ? $this->get_shipping_amount() : 0;

        $total_amount = $price + $shipping;

        return ($total_amount * $tax / 100);
    }

    /**
     * Provides the final total of the button including shipping and tax
     * 
     * @return mixed
     * */
    public function get_total(){
        $shipping = !empty( $this->get_shipping_amount() ) ? $this->get_shipping_amount() : 0;
        $tax = $this->get_tax_total();
        $price = $this->get_price();
        if( empty( $price ) ){
            $price = 0;
        }
        return $price + $shipping + $tax;
    }

    /**
     * Sets the company information
     * */
    private function set_company(){
        if( empty( $this->get_company_id() ) ){
            return false;
        }

        global $wpdb;

        $company = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}angelleye_paypal_button_manager_companies WHERE ID = %d", $this->get_company_id() ), ARRAY_A );
        if( empty( $company ) ){
            return false;
        }

        $this->data['paypal_company'] = $company;
    }

    /**
     * Checks if the company information is not set, then allows to set the same
     * */
    public function maybe_set_company(){
        if( !isset( $this->data['paypal_company'] ) || empty( $this->data['paypal_company'] ) ){
            $this->set_company();
        }
    }

    /**
     * Returns if the company is in test mode
     * 
     * @return boolean
     * */
    public function is_company_test_mode(){
        $this->maybe_set_company();
        return $this->data['paypal_company']['paypal_mode'] == 'sandbox' ? true : false;
    }

    /**
     * Returns the merchant id of the company
     * 
     * @return string
     * */
    public function get_company_merchant_id(){
        $this->maybe_set_company();
        return $this->data['paypal_company']['paypal_merchant_id'];
    }
}