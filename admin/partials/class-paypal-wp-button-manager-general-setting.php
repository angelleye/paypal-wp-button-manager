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
            'title' => __('General Settings', 'paypal-wp-button-manager'),
            'type' => 'title',
            'id' => 'general_options_setting'
        );
        $fields[] = array(
            'title' => __('Debug Log', 'paypal-wp-button-manager'),
            'id' => 'log_enable_button_manager',
            'type' => 'checkbox',
            'label' => __('Enable logging', 'paypal-wp-button-manager'),
            'default' => 'no',
            'desc' => sprintf(__('Log PayPal WP Button Manager events in <code>%s</code>', 'paypal-wp-button-manager'), PAYPAL_WP_BUTTON_MANAGER_LOG_DIR)
        );
        $fields[] = array('type' => 'sectionend', 'id' => 'general_options_setting');
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
       
        <div class="div_general_settings">
        <div class="div_log_settings">
	        <form id="button_manager_integration_form_general" enctype="multipart/form-data" action="" method="post">
	            <?php $Html_output->init($genral_setting_fields); ?>
	            <p class="submit">
	                <input type="submit" name="paypal_intigration" class="button-primary" value="<?php esc_attr_e('Save Settings', 'Option'); ?>" />
	            </p>
	        </form>
        </div>
         <?php 
        
       
      	 $is_cancel = get_option('paypal_wp_button_cancel');
       if (isset($is_cancel) && empty($is_cancel)): ?>
                
        <div class="div_buymebeer">
    	 <a href="https://www.angelleye.com/product/buy-beer/?utm_source=paypal_wp_button_manager&utm_medium=buy_me_a_beer&utm_campaign=beer_me" target="_blank"><img src="<?php echo BMW_PLUGIN_URL ?>/admin/images/buy-us-a-beer.png" id="img_beer"/></a>

    	  <div class="div_cancel_donate">
        		<span class="button-primary btn_can_notice">Dismiss</span>
        </div>
    	 
    	 </div>
        
       
        <?php  endif; ?>
        </div>
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
