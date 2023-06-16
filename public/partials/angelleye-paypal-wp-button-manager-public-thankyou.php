<?php
get_header();

?><div class="order-message order-<?php echo $success ? 'success' : 'failed'; ?>">
    <div class="message"><?php echo $success ? __('Your order received successfully.','angelleye-paypal-wp-button-manager') : $message; ?></div><?php
    if( $success ){
        ?><div class="order_id"><?php echo sprintf(__( 'Transaction ID: %s', 'angelleye-paypal-wp-button-manager'), $order_id); ?></div><?php
    }
?></div><?php 

get_footer();
?>