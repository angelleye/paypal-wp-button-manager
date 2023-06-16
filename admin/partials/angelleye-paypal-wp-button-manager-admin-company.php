<div class="paypal-ac-wordpress"><?php
    include_once( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH .'/admin/partials/angelleye-paypal-wp-button-manager-admin-add-company.php');
    ?><div class="paypal-wp-manager">
        <div class="paypal-wp-manager-left">
            <div class="paypal-wp-l-logo-details">
                <img width="159" height="188" src="<?php echo ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_IMAGE_PATH . 'logo.png'; ?>">
                <div class="paypal-wp-l-details">
                    <div class="paypal-ac-heading"><?php _e('Welcome To The Most PayPal WP Button Manager','angelleye-paypal-wp-button-manager'); ?></div>
                    <p><?php _e('Boost average order totals and conversion rates with<br>
                    PayPal Checkout, PayPal Credit, Buy Now Pay Later, Venmo, and more!<br>
                    All for a total fee of only 3.59% + 49¢</p>
                    <p>Save money on Visa/MasterCard/Discover transactions with a total fee of only 2.69% + 49¢','angelleye-paypal-wp-button-manager'); ?></p><?php
                    if( isset( $redirect_url ) && is_wp_error( $redirect_url ) ){
                        ?><span class="signup-url-error"><?php echo $redirect_url->get_error_message(); ?></span><?php
                    } else {
                        ?><a data-paypal-button="true" class="b-btn <?php echo isset( $redirect_url ) ? 'active' : ''; ?>" href="<?php echo isset( $redirect_url ) ? $redirect_url . '&displayMode=minibrowser' : 'javascript:void(0)'; ?>" target="PPFrame"><?php _e('Begin Now','angelleye-paypal-wp-button-manager'); ?><img src="<?php echo ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_IMAGE_PATH . 'arrow.png'; ?>"></a><?php
                    }
                ?></div>

            </div>
            <div class="wave-vector-wordpress">
                <img width="164" height="140" src="<?php echo ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_IMAGE_PATH . 'vector-paypal-wordpress.png'; ?>">
            </div>
        </div><?php
        include_once( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH .'/admin/partials/angelleye-paypal-wp-button-manager-admin-help.php');
    ?></div>
</div>