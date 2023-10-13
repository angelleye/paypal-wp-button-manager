<?php
defined( 'ABSPATH' ) || exit;
/*
 * Class responsible for create paypal button subscription renewals.
 */
class Angelleye_Paypal_Wp_Button_Manager_Subscription_Renewal{

	public function __construct(){
		if( !wp_next_scheduled( 'angelleye_paypal_renew_subscriptions' ) ){
			wp_schedule_event( time(), 'daily', 'angelleye_paypal_renew_subscriptions' );
		}

		add_action('angelleye_paypal_renew_subscriptions', array( $this, 'renew_subscriptions' ) );
	}

	public function renew_subscriptions(){
		global $wpdb;

		$subscriptions = $wpdb->get_col( "SELECT ID FROM {$wpdb->prefix}angelleye_paypal_button_manager_subscriptions WHERE DATE(next_payment_due_date) = '" . strtotime('Y-m-d') . "'" );

		foreach( $subscriptions as $subscription_id ){
			$this->renew_subscription( $subscription_id );
		}
	}

	public function renew_subscription( $subscription_id ){
		$subscription = new Angelleye_Paypal_Wp_Button_Manager_Subscription( $subscription_id );
		$button_id = $subscription->get_button_id();

		$button = new Angelleye_Paypal_Wp_Button_Manager_Button( $button_id );

		$amount = $button->get_total();
		if( $amount <= 0 ){
			$subscription->update_renew_date();
			$subscription->save();
			return;
		}

		$api = new Angelleye_Paypal_Wp_Button_Manager_Paypal_API( $button->get_company_merchant_id(), $button->is_company_test_mode() );
		$api->set_method('POST');

		$paypal_body = array(
		    'purchase_units' => array(
		        array(
		            'items' => array(
		                array(
		                    'name' => $button->get_item_name(),
		                    'quantity' => 1,
		                    'unit_amount' => array(
		                        'currency_code' => $button->get_currency(),
		                        'value' => $button->get_price()
		                    ),
		                )
		            ),
		            'amount' => array(
		                'currency_code' => $button->get_currency(),
		                'value' => $amount,
		                'breakdown' => array(
		                    'item_total' => array(
		                        'currency_code' => $button->get_currency(),
		                        'value' => $button->get_price()
		                    )
		                )
		            ),
		            'payee' => array(
		                'merchant_id' => $button->get_company_merchant_id(),
		                // 'email_address' => 'sb-crfvt22010093@business.example.com'
		            ),
		        )
		    ),
		    'intent' => 'CAPTURE',
		    'payment_source' => array(
		    	$subscription->get_payment_source() => array(
		    		'vault_id' => $subscription->get_vault_id()
		    	)
		    ),
		);

		if( !empty( $button->get_shipping_amount() ) ){
		    $paypal_body['purchase_units'][0]['amount']['breakdown']['shipping'] = array(
		        'currency_code' => $button->get_currency(),
		        'value' => $button->get_shipping_amount(),
		    );
		}

		if( !empty( $button->get_tax_total() ) ){
		    $paypal_body['purchase_units'][0]['items'][0]['tax'] = array(
		        'currency_code' => $button->get_currency(),
		        'value' => $button->get_tax_total()
		    );
		    $paypal_body['purchase_units'][0]['amount']['breakdown']['tax_total'] = array(
		        'currency_code' => $button->get_currency(),
		        'value' => $button->get_tax_total()
		    );
		}

		$api->set_body( $paypal_body );
		$api->set_action('create_order');
		$payment = $api->submit();
		print("<pre>");print_r( $payment );print("</pre>");

		// Capture Payment
		// Update Renewal Date if Capture Success
	}
}