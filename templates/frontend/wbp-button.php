<div id="form-<?php echo $button_id; ?>" data-id="<?php echo $button_id; ?>" class="wbp-form"><?php
	?><div class="item-name-details">
		<label class="item-name-label"><?php _e("Item Name:", "paypal-wp-button-manager") ?></label>
		<p class="item-name"><?php echo $button->get_item_name(); ?></p>
	</div>
	
	<div class="price-currency">
		<div class="price">
			<label class="item-price-label"><?php _e("Price: ", "paypal-wp-button-manager"); ?></label>
			<p class="item-price"><?php echo angelleye_paypal_button_manager_get_price_html( $button->get_price(), $button->get_currency() ); ?></p>
		</div>
	</div><?php

	if( !empty( $button->get_shipping_amount() ) ){
		?><div class="shipping-rate">
			<label class="shipping-rate-label"><?php _e("Shipping Rate:", "paypal-wp-button-manager"); ?></label>
			<p class="shipping"><?php echo angelleye_paypal_button_manager_get_price_html( $button->get_shipping_amount(), $button->get_currency() ); ?></p>
		</div><?php 
	} 

	$tax = !empty( $button->get_tax_rate() ) ? $button->get_tax_total() : 0;
	$total_amount = $button->get_total();

	if( !empty( $button->get_tax_rate() ) ){
		?><div class="tax-rate">
			<label class="tax-rate-label"><?php echo sprintf( __("Tax (%s%%): ", "paypal-wp-button-manager"), $button->get_tax_rate() ); ?></label>
			<p class="tax-amount"><?php echo angelleye_paypal_button_manager_get_price_html( $tax, $button->get_currency() ); ?></p>
		</div><?php 
	}

	?><div>
		<label class="total-amount-label"><?php _e("Total Amount:", "paypal-wp-button-manager"); ?></label>
		<p class="total-amount"><?php echo angelleye_paypal_button_manager_get_price_html($total_amount, $button->get_currency() ) ?></p>
	</div>
	<div id="wbp-button-<?php echo $button_id; ?>" class="wbp-button" data-button_id="<?php echo $button_id; ?>"></div>
</div>