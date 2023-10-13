<?php
defined( 'ABSPATH' ) || exit;
/*
 * Class responsible for create paypal button subscription.
 */
class Angelleye_Paypal_Wp_Button_Manager_Subscription{

	private $user_id;
	private $email_address;
	private $first_name;
	private $last_name;
	private $button_id;
	private $payment_source;
	private $vault_id;
	private $next_payment_due_date;
	private $subscription_id;
	private $status;
	private $data = array();
	private $wpdb;
	private $update_renew_date = false;

	public function __construct( $subscription_id=null ){
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->subscription_id = $subscription_id;
	}

	/**
	 * Allows to set subscribeption button id.
	 * 
	 * @param string 	button_id	Button ID of the subscription
	 * 
	 * @return void
	 * */
	public function set_button_id( $button_id ){
		$this->button_id = $button_id;
		$this->data['button_id'] = $button_id;
	}

	/**
	 * Allows to set subscriber's user id
	 * 
	 * @param string 	user_id 	user id of the subscriber
	 * 
	 * @return void
	 * */
	public function set_user_id( $user_id ){
		$this->user_id = $user_id;
		$this->data['user_id'] = $user_id;
	}

	/**
	 * Allows to set subscriber's email
	 * 
	 * @param string 	email	email of the subscriber
	 * 
	 * @return void
	 * */
	public function set_email_address( $email ){
		$this->email_address = $email;
		$this->data['email_address'] = $email;
	}

	/**
	 * Allows to set subscriber's first name
	 * 
	 * @param string 	name	first name of the subscriber
	 * 
	 * @return void
	 * */
	public function set_first_name( $name ){
		$this->first_name = $name;
		$this->data['first_name'] = $name;
	}

	/**
	 * Allows to set subscriber's last name
	 * 
	 * @param string 	name	last name of the subscriber
	 * 
	 * @return void
	 * */
	public function set_last_name( $name ){
		$this->last_name = $name;
		$this->data['last_name'] = $name;
	}

	/**
	 * Allows to set subscription payment source
	 * 
	 * @param string 	payment_source	payment source of the subscription 
	 * 
	 * @return void
	 * */
	public function set_payment_source( $source ){
		$this->payment_source = $source;
		$this->data['payment_source'] = $source;
	}

	/**
	 * Allows to set subscription vault id
	 * 
	 * @param string 	vault_id 	vault id of the subscription 
	 * 
	 * @return void
	 * */
	public function set_vault_id( $vault_id ){
		$this->vault_id = $vault_id;
		$this->data['vault_id'] = $vault_id;
	}

	/**
	 * Allows to set subscription status
	 * 
	 * @param string 	status 		status of the subscription 
	 * 
	 * @return void
	 * */
	public function set_status( $status ){
		if( in_array( $status, self::get_available_statuses() ) ){
			$this->status = $status;
			$this->data['status'] = $status;
		}
	}

	/**
	 * Allows to get the subscription's button id.
	 * 
	 * @param string 	context 	context of the data. i.e. view or edit
	 *
	 * @return mixed
	 * */
	public function get_button_id( $context='view' ){
		return $this->get_prop( 'button_id', $context );
	}

	/**
	 * Allows to get the subscriber's user id
	 * 
	 * @param string 	context 	context of the data. i.e. view or edit
	 *
	 * 
	 * @return mixed
	 * */
	public function get_user_id( $context='view' ){
		return $this->get_prop('user_id', $context );
	}

	/**
	 * Allows to get the subscriber's email address
	 * 
	 * @param string 	context 	context of the data. i.e. view or edit
	 *
	 * 
	 * @return mixed
	 * */
	public function get_email_address( $context='view' ){
		return $this->get_prop('email_address', $context );
	}

	/**
	 * Allows to get the subscriber's first name
	 * * 
	 * @param string 	context 	context of the data. i.e. view or edit
	 *
	 * 
	 * @return mixed
	 * */
	public function get_first_name( $context='view' ){
		return $this->get_prop('first_name', $context );
	}

	/**
	 * Allows to get the subscriber's last name
	 * 
	 * @param string 	context 	context of the data. i.e. view or edit
	 *
	 * 
	 * @return mixed
	 * */
	public function get_last_name( $context='view' ) {
		return $this->get_prop('last_name', $context );
	}

	/**
	 * Allows to get the subscription payment source
	 * 
	 * @param string 	context 	context of the data. i.e. view or edit
	 *
	 * 
	 * @return mixed
	 * */
	public function get_payment_source( $context='view' ){
		return $this->get_prop('payment_source', $context );
	}

	/**
	 * Allows to get the subscription vault id
	 * 
	 * @param string 	context 	context of the data. i.e. view or edit
	 *
	 * 
	 * @return mixed
	 * */
	public function get_vault_id( $context='view' ){
		return $this->get_prop('vault_id', $context);
	}

	/**
	 * Allows to get the subscription status
	 * 
	 * @param string 	context 	context of the data. i.e. view or edit
	 *
	 * 
	 * @return mixed
	 * */
	public function get_status( $context='view' ){
		return $this->get_prop('status', $context);
	}

