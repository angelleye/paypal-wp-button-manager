<div id="form-<?php echo $button_id; ?>" data-id="<?php echo $button_id; ?>" class="wbp-form"><?php
	if($button->get_button_type() == 'services' && $button->is_data_fields_hidden() !== 'yes') {
		?><div class="item-name-details" style="<?php echo (!empty($button->right_background_color())) ? 'background: '.$button->right_background_color() : '' ?>; <?php echo (!empty($button->right_foreground_color())) ? 'color: '.$button->right_foreground_color() : ''; ?>">
			<label class="item-name-label" style="<?php echo (!empty($button->left_background_color())) ? 'background: '.$button->left_background_color() : '' ?>; <?php echo (!empty($button->left_foreground_color())) ? 'color: '.$button->left_foreground_color() : ''; ?>"><?php _e("Item Name:", "angelleye-paypal-wp-button-manager") ?></label>
			<p class="item-name" style="<?php echo (!empty($button->right_background_color())) ? 'background: '.$button->right_background_color() : '' ?>; <?php echo (!empty($button->right_foreground_color())) ? 'color: '.$button->right_foreground_color() : ''; ?>"><?php echo $button->get_item_name(); ?></p>
		</div>
		
		<div class="price-currency" style="<?php echo (!empty($button->right_background_color())) ? 'background: '.$button->right_background_color() : '' ?>; <?php echo (!empty($button->right_foreground_color())) ? 'color: '.$button->right_foreground_color() : ''; ?>">
			<div class="price">
				<label class="item-price-label" style="<?php echo (!empty($button->left_background_color())) ? 'background: '.$button->left_background_color() : '' ?>; <?php echo (!empty($button->left_foreground_color())) ? 'color: '.$button->left_foreground_color() : ''; ?>"><?php _e("Price: ", "angelleye-paypal-wp-button-manager"); ?></label>
				<p class="item-price"><?php echo angelleye_paypal_button_manager_get_price_html( $button->get_price(), $button->get_currency() ); ?></p>
			</div>
		</div><?php

		if( !empty( $button->get_shipping_amount() ) ){
			?><div class="shipping-rate" style="<?php echo (!empty($button->right_background_color())) ? 'background: '.$button->right_background_color() : '' ?>; <?php echo (!empty($button->right_foreground_color())) ? 'color: '.$button->right_foreground_color() : ''; ?>">
				<label class="shipping-rate-label" style="<?php echo (!empty($button->left_background_color())) ? 'background: '.$button->left_background_color() : '' ?>; <?php echo (!empty($button->left_foreground_color())) ? 'color: '.$button->left_foreground_color() : ''; ?>"><?php _e("Shipping Rate:", "angelleye-paypal-wp-button-manager"); ?></label>
				<p class="shipping"><?php echo angelleye_paypal_button_manager_get_price_html( $button->get_shipping_amount(), $button->get_currency() ); ?></p>
			</div><?php 
		} 

		$tax = !empty( $button->get_tax_rate() ) ? $button->get_tax_total() : 0;
		$total_amount = $button->get_total();

		if( !empty( $button->get_tax_rate() ) ){
			?><div class="tax-rate" style="<?php echo (!empty($button->right_background_color())) ? 'background: '.$button->right_background_color() : '' ?>; <?php echo (!empty($button->right_foreground_color())) ? 'color: '.$button->right_foreground_color() : ''; ?>">
				<label class="tax-rate-label" style="<?php echo (!empty($button->left_background_color())) ? 'background: '.$button->left_background_color() : '' ?>; <?php echo (!empty($button->left_foreground_color())) ? 'color: '.$button->left_foreground_color() : ''; ?>"><?php echo sprintf( __("Tax (%s%%): ", "angelleye-paypal-wp-button-manager"), $button->get_tax_rate() ); ?></label>
				<p class="tax-amount"><?php echo angelleye_paypal_button_manager_get_price_html( $tax, $button->get_currency() ); ?></p>
			</div><?php 
		}

		?><div style="<?php echo (!empty($button->right_background_color())) ? 'background: '.$button->right_background_color() : '' ?>; <?php echo (!empty($button->right_foreground_color())) ? 'color: '.$button->right_foreground_color() : ''; ?>">
			<label class="total-amount-label" style="<?php echo (!empty($button->left_background_color())) ? 'background: '.$button->left_background_color() : '' ?>; <?php echo (!empty($button->left_foreground_color())) ? 'color: '.$button->left_foreground_color() : ''; ?>"><?php _e("Total Amount:", "angelleye-paypal-wp-button-manager"); ?></label>
			<p class="total-amount"><?php echo angelleye_paypal_button_manager_get_price_html($total_amount, $button->get_currency() ) ?></p>
		</div><?php
	}
	?><div id="wbp-button-<?php echo $button_id; ?>" class="wbp-button" data-button_id="<?php echo $button_id; ?>"></div>
</div>