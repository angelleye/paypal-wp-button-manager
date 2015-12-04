<?php

/**
 * This class defines all paypal custom functions
 * @class       AngellEYE_PayPal_WP_Button_Manager_PayPal_Helper
 * @version	1.0.0
 * @package		paypal-wp-button-manager/partials
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_PayPal_Helper {

    /**
     * Hook in methods
     * @since    0.1.0
     * @access   static
     */
    public function __construct() {
        $this->paypal_wp_button_manager_get_button_type();
    }

    /**
     * paypal_wp_button_manager_get_button_type prepairs array for button type.
     * @since 1.0.0
     * @access public
     */
    public function paypal_wp_button_manager_get_button_type() {
        if (isset($_POST['button_type']) && !empty($_POST['button_type'])) {
            $button_type = $_POST['button_type'];
        } else {
            $button_type = 'CART';
        }

        switch ($button_type) {
            case 'products' : $button_type = 'CART';
                break;
            case 'services' : $button_type = 'BUYNOW';
                break;
            case 'donations' : $button_type = 'DONATE';
                break;
            case 'gift_certs' : $button_type = 'GIFTCERTIFICATE';
                break;
            case 'subscriptions' : $button_type = 'SUBSCRIBE';
                break;
            default : $button_type = 'CART';
                break;
        }
        return $button_type;
    }

    /**
     * paypal_wp_button_manager_get_paypalconfig function is for getting
     * paypal api settings detail.
     * @since 1.0.0
     * @access public
     */
    public function paypal_wp_button_manager_get_paypalconfig() {


        if (isset($_POST['ddl_companyname']) && !empty($_POST['ddl_companyname'])) {
            global $wpdb;
            $flag = '';
            $tbl_name = $wpdb->prefix . 'paypal_wp_button_manager_companies'; // do not forget about tables prefix
            $getconfig = $wpdb->get_row("SELECT * FROM `{$tbl_name}` where ID='$_POST[ddl_companyname]'");
            $is_sandbox = isset($getconfig->paypal_mode) ? $getconfig->paypal_mode : '';
            if (isset($is_sandbox) && !empty($is_sandbox)) {
                if ($is_sandbox == 'Sandbox') {
                    $flag = TRUE;
                } else if ($is_sandbox == 'Live') {
                    $flag = FALSE;
                }
            }

            $APIUsername = isset($getconfig->paypal_api_username) ? $getconfig->paypal_api_username : '';
            $APIPassword = isset($getconfig->paypal_api_password) ? $getconfig->paypal_api_password : '';
            $APISignature = isset($getconfig->paypal_api_signature) ? $getconfig->paypal_api_signature : '';

            $payapalconfig = array('Sandbox' => $flag,
                'APIUsername' => isset($APIUsername) ? $APIUsername : '',
                'APIPassword' => isset($APIPassword) ? $APIPassword : '',
                'APISignature' => isset($APISignature) ? $APISignature : '',
                'PrintHeaders' => isset($print_headers) ? $print_headers : '',
                'LogResults' => isset($log_results) ? $log_results : '',
                'LogPath' => isset($log_path) ? $log_path : ''
            );
        }

        return $payapalconfig;
    }

    /**
     * paypal_wp_button_manager_get_buttonfields prepairs array for
     * button fields.
     * @since 1.0.0
     * @access public
     */
    public function paypal_wp_button_manager_get_buttonfields() {

        if (isset($_POST['enable_hosted_buttons'])) {
            $buttontype = 'HOSTED';
        } else {
            $buttontype = 'CLEARTEXT';
        }

        if ($_POST['button_type'] == 'products') {
            $buttonimage = 'REG';
        } else if (isset($_POST['cc_logos'])) {
            $buttonimage = 'CC';
        } else {
            $buttonimage = 'SML';
        }
        if (isset($_POST['wpss_upload_image']) && !empty($_POST['wpss_upload_image'])) {
            $bmcreatebuttonfields = array
                (
                'buttoncode' => $buttontype, // The kind of button code to create.  It is one of the following values:  HOSTED, ENCRYPTED, CLEARTEXT, TOKEN
                'buttontype' => AngellEYE_PayPal_WP_Button_Manager_PayPal_Helper::paypal_wp_button_manager_get_button_type(), // Required.  The kind of button you want to create.  It is one of the following values:  BUYNOW, CART, GIFTCERTIFICATE, SUBSCRIBE, DONATE, UNSUBSCRIBE, VIEWCART, PAYMENTPLAN, AUTOBILLING, PAYMENT
                'buttonsubtype' => '', // The use of button you want to create.  Values are:  PRODUCTS, SERVICES
                'buttonimage' => $buttonimage, //isset($_POST['cc_logos']) ? 'CC' : 'SML',
                'buttonimageurl' => $_POST['wpss_upload_image'],
                'buttonlanguage' => isset($_POST['select_country_language']) ? $_POST['select_country_language'] : ''
            );
            return $bmcreatebuttonfields;
        } else {

            $bmcreatebuttonfields = array
                (
                'buttoncode' => $buttontype, // The kind of button code to create.  It is one of the following values:  HOSTED, ENCRYPTED, CLEARTEXT, TOKEN
                'buttontype' => AngellEYE_PayPal_WP_Button_Manager_PayPal_Helper::paypal_wp_button_manager_get_button_type(), // Required.  The kind of button you want to create.  It is one of the following values:  BUYNOW, CART, GIFTCERTIFICATE, SUBSCRIBE, DONATE, UNSUBSCRIBE, VIEWCART, PAYMENTPLAN, AUTOBILLING, PAYMENT
                'buttonsubtype' => '', // The use of button you want to create.  Values are:  PRODUCTS, SERVICES
                'buttonimage' => $buttonimage, //isset($_POST['cc_logos']) ? 'CC' : 'SML',
                'buttonlanguage' => isset($_POST['select_country_language']) ? $_POST['select_country_language'] : ''
            );
            return $bmcreatebuttonfields;
        }
    }

    /**
     * You may pass in any variables from the Standard Payments list.
     * Depending on the type of button you are creating some variables will be required.
     * Refer to the HTML Standard Variable reference for more details on variables for specific button types.
     * @since 1.0.0
     * @access public
     */
    public function paypal_wp_button_manager_get_buttonvars() {
        if (isset($_POST['product_id'])) {
            $item_number = $_POST['product_id'];
        } else if (isset($_POST['donation_id'])) {
            $item_number = $_POST['donation_id'];
        } else if (isset($_POST['subscription_id'])) {
            $item_number = $_POST['subscription_id'];
        } else {
            $item_number = '';
        }

        if (isset($_POST['donation_name'])) {
            $item_name = $_POST['donation_name'];
        } else if (isset($_POST['product_name'])) {
            $item_name = $_POST['product_name'];
        } else if (isset($_POST['subscription_name'])) {
            $item_name = $_POST['subscription_name'];
        } else {
            $item_name = '';
        }


        $post = array();
        foreach (explode('&', file_get_contents('php://input')) as $keyValuePair) {
            list($key, $value) = explode('=', $keyValuePair);
            $post[$key][] = $value;
        }
        $ddp_option_name = isset($post['ddp_option_name']) ? $post['ddp_option_name'] : '';
        if ((isset($ddp_option_name) && !empty($ddp_option_name)) && (empty($_POST['dropdown_price_title']) || !empty($_POST['dropdown_price_title']))) {
            $item_price = '';
        } else if (isset($_POST['item_price']) && !empty($_POST['item_price'])) {
            $item_price = $_POST['item_price'];
        } else {
            $item_price = '';
        }

        if (isset($_POST['ddl_companyname']) && !empty($_POST['ddl_companyname'])) {
            global $wpdb;

            $get_business_tbl = $wpdb->prefix . 'paypal_wp_button_manager_companies'; // do not forget about tables prefix
            $get_business = $wpdb->get_row("SELECT * FROM `{$get_business_tbl}` where ID='$_POST[ddl_companyname]'");
            if (isset($get_business)) {
				
            	if (isset($get_business->paypal_account_mode) && !empty ($get_business->paypal_account_mode)) {
            		$paypal_account_mode = $get_business->paypal_account_mode;
            	}

            	
            	if (isset($paypal_account_mode) && !empty($paypal_account_mode)) {
               			if ($paypal_account_mode == 'paypal_account_id') {
               				$get_business_email = $get_business->paypal_merchant_id;	
               			}else {
               				$get_business_email = $get_business->paypal_person_email;
               			}
            		
            	}
                
            }
        }

        $buttonvars = array(
            'notify_url' => isset($_POST['ipn_urlinput']) ? $_POST['ipn_urlinput'] : '', // The URL to which PayPal posts information about the payment. in the form of an IPN message.
            'amount' => isset($item_price) ? $item_price : '', // The price or amount of the product, service, or contribution, not including shipping, handling, or tax.  If this variable is omitted from Buy Now or Donate buttons, buyers enter their own amount at the time of the payment.
            'discount_amount' => '', // Discount amount associated with an item.  Must be less than the selling price of the item.  Valid only for Buy Now and Add to Cart buttons.
            'discount_amount2' => '', // Discount amount associated with each additional quantity of the item.  Must be equal to or less than the selling price of the item.
            'discount_rate' => '', // Discount rate (percentage) associated with an item.  Must be set to a value less than 100.
            'discount_rate2' => '', // Discount rate (percentage) associated with each additional quantity of the item.  Must be equal to or less than 100.
            'discount_num' => '', // Number of additional quantities of the item to which the discount applies.
            'item_name' => isset($item_name) ? $item_name : '', // Description of the item.  If this is omitted, buyers enter their own name during checkout.
            'item_number' => isset($item_number) ? $item_number : '', // Pass-through variable for you to track product or service purchased or the contribution made.
            'quantity' => '', // Number of items.
            'shipping' => isset($_POST['item_shipping_amount']) ? $_POST['item_shipping_amount'] : '', // The cost of shipping this item.
            'shipping2' => '', // The cost of shipping each additional unit of this item.
            'handling' => '', // handling charges.  This variable is not quantity-specific.
            'tax' => '', // Transaction-based tax override variable.  Set this variable to a flat tax amount to apply to the payment regardless of the buyer's location.  This overrides any tax settings in the account profile.
            'tax_rate' => isset($_POST['item_tax_rate']) ? $_POST['item_tax_rate'] : '', // Transaction-based tax override variable.  Set this variable to a percentage that applies to the amount multipled by the quantity selected uring checkout.  This overrides your paypal account profile.
            'undefined_quantity' => '', // Set to 1 to allow the buyer to specify the quantity.
            'weight' => '', // Weight of items.
            'weight_unit' => '', // The unit of measure if weight is specified.  Values are:  lbs, kgs
            'address_override' => '', // Set to 1 to override the payer's address stored in their PayPal account.
            'currency_code' => isset($_POST['item_price_currency']) ? $_POST['item_price_currency'] : '', // The currency of the payment.  https://developer.paypal.com/docs/classic/api/currency_codes/#id09A6G0U0GYK
            'custom' => '', // Pass-through variable for your own tracking purposes, which buyers do not see.
            'invoice' => '', // Pass-through variable you can use to identify your invoice number for the purchase.
            'tax_cart' => '', // Cart-wide tax, overriding any individual item tax_ value
            'handling_cart' => '', // Single handling fee charged cart-wide.
            'weight_cart' => '', // If profile-based shipping rates are configured with a basis of weight, PayPal uses this value to calculate the shipping charges for the payment.  This value overrides the weight values of individual items.
            'add' => '', // Set to 1 to add an item to the PayPal shopping cart.
            'display' => '', // Set to 1 to display the contents of the PayPal shopping cart to the buyer.
            'upload' => '', // Set to 1 to upload the contents of a third-party shopping cart or a custom shopping cart.
            'business' => isset($get_business_email) ? $get_business_email: '', // Your PayPal ID or an email address associated with your PayPal account.  Email addresses must be confirmed.
            'paymentaction' => '', // Indicates whether the payment is a finale sale or an authorization for a final sale, to be captured later.  Values are:  sale, authorization, order
            'shopping_url' => isset($_POST['gift_certificate_shop_url']) ? esc_url($_POST['gift_certificate_shop_url']) : '', // The URL of the page on the merchant website that buyers go to when they click the Continue Shopping button on the PayPal shopping cart page.
            'a1' => isset($_POST['subscription_trial_rate']) ? $_POST['subscription_trial_rate'] : '', // Trial period 1 price.  For a free trial period, specify 0.
            'p1' => isset($_POST['subscription_trial_duration']) ? $_POST['subscription_trial_duration'] : '', // Trial period 1 duration.  Required if you specify a1.
            't1' => isset($_POST['subscription_trial_duration_type']) ? $_POST['subscription_trial_duration_type'] : '', // Trial period 1 units of duration.  Values are:  D, W, M, Y
            'a2' => isset($_POST['subscription_trial_2_rate']) ? $_POST['subscription_trial_2_rate'] : '', // Trial period 2 price.  Can be specified only if you also specify a1.
            'p2' => isset($_POST['subscription_trial_2_duration']) ? $_POST['subscription_trial_2_duration'] : '', // Trial period 2 duration.
            't2' => isset($_POST['subscription_trial_2_duration_type']) ? $_POST['subscription_trial_2_duration_type'] : '', // Trial period 2 units of duration.
            'a3' => isset($_POST['subscription_billing_amount']) ? $_POST['subscription_billing_amount'] : '', // Regular subscription price.
            'p3' => isset($_POST['subscription_billing_cycle_number']) ? $_POST['subscription_billing_cycle_number'] : '', // Regular subscription duration.
            't3' => isset($_POST['subscription_billing_cycle_period']) ? $_POST['subscription_billing_cycle_period'] : '', // Regular subscription units of duration.
            'src' => isset($_POST['subscription_billing_limit']) ? '1' : '', // Recurring payments.  Subscription payments recur unless subscribers cancel.  Values are:  1, 0
            'srt' => isset($_POST['subscription_billing_limit']) ? $_POST['subscription_billing_limit'] : '',
            'sra' => '', // Reattempt on failure.  If a recurring payment fails, PayPal attempts to collect the payment two more times before canceling.  Values are:  1, 0
            'no_note' => '', // Set to 1 to disable prompts for buyers to include a note with their payments.
            'modify' => '', // Modification behavior.  0 - allows subscribers only to sign up for new subscriptions.  1 - allows subscribers to sign up for new subscriptions and modify their current subscriptions.  2 - allows subscribers to modify only their current subscriptions.
            'usr_manage' => isset($_POST['enable_username_password_creation']) ? $_POST['enable_username_password_creation'] : '', // Set to 1 to have PayPal generate usernames and passwords for subscribers.  https://developer.paypal.com/docs/classic/paypal-payments-standard/integration-guide/subscribe_buttons/#id08ADFB00QWS
            'max_text' => '', // A description of the automatic billing plan.
            'set_customer_limit' => '', // Specify whether to let buyers enter maximum billing limits in a text box or choose from a list of max billing limits that you specify.  Values are:  max_limit_own, max_limit_defined
            'min_amount' => '', // The minimum monthly billing limit, if you have one.
            'disp_tot' => '', // Display the total payment amount to buyers during checkout.  Values are:  Y, N
            'page_style' => '', // The custom payment page style for checkout pages.  Values are:  paypal, primary, page_style_name
            'image_url' => '', // The URL of the 150x50 image displayed as your logo on the PayPal checkout pages.
            'cpp_cart_border_color' => '', // The HTML hex code for your principal identifying color.  PayPal blends your color to white on the checkout pages.
            'cpp_header_image' => '', // The image at the top, left of the checkout page.  Max size is 750x90.
            'cpp_headerback_color' => '', // The background color for the header of the checkout page.
            'cpp_headerborder_color' => '', // The border color around the header of the checkout page.
            'cpp_logo_image' => '', // A URL to your logo image.  Must be .gif, .jpg, or .png.  190x60
            'cpp_payflow_color' => '', // The background color for the checkout page below the header.
            'lc' => '', // The locale of the login or sign-up page.
            'cn' => '', // Label that appears above the note field.
            'no_shipping' => '', // Do not prompt buyers for a shipping address.  Values are:  0 - prompt for an address but do not require.  1 - do not prompt.  2 - prompt and require address.
            'return' => isset($_POST['return']) ? esc_url($_POST['return']) : '', // The URL to which PayPal redirects buyers' browsers after they complete their payment.
            'rm' => '', // Return method.  Values are:  0 - all shopping cart payments use GET method.  1 - buyer's browser is redirected using the GET method. 2 - buyer's browser is redirected using POST.
            'cbt' => '', // Sets the text for the Return to Merchant button on the PayPal completed payment page.
            'cancel_return' => isset($_POST['cancel_return']) ? esc_url($_POST['cancel_return']) : '', // A URL to which PayPal redirects buyers if they cancel the payment.
            'address1' => '',
            'address2' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'country' => '',
            'email' => '',
            'first_name' => '',
            'last_name' => '',
            'charset' => '', // Sets the character set and character encoding for the billing login page.
            'night_phone_a' => '', // Area code for US phone numbers or country code for phone numbers outside the US.
            'night_phone_b' => '', // 3 digit prefix for US numbers or the entire phone number for numbers outside the US.
            'night_phone_c' => '', // 4 digit phone number for US numbers.
            'fixed_denom' => isset($_POST['gc_fixed_amount']) ? $_POST['gc_fixed_amount'] : ''
        );

        return $buttonvars;
    }
    
    
   

    /**
     * paypal_wp_button_manager_get_dropdown_values function prepairs array
     * for generate dropdown menu for button.
     * @since 1.0.0
     * @access public
     */
    public function paypal_wp_button_manager_get_dropdown_values() {

        $BMButtonOptions = array();
        $ddp_option_name = array();

      
        $post = array();
        foreach (explode('&', urldecode(file_get_contents('php://input'))) as $keyValuePair) {
            list($key, $value) = explode('=', $keyValuePair);
            $post[$key][] = $value;
        }
        $ddp_option_name = isset($post['ddp_option_name']) ? $post['ddp_option_name'] : '';
        if (isset($ddp_option_name) && !empty($ddp_option_name)) {
            $BMButtonOptionSelections = array();
            foreach ($ddp_option_name as $ddp_option_name_key => $ddp_option_name_value) {
                $BMButtonOptionSelection = array(
                    'value' => $ddp_option_name_value,
                    'price' => $post['ddp_option_price'][$ddp_option_name_key],
                    'type' => '',
                    'billingperiod' => ( isset($post['ddp_option_frequency'][$ddp_option_name_key]) && !empty($post['ddp_option_frequency'][$ddp_option_name_key]) ) ? $post['ddp_option_frequency'][$ddp_option_name_key] : ''
                );

                array_push($BMButtonOptionSelections, $BMButtonOptionSelection);
            }
            $BMButtonOption = array(
                'name' => isset($_POST['dropdown_price_title']) ? $_POST['dropdown_price_title'] : '',
                'selections' => $BMButtonOptionSelections
            );
            array_push($BMButtonOptions, $BMButtonOption);
        }

        for ($i = 1; $i <= 6; $i++) {
            if (isset($post['dd' . $i . '_option_name']) || !empty($post['dd' . $i . '_option_name'])) {

                $BMButtonOptionSelections_dd_all = array();

                $ddall_option_name = array();

                $ddall_option_name = $post['dd' . $i . '_option_name'];

                foreach ($ddall_option_name as $ddall_option_name_key => $ddall_option_name_value) {
                    $BMButtonOptionSelection_dd_all[] = array(
                        'value' => $ddall_option_name_value,
                        'price' => '',
                        'type' => ''
                    );
                }

                $BMButtonOption_dd_all = array(
                    'name' => $post['dropdown' . $i . '_title'][0],
                    'selections' => $BMButtonOptionSelection_dd_all
                );


                array_push($BMButtonOptions, $BMButtonOption_dd_all);
                unset($BMButtonOption_dd_all);
                unset($BMButtonOptionSelection_dd_all);
            }
        }
        
        $BMTextField = false;
        if( isset($post['textfield'][0]) && 'createdTextfield' == $post['textfield'][0]) {
            if( isset($post['textfield1_title'][0]) && !empty($post['textfield1_title'][0])) {
                $BMTextField = '&L_TextBox0='. $post['textfield1_title'][0];
            }
        }

        $paypalrequestdata = array(
            'BMCreateButtonFields' => AngellEYE_PayPal_WP_Button_Manager_PayPal_Helper::paypal_wp_button_manager_get_buttonfields(),
            'BMButtonVars' => AngellEYE_PayPal_WP_Button_Manager_PayPal_Helper::paypal_wp_button_manager_get_buttonvars(),
            'BMButtonOptions' => $BMButtonOptions,
            'BMTextField' => $BMTextField
        );

        return $paypalrequestdata;
    }

    

}
