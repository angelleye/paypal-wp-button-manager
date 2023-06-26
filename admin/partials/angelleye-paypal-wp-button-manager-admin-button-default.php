<div class="paypal-ac-wordpress paypal-ac-new">
	<div class="paypal-wp-manager">
        <div class="paypal-wp-manager-left">
            <div class="paypal-wp-l-logo-details">
                <img width="159" height="188" src="<?php echo ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_IMAGE_PATH . 'logo.png'; ?>">
                <div class="paypal-wp-l-details">
                    <p><?php _e('To create a PayPal button, you need to first connect one or more PayPal accounts.', 'angelleye-paypal-wp-button-manager' ); ?>
                    <p><?php _e('Once connected, you can create a button and select the account to which it should be linked.', 'angelleye-paypal-wp-button-manager'); ?></p>
                    <p><?php echo sprintf( __('Ready to get started? <a href="%s">Click here</a> to connect your first PayPal account!', 'angelleye-paypal-wp-button-manager'), admin_url('admin.php?page=' . Angelleye_Paypal_Wp_Button_Manager_Company::$paypal_button_company_slug . '&type=new')); ?></p>
                </div>
            </div>
         	<div class="wave-vector-wordpress">
                <img width="164" height="140" src="<?php echo ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_IMAGE_PATH . 'vector-paypal-wordpress.png'; ?>">
            </div>
        </div><?php
        include_once( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH .'/admin/partials/angelleye-paypal-wp-button-manager-admin-help.php');
   	?></div>
</div>
<style>
    .wrap, #screen-meta-links {
        display: none;
    }
</style>