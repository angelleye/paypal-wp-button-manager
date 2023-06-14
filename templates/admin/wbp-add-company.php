<form method="POST" class="paypal-ac-setting" action="<?php echo admin_url('admin-post.php'); ?>">
    <input type="hidden" name="action" value="wbp_add_company">
    <div class="paypal-ac-heading"><?php _e('PayPal Account Settings','paypal-wp-button-manager'); ?></div>
    <div class="paypal-gp">
        <label class="PayPal-gp-left"><?php _e('PayPal Sandbox:','paypal-wp-button-manager'); ?></label>
        <div class="paypal-ac-checkbox">
            <div id="paypal-ac-type">
                <label class="checkbox" for="paypal-ac-type-cb">
                    <input type="checkbox" name="paypal_sandbox" id="paypal-ac-type-cb" <?php echo isset( $company ) ? 'disabled="disabled"' : ''; ?> <?php echo (isset( $company ) && $company->paypal_mode == 'sandbox') ? 'checked="checked"' : ''; ?>>
                    <span class="checkmark"></span>
                    <?php _e('Enable PayPal Sandbox','paypal-wp-button-manager'); ?>
                </label>
            </div>
        </div>
    </div>
    <div class="paypal-gp">
        <label class="PayPal-gp-left" for="paypal-company"><?php _e('Company Name:','paypal-wp-button-manager'); ?></label>
        <div class="paypal-ac-checkbox">
            <div id="paypal-ac-type">
                <input type="text" name="company_name" id="paypal-company" value="<?php echo isset( $company ) ? $company->company_name : ''; ?>" <?php echo isset( $company ) ? 'disabled="disabled"' : ''; ?>>
            </div>
        </div>
    </div>
    <div class="paypal-gp">
        <label class="PayPal-gp-left" for="paypal-contact"><?php _e('Contact Name:','paypal-wp-button-manager'); ?></label>
        <div class="paypal-ac-checkbox">
            <div id="paypal-ac-type">
                <input type="text" name="contact_name" id="paypal-contact" value="<?php echo isset( $company ) ? $company->paypal_person_name : ''; ?>" <?php echo isset( $company ) ? 'disabled="disabled"' : ''; ?>>
            </div>
        </div>
    </div>
    <div class="paypal-gp">
        <label class="PayPal-gp-left" for="paypal-country"><?php _e('Country: ','paypal-wp-button-manager'); ?></label>
        <div class="paypal-ac-checkbox">
            <div id="paypal-ac-type">
                <select name="country" id="paypal-country" <?php echo isset( $company ) ? 'disabled="disabled"' : ''; ?>>
                    <option value=""><?php _e('Please Select','paypal-wp-button-manager'); ?></option><?php
                    foreach (angelleye_paypal_wp_button_manager_get_countries() as $country_id => $country) {
                        ?><option value="<?php echo $country_id; ?>" <?php echo isset( $company ) ? selected( $country_id, $company->country, false ) : ''; ?>><?php echo $country; ?></option><?php
                    }
                ?></select>
            </div>
        </div>
    </div>
    <button name="save_paypal_ac_type" type="submit" class="paypal-ac-type-btn" <?php echo isset( $company ) ? 'disabled="disabled"' : ''; ?>><?php _e('Save', 'paypal-wp-button-manager'); ?></button>
    <div class="wave-vector">
        <img width="110" height="110" src="<?php echo WBP_IMAGE_PATH . 'vector-paypal.png'; ?>">
    </div>
</form>