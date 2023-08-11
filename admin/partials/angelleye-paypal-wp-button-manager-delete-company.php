<form method="POST" class="paypal-ac-setting" action="<?php echo admin_url('admin-post.php'); ?>">
    <input type="hidden" name="action" value="angelleye_paypal_wp_button_manager_admin_delete_company">
    <input type="hidden" name="company_id" value="<?php echo $company->ID; ?>">
    <div class="paypal-ac-heading"><?php _e('You have specified this account for deletion:', 'angelleye-paypal-wp-button-manager'); ?></div>
    <div class="paypal-gp">
        <label class="PayPal-gp-left"><?php _e('PayPal Sandbox:','angelleye-paypal-wp-button-manager'); ?></label>
        <div class="paypal-ac-checkbox">
            <div id="paypal-ac-type">
                <label class="checkbox" for="paypal-ac-type-cb">
                    <input type="checkbox" name="paypal_sandbox" id="paypal-ac-type-cb" <?php echo isset( $company ) ? 'disabled="disabled"' : ''; ?> <?php echo (isset( $company ) && $company->paypal_mode == 'sandbox') ? 'checked="checked"' : ''; ?>>
                    <span class="checkmark"></span>
                    <?php _e('Enable PayPal Sandbox','angelleye-paypal-wp-button-manager'); ?>
                </label>
            </div>
        </div>
    </div>
    <div class="paypal-gp">
        <label class="PayPal-gp-left" for="paypal-company"><?php _e('Account Name:','angelleye-paypal-wp-button-manager'); ?></label>
        <div class="paypal-ac-checkbox">
            <div id="paypal-ac-type">
                <input type="text" name="company_name" id="paypal-company" value="<?php echo isset( $company ) ? $company->company_name : ''; ?>" <?php echo isset( $company ) ? 'disabled="disabled"' : ''; ?>>
            </div>
        </div>
    </div>
    <div class="paypal-gp">
        <label class="PayPal-gp-left" for="paypal-contact"><?php _e('Contact Name:','angelleye-paypal-wp-button-manager'); ?></label>
        <div class="paypal-ac-checkbox">
            <div id="paypal-ac-type">
                <input type="text" name="contact_name" id="paypal-contact" value="<?php echo isset( $company ) ? $company->paypal_person_name : ''; ?>" <?php echo isset( $company ) ? 'disabled="disabled"' : ''; ?>>
            </div>
        </div>
    </div>
    <div class="paypal-gp">
        <label class="PayPal-gp-left" for="paypal-country"><?php _e('Country: ','angelleye-paypal-wp-button-manager'); ?></label>
        <div class="paypal-ac-checkbox">
            <div id="paypal-ac-type">
                <select name="country" id="paypal-country" <?php echo isset( $company ) ? 'disabled="disabled"' : ''; ?>>
                    <option value=""><?php _e('Please Select','angelleye-paypal-wp-button-manager'); ?></option><?php
                    foreach (angelleye_paypal_wp_button_manager_get_countries() as $country_id => $country) {
                        ?><option value="<?php echo $country_id; ?>" <?php echo isset( $company ) ? selected( $country_id, $company->country, false ) : ''; ?>><?php echo $country; ?></option><?php
                    }
                ?></select>
            </div>
        </div>
    </div>
    <p class="paypal-delete-guide"><?php _e("Deleting PayPal account will result in the loss of the buttons you have assigned to this account.",'angelleye-paypal-wp-button-manager'); ?></p>
    <p class="paypal-delete-guide"><?php _e("Please select the account below to which you want to re-assign the existing button of this account, or leave it unassigned if you don't wish to re-assign it.",'angelleye-paypal-wp-button-manager'); ?></p>
    <div class="paypal-gp">
        <label class="PayPal-gp-left" for="reassign-company"><?php _e('Assign To PayPal Account: ','angelleye-paypal-wp-button-manager'); ?></label>
        <div class="paypal-ac-checkbox">
            <div id="paypal-ac-type">
                <select name="reassign_company" id="reassign-company">
                    <option value=""><?php _e('Please Select','angelleye-paypal-wp-button-manager'); ?></option><?php
                    foreach ($companies as $company) {
                        ?><option value="<?php echo $company->ID; ?>"><?php echo $company->company_name; ?></option><?php
                    }
                ?></select>
            </div>
        </div>
    </div>
    <button name="delete_paypal_ac" type="submit" class="paypal-ac-type-btn"><?php _e('Delete Account', 'angelleye-paypal-wp-button-manager'); ?></button>
    <div class="wave-vector">
        <img width="110" height="110" src="<?php echo ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_IMAGE_PATH . 'vector-paypal.png'; ?>">
    </div>
</form>