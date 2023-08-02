<div class="paypal-ac-wordpress"><?php
    include_once( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH .'/admin/partials/angelleye-paypal-wp-button-manager-admin-add-company.php');
    ?><div class="paypal-wp-manager">
        <div class="paypal-wp-manager-left">
            <div class="paypal-wp-l-logo-details">
                <img width="159" height="188" src="<?php echo ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_IMAGE_PATH . 'logo.png'; ?>">
                <div class="paypal-wp-l-details"><?php
                    if( !is_array( $products ) ){
                        ?><div class="paypal-payments">
                            <span class="paypal-payments-text"><?php echo angelleye_paypal_wp_button_manager_format_strings( $products ); ?>
                        </div><?php
                    } else {
                        foreach( $products as $product ){
                            $name = angelleye_paypal_wp_button_manager_format_strings( str_replace( '_', ' ', $product['name'] ) );
                            $capabilities = array_map( 'angelleye_paypal_wp_button_manager_format_strings', array_map( function( $element ){
                                    return str_replace('_', ' ', $element);
                                }, $product['capabilities'] ) 
                            );
                            $product_method = array($name, str_replace( '_', ' ', implode( ', ', $capabilities ) ) );
                            $product_method = array_filter( $product_method );
                            ?><div class="paypal-payments">
                                <span class="<?php echo in_array( $product['vetting_status'], array( 'SUBSCRIBED', 'ACTIVE') ) ? 'approved' : 'rejected'; ?>"></span>
                                <span class="paypal-payments-text"><?php echo implode(' - ', $product_method ); ?></span>
                            </div><?php
                        }
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