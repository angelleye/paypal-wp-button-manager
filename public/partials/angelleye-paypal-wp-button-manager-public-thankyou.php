<?php
get_header();

?><div class="order-message order-<?php echo $success ? 'success' : 'failed'; ?>">
    <div class="message"><?php echo $success ? __('Your order received successfully.','angelleye-paypal-wp-button-manager') : $message; ?></div><?php
    if( $success ){
        ?><div class="order-details">
            <div class="transaction">
                <label class="transaction-label"><?php _e("Transaction ID:", "angelleye-paypal-wp-button-manager") ?></label>
                <p class="transaction-id"><?php echo $order_id; ?></p>
            </div>

            <div class="item-name-details">
                <label class="item-name-label"><?php _e("Item Name:", "angelleye-paypal-wp-button-manager") ?></label>
                <p class="item-name"><?php echo $button->get_item_name(); ?></p>
            </div>
            
            <div class="price-currency">
                <div class="price">
                    <label class="item-price-label"><?php _e("Price: ", "angelleye-paypal-wp-button-manager"); ?></label>
                    <p class="item-price"><?php echo angelleye_paypal_button_manager_get_price_html( $button->get_price(), $button->get_currency() ); ?></p>
                </div>
            </div><?php

            if( !empty( $button->get_shipping_amount() ) ){
                ?><div class="shipping-rate">
                    <label class="shipping-rate-label"><?php _e("Shipping Rate:", "angelleye-paypal-wp-button-manager"); ?></label>
                    <p class="shipping"><?php echo angelleye_paypal_button_manager_get_price_html( $button->get_shipping_amount(), $button->get_currency() ); ?></p>
                </div><?php 
            } 

            $tax = !empty( $button->get_tax_rate() ) ? $button->get_tax_total() : 0;
            $total_amount = $button->get_total();

            if( !empty( $button->get_tax_rate() ) ){
                ?><div class="tax-rate">
                    <label class="tax-rate-label"><?php echo sprintf( __("Tax (%s%%): ", "angelleye-paypal-wp-button-manager"), $button->get_tax_rate() ); ?></label>
                    <p class="tax-amount"><?php echo angelleye_paypal_button_manager_get_price_html( $tax, $button->get_currency() ); ?></p>
                </div><?php 
            }

            ?><div class="total-amount-details">
                <label class="total-amount-label"><?php _e("Total Amount:", "angelleye-paypal-wp-button-manager"); ?></label>
                <p class="total-amount"><?php echo angelleye_paypal_button_manager_get_price_html($total_amount, $button->get_currency() ) ?></p>
            </div>
        </div><?php
    }
?></div><?php 

get_footer();
?>