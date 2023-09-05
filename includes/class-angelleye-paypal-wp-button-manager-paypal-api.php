<?php
defined( 'ABSPATH' ) || exit;
/*
 * Class responsible for authorizing the paypal API.
 */
class Angelleye_Paypal_Wp_Button_Manager_Paypal_API {

    private $api_url;
	private $merchant_id;
	private $partner_client_id;
	private $testmode;
    private $paypal_url;
    private $paypal_header;
    private $paypal_body;
    private $paypal_method;
    private $action_name;
    private $logger;

	public function __construct( $merchant_id, $testmode ){
        $this->api_url = ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PPCP_LINK;
		$this->merchant_id = $merchant_id;
        $this->testmode = $testmode;
        $this->partner_client_id = $testmode ? ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_SANDBOX_PARTNER_CLIENT_ID : ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_LIVE_PARTNER_CLIENT_ID;
        $this->paypal_url = $testmode ? 'https://api-m.sandbox.paypal.com/v2/checkout/orders/' : 'https://api-m.paypal.com/v2/checkout/orders/';
        $this->logger = new Angelleye_PayPal_WP_Button_Manager_Logger( 'ppcp-paypal' );
        add_action('angelleye_button_manager_request_respose_data', array($this, 'angelleye_ppcp_tpv_tracking'), 10, 3);
	}

    /**
     * Sets the paypal URL
     * 
     * @param url string paypal url
     * @param append boolean whether to append or overwrite
     * */
    public function set_paypal_url( $url, $append=false ){
        if( $append ){
            $this->paypal_url .= $url;
        } else {
            $this->paypal_url = $url;
        }
    }

    /**
     * Allows to build the header of the API call.
     * */
    private function build_header(){
        $this->paypal_header = array(
            'Content-Type' => 'application/json',
            'Authorization' => '',
            'prefer' => 'return=representation',
            'PayPal-Request-Id' => 'angelleye-' . bin2hex( random_bytes( 10 ) ),
            'PayPal-Auth-Assertion' => $this->angelleye_ppcp_paypalauthassertion()
        );
    }

    /**
     * Sets the paypal api method
     * 
     * @param method string method
     * */
    public function set_method( $method ){
        $this->paypal_method = $method;
    }

    /**
     * Sets the paypal api body
     * 
     * @param body array body of the API call
     * */
    public function set_body( $body ){
        $this->paypal_body = $body;
    }

    /**
     * Sets the paypal action
     * 
     * @param action string paypal action
     * */
    public function set_action( $action ){
        $this->action_name = $action;
    }

    /**
     * Creates the API call and creates the charge
     * 
     * @return mixed
     * */
    public function submit(){
        $this->build_header();
        $request_body = array(
            'testmode' => $this->testmode ? 'yes' : 'no',
            'paypal_url' => $this->paypal_url,
            'paypal_header' => $this->paypal_header,
            'paypal_method' => $this->paypal_method
        );

        if( !empty( $this->action_name ) ){
            $request_body['action_name'] = $this->action_name;
        }

        if( !empty( $this->paypal_body ) ){
            $request_body['paypal_body'] = $this->paypal_body;
        } else {
            $request_body['paypal_body'] = null;
        }

        $request = array(
            'method' => $this->paypal_method,
            'body' => wp_json_encode( $request_body ),
            'user-agent' => 'PFW_PPCP',
            'timeout' => 70,
            'headers' => array(
                'Content-Type' => 'application/json',
                'ae_p_f' => "true",
                'plugin_version_id' => '1.0.0',
                'Content-Length' => strlen( wp_json_encode( $request_body ) )
            )
        );

        $this->logger->info('Request parameters are built', array('api_url' => $this->api_url, 'request' => $request ) );

        $response = wp_remote_request( $this->api_url, $request );
        
        

        if( is_wp_error( $response ) ){
            $this->logger->error('WP Error Received', $response );
            return $response;
        }

        if( $response['response']['code'] !== 200 ){
            $this->logger->error('Invalid response code', $response );
            return new WP_Error( 'paypal-api-error', __('Internal server error','angelleye-paypal-wp-button-manager') );
        }
       
        $response = json_decode( $response['body'] );
        if( !$response->status ){
            $this->logger->error('No response statuts', $response );
            return new WP_Error('paypal-api-error',  __('Internal server error','angelleye-paypal-wp-button-manager') );
        }
        
        if( !in_array( $response->statusCode, array( 200, 201 ) ) ){
            $this->logger->error('Invalid status code', $response );
            $error = new WP_Error( 'paypal-api-error' );
            foreach( $response->body->details as $detail ){
                $error->add( 'paypal-api-error', $detail->description );
            }
            return $error;
        }
        
        do_action('angelleye_button_manager_request_respose_data', $request, $response, $this->action_name);

        $this->logger->info('API execution completed', $response );
        return $response;
    }

    /**
     * Allows to get the paypal auth assertion
     * 
     * @return string
     * */
	public function angelleye_ppcp_paypalauthassertion() {
        $temp = array(
            "alg" => "none"
        );
        $returnData = base64_encode(json_encode($temp)) . '.';
        $temp = array(
            "iss" => $this->partner_client_id,
            "payer_id" => $this->merchant_id
        );
        $returnData .= base64_encode(json_encode($temp)) . '.';
        return $returnData;
    }
    
    public function angelleye_ppcp_tpv_tracking($request, $response, $action_name) {
        try {
            $allow_payment_event = array('capture_order', 'refund_order', 'authorize_order', 'void_authorized', 'capture_authorized');
            if (in_array($action_name, $allow_payment_event)) {
                if (class_exists('AngellEYE_PFW_Payment_Logger')) {
                    $amount = '';
                    $transaction_id = '';
                    if (isset($response['purchase_units']['0']['amount']['value'])) {
                        $amount = $response['purchase_units']['0']['amount']['value'];
                    } elseif (isset($response['amount']['value'])) {
                        $amount = $response['amount']['value'];
                    }
                    if (isset($response['purchase_units']['0']['payments']['captures'][0]['id'])) {
                        $transaction_id = $response['purchase_units']['0']['payments']['captures'][0]['id'];
                    } elseif (isset($response['purchase_units']['0']['payments']['authorizations']['0']['id'])) {
                        $transaction_id = $response['purchase_units']['0']['payments']['authorizations']['0']['id'];
                    } elseif (isset($response['id'])) {
                        $transaction_id = $response['id'];
                    }
                    $payment_logger = AngellEYE_PFW_Payment_Logger::instance();
                    $request_param['type'] = 'ppcp_' . $action_name;
                    $request_param['amount'] = $amount;
                    $request_param['status'] = 'Success';
                    $request_param['site_url'] = get_bloginfo('url');
                    $request_param['mode'] = ($this->is_sandbox === true) ? 'sandbox' : 'live';
                    $request_param['merchant_id'] = isset($response['purchase_units']['0']['payee']['merchant_id']) ? $response['purchase_units']['0']['payee']['merchant_id'] : '';
                    $request_param['correlation_id'] = '';
                    $request_param['transaction_id'] = $transaction_id;
                    $request_param['product_id'] = '1';
                    $payment_logger->angelleye_tpv_request($request_param);
                }
            }
        } catch (Exception $ex) {
            $this->api_log->log("The exception was created on line: " . $ex->getFile() . ' ' .$ex->getLine(), 'error');
            $this->api_log->log($ex->getMessage(), 'error');
        }
    }

}