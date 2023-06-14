<?php
// Add a nonce field so we can check for it later.
wp_nonce_field( 'paypal_button_settings', 'paypal_button_settings_nonce' );
?><div class="paypal-button-generator-form">
    <div class="btn-type-payment-details">
        <div class="col-md-12">
            <div class="form-pd">
                <div class="row">
                    <div class="col-md-12">
                        <label for="company_id"><?php _e("Choose Company Name:", "paypal-wp-button-manager");?>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <select class="form-control select-company" name="company_id" id="company_id" required>
                            <option value=""><?php _e("Select Company", "paypal-wp-button-manager"); ?></option><?php 
                            foreach ($companies as $company) {
                                ?><option value="<?php echo $company->ID; ?>"<?php selected($company->ID, $button->get_company_id( 'edit' ) ); ?>><?php _e($company->company_name, "paypal-wp-button-manager") ?></option><?php 
                            }
                        ?></select>
                    </div>
                </div>
            </div>
        </div>
        <div id="stepOne" class="panel panel-primary">
            <div class="panel-body" id="collapseOne">
                <div class="row" class="row">
                    <div class="btn_type col-md-12">
                        <div class="form-pd">
                            <label for="button_type"><?php _e("Choose a button type", "paypal-wp-button-manager") ?></label>
                            <select class="form-control button-type" name="button_type" id="button_type">
                                <option value="services" <?php selected('services', $button->get_button_type( 'edit' ) ); ?>><?php _e("Buy Now", "paypal-wp-button-manager"); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-3" class="item-details">
                    <div class="col-md-6">
                        <div class="form-pd">
                            <label for="item-name"><?php _e("Item Name", "paypal-wp-button-manager") ?></label>
                            <input type="text" name="product_name" id="item-name" class="form-control" value="<?php echo $button->get_item_name( 'edit' ); ?>" maxlength="100" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-pd">
                            <label for="item-id"><?php _e("Item ID", "paypal-wp-button-manager") ?></label>
                            <input type="text" name="product_id" id="item-id" class="form-control" value="<?php echo $button->get_item_id( 'edit' ); ?>">
                        </div>
                    </div>
                </div>
                <div class="row mb-3" class="item-details-meta">
                    <div class="col-md-6">
                        <div class="form-pd">
                            <label for="item-price"><?php _e("Price", "paypal-wp-button-manager") ?></label>
                            <input type="number" min="0" name="item_price" id="item-price" class="form-control" value="<?php echo $button->get_price( 'edit' ); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-pd">
                            <label for="item_price_currency"><?php _e("Currency", "paypal-wp-button-manager") ?></label>
                            <select class="form-control currency" name="item_price_currency" id="item_price_currency" required><?php 
                            foreach($currencies as $currency){
                                ?><option value="<?php echo $currency['value']; ?>" <?php selected( $currency['value'], $button->get_currency( 'edit' ) ) ?>><?php echo $currency['value']; ?></option><?php
                            }
                            ?></select>
                        </div>
                    </div>
                </div>
                <div class="shipping-tax row">
                    <div class="shipping col-md-6">
                        <div class="form-pd">
                            <h5><?php _e("Shipping", "paypal-wp-button-manager", "paypal-wp-button-manager") ?></h5>
                            <p><label><?php _e("Use specific amount: (<span class=shipping-currency>USD</span>)", "paypal-wp-button-manager") ?></label>
                            </p>
                            <input type="number" min="0" class="shipping-amount form-control" name="item_shipping_amount" value="<?php echo $button->get_shipping_amount(); ?>">
                        </div>
                    </div>
                    <div class="tax col-md-6">
                        <div class="form-pd">
                            <h5><?php _e("Tax", "paypal-wp-button-manager", "paypal-wp-button-manager") ?></h5>
                            <p><label><?php _e("Use tax rate: (%)", "paypal-wp-button-manager") ?></label></p>
                            <input type="number" min="0" class="text-rate form-control" name="item_tax_rate" value="<?php echo $button->get_tax_rate(); ?>">
                        </div>
                    </div>
                </div>
                <div class="row customization">
                    <div class="customize-button col-md-6">
                        <div class="customize-row">
                            <div class="form-pd"><p class="btn-text"><?php _e("Customize Button", "paypal-wp-button-manager"); ?></p></div>
                            <div class="form-group">
                                <div class="form-pd">
                                    <label for="wbp-button-layout"><?php _e('Button Layout','paypal-wp-button-manager'); ?></label>
                                    <select name="wbp-button-layout" id="wbp-button-layout" class="form-control wbp-field">
                                        <option value="horizontal" <?php selected('horizontal',$button->get_button_layout( 'edit' ) ); ?>><?php _e('Horizontal (Recommended)', 'paypal-wp-button-manager'); ?></option>
                                        <option value="vertical" <?php selected('vertical',$button->get_button_layout( 'edit' ) ); ?>><?php _e('Vertical','paypal-wp-button-manager'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                               <div class="form-pd">
                                    <label for="wbp-button-color"><?php _e('Button Color','paypal-wp-button-manager'); ?></label>
                                    <select name="wbp-button-color" id="wbp-button-color" class="form-control wbp-field">
                                        <option value="gold" <?php selected('gold',$button->get_button_color( 'edit' ) ); ?>><?php _e('Gold (Recommended)', 'paypal-wp-button-manager'); ?></option>
                                        <option value="blue" <?php selected('blue',$button->get_button_color( 'edit' ) ); ?>><?php _e('Blue','paypal-wp-button-manager'); ?></option>
                                        <option value="silver" <?php selected('silver',$button->get_button_color( 'edit' ) ); ?>><?php _e('Silver','paypal-wp-button-manager'); ?></option>
                                        <option value="white" <?php selected('white',$button->get_button_color( 'edit' ) ); ?>><?php _e('White','paypal-wp-button-manager'); ?></option>
                                        <option value="black" <?php selected('black',$button->get_button_color( 'edit' ) ); ?>><?php _e('Black','paypal-wp-button-manager'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-pd">
                                    <label for="wbp-button-shape"><?php _e('Button Shape','paypal-wp-button-manager'); ?></label>
                                    <select name="wbp-button-shape" id="wbp-button-shape" class="form-control wbp-field">
                                        <option value="rect" <?php selected('rect',$button->get_button_shape( 'edit' ) ); ?>><?php _e('Rect (Recommended)', 'paypal-wp-button-manager'); ?></option>
                                        <option value="pill" <?php selected('pill',$button->get_button_shape( 'edit' ) ); ?>><?php _e('Pill','paypal-wp-button-manager'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-pd">
                                    <label for="wbp-button-size"><?php _e('Button Size','paypal-wp-button-manager'); ?></label>
                                    <select name="wbp-button-size" id="wbp-button-size" class="form-control wbp-field">
                                        <option value="responsive" <?php selected('responsive',$button->get_button_size( 'edit' ) ); ?>><?php _e('Responsive (Recommended)', 'paypal-wp-button-manager'); ?></option>
                                        <option value="small" <?php selected('small',$button->get_button_size( 'edit' ) ); ?>><?php _e('Small','paypal-wp-button-manager'); ?></option>
                                        <option value="medium" <?php selected('medium',$button->get_button_size( 'edit' ) ); ?>><?php _e('Medium','paypal-wp-button-manager'); ?></option>
                                        <option value="large" <?php selected('large',$button->get_button_size( 'edit' ) ); ?>><?php _e('Large','paypal-wp-button-manager'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-pd">
                                    <label for="wbp-button-height"><?php _e('Button Height','paypal-wp-button-manager'); ?></label>
                                    <select name="wbp-button-height" id="wbp-button-height" class="form-control wbp-field">
                                        <option value="" <?php selected('',$button->get_button_height( 'edit' ) ); ?>><?php _e('Default Height (Recommended)', 'paypal-wp-button-manager'); ?></option><?php
                                        for( $i=25; $i<=55; $i++ ){
                                            ?><option value="<?php echo $i; ?>" <?php selected($i,$button->get_button_height( 'edit' ) ); ?>><?php echo sprintf( __('%d px','paypal-wp-button-manager'), $i); ?></option><?php
                                        }
                                ?></select>
                                 </div>
                            </div>
                            <div class="form-group">
                                <div class="form-pd">
                                    <label for="wbp-button-label"><?php _e('Button Label','paypal-wp-button-manager'); ?></label>
                                    <select name="wbp-button-label" id="wbp-button-label" class="form-control wbp-field">
                                        <option value="paypal" <?php selected('paypal',$button->get_button_label( 'edit' ) ); ?>><?php _e('PayPal (Recommended)', 'paypal-wp-button-manager'); ?></option>
                                        <option value="checkout" <?php selected('checkout',$button->get_button_label( 'edit' ) ); ?>><?php _e('Checkout','paypal-wp-button-manager'); ?></option>
                                        <option value="buynow" <?php selected('buynow',$button->get_button_label( 'edit' ) ); ?>><?php _e('Buy Now','paypal-wp-button-manager'); ?></option>
                                        <option value="pay" <?php selected('pay',$button->get_button_label( 'edit' ) ); ?>><?php _e('Pay','paypal-wp-button-manager'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="wbp-tagline-field">
                                <div class="form-pd">
                                    <label for="wbp-button-tagline"><?php _e('Enable Tag Line','paypal-wp-button-manager'); ?></label>
                                    <select name="wbp-button-tagline" id="wbp-button-tagline" class="form-control wbp-field">
                                        <option value="false" <?php selected('false',$button->get_button_tagline( 'edit' ) ); ?>><?php _e('No', 'paypal-wp-button-manager'); ?></option>
                                        <option value="true" <?php selected('true',$button->get_button_tagline( 'edit' ) ); ?>><?php _e('Yes','paypal-wp-button-manager'); ?></option>
                                    </select>
                                </div>
                            </div>
                             <div class="form-group wbp-hide-funding">
                                <div class="form-pd">
                                    <label for="wbp-button-hide-funding"><?php _e('Hide Funding Methods','paypal-wp-button-manager'); ?></label>
                                    <select name="wbp-button-hide-funding[]" id="wbp-button-hide-funding" class="form-control" multiple="multiple">
                                        <option value="card" <?php echo in_array( 'card', $button->get_hide_funding_method() ) ? 'selected="selected"' : ""; ?>><?php _e('Credit or Debit Card','paypal-wp-button-manager'); ?></option>
                                        <option value="credit" <?php echo in_array( 'credit', $button->get_hide_funding_method() ) ? 'selected="selected"' : ""; ?>><?php _e('PayPal Credit','paypal-wp-button-manager'); ?></option>
                                        <option value="paylater" <?php echo in_array( 'paylater', $button->get_hide_funding_method() ) ? 'selected="selected"' : ""; ?>><?php _e('Pay Later','paypal-wp-button-manager'); ?></option>
                                        <option value="bancontact" <?php echo in_array( 'bancontact', $button->get_hide_funding_method() ) ? 'selected="selected"' : ""; ?>><?php _e('Bancontact','paypal-wp-button-manager'); ?></option>
                                        <option value="blik" <?php echo in_array( 'blik', $button->get_hide_funding_method() ) ? 'selected="selected"' : ""; ?>><?php _e('BLIK','paypal-wp-button-manager'); ?></option>
                                        <option value="eps" <?php echo in_array( 'eps', $button->get_hide_funding_method() ) ? 'selected="selected"' : ""; ?>><?php _e('eps','paypal-wp-button-manager'); ?></option>
                                        <option value="giropay" <?php echo in_array( 'giropay', $button->get_hide_funding_method() ) ? 'selected="selected"' : ""; ?>><?php _e('giropay','paypal-wp-button-manager'); ?></option>
                                        <option value="ideal" <?php echo in_array( 'ideal', $button->get_hide_funding_method() ) ? 'selected="selected"' : ""; ?>><?php _e('iDEAL','paypal-wp-button-manager'); ?></option>
                                        <option value="mercadopago" <?php echo in_array( 'mercadopago', $button->get_hide_funding_method() ) ? 'selected="selected"' : ""; ?>><?php _e('Mercado Pago','paypal-wp-button-manager'); ?></option>
                                        <option value="mybank" <?php echo in_array( 'mybank', $button->get_hide_funding_method() ) ? 'selected="selected"' : ""; ?>><?php _e('MyBank','paypal-wp-button-manager'); ?></option>
                                        <option value="p24" <?php echo in_array( 'p24', $button->get_hide_funding_method() ) ? 'selected="selected"' : ""; ?>><?php _e('Przelewy24','paypal-wp-button-manager'); ?></option>
                                        <option value="sepa" <?php echo in_array( 'sepa', $button->get_hide_funding_method() ) ? 'selected="selected"' : ""; ?>><?php _e('SEPA-Lastschrift','paypal-wp-button-manager'); ?></option>
                                        <option value="sofort" <?php echo in_array( 'sofort', $button->get_hide_funding_method() ) ? 'selected="selected"' : ""; ?>><?php _e('Sofort','paypal-wp-button-manager'); ?></option>
                                        <option value="venmo" <?php echo in_array( 'venmo', $button->get_hide_funding_method() ) ? 'selected="selected"' : ""; ?>><?php _e('Venmo','paypal-wp-button-manager'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="customer-view col-md-6">
                        <div class="form-pd">
                            <p class="btn-text"><?php  _e("Your customer's view", "paypal-wp-button-manager"); ?></p>
                            <div id="wbp-paypal-button"></div>
                            <div class="wbp-funding-method-message"><?php _e('Hiding the funding method will not change the preview here but it will be hidden for the customer.','paypal-wp-button-manager'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>