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
    private $endpoint;
    private $api_method;

	public function __construct( $merchant_id, $testmode ){
        $this->api_url = ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PPCP_LINK;
        $this->endpoint = 'ppcp-request';
		$this->merchant_id = $merchant_id;
        $this->testmode = $testmode;
        $this->partner_client_id = $testmode ? ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_SANDBOX_PARTNER_CLIENT_ID : ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_LIVE_PARTNER_CLIENT_ID;
        $this->paypal_url = $testmode ? 'https://api-m.sandbox.paypal.com/v2/checkout/orders/' : 'https://api-m.paypal.com/v2/checkout/orders/';
        $this->logger = new Angelleye_PayPal_WP_Button_Manager_Logger( 'ppcp-paypal' );
	}

    /**
     * Allows to set AWS Endpoint
     * 
     * @param endpoint  string      endpoint of the AWS URL.
     * */
    public function set_ppcp_endpoint( $endpoint ){
        $this->endpoint = $endpoint;
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

    public function set_api_url( $url ){
        $this->api_url = $url;
    }

    public function set_api_method( $method ){
        $this->api_method = $method;
    }

    private function get_api_url(){
        return $this->api_url . $this->endpoint;
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

        $this->logger->info('Request parameters are built', array('api_url' => $this->get_api_url(), 'request' => $request ) );

        if( $this->api_method == 'GET'){
            $response = wp_remote_get( $this->get_api_url(), $request );
        } else if( $this->api_method == 'POST' ){
            $response = wp_remote_post( $this->get_api_url(), $request );
        } else {
            $response = wp_remote_request( $this->get_api_url(), $request );
        }

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
        
        if( isset( $response->statusCode ) && !in_array( $response->statusCode, array( 200, 201 ) ) ){
            $this->logger->error('Invalid status code', $response );
            $error = new WP_Error( 'paypal-api-error' );
            foreach( $response->body->details as $detail ){
                $error->add( 'paypal-api-error', $detail->description );
            }
            return $error;
        }

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
}