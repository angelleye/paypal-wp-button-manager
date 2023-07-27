<?php
get_header();

?><div class="order-message order-<?php echo $success ? 'success' : 'failed'; ?>">
    <div class="message"><?php echo $success ? ( $button->get_button_type() == 'services' ? __('Your order received successfully.','angelleye-paypal-wp-button-manager') : __('Your donation received successfully.','angelleye-paypal-wp-button-manager') ) : $message; ?></div><?php
    if( $success ){
        ?><div class="order-details"><?php
            if( $button->get_button_type() == 'services' ){
                ?><div class="transaction" style="<?php echo (!empty($button->right_background_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'background: '.$button->right_background_color() : '' ?>; <?php echo (!empty($button->right_foreground_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'color: '.$button->right_foreground_color() : ''; ?>">
                    <label class="transaction-label" style="<?php echo (!empty($button->left_background_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'background: '.$button->left_background_color() : '' ?>; <?php echo (!empty($button->left_foreground_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'color: '.$button->left_foreground_color() : ''; ?>"><?php _e("Transaction ID:", "angelleye-paypal-wp-button-manager") ?></label>
                    <p class="transaction-id"><?php echo $order_id; ?></p>
                </div>

                <div class="item-name-details" style="<?php echo (!empty($button->right_background_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'background: '.$button->right_background_color() : '' ?>; <?php echo (!empty($button->right_foreground_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'color: '.$button->right_foreground_color() : ''; ?>">
                    <label class="item-name-label" style="<?php echo (!empty($button->left_background_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'background: '.$button->left_background_color() : '' ?>; <?php echo (!empty($button->left_foreground_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'color: '.$button->left_foreground_color() : ''; ?>"><?php _e("Item Name:", "angelleye-paypal-wp-button-manager") ?></label>
                    <p class="item-name"><?php echo $button->get_item_name(); ?></p>
                </div>
                
                <div class="price-currency" style="<?php echo (!empty($button->right_background_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'background: '.$button->right_background_color() : '' ?>; <?php echo (!empty($button->right_foreground_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'color: '.$button->right_foreground_color() : ''; ?>">
                    <div class="price">
                        <label class="item-price-label" style="<?php echo (!empty($button->left_background_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'background: '.$button->left_background_color() : '' ?>; <?php echo (!empty($button->left_foreground_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'color: '.$button->left_foreground_color() : ''; ?>"><?php _e("Price: ", "angelleye-paypal-wp-button-manager"); ?></label>
                        <p class="item-price"><?php echo angelleye_paypal_button_manager_get_price_html( $button->get_price(), $button->get_currency() ); ?></p>
                    </div>
                </div><?php

                if( !empty( $button->get_shipping_amount() ) ){
                    ?><div class="shipping-rate" style="<?php echo (!empty($button->right_background_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'background: '.$button->right_background_color() : '' ?>; <?php echo (!empty($button->right_foreground_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'color: '.$button->right_foreground_color() : ''; ?>">
                        <label class="shipping-rate-label" style="<?php echo (!empty($button->left_background_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'background: '.$button->left_background_color() : '' ?>; <?php echo (!empty($button->left_foreground_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'color: '.$button->left_foreground_color() : ''; ?>"><?php _e("Shipping Rate:", "angelleye-paypal-wp-button-manager"); ?></label>
                        <p class="shipping"><?php echo angelleye_paypal_button_manager_get_price_html( $button->get_shipping_amount(), $button->get_currency() ); ?></p>
                    </div><?php 
                } 

                $tax = !empty( $button->get_tax_rate() ) ? $button->get_tax_total() : 0;
                $total_amount = $button->get_total();

                if( !empty( $button->get_tax_rate() ) ){
                    ?><div class="tax-rate" style="<?php echo (!empty($button->right_background_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'background: '.$button->right_background_color() : '' ?>; <?php echo (!empty($button->right_foreground_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'color: '.$button->right_foreground_color() : ''; ?>">
                        <label class="tax-rate-label" style="<?php echo (!empty($button->left_background_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'background: '.$button->left_background_color() : '' ?>; <?php echo (!empty($button->left_foreground_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'color: '.$button->left_foreground_color() : ''; ?>"><?php echo sprintf( __("Tax (%s%%): ", "angelleye-paypal-wp-button-manager"), $button->get_tax_rate() ); ?></label>
                        <p class="tax-amount"><?php echo angelleye_paypal_button_manager_get_price_html( $tax, $button->get_currency() ); ?></p>
                    </div><?php 
                }

                ?><div class="total-amount-details" style="<?php echo (!empty($button->right_background_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'background: '.$button->right_background_color() : '' ?>; <?php echo (!empty($button->right_foreground_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'color: '.$button->right_foreground_color() : ''; ?>">
                    <label class="total-amount-label" style="<?php echo (!empty($button->left_background_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'background: '.$button->left_background_color() : '' ?>; <?php echo (!empty($button->left_foreground_color()) && $button->is_data_fields_hidden() !== 'yes') ? 'color: '.$button->left_foreground_color() : ''; ?>"><?php _e("Total Amount:", "angelleye-paypal-wp-button-manager"); ?></label>
                    <p class="total-amount"><?php echo angelleye_paypal_button_manager_get_price_html($total_amount, $button->get_currency() ) ?></p>
                </div><?php
            } else if ( $button->get_button_type() == 'donate' ){
                ?><div class="transaction">
                    <label class="transaction-label"><?php _e("Transaction ID:", "angelleye-paypal-wp-button-manager") ?></label><p class="transaction-id"><?php echo $order_id; ?></p>
                </div><?php
            }
        ?></div><?php
    }
?></div><?php 

get_footer();
?>