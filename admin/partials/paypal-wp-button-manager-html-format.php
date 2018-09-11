<?php

/**
 * This class defines all code necessary to button generator interface
 * @class       AngellEYE_PayPal_WP_Button_Manager_button_interface
 * @version	1.0.0
 * @package		paypal-wp-button-manager/partials
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_button_interface {

    /**
     * Hook in methods
     * @since    0.1.0
     * @access   static
     */
    public static function init() {
        add_action('paypal_wp_button_manager_interface', array(__CLASS__, 'paypal_wp_button_manager_for_wordpress_button_interface_html'));
        add_action('paypal_wp_button_manager_before_interface', array(__CLASS__, 'paypal_wp_button_manager_for_wordpress_button_interface_html_before'));
        add_action('admin_head', array(__CLASS__, 'paypal_wp_button_manager_hide_update_metabox'));
        add_action('wp_ajax_save_non_hosted_button_snippet', array(__CLASS__, 'save_non_hosted_button_snippet'));
        add_action("wp_ajax_nopriv_save_non_hosted_button_snippet", array(__CLASS__, 'save_non_hosted_button_snippet'));
    }

    public static function save_non_hosted_button_snippet(){
        if(isset($_POST['textarea_snippet']) && !empty($_POST['textarea_snippet'])){
            $paypal_button_html = update_post_meta($_POST['post_id'], 'paypal_button_response',wp_kses_post($_POST['textarea_snippet']));
            echo json_encode(array('success'=>'true'));
            exit;
        }
    }

    public static function paypal_wp_button_manager_for_wordpress_button_interface_html_before($string_param) {
            global $wpdb;
        ?> 
         <?php 
            if($string_param=='edit'){
                $post_id = get_the_ID();
                $meta = get_post_meta($post_id);
                if(isset($meta['paypal_wp_button_manager_company_rel'][0])){
                    $edit_button_param_company_id=$meta['paypal_wp_button_manager_company_rel'][0];
                    if(!isset($meta['paypal_wp_button_manager_button_id'])){
                         $paypal_button_html = get_post_meta($post_id, 'paypal_button_response', true);
                        ?>
                        <table class="tbl_shortcode">
                            <tr>
                                <td class="td_title">
                                    <?php echo _e('You can edit values for non-hosted button.', 'paypal-wp-button-manager'); ?>
                                    <a class="btn btn-primary btn-sm" id="non_hosted_button_edit"><?php _e('Edit','paypal-wp-button-manager')?></a>
                                    <a class="btn btn-primary btn-sm hidden" data-postid="<?php echo $post_id; ?>" id="non_hosted_button_save"><?php _e('Save','paypal-wp-button-manager')?></a>
                                    <a class="btn btn-primary btn-sm hidden" id="non_hosted_button_cancel"><?php _e('Cancel','paypal-wp-button-manager')?></a>
                                </td>
                            </tr>                            
                            <tr>
                                <td><textarea  id="snippet_textarea" readonly="readonly" class="wp-ui-text-highlight code txtarea_response" cols="70" rows="10"><?php echo $paypal_button_html; ?></textarea></td>
                            </tr>
                            <tr>
                                <td class="alert alert-info"><?php echo _e('Non-Hosted buttons are not saved on PayPal so that you just need to edit "Value" attribute in above snippet.', 'paypal-wp-button-manager'); ?>
                                    <a href="https://developer.paypal.com/docs/classic/paypal-payments-standard/integration-guide/Appx_websitestandard_htmlvariables/" target="_blank"><?php _e('More info',''); ?></a>    
                                </td>
                            </tr>
                            
                        </table>
                        <?php                        
                        exit;    
                    }   
                }
                else{
                    exit;
                }
            }
        ?>
        <div class="div_companies_dropdown col-lg-4" >
            <div class="div_companyname form-group">                               
                <?php
                    if($string_param=='edit'){
                        $companies = $wpdb->prefix . 'paypal_wp_button_manager_companies'; // do not forget about tables prefix
                        $result_records = $wpdb->get_results("SELECT title FROM `{$companies}` WHERE paypal_mode !='' AND ID={$edit_button_param_company_id}", ARRAY_A);
                    ?>
                        <label for="paypalcompanyname" class="control-label"><strong><?php echo esc_html__('Company Name:','paypal-wp-button-manager');?></strong></label>
                        <label><?php echo $result_records[0]['title']; ?></label>
                        <input type="hidden" name="ddl_companyname" value="<?php echo $edit_button_param_company_id; ?>">
                    <?php
                    }
                    else{
                        $companies = $wpdb->prefix . 'paypal_wp_button_manager_companies'; // do not forget about tables prefix
                        $result_records = $wpdb->get_results("SELECT * FROM `{$companies}` WHERE paypal_mode !=''", ARRAY_A); 
                ?>
                <label for="paypalcompanyname" class="control-label"><strong><?php echo esc_html__('Choose Company Name:','paypal-wp-button-manager');?></strong></label>
                <select id="ddl_companyname" name="ddl_companyname" class="form-control">
                    <option value=""><?php echo esc_html__('--Select Company--','paypal-wp-button-manager'); ?></option>
                    <?php foreach ($result_records as $result_records_value) { ?>                        
                        <option value="<?php echo $result_records_value['ID']; ?>"><?php echo $result_records_value['title']; ?></option>
                    <?php }
                    ?>
                </select>
                <?php } ?>
            </div>
        </div>
        <?php
    }
    
    public static function paypal_wp_button_manager_hide_update_metabox(){        
        if( isset($_REQUEST['post']) && get_post_type(sanitize_key($_REQUEST['post'])) === 'paypal_buttons'){
            if(isset($_REQUEST['action']) && isset($_REQUEST['view']) && sanitize_key($_REQUEST['action']) === 'edit' && sanitize_key($_REQUEST['view']) =='true'){
                ?>
                <style>
                    #side-sortables { display: none; }
                </style>
                <?php
            }
            $paypal_button_id = get_post_meta(sanitize_key($_REQUEST['post']), 'paypal_wp_button_manager_button_id', true);                                    
            if(isset($_REQUEST['action']) && sanitize_key($_REQUEST['action'])=== 'edit' && empty($paypal_button_id)){
                ?>
                <style>
                    #side-sortables { display: none; }
                </style>
                <?php
            }
            
        }
    }

    /**
     * paypal_wp_button_manager_for_wordpress_button_interface_html function is for
     * html of interface.
     * @since 1.0.0
     * @access public
     */
    public static function paypal_wp_button_manager_for_wordpress_button_interface_html($string) {
// Define below variable that will not conflict/gives undefined index error while add/edit buttons.
        $button_option_value = '';
        $edit_button = false;
        $no_note = 0;
        $shippingYes = 'checked';
        $shippingNo = '';
        $cancleFormcontrol = '';
        $cancellationCheckbox = '';
        $successfulCheckbox = '';
        $returnFormcontrol = '';
        $button_img_src = '';
        $paypalButtonSection_class = 'opened';
        $customButtonSection_class = 'hide';
        $donation_name = '';
        $donation_id = '';
        $donation_currency = '';
        $buttonImageSize = '';
        $buttonImageUrl = '';
        $donation_amount = '';
        $account_id = '';
        $customersShippingAddress = '';
        $cancel_return = '';
        $return = '';
        $add_special_instruction = '';
        $add_special_instruction_place_holder = __('Add special instructions to the seller:','paypal-wp-button-manager');
        $enableHostedButtons_checkbox = '';
        $track_inv = '';
        $track_pnl = '';
        $item_number_step2 = '';
        $item_qty_step2 = '';
        $item_alert_step2 = '';
        $item_cost_step2 = '';
        $item_soldout_url_step2 = '';
        $radioAddToCartButton = 'checked';
        $radioBuyNowButton = '';
        $product_name = '';
        $product_id = '';
        $item_price = '';
        $item_price_currency = 'USD';
        $item_shipping_amount = '';
        $itemTaxRate = '';
        $optionname = array();
        $optionselect = array();
        $buttonLanguage = '';
        $buttonCountry = '';
        $byOptionTableBody_class='accessAid';
        $trackByItem_checkbox='checked';
        $trackByOption_checkbox='';
        $byItemTableBody_class='';
        $byOptionTableBody_class='';
        $subscription_name = '';
        $subscription_id = '';
        $subscriptionBillingAmount ='';
        $subscription_billing_cycle_number ='';
        $subscription_billing_cycle_period = '';
        $subscription_billing_limit = '';
        $subscription_trial_rate ='';
        $subscription_trial_duration = '';
        $subscription_trial_duration_type = '';
        $subscription_trial_2_rate = '';
        $subscription_trial_2_duration = '';
        $subscription_trial_2_duration_type = '';
        $subscribe_text='';
        $buynowtext='BUYNOW';
        
        if ($string == 'edit') {

            $enableHostedButtons_checkbox = 'disabled';
            $edit_button = true;
            $meta = get_post_meta(get_the_ID());
            $edit_hosted_button_id = $meta['paypal_wp_button_manager_button_id'][0];
            $edit_button_company_id=$meta['paypal_wp_button_manager_company_rel'][0];
            $payapal_helper = new AngellEYE_PayPal_WP_Button_Manager_PayPal_Helper();
            $PayPalConfig = $payapal_helper->paypal_wp_button_manager_get_paypalconfig($edit_button_company_id);
            $PayPal = new Angelleye_PayPal($PayPalConfig);
            $button_details_array = $PayPal->BMGetButtonDetails($edit_hosted_button_id);
            foreach ($button_details_array as $key => $value) {
                $btnvar_key = explode('BUTTONVAR', $key);
                if ($btnvar_key[0] == 'L_') {
                    $btnvar_val = explode('=', $value);
                    $BUTTONVAR[substr($btnvar_val[0], 1)] = substr($btnvar_val[1], 0, -1);
                }

                $option0price_key = explode('OPTION0PRICE', $key);
                if ($option0price_key[0] == 'L_') {
                    $OPTION0PRICE[] = substr(substr($value, 1), 0, -1);
                }

                $textbox0_key = explode('TEXTBOX', $key);
                if ($textbox0_key[0] == 'L_') {
                    $TEXTBOX[] = $value;
                }
            }

            /* START section to get values from dropdown of the customizzation section */

            for ($k = 0; $k < 5; $k++) {
                if (array_key_exists('OPTION' . $k . 'NAME', $button_details_array)) {
                    $optionname[] = substr(substr($button_details_array['OPTION' . $k . 'NAME'], 1), 0, -1);
                    if (array_key_exists('L_OPTION' . $k . 'PRICE0', $button_details_array)) {
                        for ($j = 0; $j < 10; $j++) {
                            if (array_key_exists('L_OPTION' . $k . 'PRICE' . $j, $button_details_array)) {
                                $optionprice[$k][] = substr(substr($button_details_array['L_OPTION' . $k . 'PRICE' . $j], 1), 0, -1);
                            }
                        }
                    }
                    for ($i = 0; $i < 10; $i++) {
                        if (array_key_exists('L_OPTION' . $k . 'SELECT' . $i, $button_details_array)) {
                            $optionselect[$k][] = substr(substr($button_details_array['L_OPTION' . $k . 'SELECT' . $i], 1), 0, -1);
                        }
                    }
                }
            }
            /* END of section to get values from dropdown of the customizzation section */
           
            $buttonType = isset($button_details_array['BUTTONTYPE']) ? $button_details_array['BUTTONTYPE'] : '';
            $buttonCountry = isset($button_details_array['BUTTONCOUNTRY']) ? $button_details_array['BUTTONCOUNTRY'] : '';
            $buttonLanguage = isset($button_details_array['BUTTONLANGUAGE']) ? $button_details_array['BUTTONLANGUAGE'] : '';
            $buttonImageSize = isset($button_details_array['BUTTONIMAGE']) ? $button_details_array['BUTTONIMAGE'] : '';
            $buttonImageUrl = isset($button_details_array['BUTTONIMAGEURL']) ? $button_details_array['BUTTONIMAGEURL'] : '';

            $account_id = isset($BUTTONVAR['business']) ? $BUTTONVAR['business'] : '';
            $no_note = isset($BUTTONVAR['no_note']) ? $BUTTONVAR['no_note'] : '';
            $add_special_instruction = isset($BUTTONVAR['cn']) ? $BUTTONVAR['cn'] : '';
            $customersShippingAddress = isset($BUTTONVAR['no_shipping']) ? $BUTTONVAR['no_shipping'] : '';
            $cancel_return = isset($BUTTONVAR['cancel_return']) ? $BUTTONVAR['cancel_return'] : '';
            $return = isset($BUTTONVAR['return']) ? $BUTTONVAR['return'] : '';



            if ($buttonType == 'DONATE') {
                $button_option_value = 'donations';
                $donation_name = isset($BUTTONVAR['item_name']) ? $BUTTONVAR['item_name'] : '';
                $donation_id = isset($BUTTONVAR['item_number']) ? $BUTTONVAR['item_number'] : '';
                $donation_amount = isset($BUTTONVAR['amount']) ? $BUTTONVAR['amount'] : '';
                $donation_currency = isset($BUTTONVAR['currency_code']) ? $BUTTONVAR['currency_code'] : '';
            }
            
            if($buttonType=='SUBSCRIBE'){
                $button_option_value = 'subscriptions';
                $subscription_name = isset($BUTTONVAR['item_name']) ? $BUTTONVAR['item_name'] : '';
                $subscription_id = isset($BUTTONVAR['item_number']) ? $BUTTONVAR['item_number'] : '';
                $item_price_currency = isset($BUTTONVAR['currency_code']) ? $BUTTONVAR['currency_code'] : '';
                $subscriptionBillingAmount = isset($BUTTONVAR['a3']) ? $BUTTONVAR['a3'] : '';
                $subscription_billing_cycle_number = isset($BUTTONVAR['p3']) ? $BUTTONVAR['p3'] : '';
                $subscription_billing_cycle_period = isset($BUTTONVAR['t3']) ? $BUTTONVAR['t3'] : '';
                $subscription_billing_limit = isset($BUTTONVAR['srt']) ? $BUTTONVAR['srt'] : '';
                
                $subscription_trial_rate = isset($BUTTONVAR['a1']) ? $BUTTONVAR['a1'] : '';
                $subscription_trial_duration = isset($BUTTONVAR['p1']) ? $BUTTONVAR['p1'] : '';
                $subscription_trial_duration_type = isset($BUTTONVAR['t1']) ? $BUTTONVAR['t1'] : '';
                
                $subscription_trial_2_rate = isset($BUTTONVAR['a2']) ? $BUTTONVAR['a2'] : '';
                $subscription_trial_2_duration = isset($BUTTONVAR['p2']) ? $BUTTONVAR['p2'] : '';
                $subscription_trial_2_duration_type = isset($BUTTONVAR['t2']) ? $BUTTONVAR['t2'] : '';
                
                $subscribe_text= isset($button_details_array['SUBSCRIBETEXT']) ? $button_details_array['SUBSCRIBETEXT'] : '' ;
            }

            if($buttonType=='ADDCART'){
                $button_option_value = 'products';
                $product_name = isset($BUTTONVAR['item_name']) ? $BUTTONVAR['item_name'] : '';
                $product_id = isset($BUTTONVAR['item_number']) ? $BUTTONVAR['item_number'] : '';
                $item_price = isset($BUTTONVAR['amount']) ? $BUTTONVAR['amount'] : '';
                $item_price_currency = isset($BUTTONVAR['currency_code']) ? $BUTTONVAR['currency_code'] : '';
                $item_shipping_amount = isset($BUTTONVAR['shipping']) ? $BUTTONVAR['shipping'] : '';
                $itemTaxRate = isset($BUTTONVAR['tax_rate']) ? $BUTTONVAR['tax_rate'] : '';
                $inventory_set = true;
                $DataArray = array();
                $PayPal_get_inventory = $PayPal->BMGetInventory($DataArray, $edit_hosted_button_id);
                
                if (isset($PayPal_get_inventory['ERRORS']) && !empty($PayPal_get_inventory['ERRORS'])) {
                    if ($PayPal_get_inventory['L_ERRORCODE0'] == '11991') {
                        $inventory_set = false;
                    }
                } else {
                    $track_inv = isset($PayPal_get_inventory['TRACKINV']) ? $PayPal_get_inventory['TRACKINV'] : '';
                    $track_pnl = isset($PayPal_get_inventory['TRACKPNL']) ? $PayPal_get_inventory['TRACKPNL'] : '';
                    $item_number_step2 = isset($PayPal_get_inventory['ITEMNUMBER']) ? $PayPal_get_inventory['ITEMNUMBER'] : '';
                    $item_qty_step2 = isset($PayPal_get_inventory['ITEMQTY']) ? $PayPal_get_inventory['ITEMQTY'] : '';
                    $item_alert_step2 = isset($PayPal_get_inventory['ITEMALERT']) ? $PayPal_get_inventory['ITEMALERT'] : '';
                    $item_cost_step2 = isset($PayPal_get_inventory['ITEMCOST']) ? $PayPal_get_inventory['ITEMCOST'] : '';
                    $item_soldout_url_step2 = isset($PayPal_get_inventory['SOLDOUTURL']) ? $PayPal_get_inventory['SOLDOUTURL'] : '';
                    
                    if(array_key_exists('OPTIONNAME', $PayPal_get_inventory)){
                        for($i=0;$i<10;$i++){
                             if(array_key_exists('L_OPTIONSELECT'.$i, $PayPal_get_inventory)){
                                $inv_optionselect[] = $PayPal_get_inventory['L_OPTIONSELECT'.$i];
                            }
                        }
                    }
                    if(array_key_exists('OPTIONNAME', $PayPal_get_inventory)){
                        for($i=0;$i<10;$i++){
                             if(array_key_exists('L_OPTIONQTY'.$i, $PayPal_get_inventory)){
                                $inv_optionqty[] = $PayPal_get_inventory['L_OPTIONQTY'.$i];
                            }
                        }
                    }
                    if(array_key_exists('OPTIONNAME', $PayPal_get_inventory)){
                        for($i=0;$i<10;$i++){
                             if(array_key_exists('L_OPTIONALERT'.$i, $PayPal_get_inventory)){
                                $inv_optionalert[] = $PayPal_get_inventory['L_OPTIONALERT'.$i];
                            }
                        }
                    }
                    
                    if(array_key_exists('OPTIONNAME', $PayPal_get_inventory)){
                        for($i=0;$i<10;$i++){
                             if(array_key_exists('L_OPTIONCOST'.$i, $PayPal_get_inventory)){
                                $inv_optioncost[] = $PayPal_get_inventory['L_OPTIONCOST'.$i];
                            }
                        }
                    }
                    
                    if(array_key_exists('OPTIONNAME', $PayPal_get_inventory)){
                        for($i=0;$i<10;$i++){
                             if(array_key_exists('L_OPTIONNUMBER'.$i, $PayPal_get_inventory)){
                                $inv_optionnumber[] = $PayPal_get_inventory['L_OPTIONNUMBER'.$i];
                            }
                        }
                    }
                    
                }
            }
            
            if ($buttonType == 'BUYNOW') {
                $button_option_value = 'services';
                $product_name = isset($BUTTONVAR['item_name']) ? $BUTTONVAR['item_name'] : '';
                $product_id = isset($BUTTONVAR['item_number']) ? $BUTTONVAR['item_number'] : '';
                $item_price = isset($BUTTONVAR['amount']) ? $BUTTONVAR['amount'] : '';
                $item_price_currency = isset($BUTTONVAR['currency_code']) ? $BUTTONVAR['currency_code'] : '';
                $item_shipping_amount = isset($BUTTONVAR['shipping']) ? $BUTTONVAR['shipping'] : '';
                $itemTaxRate = isset($BUTTONVAR['tax_rate']) ? $BUTTONVAR['tax_rate'] : '';
                $buynowtext = isset($button_details_array['BUYNOWTEXT']) ? $button_details_array['BUYNOWTEXT'] : '';
                $inventory_set = true;
                $DataArray = array();
                $PayPal_get_inventory = $PayPal->BMGetInventory($DataArray, $edit_hosted_button_id);
                
                if (isset($PayPal_get_inventory['ERRORS']) && !empty($PayPal_get_inventory['ERRORS'])) {
                    if ($PayPal_get_inventory['L_ERRORCODE0'] == '11991') {
                        $inventory_set = false;
                    }
                } else {
                    $track_inv = isset($PayPal_get_inventory['TRACKINV']) ? $PayPal_get_inventory['TRACKINV'] : '';
                    $track_pnl = isset($PayPal_get_inventory['TRACKPNL']) ? $PayPal_get_inventory['TRACKPNL'] : '';
                    $item_number_step2 = isset($PayPal_get_inventory['ITEMNUMBER']) ? $PayPal_get_inventory['ITEMNUMBER'] : '';
                    $item_qty_step2 = isset($PayPal_get_inventory['ITEMQTY']) ? $PayPal_get_inventory['ITEMQTY'] : '';
                    $item_alert_step2 = isset($PayPal_get_inventory['ITEMALERT']) ? $PayPal_get_inventory['ITEMALERT'] : '';
                    $item_cost_step2 = isset($PayPal_get_inventory['ITEMCOST']) ? $PayPal_get_inventory['ITEMCOST'] : '';
                    $item_soldout_url_step2 = isset($PayPal_get_inventory['SOLDOUTURL']) ? $PayPal_get_inventory['SOLDOUTURL'] : '';
                    
                    if(array_key_exists('OPTIONNAME', $PayPal_get_inventory)){
                        for($i=0;$i<10;$i++){
                             if(array_key_exists('L_OPTIONSELECT'.$i, $PayPal_get_inventory)){
                                $inv_optionselect[] = $PayPal_get_inventory['L_OPTIONSELECT'.$i];
                            }
                        }
                    }
                    if(array_key_exists('OPTIONNAME', $PayPal_get_inventory)){
                        for($i=0;$i<10;$i++){
                             if(array_key_exists('L_OPTIONQTY'.$i, $PayPal_get_inventory)){
                                $inv_optionqty[] = $PayPal_get_inventory['L_OPTIONQTY'.$i];
                            }
                        }
                    }
                    if(array_key_exists('OPTIONNAME', $PayPal_get_inventory)){
                        for($i=0;$i<10;$i++){
                             if(array_key_exists('L_OPTIONALERT'.$i, $PayPal_get_inventory)){
                                $inv_optionalert[] = $PayPal_get_inventory['L_OPTIONALERT'.$i];
                            }
                        }
                    }
                    
                    if(array_key_exists('OPTIONNAME', $PayPal_get_inventory)){
                        for($i=0;$i<10;$i++){
                             if(array_key_exists('L_OPTIONCOST'.$i, $PayPal_get_inventory)){
                                $inv_optioncost[] = $PayPal_get_inventory['L_OPTIONCOST'.$i];
                            }
                        }
                    }
                    
                    if(array_key_exists('OPTIONNAME', $PayPal_get_inventory)){
                        for($i=0;$i<10;$i++){
                             if(array_key_exists('L_OPTIONNUMBER'.$i, $PayPal_get_inventory)){
                                $inv_optionnumber[] = $PayPal_get_inventory['L_OPTIONNUMBER'.$i];
                            }
                        }
                    }
                    
                }
            }

            if ($buttonType == 'BUYNOW') {
                $radioBuyNowButton = 'checked';
            } else {
                $radioBuyNowButton = '';
            }

            if ($buttonType == 'ADDCART') {
                $radioAddToCartButton = 'checked';
            } else {
                $radioAddToCartButton = '';
            }
        }
        ?>
        <div id="wrap">
            <div id="main" class="legacyErrors">
                <div class="layout1">
                    <script type="text/javascript">var oPage = document.getElementById('main').getElementsByTagName('div')[0];var oContainer = document.createElement('div');oContainer.id = 'pageLoadMsg';oContainer.innerHTML = "Loading...";oPage.appendChild(oContainer);</script>
                    <div id="pageLoadMsg" class="accessAid"><?php echo esc_html__('Loading...','paypal-wp-button-manager'); ?></div>
                    <div class="accessAid" id="ddLightbox">
                        <div class="header">
                            <h2><?php echo esc_html__('Change dropdown','paypal-wp-button-manager'); ?></h2>
                        </div>
                        <div class="">
                            <p><?php echo esc_html__('You can assign inventory options in only one dropdown.','paypal-wp-button-manager'); ?><br><br><span id="lightboxChoiceBody"><?php echo esc_html__('Choose:','paypal-wp-button-manager'); ?></span></p>
                            <div class="buttons"><button class="default primary btn btn-primary" type="submit" id="ddLightboxSubmit" name="done"><?php echo esc_html__('Done','paypal-wp-button-manager'); ?></button>
                                <button class="closer btn btn-danger" type="button" id="ddLightboxCancel" name="cancel"><?php echo esc_html__('Cancel','paypal-wp-button-manager'); ?></button>
                            </div><br>
                        </div>
                    </div>                    
                
                    <noscript>&lt;h1&gt;Create PayPal payment button&lt;/h1&gt;&lt;p class="noScriptBlurb"&gt;This tool requires that you enable JavaScript in your browser. If you do not want to enable JavaScript, you can still use the older version of the tool.&lt;/p&gt;&lt;div id="noScriptButtons"&gt;&lt;div class="splitLeft"&gt;&lt;form method="post" id="newbfform" name="newbfform" action="https://www.paypal.com/us/cgi-bin/webscr?SESSION=r%2dJATqaKB0V%5fGgX3SFEuwzdHUx817cuxpfnzP7EgGCBmfAugmNortPmiPdi&amp;dispatch=5885d80a13c0db1f8e263663d3faee8de62a88b92df045c56447d40d60b23a7c"&gt;&lt;input type="hidden" id="CONTEXT_CGI_VAR" name="CONTEXT" value="X3&amp;#x2d;7SZn2ExXucINxlliZ&amp;#x5f;05NdFsrIIpaV9TcRYNLL&amp;#x5f;GiOwm9XgEZzWKQeV0"&gt;&lt;input type="hidden" id="cmd" name="cmd" value="_button-designer"&gt;&lt;h2&gt;New tool&lt;/h2&gt;&lt;ol&gt;&lt;li&gt;Enable JavaScript in your browser.&lt;/li&gt;&lt;li&gt;Click &lt;strong&gt;Go&lt;/strong&gt; below.&lt;/li&gt;&lt;/ol&gt;&lt;input class="button primary" type="submit" id="newBFButton" name="goto_new_BF" value="Go"&gt;&lt;input name="auth" type="hidden" value="A&amp;#x2d;rjNZhZLRt86QdaItbFAxbuyoRwDPaz&amp;#x2e;dCe2iQoCD7uF8ECex&amp;#x2d;ZSw9OPM48gvdgrXEkoaVqwAJFtLx1spKPOsUQFkgigL0Oz&amp;#x2e;FnzFLIbiDs"&gt;&lt;input name="form_charset" type="hidden" value="UTF&amp;#x2d;8"&gt;&lt;/form&gt;&lt;/div&gt;&lt;div class="splitDivider"&gt;&lt;span class="centeredText"&gt;or&lt;/span&gt;&lt;/div&gt;&lt;div class="splitRight"&gt;&lt;form method="post" id="oldbfform" name="oldbfform" action="https://www.paypal.com/us/cgi-bin/webscr?SESSION=r%2dJATqaKB0V%5fGgX3SFEuwzdHUx817cuxpfnzP7EgGCBmfAugmNortPmiPdi&amp;dispatch=5885d80a13c0db1f8e263663d3faee8de62a88b92df045c56447d40d60b23a7c"&gt;&lt;input type="hidden" id="CONTEXT_CGI_VAR" name="CONTEXT" value="X3&amp;#x2d;7SZn2ExXucINxlliZ&amp;#x5f;05NdFsrIIpaV9TcRYNLL&amp;#x5f;GiOwm9XgEZzWKQeV0"&gt;&lt;h2&gt;Older tool&lt;/h2&gt;&lt;ol&gt;&lt;li&gt;Select button type: &lt;br&gt;&lt;select name="cmd"&gt;&lt;option value="_web-tools"&gt;Buy Now&lt;/option&gt;&lt;option value="_cart-factory"&gt;Add to Cart&lt;/option&gt;&lt;option value="_xclick-donations-factory"&gt;Donate&lt;/option&gt;&lt;option value="_xclick-sub-factory"&gt;Subscribe&lt;/option&gt;&lt;option value="_xclick-gc-factory"&gt;Gift Certificate&lt;/option&gt;&lt;/select&gt;&lt;/li&gt;&lt;li&gt;Click &lt;strong&gt;Continue&lt;/strong&gt; below.&lt;/li&gt;&lt;/ol&gt;&lt;input class="tertiary" type="submit" id="oldBFButton" name="goto_old_BF" value="Continue"&gt;&lt;input name="auth" type="hidden" value="A&amp;#x2d;rjNZhZLRt86QdaItbFAxbuyoRwDPaz&amp;#x2e;dCe2iQoCD7uF8ECex&amp;#x2d;ZSw9OPM48gvdgrXEkoaVqwAJFtLx1spKPOsUQFkgigL0Oz&amp;#x2e;FnzFLIbiDs"&gt;&lt;input name="form_charset" type="hidden" value="UTF&amp;#x2d;8"&gt;&lt;/form&gt;&lt;/div&gt;&lt;/div&gt;</noscript>
                    <div id="">

                        <!--            <form method="post" id="buttonDesignerForm" name="buttonDesignerForm" action="">-->
                        <input type="hidden" id="CONTEXT_CGI_VAR" name="CONTEXT" value="X3-7SZn2ExXucINxlliZ_05NdFsrIIpaV9TcRYNLL_GiOwm9XgEZzWKQeV0">
                        <input type="hidden" id="cmd" name="cmd" value="_flow">
                        <input type="hidden" id="onboarding_cmd" name="onboarding_cmd" value="">
                        <input type="hidden" id="fakeSubmit" name="fakeSubmit" value="">
                        <input type="hidden" id="secureServerName" name="secureServerName" value="www.paypal.com/us">
                        <input type="hidden" id="selectedDropDown" name="selectedDropDown" value="">
                        <input type="hidden" name="button_type" value="<?php echo $button_option_value; ?>">
                        <?php if ($string == 'edit') { ?>
                            <input type="hidden" name="enable_hosted_buttons" value="enabled">
                        <?php } ?>    
                        <div id="accordion" class="panel-group"  role="tablist" aria-multiselectable="true">




                            <div id="stepOne" class="panel panel-primary">
                                <div class="header panel-heading" role="tab" id="headingOne">

                                    <?php echo '<h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="width: 100%;display: block;text-decoration: none;">' . __('Step 1: Choose a button type and enter your payment details') . '</a></h4>'; ?>
                                </div>                                                                                                
                                    <div id="collapseOne" class="panel-body panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                            <div class="group buttonType <?php
                                            if ($edit_button) {
                                                echo 'fadedOut';
                                            }
                                            ?>">
                                                <div class="col-lg-4">
                                                    <label for="buttonType" class="control-label"><?php echo esc_html__('Choose a button type','paypal-wp-button-manager'); ?></label>
                                                    <?php $paypal_button_options = get_paypal_button_options(); ?>
                                                    <select id="buttonType" name="button_type" class="form-control">
                                                        <?php foreach ($paypal_button_options as $paypal_button_options_key => $paypal_button_options_value) { ?>
                                                            <?php
                                                            if ($paypal_button_options_key == $button_option_value) {
                                                                $button_type_selected = 'selected';
                                                            } else {
                                                                $button_type_selected = '';
                                                            }
                                                            ?>
                                                            <option value="<?php echo $paypal_button_options_key; ?>" <?php echo $button_type_selected; ?>><?php echo $paypal_button_options_value; ?></option>
                                                        <?php } ?>

                                                    </select>                                                
                                                </div>        
                                            </div>
                                            <div class="products">                                                
                                                <input class="hide radio subButtonType" type="radio" id="radioAddToCartButton"  <?php echo $radioAddToCartButton; ?> name="sub_button_type" value="add_to_cart">
                                                <input class="hide radio subButtonType" type="radio" id="radioBuyNowButton" <?php echo $radioBuyNowButton; ?> name="sub_button_type" value="buy_now">
                                            </div>
                                            <div class="group details">
                                                <div class="products">
                                                    <div class="col-lg-4">
                                                        <label for="itemName" class="control-label"><?php echo esc_html__('Item name','paypal-wp-button-manager'); ?></label>
                                                        <input class="form-control" maxlength="127" type="text" id="itemName" name="product_name" value="<?php echo $product_name; ?>">
                                                    </div>
                                                    <div class="col-lg-4"><label for="itemID"><?php echo esc_html__('Item ID','paypal-wp-button-manager'); ?><span class="fieldNote"><?php echo esc_html__('(optional)','paypal-wp-button-manager'); ?></span></label><input class="form-control" maxlength="127" type="text" id="itemID" size="9" name="product_id" value="<?php echo $product_id; ?>"></div>
                                                </div>
                                                <div class="donations accessAid fadedOut">
                                                    <div class="col-lg-4"><label for="donationName" class="control-label"><?php echo esc_html__('Organization name/service','paypal-wp-button-manager'); ?></label><input class="form-control" maxlength="127" type="text" id="donationName" name="donation_name" value="<?php echo $donation_name; ?>" disabled=""></div>
                                                    <div class="col-lg-4"><label for="donationID" class="control-label"><?php echo esc_html__('Donation ID','paypal-wp-button-manager'); ?><span class="fieldNote"> <?php echo esc_html__('(optional)','paypal-wp-button-manager'); ?></span>
                                                        </label>
                                                        <input class="form-control" maxlength="127" type="text" id="donationID" size="27" name="donation_id" value="<?php echo $donation_id; ?>" disabled=""></div>
                                                </div>
                                                <div class="subscriptions accessAid fadedOut">
                                                    <div class="col-lg-4"><label for="subscriptionName" class="control-label"><?php echo esc_html__('Item name','paypal-wp-button-manager'); ?></label><input class="form-control" maxlength="127" type="text" id="subscriptionName" name="subscription_name" value="<?php echo $subscription_name; ?>" disabled=""></div>
                                                    <div class="col-lg-4"><label for="subscriptionID" class="control-label"><?php echo esc_html__('Subscription ID','paypal-wp-button-manager'); ?><span class="fieldNote"> <?php echo esc_html__('(optional)','paypal-wp-button-manager'); ?> </span></label><input class="form-control" maxlength="127" type="text" id="subscriptionID" size="27" name="subscription_id" value="<?php echo $subscription_id; ?>" disabled=""></div>
                                                </div>
                                                
                                            </div>
                                            <div class="group products pricing opened">
                                                <div class="col-lg-4"><label for="itemPrice" class="control-label"><?php echo esc_html__('Price','paypal-wp-button-manager'); ?></label><input class="form-control" type="text" id="itemPrice" size="9" name="item_price" value="<?php echo $item_price; ?>"></div>
                                                <div class="col-lg-4">
                                                    <label for="itemPriceCurrency" class="control-label"><?php echo esc_html__('Currency','paypal-wp-button-manager'); ?></label>
                                                    <?php $paypal_button_currency_with_symbole = get_paypal_button_currency_with_symbole(); ?>
                                                    <select id="BillingAmountCurrency" name="item_price_currency" class="currencySelect form-control">

                                                        <?php foreach ($paypal_button_currency_with_symbole as $paypal_button_currency_with_symbole_key => $paypal_button_currency_with_symbole_value) { ?>
                                                            <?php
                                                            if ($paypal_button_currency_with_symbole_key == $item_price_currency) {
                                                                $item_currency_selected = 'selected';
                                                            } else {
                                                                $item_currency_selected = '';
                                                            }
                                                            ?>
                                                            <option value="<?php echo $paypal_button_currency_with_symbole_key; ?>" <?php echo $item_currency_selected; ?> title="<?php echo $paypal_button_currency_with_symbole_value; ?>"><?php echo $paypal_button_currency_with_symbole_key; ?></option>
                                                        <?php } ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="group subscriptions accessAid fadedOut col-lg-4">
                                                <label for="subscriptionBillingAmountCurrency" class="control-label"><?php echo esc_html__('Currency','paypal-wp-button-manager'); ?></label>
                                                <?php $paypal_button_currency = get_paypal_button_currency(); ?>
                                                <select id="subscriptionBillingAmountCurrency" name="item_price_currency" class="currencySelect form-control" disabled="">
                                                    <?php foreach ($paypal_button_currency as $paypal_button_currency_key => $paypal_button_currency_value) { ?>
                                                        <option value="<?php echo $paypal_button_currency_value; ?>" title="<?php echo $paypal_button_options_key; ?>"><?php echo $paypal_button_currency_value; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>                                                    
                                            <div class="group outerContainer" id="sBox">
                                                <div class="customizeButtonSection">
                                                    <div class="borderBox">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p class="heading"><strong><?php echo esc_html__('Customize button','paypal-wp-button-manager'); ?></strong></p>
                                                            </div>
                                                        </div>

                                                        <div id="customizeSection">
                                                            <?php
                                                            if (!empty($optionprice) && $string == 'edit') {
                                                                $dropdownPrice_checkbox = 'checked';
                                                                $savedDropdownPriceSection_class = 'opened';
                                                                $dropdown_price_title_input = $optionname[0];
                                                                $dropdown_price_title_disabled = '';
                                                            } else {
                                                                $dropdownPrice_checkbox = '';
                                                                $savedDropdownPriceSection_class = 'hide';
                                                                $dropdown_price_title_input = '';
                                                                $dropdown_price_title_disabled = 'disabled';
                                                            }
                                                            ?>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p id="addDropdownPrice" class="hideShow opened">
                                                                        <label for="dropdownPrice" class="control-label">
                                                                            <input class="checkbox form-control" type="checkbox" id="dropdownPrice" name="dropdown_price" value="createdDropdownPrice" <?php echo $dropdownPrice_checkbox; ?>>
                                                                            <span class="products"><?php echo esc_html__('Add drop-down menu with price/option ','paypal-wp-button-manager'); ?></span>
                                                                            <span class="subscriptions accessAid fadedOut"><?php echo esc_html__('Add a dropdown menu with prices and options','paypal-wp-button-manager'); ?></span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div id="dropdownPriceSection" class="hideShow accessAid hide">
                                                                <p class="title dropdownPriceTitle col-md-9">
                                                                    <label for="dropdownPriceTitle" class="control-label">
                                                                        <span class="products"><?php echo esc_html__('Name of drop-down menu (ex.: "Colors," "Sizes")','paypal-wp-button-manager'); ?></span>
                                                                        <span class="subscriptions accessAid fadedOut"><?php echo esc_html__('Description (For example, "Payment options".)','paypal-wp-button-manager'); ?></span>
                                                                    </label>
                                                                    <input class="text form-control" maxlength="64" type="text" id="dropdownPriceTitle" <?php echo $dropdown_price_title_disabled; ?> name="dropdown_price_title" value="<?php echo $dropdown_price_title_input; ?>" required="required">
                                                                </p>
                                                                <p><label class="optionNameLbl control-label" for=""><span class="products"><?php echo esc_html__('Menu option name','paypal-wp-button-manager'); ?></span><span class="subscriptions accessAid fadedOut"><?php echo esc_html__('Menu Name','paypal-wp-button-manager'); ?></span></label><label class="optionPriceLbl control-label" for="optionPrice"><span class="products"><?php echo esc_html__('Price','paypal-wp-button-manager'); ?></span><span class="subscriptions accessAid fadedOut"><?php echo esc_html__('Amount','paypal-wp-button-manager'); ?> (<span class="currencyLabel control-label"><?php echo $item_price_currency; ?></span>)</span></label><label class="optionCurrencyLbl control-label" for="optionCurrency"><span class="products"><?php echo esc_html__('Currency','paypal-wp-button-manager'); ?></span><span class="subscriptions accessAid fadedOut control-label"><?php echo esc_html__('Frequency','paypal-wp-button-manager'); ?></span></label></p>
                                                                <div id="optionsPriceContainer">
                                                                    <?php                                                                    
                                                                    $optionselectcount = isset($optionprice[0]) && count($optionprice[0]) > 0 ? count($optionprice[0]) : 0;
                                                                    if(!empty($optionprice[0])){
                                                                        $ddp_option_name_disabled='';
                                                                    }
                                                                    else{
                                                                        $ddp_option_name_disabled='disabled';
                                                                    }
                                                                    $index = 0;
                                                                    do {
                                                                        ?>
                                                                        <p class="optionRow col-sm-12 form-inline">
                                                                            <input maxlength="64" type="text" class="ddpOptionName text form-control"  name="ddp_option_name" value="<?php echo isset($optionselect[0])? $optionselect[0][$index] : "Option 1";?>" <?php echo $ddp_option_name_disabled; ?>>
                                                                            <?php ?>
                                                                            <input type="text" class="ddpOptionPrice text form-control" name="ddp_option_price" value="<?php echo isset($optionprice[0])? $optionprice[0][$index] : "1.00";?>">
                                                                            <?php
                                                                            $paypal_button_currency = get_paypal_button_currency();
                                                                            if ($index == 0) {
                                                                                ?>
                                                                                <select class="ddpOptionCurrency show form-control" name="ddp_option_currency">
                                                                                    <?php foreach ($paypal_button_currency as $paypal_button_currency_key => $paypal_button_currency_value) { ?>
                                                                                        <?php
                                                                                        if ($item_price_currency == $paypal_button_currency_value) {
                                                                                            $ddpOptionCurrency_selected = 'selected';
                                                                                        } else {
                                                                                            $ddpOptionCurrency_selected = '';
                                                                                        }
                                                                                        ?>
                                                                                        <option value="<?php echo $paypal_button_currency_value; ?>" title="<?php echo $paypal_button_options_key; ?>" <?php echo $ddpOptionCurrency_selected; ?>><?php echo $paypal_button_currency_value; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                                <?php
                                                                            } else {
                                                                                ?>
                                                                                <label class="ddpOptionCurrency show control-label" for=""><?php echo $item_price_currency; ?></label>
                                                                                <?php
                                                                            }
                                                                            $paypal_button_subscriptions = get_paypal_button_subscriptions();
                                                                            ?>

                                                                            <select class="subscriptions ddpOptionFrequency form-control" name="ddp_option_frequency" disabled="">
                                                                                <?php foreach ($paypal_button_subscriptions as $paypal_button_subscriptions_key => $paypal_button_subscriptions_value) { ?>
                                                                                    <option value="<?php echo $paypal_button_subscriptions_key; ?>"><?php echo $paypal_button_subscriptions_value; ?></option>
                                                                                <?php } ?>
                                                                            </select>

                                                                        </p>
                                                                        <?php
                                                                        $index++;
                                                                    } while ($index < $optionselectcount);
                                                                    if(isset($optionselect[0]))
                                                                    foreach ($optionselect[0] as $row_option) {
                                                                        break;
                                                                        ?>
                                                                        <p class="optionRow col-sm-12 form-inline">
                                                                            <input maxlength="64" type="text" class="ddpOptionName text form-control" disabled="" name="ddp_option_name" value="<?php echo $row_option; ?>">
                                                                            <input type="text" class="ddpOptionPrice text form-control" disabled="" name="ddp_option_price" value="<?php echo $optionprice[0][$index]; ?>">
                                                                            <?php
                                                                            $paypal_button_currency = get_paypal_button_currency();
                                                                            if ($index == 0) {
                                                                                ?>
                                                                                <select class="ddpOptionCurrency show form-control" name="ddp_option_currency">
                                                                                    <?php foreach ($paypal_button_currency as $paypal_button_currency_key => $paypal_button_currency_value) { ?>
                                                                                        <?php
                                                                                        if ($item_price_currency == $paypal_button_currency_value) {
                                                                                            $ddpOptionCurrency_selected = 'selected';
                                                                                        } else {
                                                                                            $ddpOptionCurrency_selected = '';
                                                                                        }
                                                                                        ?>
                                                                                        <option value="<?php echo $paypal_button_currency_value; ?>" title="<?php echo $paypal_button_options_key; ?>" <?php echo $ddpOptionCurrency_selected; ?>><?php echo $paypal_button_currency_value; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                                <?php
                                                                            } else {
                                                                                ?>
                                                                                <label class="ddpOptionCurrency show control-label" for=""><?php echo $item_price_currency; ?></label>
                                                                                <?php
                                                                            }
                                                                            $paypal_button_subscriptions = get_paypal_button_subscriptions();
                                                                            ?>

                                                                            <select class="subscriptions ddpOptionFrequency form-control" name="ddp_option_frequency" disabled="">
                                                                                <?php foreach ($paypal_button_subscriptions as $paypal_button_subscriptions_key => $paypal_button_subscriptions_value) { ?>
                                                                                    <option value="<?php echo $paypal_button_subscriptions_key; ?>"><?php echo $paypal_button_subscriptions_value; ?></option>
                                                                                <?php } ?>
                                                                            </select>

                                                                        </p>
                                                                        <?php
                                                                        $index++;
                                                                    }
                                                                    ?>
                                    <!--<p class="optionRow clearfix">
                                        <input maxlength="64" type="text" class="ddpOptionName text form-control" disabled="" name="ddp_option_name" value="Option 2">
                                        <input type="text" class="ddpOptionPrice text form-control" disabled="" name="ddp_option_price" value=""><label class="ddpOptionCurrency show control-label" for="">USD</label>

                                        <select class="subscriptions ddpOptionFrequency accessAid fadedOut hide form-control" name="ddp_option_frequency" disabled="">
                                                                    <?php foreach ($paypal_button_subscriptions as $paypal_button_subscriptions_key => $paypal_button_subscriptions_value) { ?>
                                                                                <option value="<?php echo $paypal_button_subscriptions_key; ?>"><?php echo $paypal_button_subscriptions_value; ?></option>
                                                                    <?php } ?>
                                        </select>
                                    </p>
                                    <p class="optionRow clearfix">
                                        <input maxlength="64" type="text" class="ddpOptionName text form-control" disabled="" name="ddp_option_name" value="Option 3"><input type="text" class="ddpOptionPrice text form-control" disabled="" name="ddp_option_price" value=""><label class="ddpOptionCurrency show control-label" for="">USD</label>

                                        <select class="subscriptions ddpOptionFrequency accessAid fadedOut hide form-control" name="ddp_option_frequency" disabled="">
                                                                    <?php foreach ($paypal_button_subscriptions as $paypal_button_subscriptions_key => $paypal_button_subscriptions_value) { ?>
                                                                                <option value="<?php echo $paypal_button_subscriptions_key; ?>"><?php echo $paypal_button_subscriptions_value; ?></option>
                                                                    <?php } ?>
                                        </select>
                                    </p> -->
                                                                </div>
                                                                <p class="moreOptionsLink">
                                                                    <a id="addOptionPrice" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span></a>
                                                                    <a id="removeOptionPrice" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=" class="btn btn-danger"><span class="glyphicon glyphicon-remove-sign"></span></a></p>
                                                                <p class="saveCancel"><input class="btn btn-default" type="submit" id="saveOptionPrice" name="save_option_price" value="Done" alt="Done"><a id="cancelOptionPrice" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=" class="btn btn-danger"><?php echo esc_html__('Cancel','paypal-wp-button-manager'); ?></a></p>
                                                            </div>
                                                            <div id="savedDropdownPriceSection" class="hideShow accessAid <?php echo $savedDropdownPriceSection_class; ?>">
                                                                <p><label id="savedDropdownPrice" for="" style="font-size: 12px;font-weight: 500;"><?php
                                                                        if(isset($optionname[0])){
                                                                            echo $optionname[0] . ': ';
                                                                            echo implode(', ', $optionselect[0]);
                                                                        }                                                                        
                                                                        ?></label></p>
                                                                <p class="editDelete"><a id="editDropdownPrice" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=" class="btn btn-sm btn-info"><span class="products"><?php echo esc_html__('Edit','paypal-wp-button-manager'); ?></span><span class="subscriptions accessAid fadedOut"><?php echo esc_html__('Change','paypal-wp-button-manager'); ?></span></a>&nbsp;|&nbsp;<a id="deleteDropdownPrice" class="btn btn-sm btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><span class="products"><?php echo esc_html__('Delete','paypal-wp-button-manager'); ?></span><span class="subscriptions accessAid fadedOut glyphicon glyphicon-remove"></span></a></p>
                                                            </div>


                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p id="addDropdown" class="hideShow opened">
                                                                        <?php
                                                                        if (isset($optionprice[0]) && !empty($optionprice[0])) {
                                                                            $t = 1;
                                                                        } else {
                                                                            $t = 0;
                                                                        }

                                                                        if (isset($optionname[0 + $t])) {
                                                                            $savedDropdownSection1_class = 'opened';
                                                                            $dropdown1_title = $optionname[0 + $t];
                                                                            $dropdown1_title_disabled = '';
                                                                            $dropdown_checkbox = 'checked';
                                                                            $previewDropdownSection1_label = $optionname[0 + $t];
                                                                            $previewDropdownSection1_class = 'opened';
                                                                        } else {
                                                                            $savedDropdownSection1_class = 'hide';
                                                                            $dropdown1_title = '';
                                                                            $dropdown1_title_disabled = 'disabled';
                                                                            $dropdown_checkbox = '';
                                                                            $previewDropdownSection1_label = 'Dropdown Title';
                                                                            $previewDropdownSection1_class = 'hide';
                                                                        }

                                                                        if (isset($optionname[1 + $t])) {
                                                                            $savedDropdownSection2_class = 'opened';
                                                                            $dropdown2_title = $optionname[1 + $t];
                                                                            $dropdown2_title_disabled = '';
                                                                            $previewDropdownSection2_label = $optionname[1 + $t];
                                                                            $previewDropdownSection2_class = 'opened';
                                                                        } else {
                                                                            $savedDropdownSection2_class = 'hide';
                                                                            $dropdown2_title = '';
                                                                            $dropdown2_title_disabled = 'disabled';
                                                                            $previewDropdownSection2_label = 'Dropdown Title';
                                                                            $previewDropdownSection2_class = 'hide';
                                                                        }

                                                                        if (isset($optionname[2 + $t])) {
                                                                            $savedDropdownSection3_class = 'opened';
                                                                            $dropdown3_title = $optionname[2 + $t];
                                                                            $dropdown3_title_disabled = '';
                                                                            $previewDropdownSection3_label = $optionname[2 + $t];
                                                                            $previewDropdownSection3_class = 'opened';
                                                                        } else {
                                                                            $savedDropdownSection3_class = 'hide';
                                                                            $dropdown3_title = '';
                                                                            $dropdown3_title_disabled = 'disabled';
                                                                            $previewDropdownSection3_label = 'Dropdown Title';
                                                                            $previewDropdownSection3_class = 'hide';
                                                                        }

                                                                        if (isset($optionname[3 + $t])) {
                                                                            $savedDropdownSection4_class = 'opened';
                                                                            $dropdown4_title = $optionname[3 + $t];
                                                                            $dropdown4_title_disabled = '';
                                                                            $previewDropdownSection4_label = $optionname[3 + $t];
                                                                            $previewDropdownSection4_class = 'opened';
                                                                        } else {
                                                                            $savedDropdownSection4_class = 'hide';
                                                                            $dropdown4_title = '';
                                                                            $dropdown4_title_disabled = 'disabled';
                                                                            $previewDropdownSection4_label = 'Dropdown Title';
                                                                            $previewDropdownSection4_class = 'hide';
                                                                        }
                                                                        ?>
                                                                        <label for="dropdown" class="control-label">
                                                                            <input class="checkbox form-control" type="checkbox" id="dropdown" name="dropdown" value="createdDropdown" <?php echo $dropdown_checkbox; ?>>
                                                                            <span class="hideShow accessAid hide" id="dropDownLabelForSubscription"><?php echo esc_html__('Add a dropdown menu','paypal-wp-button-manager'); ?></span>
                                                                            <span id="dropDownLabel" class="opened"><?php echo esc_html__('Add drop-down menu','paypal-wp-button-manager'); ?></span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                            </div>   

                                                            <div class="hideShow dropdownSection accessAid hide" id="dropdownSection1">
                                                                <p class="title col-md-9"><label for="" class="control-label"><?php echo esc_html__('Name of drop-down menu (ex.: "Colors," "Sizes")','paypal-wp-button-manager'); ?></label><input maxlength="64" type="text" class="dropdownTitle text form-control" <?php echo $dropdown1_title_disabled; ?> name="dropdown1_title" value="<?php echo $dropdown1_title; ?>"></p>
                                                                <p class="title col-md-9"><label for="" class="control-label"><?php echo esc_html__('Menu option name','paypal-wp-button-manager'); ?></label></p>
                                                                <?php if ($string == 'edit' && !empty($optionselect[0 + $t])) { ?>
                                                                    <div id="optionsContainer1">
                                                                        <?php
                                                                        for ($i = 0; $i < count($optionselect[0 + $t]); $i++) {
                                                                            echo '<p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" name="dd1_option_name" value="' . $optionselect[0 + $t][$i] . '"></p>';
                                                                        }
                                                                        ?>                                                                                    
                                                                    </div>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <div id="optionsContainer1">
                                                                        <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd1_option_name" value="Option 1"></p>
                                                                        <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd1_option_name" value="Option 2"></p>
                                                                        <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd1_option_name" value="Option 3"></p>
                                                                    </div>
                                                                <?php } ?>
                                                                <p class="moreOptionsLink"><a class="addOption btn btn-sm btn-success" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Add option','paypal-wp-button-manager'); ?></a></p>
                                                                <p class="saveCancel"><input class="saveOption btn btn-default" type="submit" name="save_option" value="Done" alt="Done"><a class="cancelOption btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Cancel','paypal-wp-button-manager'); ?></a></p>
                                                            </div>
                                                            <div class="hideShow accessAid savedDropdownSection <?php echo $savedDropdownSection1_class; ?>" id="savedDropdownSection1">
                                                                <p><label id="savedDropdown1" for="" class="control-label" style="font-size: 12px;font-weight: 500;"><?php
                                                                        if(isset($optionname[0 + $t])){
                                                                            echo $optionname[0 + $t] . ": ";
                                                                            echo implode(", ", $optionselect[0 + $t]);
                                                                        }
                                                                        ?></label></p>
                                                                <p class="editDelete"><a class="editDropdown btn btn-info btn-sm" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Edit','paypal-wp-button-manager'); ?></a>&nbsp;|&nbsp;<a class="deleteDropdown btn btn-sm btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Delete','paypal-wp-button-manager'); ?></a></p>
                                                            </div>


                                                            <div class="hideShow dropdownSection accessAid hide" id="dropdownSection2">
                                                                <p class="title col-md-9"><label for="" class="control-label"><?php echo esc_html__('Name of drop-down menu (ex.: "Colors," "Sizes")','paypal-wp-button-manager'); ?></label><input maxlength="64" type="text" class="dropdownTitle text form-control" <?php echo $dropdown2_title_disabled; ?> name="dropdown2_title" value="<?php echo $dropdown2_title; ?>"></p>
                                                                <p class="title col-md-9"><label for="" class="control-label"><?php echo esc_html__('Menu option name','paypal-wp-button-manager'); ?></label></p>
                                                                <?php if ($string == 'edit' && !empty($optionselect[1 + $t])) { ?>
                                                                    <div id="optionsContainer2">
                                                                        <?php
                                                                        for ($i = 0; $i < count($optionselect[1 + $t]); $i++) {
                                                                            echo '<p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" name="dd2_option_name" value="' . $optionselect[1 + $t][$i] . '"></p>';
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <?php
                                                                } else {
                                                                    ?>                                                                        
                                                                    <div id="optionsContainer2">
                                                                        <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd2_option_name" value="Option 1"></p>
                                                                        <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd2_option_name" value="Option 2"></p>
                                                                        <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd2_option_name" value="Option 3"></p>
                                                                    </div>
                                                                <?php } ?>
                                                                <p class="moreOptionsLink"><a class="addOption btn btn-sm btn-success" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Add option','paypal-wp-button-manager'); ?></a></p>
                                                                <p class="saveCancel"><input class="saveOption btn btn-default" type="submit" name="save_option_2" value="Done" alt="Done"><a class="cancelOption btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Cancel','paypal-wp-button-manager'); ?></a></p>
                                                            </div>
                                                            <div class="hideShow accessAid savedDropdownSection <?php echo $savedDropdownSection2_class; ?>" id="savedDropdownSection2">
                                                                <p><label id="savedDropdown2" for="" class="control-label" style="font-size: 12px;font-weight: 500;"><?php
                                                                        if(isset($optionname[$t + 1])){
                                                                         echo $optionname[$t + 1] . ": ";
                                                                         echo implode(", ", $optionselect[$t + 1]);   
                                                                        }
                                                                        ?></label></p>
                                                                <p class="editDelete"><a class="editDropdown btn btn-info btn-sm" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Edit','paypal-wp-button-manager'); ?></a>&nbsp;|&nbsp;<a class="deleteDropdown btn btn-sm btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Delete','paypal-wp-button-manager'); ?></a></p>
                                                            </div>


                                                            <div class="hideShow dropdownSection accessAid hide" id="dropdownSection3">
                                                                <p class="title col-md-9"><label for="" class="control-label"><?php echo esc_html__('Name of drop-down menu (ex.: "Colors," "Sizes")','paypal-wp-button-manager'); ?></label><input maxlength="64" type="text" class="dropdownTitle text form-control" <?php echo $dropdown3_title_disabled; ?> name="dropdown3_title" value="<?php echo $dropdown3_title; ?>"></p>
                                                                <p class="title col-md-9"><label for="" class="control-label"><?php echo esc_html__('Menu option name','paypal-wp-button-manager'); ?></label></p>
                                                                <?php if ($string == 'edit' && !empty($optionselect[$t + 2])) { ?>
                                                                    <div id="optionsContainer3">
                                                                        <?php
                                                                        for ($i = 0; $i < count($optionselect[$t + 2]); $i++) {
                                                                            echo '<p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" name="dd3_option_name" value="' . $optionselect[$t + 2][$i] . '"></p>';
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <?php
                                                                } else {
                                                                    ?>        
                                                                    <div id="optionsContainer3">
                                                                        <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd3_option_name" value="Option 1"></p>
                                                                        <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd3_option_name" value="Option 2"></p>
                                                                        <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd3_option_name" value="Option 3"></p>
                                                                    </div>
                                                                <?php } ?>
                                                                <p class="moreOptionsLink"><a class="addOption btn btn-sm btn-success" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Add option','paypal-wp-button-manager'); ?></a></p>
                                                                <p class="saveCancel"><input class="saveOption  btn btn-default" type="submit" name="save_option_3" value="Done" alt="Done"><a class="cancelOption btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Cancel','paypal-wp-button-manager'); ?></a></p>
                                                            </div>
                                                            <div class="hideShow accessAid savedDropdownSection <?php echo $savedDropdownSection3_class; ?>" id="savedDropdownSection3">
                                                                <p><label id="savedDropdown3" for="" style="font-size: 12px;font-weight: 500;"><?php
                                                                        if(isset($optionname[$t + 2]))
                                                                        {
                                                                            echo $optionname[$t + 2] . ": ";
                                                                            echo implode(", ", $optionselect[$t + 2]);                                                                             
                                                                        }
                                                                        ?></label></p>
                                                                <p class="editDelete"><a class="editDropdown btn btn-info btn-sm" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Edit','paypal-wp-button-manager'); ?></a>&nbsp;|&nbsp;<a class="deleteDropdown btn btn-sm btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Delete','paypal-wp-button-manager'); ?></a></p>
                                                            </div>


                                                            <div class="hideShow dropdownSection accessAid hide" id="dropdownSection4">
                                                                <p class="title col-md-9"><label for="" class="control-label"><?php echo esc_html__('Name of drop-down menu (ex.: "Colors," "Sizes")','paypal-wp-button-manager'); ?></label><input maxlength="64" type="text" class="dropdownTitle text form-control"  <?php echo $dropdown4_title_disabled; ?> name="dropdown4_title" value="<?php echo $dropdown4_title; ?>"></p>
                                                                <p class="title col-md-9"><label for="" class="control-label"><?php echo esc_html__('Menu option name','paypal-wp-button-manager'); ?></label></p>
                                                                <?php if ($string == 'edit' && !empty($optionselect[$t + 3])) { ?>
                                                                    <div id="optionsContainer4">
                                                                        <?php
                                                                        for ($i = 0; $i < count($optionselect[$t + 3]); $i++) {
                                                                            echo '<p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" name="dd4_option_name" value="' . $optionselect[$t + 3][$i] . '"></p>';
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <?php
                                                                } else {
                                                                    ?>    
                                                                    <div id="optionsContainer4">
                                                                        <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd4_option_name" value="Option 1"></p>
                                                                        <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd4_option_name" value="Option 2"></p>
                                                                        <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd4_option_name" value="Option 3"></p>
                                                                    </div>
                                                                <?php } ?>
                                                                <p class="moreOptionsLink"><a class="addOption btn btn-sm btn-success" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Add option','paypal-wp-button-manager'); ?></a></p>
                                                                <p class="saveCancel"><input class="saveOption btn btn-default" type="submit" name="save_option_4" value="Done" alt="Done"><a class="cancelOption btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Cancel','paypal-wp-button-manager'); ?></a></p>
                                                            </div>
                                                            <div class="hideShow accessAid savedDropdownSection  <?php echo $savedDropdownSection4_class; ?>" id="savedDropdownSection4">
                                                                <p><label id="savedDropdown4" for="" class="control-label" style="font-size: 12px;font-weight: 500;"><?php
                                                                        if(isset($optionname[$t + 3])){
                                                                            echo $optionname[$t + 3] . ": ";
                                                                            echo implode(", ", $optionselect[$t + 3]);
                                                                        }
                                                                        ?></label></p>
                                                                <p class="editDelete"><a class="editDropdown btn btn-info btn-sm" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Edit','paypal-wp-button-manager'); ?></a>&nbsp;|&nbsp;<a class="deleteDropdown btn btn-sm btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Delete','paypal-wp-button-manager'); ?></a></p>
                                                            </div>

                                                            <?php
                                                            if (isset($optionname[$t + 3])) {
                                                                $addNewDropdownSection_class = 'hide';
                                                            } else {
                                                                if (empty($dropdown_checkbox)) {
                                                                    $addNewDropdownSection_class = 'hide';
                                                                } else {
                                                                    $addNewDropdownSection_class = 'opened';
                                                                }
                                                            }
                                                            ?>
                                                            <p id="addNewDropdownSection" class="editDelete hideShow accessAid <?php echo $addNewDropdownSection_class; ?>"><a id="addNewDropdown" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=" class="btn btn-sm btn-success"><?php echo esc_html__('Add another drop-down menu','paypal-wp-button-manager'); ?></a></p>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p class="hideShow opened" id="addTextfield">
                                                                        <label for="textfield" class="control-label">
                                                                            <?php
                                                                            if (!empty($TEXTBOX)) {
                                                                                $createdTextfield_checkbox = 'checked';
                                                                                $savedTextfieldSection1 = 'opened';
                                                                                $textfieldTitle1 = $TEXTBOX[0];
                                                                                $textfieldTitle1_disabled = '';
                                                                                $previewTextfieldTitle1 = $TEXTBOX[0];
                                                                                $previewDropdown1 = 'opened';
                                                                                if (isset($TEXTBOX[1])) {
                                                                                    $savedTextfieldSection2 = 'opened';
                                                                                    $textfieldTitle2 = $TEXTBOX[1];
                                                                                    $previewTextfieldTitle2 = $TEXTBOX[1];
                                                                                    $previewDropdown2 = 'opened';
                                                                                    $textfieldTitle2_disabled = '';
                                                                                } else {
                                                                                    $previewDropdown2 = 'hide';
                                                                                    $savedTextfieldSection2 = 'hide';
                                                                                    $textfieldTitle2 = '';
                                                                                    $previewTextfieldTitle2 = 'Title';
                                                                                    $textfieldTitle2_disabled = 'disabled';
                                                                                }
                                                                            } else {
                                                                                $textfieldTitle1 = '';
                                                                                $textfieldTitle2 = '';
                                                                                $createdTextfield_checkbox = '';
                                                                                $savedTextfieldSection1 = 'hide';
                                                                                $savedTextfieldSection2 = 'hide';
                                                                                $previewTextfieldTitle1 = 'Title';
                                                                                $previewTextfieldTitle2 = 'Title';
                                                                                $previewDropdown1 = 'hide';
                                                                                $previewDropdown2 = 'hide';
                                                                                $textfieldTitle1_disabled = 'disabled';
                                                                                $textfieldTitle2_disabled = 'disabled';
                                                                            }
                                                                            ?>
                                                                            <input type="checkbox" value="createdTextfield" name="textfield" id="textfield" class="checkbox form-control" <?php echo $createdTextfield_checkbox; ?>><?php echo esc_html__('Add text field','paypal-wp-button-manager'); ?>
                                                                            <a onclick="PAYPAL.core.openWindow(event, {width: 560, height: 410})" href="https://www.paypal.com/uk/cgi-bin/webscr?cmd=_display-textfield-example" class="infoLink exampleLink" target="_blank"><?php echo esc_html__('Example','paypal-wp-button-manager'); ?></a>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="hideShow accessAid textfieldSection hide" id="textfieldSection1">
                                                                <p class="title col-md-9"><label for="textfieldTitle1" class="control-label"><?php echo esc_html__('Enter name of text field (up to 30 characters)','paypal-wp-button-manager'); ?></label><input maxlength="30" type="text" id="textfieldTitle1" class="text form-control" <?php echo $textfieldTitle1_disabled; ?> name="textfield1_title" value="<?php echo $textfieldTitle1; ?>"></p>
                                                                <p class="saveCancel"><input class="saveTextfield btn btn-default" type="submit" name="save_textfield" value="Done" alt="Done"><a class="cancelTextfield btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Cancel','paypal-wp-button-manager'); ?></a></p>
                                                            </div>

                                                            <div class="hideShow accessAid savedTextfieldSection <?php echo $savedTextfieldSection1; ?>" id="savedTextfieldSection1">
                                                                <p><label class="savedTextfield" id="savedTextfield1" for=""><?php
                                                                        if (!empty($TEXTBOX) && isset($TEXTBOX[0])) {
                                                                            echo $TEXTBOX[0];
                                                                        } else {
                                                                            echo '';
                                                                        }
                                                                        ?></label></p>
                                                                <p class="editDelete"><a class="editTextfield btn btn-sm btn-info" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Edit','paypal-wp-button-manager'); ?></a>&nbsp;|&nbsp;<a class="deleteTextfield btn btn-sm btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Delete','paypal-wp-button-manager'); ?></a></p>
                                                            </div>
                                                            <div class="hideShow accessAid textfieldSection hide" id="textfieldSection2">
                                                                <p class="title col-md-9"><label for="textfieldTitle2" class="control-label"><?php echo esc_html__('Enter name of text field (up to 30 characters)','paypal-wp-button-manager'); ?></label><input maxlength="30" type="text" id="textfieldTitle2" class="text form-control" <?php echo $textfieldTitle2_disabled; ?>  name="textfield2_title" value="<?php echo $textfieldTitle2; ?>"></p>
                                                                <p class="saveCancel"><input class="saveTextfield btn btn-default" type="submit" name="save_textfield" value="Done" alt="Done"><a class="cancelTextfield btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Cancel','paypal-wp-button-manager'); ?></a></p>
                                                            </div>
                                                            <div class="hideShow accessAid savedTextfieldSection <?php echo $savedTextfieldSection2; ?>" id="savedTextfieldSection2">
                                                                <p><label class="savedTextfield control-label" id="savedTextfield2" for=""><?php
                                                                        if (!empty($TEXTBOX) && isset($TEXTBOX[1])) {
                                                                            echo $TEXTBOX[1];
                                                                        } else {
                                                                            echo '';
                                                                        }
                                                                        ?></label></p>
                                                                <p class="editDelete"><a class="editTextfield btn btn-sm btn-info" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Edit','paypal-wp-button-manager'); ?></a>&nbsp;|&nbsp;<a class="deleteTextfield btn btn-sm btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Delete','paypal-wp-button-manager'); ?></a></p>
                                                            </div>
                                                            <?php
                                                            if ($string == 'edit') {
                                                                if (!empty($TEXTBOX) && isset($TEXTBOX[0]) && isset($TEXTBOX[1])) {
                                                                    $addNewTextfieldSection_class = 'hide';
                                                                } else {
                                                                    if (empty($createdTextfield_checkbox)) {
                                                                        $addNewTextfieldSection_class = 'hide';
                                                                    } else {
                                                                        $addNewTextfieldSection_class = 'opened';
                                                                    }
                                                                }
                                                            } else {
                                                                $addNewTextfieldSection_class = 'hide';
                                                            }
                                                            ?>
                                                            <p id="addNewTextfieldSection" class="editDelete hideShow accessAid <?php echo $addNewTextfieldSection_class; ?>"><a id="addNewTextfield" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=" class="btn btn-sm btn-success"><?php echo esc_html__('Add another text field','paypal-wp-button-manager'); ?></a></p>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <span id="buttonAppLink" class="collapsed"><a href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><?php echo esc_html__('Customize text or appearance','paypal-wp-button-manager'); ?></a><span class="fieldNote"><?php echo esc_html__('(optional)','paypal-wp-button-manager'); ?></span></span>
                                                                </div>
                                                            </div>


                                                            <div id="buttonAppSection" class="hideShow accessAid hide">
                                                                <?php
                                                                if (!empty($buttonImageUrl)) {
                                                                    $paypalButtonSection_class = 'hide';
                                                                    $customButtonSection_class = 'opened';
                                                                    $paypalButton_checked = '';
                                                                    $customButton_checked = 'checked';
                                                                    $previewImageSection = 'hide';
                                                                    $previewCustomImageSection = 'opened';
                                                                } else {
                                                                    $paypalButtonSection_class = 'opened';
                                                                    $customButtonSection_class = 'hide';
                                                                    $paypalButton_checked = 'checked';
                                                                    $customButton_checked = '';
                                                                    $previewImageSection = 'opened';
                                                                    $previewCustomImageSection = 'hide';
                                                                }
                                                                ?>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <p id="addPaypalButton">
                                                                            <label for="paypalButton" class="control-label">
                                                                                <input class="radio form-control" type="radio" id="paypalButton" <?php echo $paypalButton_checked; ?> name="paypal_button" value="true"><?php echo esc_html__('PayPal button','paypal-wp-button-manager'); ?>
                                                                            </label>
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div id="paypalButtonSection" class="hideShow <?php echo $paypalButtonSection_class; ?>">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <?php
                                                                            if ($buttonImageSize == 'SML') {
                                                                                $smallButton_checked = 'checked';
                                                                            } else {
                                                                                $smallButton_checked = '';
                                                                            }
                                                                            if ($buttonImageSize == 'CC') {
                                                                                $displayCcLogos_checked = 'checked';
                                                                            } else {
                                                                                $displayCcLogos_checked = '';
                                                                            }
                                                                            if ($buttonImageSize == 'REG') {
                                                                                $smallButton_checked = '';
                                                                                $displayCcLogos_checked = 'checked';
                                                                            }
                                                                            ?>
                                                                            <p id="displaySmallButton">
                                                                                <label for="smallButton" class="control-label">
                                                                                    <input class="checkbox form-control" type="checkbox" id="smallButton" name="small_button" value="createdSmallButton" <?php echo $smallButton_checked; ?>><?php echo esc_html__('Use smaller button','paypal-wp-button-manager'); ?></label>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p id="displayCcLogos" class="hideShow hide">
                                                                                <label for="ccLogos" class="control-label">
                                                                                    <input class="checkbox form-control" type="checkbox" <?php echo $displayCcLogos_checked; ?> id="ccLogos" name="cc_logos" value="createdButtonWithCCLogo"><?php echo esc_html__('Display credit card logos','paypal-wp-button-manager'); ?></label></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p id="buttonCountryLanguage">
                                                                                <label for="" class="control-label"><?php echo esc_html__('Country and language for button','paypal-wp-button-manager'); ?></label>
                                                                                <?php $paypal_button_language = get_paypal_button_languages(); ?>
                                                                                <select id="selectCountryLanguage" name="select_country_language" class="form-control">

                                                                                    <?php foreach ($paypal_button_language as $paypal_button_language_key => $paypal_button_language_value) { ?>
                                                                                        <?php
                                                                                        if ($buttonLanguage . '_' . $buttonCountry === $paypal_button_language_key) {
                                                                                            $selectCountryLanguage_seleced = 'selected';
                                                                                        } else {
                                                                                            $selectCountryLanguage_seleced = '';
                                                                                        }
                                                                                        ?>  
                                                                                        <option value="<?php echo $paypal_button_language_key; ?>" <?php echo $selectCountryLanguage_seleced; ?>><?php echo $paypal_button_language_value; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                                <input type="hidden" id="countryCode" name="country_code" value="US"><input type="hidden" id="langCode" name="lang_code" value="en"><input type="hidden" id="buttonUrl" name="button_url" value="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"><input type="hidden" id="popupButtonUrl" name="popup_button_url" value=""><input type="hidden" id="flagInternational" name="flag_international" value="true" disabled=""><input type="hidden" id="titleStr" name="title_str" value="Title"><input type="hidden" id="optionStr" name="option_str" value="Option"><input type="hidden" id="addOptionStr" name="add_option_str" value="Add another option">
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p id="textBuyNow" class="hideShow buttonText hide">
                                                                                <label for="" class="control-label"><?php echo esc_html__('Select button text','paypal-wp-button-manager'); ?></label>
                                                                                <span class="field">
                                                                                    <select id="buttonTextBuyNow" name="button_text" disabled="" class="form-control">
                                                                                        <?php
                                                                                            if($buynowtext=='BUYNOW'){
                                                                                                $buy_now_selected='selected';
                                                                                                $pay_now_selected='';
                                                                                            }
                                                                                            else{
                                                                                                $buy_now_selected='';
                                                                                                $pay_now_selected='selected';
                                                                                            }
                                                                                        ?>
                                                                                        <option value="buy_now" <?php echo $buy_now_selected; ?> ><?php echo esc_html__('Buy Now','paypal-wp-button-manager'); ?></option>
                                                                                        <option value="pay_now" <?php echo $pay_now_selected; ?> ><?php echo esc_html__('Pay Now','paypal-wp-button-manager'); ?></option>
                                                                                    </select>
                                                                                </span>
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p id="textSubscr" class="hideShow buttonText hide">
                                                                                <label for="" class="control-label"><?php echo esc_html__('Select button text','paypal-wp-button-manager'); ?></label>
                                                                                <span class="field">
                                                                                    <select id="buttonTextSubscribe" name="button_text" disabled="" class="form-control">
                                                                                        <?php 
                                                                                            if($subscribe_text=='BUYNOW'){
                                                                                                $buttonTextSubscribe_buynow='selected';
                                                                                                $buttonTextSubscribe_subscribe='';
                                                                                            }
                                                                                            else{
                                                                                                $buttonTextSubscribe_buynow='';
                                                                                                $buttonTextSubscribe_subscribe='selected';
                                                                                            }
                                                                                        ?>
                                                                                        <option value="subscriptions" <?php echo $buttonTextSubscribe_subscribe; ?> ><?php echo esc_html__('Subscribe','paypal-wp-button-manager'); ?></option>
                                                                                        <option value="buy_now" <?php echo $buttonTextSubscribe_buynow; ?> ><?php echo esc_html__('Buy Now','paypal-wp-button-manager'); ?></option>
                                                                                    </select>
                                                                                </span>
                                                                            </p>
                                                                        </div>
                                                                    </div>                                                                            
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <p id="addCustomButton"><label for="customButton" class="control-label"><input class="radio form-control" type="radio" id="customButton" <?php echo $customButton_checked; ?> name="paypal_button" value="false"><?php echo esc_html__('Use your own button image','paypal-wp-button-manager'); ?></label></p>
                                                                    </div>
                                                                </div>

                                                                <div id="customButtonSection" class="hideShow accessAid <?php echo $customButtonSection_class; ?>">
                                                                    <input type="text" id="customImageUrl" class="text form-control" name="custom_image_url" style="width: auto" value="<?php echo $buttonImageUrl; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="buyerViewSection">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p class="heading"><strong><?php echo esc_html__('Your customer\'s view','paypal-wp-button-manager'); ?></strong></p>
                                                            </div>
                                                        </div>

                                                        <div class="previewSection" style="padding-left: 10px">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <?php 
                                                                    if(!empty($optionprice[0])){
                                                                        $previewDropdownPriceTitle=$optionname[0];
                                                                        $previewDropdown_class='opened';
                                                                    }
                                                                    else{
                                                                        $previewDropdownPriceTitle='Dropdown title';
                                                                        $previewDropdown_class='hide';
                                                                    }
                                                                    ?>
                                                                    <p id="previewDropdownPriceSection" class="hideShow accessAid previewDropdown <?php echo $previewDropdown_class; ?>">
                                                                        <label id="previewDropdownPriceTitle" for="optionsPriceDropdown" class="control-label"><?php echo $previewDropdownPriceTitle; ?></label>
                                                                        <select id="optionsPriceDropdown" name="options_price_dropdown" class="form-control">
                                                                            <?php 
                                                                            if(!empty($optionprice[0])){
                                                                                for($m=0;$m<count($optionprice[0]);$m++){
                                                                                    if($m==0){
                                                                                        $optionsPriceDropdown_slected='selected';
                                                                                    }
                                                                                    else{
                                                                                        $optionsPriceDropdown_slected='';
                                                                                    }
                                                                                    echo '<option value="Option 1" '.$optionsPriceDropdown_slected.'>'.$optionselect[0][$m].' '.$optionprice[0][$m].' '.$item_price_currency.'</option>';
                                                                                }
                                                                            }
                                                                            else{
                                                                                echo '<option value="Option 1" selected="">Option 1 - $x.xx</option>
                                                                            <option value="Option 2">Option 2 - $x.xx</option>
                                                                            <option value="Option 3">Option 3 - $x.xx</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <span class="hide control-label" id="frequencyTxt"><?php echo esc_html__('Frequency','paypal-wp-button-manager'); ?></span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p class="hideShow accessAid previewDropdown <?php echo $previewDropdownSection1_class; ?>" id="previewDropdownSection1">
                                                                        <label class="previewDropdownTitle control-label" for="optionsDropdown1"><?php echo $previewDropdownSection1_label; ?></label>
                                                                        <select id="optionsDropdown1" name="options_dropdown1" class="optionsDropdown form-control">
                                                                            <?php
                                                                            if ($string == 'edit' && !empty($optionselect[$t + 0])) {
                                                                                for ($i = 0; $i < count($optionselect[$t + 0]); $i++) {
                                                                                    if ($i == 0) {
                                                                                        $optionsDropdown1_selected = 'selected';
                                                                                    } else {
                                                                                        $optionsDropdown1_selected = '';
                                                                                    }
                                                                                    echo '<option value="' . $optionselect[$t + 0][$i] . '" ' . $optionsDropdown1_selected . '>' . $optionselect[$t + 0][$i] . '</option>';
                                                                                }
                                                                            } else {
                                                                                echo '<option value="" selected="">Option 1</option><option value="">Option 2</option><option value="">Option 3</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p class="hideShow accessAid previewDropdown <?php echo $previewDropdownSection2_class; ?>" id="previewDropdownSection2">
                                                                        <label class="previewDropdownTitle control-label" for=""><?php echo $previewDropdownSection2_label; ?></label>
                                                                        <select id="optionsDropdown2" name="options_dropdown2" class="optionsDropdown form-control">
                                                                            <?php
                                                                            if ($string == 'edit' && !empty($optionselect[$t + 1])) {
                                                                                for ($i = 0; $i < count($optionselect[$t + 1]); $i++) {
                                                                                    if ($i == 0) {
                                                                                        $optionsDropdown2_selected = 'selected';
                                                                                    } else {
                                                                                        $optionsDropdown2_selected = '';
                                                                                    }
                                                                                    echo '<option value="' . $optionselect[$t + 1][$i] . '" ' . $optionsDropdown2_selected . '>' . $optionselect[$t + 1][$i] . '</option>';
                                                                                }
                                                                            } else {
                                                                                echo '<option value="" selected="">Option 1</option><option value="">Option 2</option><option value="">Option 3</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p class="hideShow accessAid previewDropdown <?php echo $previewDropdownSection3_class; ?>" id="previewDropdownSection3">
                                                                        <label class="previewDropdownTitle control-label" for=""><?php echo $previewDropdownSection3_label; ?></label>
                                                                        <select id="optionsDropdown3" name="options_dropdown3" class="optionsDropdown form-control">
                                                                            <?php
                                                                            if ($string == 'edit' && !empty($optionselect[$t + 2])) {
                                                                                for ($i = 0; $i < count($optionselect[$t + 2]); $i++) {
                                                                                    if ($i == 0) {
                                                                                        $optionsDropdown3_selected = 'selected';
                                                                                    } else {
                                                                                        $optionsDropdown3_selected = '';
                                                                                    }
                                                                                    echo '<option value="' . $optionselect[$t + 2][$i] . '" ' . $optionsDropdown3_selected . '>' . $optionselect[$t + 2][$i] . '</option>';
                                                                                }
                                                                            } else {
                                                                                echo '<option value="" selected="">Option 1</option><option value="">Option 2</option><option value="">Option 3</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p class="hideShow accessAid previewDropdown <?php echo $previewDropdownSection4_class; ?>" id="previewDropdownSection4">
                                                                        <label class="previewDropdownTitle control-label" for=""><?php echo $previewDropdownSection4_label; ?></label>
                                                                        <select id="optionsDropdown4" name="options_dropdown4" class="optionsDropdown form-control">
                                                                            <?php
                                                                            if ($string == 'edit' && !empty($optionselect[$t + 3])) {
                                                                                for ($i = 0; $i < count($optionselect[$t + 3]); $i++) {
                                                                                    if ($i == 0) {
                                                                                        $optionsDropdown4_selected = 'selected';
                                                                                    } else {
                                                                                        $optionsDropdown4_selected = '';
                                                                                    }
                                                                                    echo '<option value="' . $optionselect[$t + 3][$i] . '" ' . $optionsDropdown4_selected . '>' . $optionselect[$t + 3][$i] . '</option>';
                                                                                }
                                                                            } else {
                                                                                echo '<option value="" selected="">Option 1</option><option value="">Option 2</option><option value="">Option 3</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p class="hideShow accessAid previewDropdown <?php echo $previewDropdown1; ?>" id="previewTextfieldSection1"><label id="previewTextfieldTitle1" for="buttonTextfield1" class="control-label"><?php echo $previewTextfieldTitle1; ?></label><input type="text" id="buttonTextfield1" class="text readOnlyLabel form-control" name="button_textfield1" value=""></p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p class="hideShow accessAid previewDropdown <?php echo $previewDropdown2; ?>" id="previewTextfieldSection2"><label id="previewTextfieldTitle2" for="buttonTextfield2" class="control-label"><?php echo $previewTextfieldTitle2; ?></label><input type="text" id="buttonTextfield2" class="text readOnlyLabel form-control" name="button_textfield2" value=""></p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p class="hideShow previewImageSection <?php echo $previewImageSection; ?>"><img id="previewImage" src="<?php echo BMW_PLUGIN_URL ?>admin/images/btn_cart_LG.gif" border="0" alt="Preview Image"></p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p class="hideShow accessAid previewCustomImageSection <?php echo $previewCustomImageSection; ?>"><img id="previewCustomImage" src="<?php echo BMW_PLUGIN_URL ?>/admin/images/info_nobuttonpreview_121wx26h.gif" border="0" alt="Use your own button image"></p>
                                                                </div>
                                                            </div>                                                                    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="group products">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="shipping">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <h4><?php echo esc_html__('Shipping','paypal-wp-button-manager'); ?></h4>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-md-12">
                                                                        <label for="itemFlatShippingAmount" class="control-label"><?php echo esc_html__('Use specific amount: (','paypal-wp-button-manager'); ?> <span class="currencyLabel"><?php echo $item_price_currency; ?></span> )</label>
                                                                        <input class="form-control" type="text" id="itemFlatShippingAmount" size="9" name="item_shipping_amount" value="<?php echo $item_shipping_amount; ?>">
                                                                    </div>                                                                                
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="tax">                                                                                
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <h4><?php echo esc_html__('Tax','paypal-wp-button-manager'); ?></h4>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">                                                                                        
                                                                        <label for="itemTaxRate" class="control-label"><?php echo esc_html__('Use tax rate ( % )','paypal-wp-button-manager'); ?></label>
                                                                        <input class="form-control" type="text" id="itemTaxRate" name="item_tax_rate" value="<?php echo $itemTaxRate; ?>">
                                                                    </div>
                                                                </div>
                                                            </div>                                                                                    
                                                        </div>                                                                            
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="group donations last accessAid fadedOut">
                                                <div class="group donationCurrency">
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label for="donationCurrency" class="control-label"><?php echo esc_html__('Currency','paypal-wp-button-manager'); ?></label>
                                                                <select id="donationCurrency" name="item_price_currency" class="currencySelect form-control" disabled="" style="width: auto !important">
                                                                    <?php foreach ($paypal_button_currency as $paypal_button_currency_key => $paypal_button_currency_value) { ?>
                                                                        <?php
                                                                        if ($paypal_button_currency_value == $donation_currency) {
                                                                            $donation_currency_selected = 'selected';
                                                                        } else {
                                                                            $donation_currency_selected = '';
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $paypal_button_currency_value; ?>" <?php echo $donation_currency_selected; ?> title="<?php echo $paypal_button_options_key; ?>"><?php echo $paypal_button_currency_value; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>                                                                        
                                                </div>
                                                <div class="group contributionAmount">

                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <h4><?php echo esc_html__('Contribution amount','paypal-wp-button-manager'); ?></h4>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group">                                                                                    
                                                                <input class="radio donationType form-control" type="radio" id="optDonationTypeFlexible" checked="" name="donation_type" value="open" disabled="">
                                                                <label for="optDonationTypeFlexible" class="control-label"><?php echo esc_html__('Donors enter their own contribution amount.','paypal-wp-button-manager'); ?></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                if (!empty($donation_amount)) {
                                                                    $donation_amount_check = "checked";
                                                                    $donation_container_amount_class = '';
                                                                } else {
                                                                    $donation_amount_check = "";
                                                                    $donation_container_amount_class = 'accessAid';
                                                                }
                                                                ?>
                                                                <input class="radio donationType form-control" <?php echo $donation_amount_check; ?> type="radio" id="optDonationTypeFixed" name="donation_type" value="fixed" disabled="">
                                                                <label for="optDonationTypeFixed" class="control-label"><?php echo esc_html__('Donors contribute a fixed amount.','paypal-wp-button-manager'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="labelOption fixedDonationAmountContainer <?php echo $donation_container_amount_class; ?>">
                                                        <div class="row">
                                                            <div class="col-md-9">
                                                                <label for="fixedDonationAmount" class="control-label"><?php echo esc_html__('Amount (','paypal-wp-button-manager'); ?> <span class="currencyLabel"><?php echo $item_price_currency; ?></span> )</label>
                                                                <input type="text" id="fixedDonationAmount" size="7" maxlength="20" class="text form-control" name="item_price" value="<?php echo $donation_amount; ?>" disabled="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row"><div class="col-md-9"><strong><?php echo esc_html__('Note:','paypal-wp-button-manager'); ?></strong> <?php echo esc_html__('This button is intended for fundraising. If you are not raising money for a cause, please choose another option. Nonprofits must verify their status to withdraw donations they receive. Users that are not verified nonprofits must demonstrate how their donations will be used, once they raise more than $10,000 USD.','paypal-wp-button-manager'); ?></div></div>
                                            </div>
                                            <div class="group subscriptions last accessAid fadedOut">
                                                <div class="group">
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <input type="checkbox" id="enableUsernamePasswordCreation" class="checkbox form-control" name="enable_username_password_creation" value="1" disabled=""><?php echo esc_html__('Have PayPal create user names and passwords for customers','paypal-wp-button-manager'); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="balloonCallout accessAid" id="customerControlHelp"><?php echo esc_html__('Give customers access to "members-only" content on your site.','paypal-wp-button-manager'); ?></div>
                                                    <div class="fieldNote">
                                                        <div class="label"><?php echo esc_html__('Notes:','paypal-wp-button-manager'); ?></div>
                                                        <div class="floatLeft">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="rbFixedAmount">
                                                    <div class="group">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="subscriptionBillingAmount" class="control-label"><?php echo esc_html__('Billing amount each cycle (','paypal-wp-button-manager'); ?><span class="currencyLabel"><?php echo $item_price_currency; ?></span> ) </label>
                                                                    <input type="text" id="subscriptionBillingAmount" size="22" class="text form-control" name="subscription_billing_amount" value="<?php echo $subscriptionBillingAmount; ?>" disabled="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="group">
                                                        <div class="row">                                                                                
                                                            <div class="form-group">
                                                                <div class="col-md-12"><label for="subscriptionBillingCycleNumber" class="control-label"><?php echo esc_html__('Billing cycle','paypal-wp-button-manager'); ?></label></div>
                                                                <div class="col-md-1">
                                                                    <?php $paypal_button_subscription_billing_cycle_number = get_paypal_button_subscription_billing_cycle_number(); ?>
                                                                    <select name="subscription_billing_cycle_number" disabled="" class="form-control" style="width: auto !important">
                                                                        <?php foreach ($paypal_button_subscription_billing_cycle_number as $paypal_button_subscription_billing_cycle_number_key => $paypal_button_subscription_billing_cycle_number_value) { ?>
                                                                            <?php 
                                                                                if($subscription_billing_cycle_number == $paypal_button_subscription_billing_cycle_number_value){
                                                                                    $subscription_billing_cycle_number_selected='selected';
                                                                                } 
                                                                                else{
                                                                                    $subscription_billing_cycle_number_selected='';
                                                                                }
                                                                            ?>
                                                                            <option value="<?php echo $paypal_button_subscription_billing_cycle_number_value; ?>" <?php echo $subscription_billing_cycle_number_selected; ?>><?php echo $paypal_button_subscription_billing_cycle_number_value; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <?php $paypal_button_subscriptions_cycle = get_paypal_button_subscriptions_cycle(); ?>
                                                                    <select id="subscriptionBillingCyclePeriod" name="subscription_billing_cycle_period" disabled="" class="form-control" style="width: auto !important">
                                                                        <?php foreach ($paypal_button_subscriptions_cycle as $paypal_button_subscriptions_cycle_key => $paypal_button_subscriptions_cycle_value) { ?>
                                                                            <?php 
                                                                                if($subscription_billing_cycle_period==$paypal_button_subscriptions_cycle_key){
                                                                                    $subscription_billing_cycle_period_selected='selected';
                                                                                }
                                                                                else{
                                                                                    $subscription_billing_cycle_period_selected='';
                                                                                }
                                                                            ?>
                                                                            <option value="<?php echo $paypal_button_subscriptions_cycle_key; ?>" <?php echo $subscription_billing_cycle_period_selected; ?>><?php echo $paypal_button_subscriptions_cycle_value; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="group">
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label for="subscriptionBillingLimit" class="control-label"><?php echo esc_html__('After how many cycles should billing stop?','paypal-wp-button-manager'); ?></label>
                                                                <select name="subscription_billing_limit" disabled="" class="form-control" style="width: auto !important">
                                                                    <?php
                                                                    $paypal_button_subscriptions_cycle_billing_limit = get_paypal_button_subscription_billing_limit();
                                                                    foreach ($paypal_button_subscriptions_cycle_billing_limit as $paypal_button_subscriptions_cycle_billing_limit_key => $paypal_button_subscriptions_cycle_billing_limit_value) {
                                                                        ?>
                                                                    <?php
                                                                        if($subscription_billing_limit==$paypal_button_subscriptions_cycle_billing_limit_value){
                                                                            $subscription_billing_limit_selected='selected';
                                                                        }
                                                                        else{
                                                                            $subscription_billing_limit_selected='';
                                                                        }
                                                                    ?>
                                                                        <option value="<?php echo $paypal_button_subscriptions_cycle_billing_limit_value; ?>" <?php echo $subscription_billing_limit_selected; ?>><?php echo $paypal_button_subscriptions_cycle_billing_limit_value; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="group">
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <?php 
                                                                    if(!empty($subscription_trial_rate) && !empty($subscription_trial_duration)){
                                                                        $subscriptions_offer_trial_checkbox='checked';
                                                                        $trialOfferOptions_class='';
                                                                    }
                                                                    else{
                                                                        $subscriptions_offer_trial_checkbox='';
                                                                        $trialOfferOptions_class='accessAid';
                                                                    }
                                                                ?>
                                                                <input type="checkbox" id="offerTrial" class="checkbox form-control" name="subscriptions_offer_trial" value="1" disabled="" <?php echo $subscriptions_offer_trial_checkbox; ?>><label for="offerTrial" class="control-label"><?php echo esc_html__('I want to offer a trial period','paypal-wp-button-manager'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="trialOfferOptions <?php echo $trialOfferOptions_class; ?>">

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="subscriptionLowerRate" class="control-label"><?php echo esc_html__('Amount to bill for the trial period (','paypal-wp-button-manager'); ?><span class="currencyLabel"><?php echo $item_price_currency; ?></span> )</label>
                                                                    <input class="hidden" type="hidden" id="subscriptionLowerRate" name="subscription_trial_billing_amount" value="1" disabled="">
                                                                    <input type="text" id="subscriptionLowerRateAmount" size="11" class="text form-control" name="subscription_trial_rate" value="<?php echo $subscription_trial_rate; ?>" disabled="">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group">
                                                                <?php $paypal_button_subscription_trial_duration = get_paypal_button_subscription_trial_duration(); ?>                                                                                    

                                                                <div class="col-md-12"><label class="control-label"><?php echo esc_html__('Define the trial period','paypal-wp-button-manager'); ?></label></div>
                                                                <div class="col-md-1">
                                                                    <select name="subscription_trial_duration" disabled="" class="form-control">
                                                                        <?php foreach ($paypal_button_subscription_trial_duration as $paypal_button_subscription_trial_duration_key => $paypal_button_subscription_trial_duration_value) { ?>
                                                                            <?php 
                                                                                if($subscription_trial_duration == $paypal_button_subscription_trial_duration_key){
                                                                                    $subscription_trial_duration_selected='selected';
                                                                                }
                                                                                else{
                                                                                    $subscription_trial_duration_selected='';
                                                                                }
                                                                            ?>
                                                                            <option value="<?php echo $paypal_button_subscription_trial_duration_key; ?>" <?php echo $subscription_trial_duration_selected; ?> ><?php echo $paypal_button_subscription_trial_duration_value; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <select id="trialDurationType" name="subscription_trial_duration_type" disabled="" class="form-control">
                                                                        <?php foreach ($paypal_button_subscriptions_cycle as $paypal_button_subscriptions_cycle_key => $paypal_button_subscriptions_cycle_value) { ?>
                                                                            <?php 
                                                                                if($subscription_trial_duration_type == $paypal_button_subscriptions_cycle_key){
                                                                                    $subscription_trial_duration_type_selected='selected';
                                                                                }
                                                                                else{
                                                                                    $subscription_trial_duration_type_selected='';
                                                                                }
                                                                            ?>
                                                                            <option value="<?php echo $paypal_button_subscriptions_cycle_key; ?>" <?php echo $subscription_trial_duration_type_selected; ?> ><?php echo $paypal_button_subscriptions_cycle_value; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>                                                                                    
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-9">
                                                                <h5><?php echo esc_html__('Do you want to offer a second trial period?','paypal-wp-button-manager'); ?><span class="autoTooltip" title="" tabindex="0"><?php echo esc_html__('What\'s this?','paypal-wp-button-manager'); ?><span class="accessAid"><?php echo esc_html__('Customers will receive just one bill for each trial period.','paypal-wp-button-manager'); ?></span></span></h5>
                                                            </div>                                                                                
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <?php 
                                                                    if(!empty($subscription_trial_2_rate) && !empty($subscription_trial_2_duration)){
                                                                         $secondSubscriptionTrialOffer_radio='checked';
                                                                         $lastSubscriptionTrialOffer_radio='';
                                                                         $secondTrialOfferOptions_class='';
                                                                    }
                                                                    else{
                                                                        $secondSubscriptionTrialOffer_radio='';
                                                                        $lastSubscriptionTrialOffer_radio='checked';
                                                                        $secondTrialOfferOptions_class='accessAid';
                                                                    }
                                                                ?>
                                                                    <input class="radio secondTrialOfferOption form-control" type="radio" id="secondSubscriptionTrialOffer" <?php echo $secondSubscriptionTrialOffer_radio; ?> name="subscriptions_offer_another_trial" value="1" disabled="">
                                                                    <label for="secondSubscriptionTrialOffer" class="control-label"><?php echo esc_html__('Yes','paypal-wp-button-manager'); ?></label>

                                                                    <input class="radio secondTrialOfferOption form-control" type="radio" id="lastSubscriptionTrialOffer" <?php echo $lastSubscriptionTrialOffer_radio; ?> name="subscriptions_offer_another_trial" value="0" disabled="">
                                                                    <label for="lastSubscriptionTrialOffer" class="control-label"><?php echo esc_html__('No','paypal-wp-button-manager'); ?></label>

                                                                </div>
                                                            </div>
                                                        </div>                                                                                
                                                        <div class="secondTrialOfferOptions <?php echo $secondTrialOfferOptions_class; ?>">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label"><?php echo esc_html__('Amount to bill for this trial period (','paypal-wp-button-manager'); ?><span class="currencyLabel"><?php echo $item_price_currency; ?></span> )</label>
                                                                        <input type="text" id="secondSubscriptionLowerRateAmount" size="11" class="text form-control" name="subscription_trial_2_rate" value="<?php echo $subscription_trial_2_rate; ?>" disabled="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">                                                                                        
                                                                <div class="form-group">
                                                                    <div class="col-md-12">
                                                                        <label class="control-label"><?php echo esc_html__('How long should the trial period last?','paypal-wp-button-manager'); ?></label>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <select name="subscription_trial_2_duration" disabled="" class="form-control">
                                                                            <?php foreach ($paypal_button_subscription_trial_duration as $paypal_button_subscription_trial_duration_key => $paypal_button_subscription_trial_duration_value) { ?>
                                                                                <?php 
                                                                                    if($subscription_trial_2_duration == $paypal_button_subscription_trial_duration_key){
                                                                                        $subscription_trial_2_duration_selected='selected';
                                                                                    }
                                                                                    else{
                                                                                        $subscription_trial_2_duration_selected='';
                                                                                    }
                                                                                ?>
                                                                                <option value="<?php echo $paypal_button_subscription_trial_duration_key; ?>" <?php echo $subscription_trial_2_duration_selected; ?> ><?php echo $paypal_button_subscription_trial_duration_value; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <select id="secondTrialDurationType" name="subscription_trial_2_duration_type" disabled="" class="form-control">
                                                                            <?php foreach ($paypal_button_subscriptions_cycle as $paypal_button_subscriptions_cycle_key => $paypal_button_subscriptions_cycle_value) { ?>
                                                                            <?php 
                                                                                if($subscription_trial_2_duration_type == $paypal_button_subscriptions_cycle_key){
                                                                                    $subscription_trial_2_duration_type_selected = 'selected';
                                                                                }
                                                                                else{
                                                                                    $subscription_trial_2_duration_type_selected = '';
                                                                                }
                                                                            ?>
                                                                                <option value="<?php echo $paypal_button_subscriptions_cycle_key; ?>" <?php echo $subscription_trial_2_duration_type_selected; ?> ><?php echo $paypal_button_subscriptions_cycle_value; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>        
                                                                </div>
                                                            </div>                                                                                    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="group notifications">
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php
                                                        if(isset($account_id) && !empty($account_id)){
                                                        ?>
                                                        <div class="form-group">
                                                            <label for="merchantIDNotificationMethod" class="control-label"><?php echo esc_html__('PayPal Email Address or Merchant Account ID : ','paypal-wp-button-manager'); ?></label>
                                                            <input type="hidden" name="business" id="business"  value="<?php echo $account_id; ?>"/>
                                                            <label style="font-weight: 400;"><?php echo $account_id; ?></label>
                                                        </div>
                                                        <?php } ?>
                                                    </div>                                                    
                                                </div>
                                            </div>                                        
                                </div>    
                            </div>


                            <div id="stepTwo" class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingTwo">
                                    <?php echo '<h4 id="giftBasedHeading" class="accessAid hide panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="width: 100%;display: block;text-decoration: none;">' . __('Step 2: Track inventory, profit & loss (optional)', 'paypal-wp-button-manager') . '</a></h4>'; ?>
                                    <?php echo '<h4 id="productBasedHeading" class="opened panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="width: 100%;display: block;text-decoration: none;">' . __('Step 2: Track inventory, profit & loss (optional)', 'paypal-wp-button-manager') . '</a></h4>'; ?>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">                       
                                    <div class="container">
                                        <div class="step2-left-active">
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input class="checkbox form-control" type="checkbox" id="enableHostedButtons" checked="" name="enable_hosted_buttons" value="enabled" <?php echo $enableHostedButtons_checkbox; ?>>
                                                        <label for="enableHostedButtons" class="control-label"><?php echo __('Save button at PayPal', 'paypal-wp-button-manager'); ?></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="info-list-wrapper">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <ul>
                                                            <li><?php echo __('Protect your buttons from fraudulent changes', 'paypal-wp-button-manager'); ?></li>
                                                            <li><?php echo __('Automatically add buttons to "My Saved Buttons" in your PayPal profile', 'paypal-wp-button-manager'); ?></li>
                                                            <li><?php echo __('Easily create similar buttons', 'paypal-wp-button-manager'); ?></li>
                                                            <li><?php echo __("Edit your buttons with PayPal's tools", 'paypal-wp-button-manager'); ?> </li>
                                                        </ul>
                                                    </div>
                                                </div>                                                                        
                                            </div>
                                            <div class="step2-inventory" id="inventoryOptions">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <?php
                                                            if ($track_inv == '1') {
                                                                $enable_inventory = 'checked';
                                                            } else {
                                                                $enable_inventory = '';
                                                            }
                                                            ?>
                                                            <input class="checkbox form-control" type="checkbox" id="enableInventory" name="enable_inventory" value="enabledInventory" <?php echo $enable_inventory; ?>>
                                                            <label for="enableInventory" class="control-label"><?php echo __('Track inventory', 'paypal-wp-button-manager'); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <p class="hint"><?php echo __("Don't oversell items not in stock -- Get an email alert when inventory is low.", 'paypal-wp-button-manager'); ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <?php
                                                            if ($track_pnl == '1') {
                                                                $enable_profit_and_loss = 'checked';
                                                            } else {
                                                                $enable_profit_and_loss = '';
                                                            }
                                                            ?>
                                                            <input class="checkbox form-control" type="checkbox" id="enableProfitAndLoss" name="enable_profit_and_loss" value="enabledProfitAndLoss" <?php echo $enable_profit_and_loss; ?>>
                                                            <label for="enableProfitAndLoss" class="control-label"><?php echo __('Track profit and losses', 'paypal-wp-button-manager'); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <p class="hint"><?php echo __("View profit and loss report by product/service.", 'paypal-wp-button-manager'); ?></p>
                                                    </div>
                                                </div>                                                                        
                                            </div>
                                        </div>
                                        <div class="step2-extra-fields opened col-md-10" id="inventoryTable">
                                            <?php 
                                                if(!empty($inv_optionselect)){
                                                    $trackByItem_checkbox='';
                                                    $trackByOption_checkbox='checked';    
                                                    $byItemTableBody_class='hide fadedOut';
                                                    $byOptionTableBody_class='opened';
                                                }
                                                else{
                                                    $trackByItem_checkbox='checked';
                                                    $trackByOption_checkbox='';
                                                    $byItemTableBody_class='';
                                                    $byOptionTableBody_class='accessAid hide';
                                                }
                                            ?>
                                            <div id="trackByItemTable" class="fadedOut">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <input class="radio form-control" type="radio" id="trackByItem" <?php echo $trackByItem_checkbox; ?> name="track_button_by" value="trackdByItem" disabled="">
                                                            <label id="byItemLabel" for="trackByItem" class="control-label"><strong><?php echo __('By item', 'paypal-wp-button-manager'); ?></strong></label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="byItemTableBody" class="<?php echo $byItemTableBody_class; ?>">
                                                    <div class="row">                                                                                
                                                        <div class="form-group">
                                                            <div class="col-md-3">
                                                                <label class="control-label"><?php echo __('Item ID', 'paypal-wp-button-manager'); ?></label>
                                                                <input class="form-control" type="text" name="item_id" value="<?php echo $product_id; ?>" disabled="">
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label class="control-label">
                                                                    <div class="invRelated"><?php echo __('Qty. in stock', 'paypal-wp-button-manager'); ?></div>
                                                                </label>
                                                                <div class="invRelated"><input class="form-control" type="text" name="items_in_stock" value="<?php echo $item_qty_step2; ?>" disabled=""></div>
                                                            </div>

                                                            <div class="col-md-3">                                                                                            
                                                                <div class="invRelated"><label><?php echo __('Alert qty. (optional)', 'paypal-wp-button-manager'); ?> <span class="autoTooltip helpText" title="" tabindex="0"><?php echo __("What's this?", 'paypal-wp-button-manager'); ?><span class="accessAid"><?php echo __('When your inventory falls to this number, PayPal will send you an e-mail alert.', 'paypal-wp-button-manager'); ?></span></span></label></div>
                                                                <div class="invRelated"><input class="form-control" type="text" name="alert_quantity" value="<?php echo $item_alert_step2; ?>" disabled=""></div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="PNLRelated"><label><?php echo __('Price', 'paypal-wp-button-manager'); ?> ( <span class="currencyLabel"><?php echo $item_price_currency; ?></span> )</label></div>
                                                                <div class="PNLRelated"><input class="form-control" type="text" name="item_cost" value="<?php echo $item_cost_step2; ?>" disabled=""></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="trackByOptionTable" class="fadedOut accessAid">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <input class="radio form-control" type="radio" id="trackByOption" name="track_button_by" value="trackdByOption" <?php echo $trackByOption_checkbox; ?> disabled=""><label for="trackByOption"><strong><?php echo __('By option', 'paypal-wp-button-manager'); ?></strong><?php echo __('(in drop-down menu)', 'paypal-wp-button-manager'); ?> <a id="chooseAnotherDropDown" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=#chooseAnotherDropDown" class="accessAid"><?php echo __('Choose a different drop-down', 'paypal-wp-button-manager'); ?></a></label>
                                                    </div>                                                    
                                                </div>
                                                <div id="byOptionTableBody" class="<?php echo $byOptionTableBody_class; ?>">
                                                    <div class="inventory-table-row">
                                                        <div class="left-edge">&nbsp;</div>
                                                        <div><strong><?php echo __('Item ID',  'paypal-wp-button-manager'); ?></strong></div>
                                                        <div class="invRelated"><strong><?php echo __('Qty in stock', 'paypal-wp-button-manager'); ?></strong></div>
                                                        <div class="invRelated"><strong><?php echo __('Alert qty. (optional)', 'paypal-wp-button-manager'); ?> <span class="autoTooltip helpText" title="" tabindex="0"><?php echo __("What's this?", 'paypal-wp-button-manager'); ?><span class="accessAid"><?php echo __('When your inventory falls to this number, PayPal will send you an e-mail alert.', 'paypal-wp-button-manager'); ?></span></span></strong></div>
                                                        <div class="PNLRelated"><strong><?php echo __('Cost', 'paypal-wp-button-manager');?> </strong></div>
                                                        <div class="right-edge">&nbsp;</div>
                                                    </div>
                                                    <?php 
                                                        if($string=='edit' && !empty($inv_optionselect)){
                                                            for($i=0;$i<count($inv_optionselect);$i++){
                                                            ?>   
                                                                <div class="inventory-table-row">
                                                                <div class="left-edge"><?php echo $inv_optionselect[$i]; ?></div>
                                                                <div><input class="type-text form-control" type="text" name="item_id" value="<?php echo $inv_optionnumber[$i]; ?>"></div>
                                                                <div class="invRelated"><input class="type-text form-control" type="text" name="items_in_stock" value="<?php echo isset($inv_optionqty[$i]) ? $inv_optionqty[$i] : ''; ?>"></div>
                                                                <div class="invRelated"><input class="type-text form-control" type="text" name="alert_quantity" value="<?php echo isset($inv_optionalert[$i]) ? $inv_optionalert[$i] : ''; ?>"></div>
                                                                <div class="PNLRelated"><input class="type-text form-control" type="text" name="item_cost" value="<?php echo isset($inv_optioncost[$i]) ? $inv_optioncost[$i] : ''; ?>"></div>
                                                                <div class="right-edge"><?php echo $item_price_currency; ?></div>
                                                                </div>
                                                            <?php
                                                            }
                                                        }
                                                        else{
                                                    ?>
                                                    <div class="inventory-table-row">
                                                        <div class="left-edge">&nbsp;</div>
                                                        <div><input class="type-text form-control" type="text" name="item_id" value="" disabled=""></div>
                                                        <div class="invRelated"><input class="type-text form-control" type="text" name="items_in_stock" value="" disabled=""></div>
                                                        <div class="invRelated"><input class="type-text form-control" type="text" name="alert_quantity" value="" disabled=""></div>
                                                        <div class="PNLRelated"><input class="type-text form-control" type="text" name="item_cost" value="" disabled=""></div>
                                                        <div class="right-edge">&nbsp;</div>
                                                    </div>
                                                        <?php } ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="step2-bottom-fields fadedOut opened" id="soldOutOption">
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <h5 id="shoppingHead" class="opened"><?php echo __('Can customers buy an item when it is sold out?', 'paypal-wp-button-manager'); ?></h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <div class="form-group">                                                                            
                                                        <div class="pre-order opened" id="shoppingPreOrder">
                                                            <?php
                                                            if (!empty($item_soldout_url_step2)) {
                                                                $dontEnablePreOrder = 'checked';
                                                                $enablePreOrder = '';
                                                            } else {
                                                                $dontEnablePreOrder = '';
                                                                $enablePreOrder = 'checked';
                                                            }
                                                            ?>
                                                            <input class="radio form-control" type="radio" id="enablePreOrder" name="enable_pre_order" value="enabledPreOrder" disabled="" <?php echo $enablePreOrder; ?>>
                                                            <label for="enablePreOrder" class="control-label"><?php echo __('Yes, customers can buy the item as usual.', 'paypal-wp-button-manager'); ?></label>
                                                        </div>
                                                        <div class="no-pre-order">
                                                            <input class="radio opened form-control" type="radio" id="dontEnablePreOrder" <?php echo $dontEnablePreOrder; ?> name="enable_pre_order" value="dontEnablePreOrder" disabled=""><label id="shoppingNoPreOrderLabel" for="dontEnablePreOrder" class="opened control-label"><?php echo __("No, don't let customers buy the item.", 'paypal-wp-button-manager'); ?> <a target="_blank" class="infoLink" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=xpt/Merchant/popup/BDSoldOutExample" onclick="PAYPAL.core.openWindow(event, {width: 560, height: 410})">Preview</a></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <p class="hint opened fadedOut" id="shoppingURL">
                                                    <span class="littleHint">
                                                        <?php echo __('Take customers to specific page when they click', 'paypal-wp-button-manager'); ?>
                                                        <strong><?php echo __('Continue Shopping', 'paypal-wp-button-manager'); ?></strong>
                                                        <?php echo __(' button on "item sold out" page', 'paypal-wp-button-manager'); ?>
                                                    </span>
                                                    <input class="type-text form-control" type="text" id="soldOutURL" name="sold_out_url" value="<?php echo $item_soldout_url_step2; ?>" disabled="">
                                                    <span class="littleHint"><?php _e('Ex: http://www.mybuynowstore.com', 'paypal-wp-button-manager'); ?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>                       
                                </div>
                            </div>
                            <div id="stepThree" class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree">
                                    <?php echo '<h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="width: 100%;display: block;text-decoration: none;">' . __('Step 3: Customize advanced features (optional)', 'paypal-wp-button-manager') . '</a></h4>'; ?>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">                                  
                                    <div class="container">
                                        <div class="row">
                                            <diV class="col-md-9">
                                                <strong><?php echo esc_html__('Customize checkout pages','paypal-wp-button-manager'); ?></strong>
                                            </diV>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <p><?php echo esc_html__('If you are an advanced user, you can customize checkout pages for your customers, streamline checkout, and more in this section.','paypal-wp-button-manager'); ?></p>
                                            </div>
                                        </div>

                                        <div id="changeOrderQuantitiesContainer" class="hide">
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <b><?php echo esc_html__('Do you want to let your customer change order quantities?','paypal-wp-button-manager'); ?></b>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <div class="form-group">                                                                                
                                                        <input class="radio form-control" type="radio" id="changeOrderQuantities" name="undefined_quantity" value="1">
                                                        <label class="control-label" for="keepOrderQuantities"><?php echo esc_html__('Yes','paypal-wp-button-manager'); ?></label>
                                                        <input class="radio" type="radio" id="keepOrderQuantities" checked="" name="undefined_quantity" value="0">
                                                        <label class="control-label" for="changeOrderQuantities"><?php echo esc_html__('No','paypal-wp-button-manager'); ?></label>
                                                    </div>
                                                </div>
                                            </div>                                                                    
                                        </div>
                                        <div id="specialInstructionsContainer" class="opened">                                                                    
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <p><b><?php echo esc_html__('Can your customer add special instructions in a message to you?','paypal-wp-button-manager'); ?></b></p>
                                                    <div class="form-group">
                                                        <?php
                                                        if ($no_note == 0) {
                                                            $cn_add_checked = 'checked';
                                                            $cn_no_checked = '';
                                                            $cn_class = 'opened';
                                                        } else {
                                                            $cn_add_checked = '';
                                                            $cn_no_checked = 'checked';
                                                            $cn_class = 'hide';
                                                        }
                                                        ?>    
                                                        <input class="radio form-control" type="radio" id="addSpecialInstructions" <?php echo $cn_add_checked; ?> name="no_note" value="0">
                                                        <label class="control-label" for="addSpecialInstructions"><?php echo esc_html__('Yes','paypal-wp-button-manager'); ?></label>
                                                        &nbsp; &nbsp;
                                                        <input class="radio form-control" type="radio" id="noSpecialInstructions" <?php echo $cn_no_checked; ?> name="no_note" value="1">
                                                        <label class="control-label" for="noSpecialInstructions"><?php echo esc_html__('No','paypal-wp-button-manager'); ?></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row <?php echo $cn_class; ?>" id="messageBoxContainer">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <label  for="messageBox" class="control-label"><?php echo esc_html__('Name of message box (40-character limit)','paypal-wp-button-manager'); ?></label>
                                                        <input type="text" id="messageBox" size="40" maxlength="40" class="form-control" name="custom_note" value="<?php echo $add_special_instruction; ?>" placeholder="<?php echo $add_special_instruction_place_holder; ?>">
                                                    </div>
                                                </div>
                                            </div>                                                                    
                                        </div>
                                        <div id="shippingAddressContainer" class="opened">

                                            <div class="row">
                                                <div class="col-md-9">
                                                    <p><b><?php echo esc_html__('Do you need your customer\'s shipping address?','paypal-wp-button-manager'); ?></b></p>
                                                    <div class="form-group">
                                                        <?php
                                                        if ($customersShippingAddress == '2') {
                                                            $shippingYes = 'checked';
                                                            $shippingNo = '';
                                                        }
                                                        if ($customersShippingAddress == '1') {
                                                            $shippingYes = '';
                                                            $shippingNo = 'checked';
                                                        }
                                                        ?>
                                                        <input class="radio form-control" type="radio" id="needShippingAddress" <?php echo $shippingYes; ?> name="no_shipping" value="2">
                                                        <label class="control-label" for="needShippingAddress"><?php echo esc_html__('Yes','paypal-wp-button-manager'); ?></label>

                                                        <input class="radio form-control" type="radio" id="noShippingAddress" <?php echo $shippingNo; ?> name="no_shipping" value="1">
                                                        <label class="control-label" for="noShippingAddress"><?php echo esc_html__('No','paypal-wp-button-manager'); ?></label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div id="cancellationRedirectURLContainer" class="opened">
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <?php
                                                        if (!empty($cancel_return)) {
                                                            $cancellationCheckbox = 'checked';
                                                            $cancleFormcontrol = '';
                                                        } else {
                                                            $cancellationCheckbox = '';
                                                            $cancleFormcontrol = 'disabled';
                                                        }
                                                        ?>
                                                        <input class="checkbox form-control" type="checkbox" id="cancellationCheckbox" name="cancellation_return" value="1" <?php echo $cancellationCheckbox; ?> >
                                                        <label for="cancellationCheckbox" class="control-label"><?php echo esc_html__('Take customers to this URL when they cancel their checkout','paypal-wp-button-manager'); ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="redirectContainer">
                                                <input type="text" id="cancellationRedirectURL" size="30" class="form-control" <?php echo $cancleFormcontrol; ?> name="cancel_return" value="<?php echo $cancel_return; ?>">
                                                <div><?php echo esc_html__('Example: ','paypal-wp-button-manager'); ?>https://www.mystore.com/cancel</div>
                                            </div>
                                        </div>
                                        <div id="successfulRedirectURLContainer" class="opened">
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <?php
                                                        if (!empty($return)) {
                                                            $successfulCheckbox = 'checked';
                                                            $returnFormcontrol = '';
                                                        } else {
                                                            $successfulCheckbox = '';
                                                            $returnFormcontrol = 'disabled';
                                                        }
                                                        ?>
                                                        <input class="checkbox form-control" type="checkbox" id="successfulCheckbox" name="successful_return" value="1" <?php echo $successfulCheckbox; ?> >
                                                        <label for="successfulCheckbox" class="control-label"><?php echo esc_html__('Take customers to this URL when they finish checkout','paypal-wp-button-manager'); ?></label>
                                                    </div>
                                                </div>
                                            </div>                                                                    
                                            <div class="redirectContainer">
                                                <input type="text" id="successfulRedirectURL" size="30" class="form-control" <?php echo $returnFormcontrol; ?> name="return" value="<?php echo $return; ?>">
                                                <div><?php echo esc_html__('Example: ','paypal-wp-button-manager'); ?> https://www.mystore.com/success</div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>               
                                        <!--  <input type="submit" value="Create Button" class="button-primary create_button" name="publish">-->

                        </div>
                        <input name="auth" type="hidden" value="A-rjNZhZLRt86QdaItbFAxbuyoRwDPaz.dCe2iQoCD7uF8ECex-ZSw9OPM48gvdgrXEkoaVqwAJFtLx1spKPOsUQFkgigL0Oz.FnzFLIbiDs"><input name="form_charset" type="hidden" value="UTF-8">

                        <!--                    </form>-->
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">var imageUrls = {en: {BuyNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fbuynowCC\x5fLG\x2egif"}, PayNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fpaynowCC\x5fLG\x2egif"}, AddToCart: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fcart\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif"}, Donate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fdonateCC\x5fLG\x2egif"}, GiftCertificate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fgift\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fgift\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fgiftCC\x5fLG\x2egif"}, Subscribe: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fsubscribeCC\x5fLG\x2egif"}, PaymentPlan: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fCC\x5fLG\x2egif"}, AutoBilling: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fCC\x5fLG\x2egif"}}, fr: {BuyNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynowCC\x5fLG\x2egif"}, PayNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynowCC\x5fLG\x2egif"}, AddToCart: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif"}, Donate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonateCC\x5fLG\x2egif"}, GiftCertificate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgift\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgift\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgiftCC\x5fLG\x2egif"}, Subscribe: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribeCC\x5fLG\x2egif"}, PaymentPlan: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fCC\x5fLG\x2egif"}, AutoBilling: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fCC\x5fLG\x2egif"}}, es: {BuyNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynowCC\x5fLG\x2egif"}, PayNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynowCC\x5fLG\x2egif"}, AddToCart: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif"}, Donate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonateCC\x5fLG\x2egif"}, GiftCertificate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgift\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgift\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgiftCC\x5fLG\x2egif"}, Subscribe: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribeCC\x5fLG\x2egif"}, PaymentPlan: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fCC\x5fLG\x2egif"}, AutoBilling: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fCC\x5fLG\x2egif"}}, zh: {BuyNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynowCC\x5fLG\x2egif"}, PayNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynowCC\x5fLG\x2egif"}, AddToCart: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif"}, Donate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonateCC\x5fLG\x2egif"}, GiftCertificate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgift\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgift\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgiftCC\x5fLG\x2egif"}, Subscribe: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribeCC\x5fLG\x2egif"}, PaymentPlan: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fCC\x5fLG\x2egif"}, AutoBilling: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fCC\x5fLG\x2egif"}}, int: {BuyNow: {cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fbuynowCC\x5fLG\x5fglobal\x2egif"}, Donate: {cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fdonateCC\x5fLG\x5fglobal\x2egif"}, GiftCertificate: {cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fgiftCC\x5fLG\x5fglobal\x2egif"}, PayNow: {cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fpaynowCC\x5fLG\x5fglobal\x2egif"}, Subscribe: {cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fsubscribeCC\x5fLG\x5fglobal\x2egif"}}};</script>
        <?php
        wp_enqueue_script('button-designer-js', BMW_PLUGIN_URL . 'admin/js/paypal-wp-button-manager-buttonDesigner.js', array(), '1.0', true);
    }

}

AngellEYE_PayPal_WP_Button_Manager_button_interface::init();
