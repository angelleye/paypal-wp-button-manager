<?php

/**
 * This class defines all code necessary to General Setting from admin side
 * @class       AngellEYE_PayPal_WP_Button_Manager_General_Setting
 * @version	1.0.0
 * @package		paypal-wp-button-manager/includes
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_General_Setting {

    /**
     * Hook in methods
     * @since    0.1.0
     * @access   static
     */
    public static function init() {

        add_action('paypal_wp_button_manager_general_setting', array(__CLASS__, 'paypal_wp_button_manager_general_setting'));
        add_action('paypal_wp_button_manager_general_setting_save_field', array(__CLASS__, 'paypal_wp_button_manager_general_setting_save_field'));
    }

    /**
     * paypal_wp_button_manager_general_setting_save_field function used for save general setting field value
     * @since 1.0.0
     * @access public static
     * 
     */
    public static function paypal_wp_button_manager_general_setting_fields() {

        $fields[] = array(
            'title' => __('PayPal API Credentials (Sandbox)', 'paypal-wp-button-manager'),
            'type' => 'title',
            'desc' => __('Use the PayPal sandbox to create fully functional test buttons. You will need to <a target="_blank" href="http://developer.paypal.com">create a developer account with PayPal</a> in order to create and use sandbox accounts for testing purposes.', 'paypal-wp-button-manager'),
            'id' => 'general_options_sandbox'
        );

        $fields[] = array(
            'title' => __('Sandbox / Test Mode', 'paypal-wp-button-manager'),
            'desc' => __("Enable this to create buttons in the PayPal sandbox for testing purposes.", 'paypal-wp-button-manager'),
            'id' => 'enable_sandbox',
            'type' => 'checkbox',
        );

        $fields[] = array(
            'title' => __('API Username', 'paypal-wp-button-manager'),
            'desc' => '',
            'id' => 'paypal_api_username_sandbox',
            'type' => 'text',
            'css' => 'min-width:300px;',
        );
        $fields[] = array(
            'title' => __('API Password', 'paypal-wp-button-manager'),
            'desc' => '',
            'id' => 'paypal_password_sandbox',
            'type' => 'password',
            'css' => 'min-width:300px;',
        );
        $fields[] = array(
            'title' => __('API Signature', 'paypal-wp-button-manager'),
            'desc' => '',
            'id' => 'paypal_signature_sandbox',
            'type' => 'text',
            'css' => 'min-width:300px;',
        );

        $fields[] = array('type' => 'sectionend', 'id' => 'general_options_sandbox');

        $fields[] = array(
            'title' => __('PayPal API Credentials (Live)', 'paypal-wp-button-manager'),
            'type' => 'title',
            'desc' => __('<a target="_blank" href="https://www.paypal-partners.com/assets/30?locale=en">Log in to this tool</a> with your PayPal account to quickly obtain your API credentials.', 'paypal-wp-button-manager'),
            'id' => 'general_options_live'
        );

        $fields[] = array(
            'title' => __('API Username', 'paypal-wp-button-manager'),
            'desc' => '',
            'id' => 'paypal_api_username_live',
            'type' => 'text',
            'css' => 'min-width:300px;',
        );
        $fields[] = array(
            'title' => __('API Password', 'paypal-wp-button-manager'),
            'desc' => '',
            'id' => 'paypal_password_live',
            'type' => 'password',
            'css' => 'min-width:300px;',
        );
        $fields[] = array(
            'title' => __('API Signature', 'paypal-wp-button-manager'),
            'desc' => '',
            'id' => 'paypal_signature_live',
            'type' => 'text',
            'css' => 'min-width:300px;',
        );

        $fields[] = array(
            'title' => __('Debug Log', 'paypal-wp-button-manager'),
            'id' => 'log_enable_button_manager',
            'type' => 'checkbox',
            'label' => __('Enable logging', 'paypal-wp-button-manager'),
            'default' => 'no',
            'desc' => sprintf(__('Log PayPal WP Button Manager events in <code>%s</code>', 'paypal-wp-button-manager'), PAYPAL_WP_BUTTON_MANAGER_LOG_DIR)
        );

        $fields[] = array('type' => 'sectionend', 'id' => 'general_options_live');

        return $fields;
    }

    /**
     * paypal_wp_button_manager_general_setting function used for submit form of settings
     * @since 1.0.0
     * @access public
     */
    public static function paypal_wp_button_manager_general_setting() {

        $genral_setting_fields = self::paypal_wp_button_manager_general_setting_fields();
        $Html_output = new AngellEYE_PayPal_WP_Button_Manager_Html_output();
        ?>
        <form id="button_manager_integration_form" enctype="multipart/form-data" action="" method="post">
            <?php $Html_output->init($genral_setting_fields); ?>
            <p class="submit">
                <input type="submit" name="paypal_intigration" class="button-primary" value="<?php esc_attr_e('Save changes', 'Option'); ?>" />
            </p>
        </form>
        <?php
    }

    /**
     * paypal_wp_button_manager_general_setting function used for display general setting block from admin side
     * @since    0.1.0
     * @access   public
     */
    public static function paypal_wp_button_manager_general_setting_save_field() {
        $paypalapi_setting_fields = self::paypal_wp_button_manager_general_setting_fields();
        $Html_output = new AngellEYE_PayPal_WP_Button_Manager_Html_output();
        $Html_output->save_fields($paypalapi_setting_fields);
        if (isset($_POST['paypal_intigration'])):
            ?>
            <div id="setting-error-settings_updated" class="updated settings-error"> 
                <p><?php echo '<strong>' . __('Settings were saved successfully.', 'paypal-wp-button-manager') . '</strong>'; ?></p></div>

            <?php
        endif;
    }

}

AngellEYE_PayPal_WP_Button_Manager_General_Setting::init();