	/**
	 * Returns the available subscription statuses
	 * 
	 * @return array
	 * */
	private static function get_available_statuses(){
		return apply_filters( 'angelleye_paypal_button_manager_subscription_available_statuses', array( 'active', 'onhold', 'cancel' ) );
	}

	/**
	 * Calculates and returns the next payment due date.
	 * 
	 * @return mixed
	 * */
	private function get_next_payment_due_date(){
		if( empty( $this->button_id ) ){
			return new WP_Error( 'button-id-not-set', __('Button ID is not set.', 'angelleye-paypal-wp-button-manager') );
		}

		$button = new Angelleye_Paypal_Wp_Button_Manager_Button( $this->button_id );
		if( $button->get_button_type() != 'subscription' ){
			return new WP_Error( 'button-not-subscription', __('This button is not subscription button.', 'angelleye-paypal-wp-button-manager') );
		}

		$frequency_count = intval( $button->get_frequency_count() );
		$frequency = $button->get_frequency();

		if( empty( $frequency_count ) || !is_integer( $frequency_count ) ){
			return new WP_Error( 'button-empty-frequency-count', __('Frequency count is not specified or invalid.', 'angelleye-paypal-wp-button-manager') );
		}

		if( empty( $frequency ) || !in_array( $frequency, array('day', 'week', 'month', 'year' ) ) ){
			return new WP_Error( 'button-empty-frequency', __('Frequency is not specified or invalid.', 'angelleye-paypal-wp-button-manager') );
		}

		return date('Y-m-d H:i:s', strtotime('+' . $frequency_count . ' ' . $frequency ) );
	}

	/**
	 * Sets the flag to update the renew date
	 * 
	 * @return void
	 * */
	public function update_renew_date(){
		$this->update_renew_date = true;
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

        $value = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->wpdb->prefix}angelleye_paypal_button_manager_subscriptions WHERE id = %d", $this->subscription_id ), ARRAY_A );

        if( $context === 'view' ){
            $this->data[$prop] = apply_filters( 'angelleye_paypal_wp_button_manager_subscription_get_' . $prop, $value[$prop], $this->subscription_id );
        } else {
            $this->subscription_id = $this->data->ID;
        }

        $this->data = $value;
        return $this->data[$prop];
    }

    /**
     * Saves the updated subscription details or creates the subscription
     * 
     * @return mixed
     * */
	public function save(){
		global $wpdb;
		
		if( is_null( $this->subscription_id ) ){
			if( empty( $this->user_id ) ){
				return new WP_Error( 'user-id-empty', __('User ID is not specified.', 'angelleye-paypal-wp-button-manager') );
			}

			if( empty( $this->email_address ) ){
				return new WP_Error( 'email-empty', __('Email is not specified.', 'angelleye-paypal-wp-button-manager') );
			}

			if( empty( $this->payment_source ) ){
				return new WP_Error( 'payment-source-empty', __('Payment source is not specified.', 'angelleye-paypal-wp-button-manager') );
			}

			if( empty( $this->vault_id ) ){
				return new WP_Error( 'vault-id-empty', __('Vault id is not specified.', 'angelleye-paypal-wp-button-manager') );
			}

			if( empty( $this->status ) ){
				return new WP_Error( 'status-empty', __('Subscription status is not specified.', 'angelleye-paypal-wp-button-manager') );
			}

			$next_due_date = $this->get_next_payment_due_date();
			if( is_wp_error( $next_due_date ) ){
				return $next_due_date;
			}

			$this->wpdb->insert( $this->wpdb->prefix . 'angelleye_paypal_button_manager_subscriptions', array(
				'user_id' => $this->user_id,
				'email_address' => $this->email_address,
				'first_name' => $this->first_name,
				'last_name' => $this->last_name,
				'button_id' => $this->button_id,
				'payment_source' => $this->payment_source,
				'vault_id' => $this->vault_id,
				'next_payment_due_date' => $next_due_date,
				'status' => $this->status
			));

			$this->subscription_id = $this->wpdb->insert_id;
		} else {
			$updates = array();

			if( $this->update_renew_date ){
				$next_due_date = $this->get_next_payment_due_date();
				if( is_wp_error( $next_due_date ) ){
					return $next_due_date;
				}
				$updates['next_payment_due_date'] = $next_due_date;
			}

			if( !empty( $this->user_id ) ){
				$updates['user_id'] = $this->user_id;
			}

			if( !empty( $this->email_address ) ){
				$updates['email_address'] = $this->email_address;
			}

			if( !empty( $this->payment_source ) ){
				$updates['payment_source'] = $this->payment_source;
			}

			if( !empty( $this->vault_id ) ){
				$updates['vault_id'] = $this->vault_id;
			}

			if( !empty( $this->status ) ){
				$updates['status'] = $this->status;
			}

			if( !empty( $updates ) ){
				$this->wpdb->update( $this->wpdb->prefix . 'angelleye_paypal_button_manager_subscriptions', $updates, array( 'ID' => $this->subscription_id ) );
			}
		}

		return $this->subscription_id;
	}
}