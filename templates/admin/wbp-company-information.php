<div class="paypal-ac-wordpress"><?php
    include_once( WBP_PLUGIN_PATH .'/templates/admin/wbp-add-company.php');
    ?><div class="paypal-wp-manager">
        <div class="paypal-wp-manager-left">
            <div class="paypal-wp-l-logo-details">
                <img width="159" height="188" src="<?php echo WBP_IMAGE_PATH . 'logo.png'; ?>">
                <div class="paypal-wp-l-details"><?php
                    if( !is_array( $products ) ){
                        ?><div class="paypal-payments">
                            <span class="paypal-payments-text"><?php echo $products; ?>
                        </div><?php
                    } else {
                        foreach( $products as $product ){
                            $product_method = array( str_replace( '_', ' ', $product['name'] ), str_replace( '_', ' ', implode( ', ', $product['capabilities'] ) ) );
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
                <img width="164" height="140" src="<?php echo WBP_IMAGE_PATH . 'vector-paypal-wordpress.png'; ?>">
            </div>
        </div><?php
        include_once( WBP_PLUGIN_PATH .'/templates/admin/wbp-help.php');
    ?></div>
</div>