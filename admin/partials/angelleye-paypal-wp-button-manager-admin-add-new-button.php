<div class="paypal-ac-wordpress paypal-ac-new">
	<div class="paypal-wp-manager">
        <div class="paypal-wp-manager-left">
            <div class="paypal-wp-l-logo-details">
                <img width="159" height="188" src="<?php echo ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_IMAGE_PATH . 'logo.png'; ?>">
                <div class="paypal-wp-l-details">
                	<a class="paypal-add-company button" href="<?php echo admin_url('admin.php?page=' . self::$paypal_button_company_slug . '&type=new'); ?>"><?php _e('Add Company','angelleye-paypal-wp-button-manager'); ?></a>
                </div>
            </div>
         	<div class="wave-vector-wordpress">
                <img width="164" height="140" src="<?php echo ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_IMAGE_PATH . 'vector-paypal-wordpress.png'; ?>">
            </div>
        </div><?php
        include_once( ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH .'/admin/partials/angelleye-paypal-wp-button-manager-admin-help.php');
   	?></div>
</div>