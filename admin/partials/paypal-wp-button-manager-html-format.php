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
    }
    
    public static function paypal_wp_button_manager_for_wordpress_button_interface_html_before() {
        global $wpdb;
        $companies = $wpdb->prefix . 'paypal_wp_button_manager_companies'; // do not forget about tables prefix
        $result_records = $wpdb->get_results("SELECT * FROM `{$companies}` WHERE paypal_mode !=''", ARRAY_A);
        ?> <div class="div_companies_dropdown col-lg-4" >

            <div class="div_companyname form-group">
                <label for="paypalcompanyname" class="control-label"><strong>Choose Company Name:</strong></label>
                <select id="ddl_companyname" name="ddl_companyname" class="form-control">
                    <option value="">--Select Company--</option>
                    <?php foreach ($result_records as $result_records_value) { ?>
                    <option value="<?php echo $result_records_value['ID']; ?>" selected=""><?php echo $result_records_value['title']; ?></option>
                    <?php }
                    ?>
                </select>
            </div>
        </div>
        <?php
    }

    /**
     * paypal_wp_button_manager_for_wordpress_button_interface_html function is for
     * html of interface.
     * @since 1.0.0
     * @access public
     */
    public static function paypal_wp_button_manager_for_wordpress_button_interface_html($string) {
        // Define below variable that will not conflict/gives undefined index error while add/edit buttons.
        $button_option_value='';
        $edit_button=false;
        $no_note=0;
        $shippingYes='checked';
        $shippingNo='';
        $cancleFormcontrol='';
        $cancellationCheckbox='';
        $successfulCheckbox='';
        $returnFormcontrol='';
        $button_img_src='';
        $paypalButtonSection_class='opened';
        $customButtonSection_class='hide';        
        $donation_name='';
        $donation_id='';
        $buttonImageSize ='';
        $buttonImageUrl='';
        $donation_amount ='';
        $account_id='';
        $customersShippingAddress='';
        $cancel_return='';
        $return='';
        $add_special_instruction='Add special instructions to the seller:';
        $enableHostedButtons_checkbox='';   
        $track_inv         = '';
        $track_pnl         = '';
        $item_number_step2 = '';
        $item_qty_step2    = '';
        $item_alert_step2  = '';
        $item_cost_step2   = '';
        $item_soldout_url_step2='';
        
        if($string=='edit'){            
            $enableHostedButtons_checkbox='disabled';
            $edit_button=true;
            $meta = get_post_meta(get_the_ID());
            $edit_hosted_button_id=$meta['paypal_wp_button_manager_button_id'][0];  
            $payapal_helper = new AngellEYE_PayPal_WP_Button_Manager_PayPal_Helper();
            $PayPalConfig = $payapal_helper->paypal_wp_button_manager_get_paypalconfig();            
            $PayPal = new Angelleye_PayPal($PayPalConfig);
            $button_details_array=$PayPal->BMGetButtonDetails($edit_hosted_button_id);           
            foreach($button_details_array as $key => $value){
                $btnvar_key = explode('BUTTONVAR', $key);
                if($btnvar_key[0] == 'L_'){
                    $btnvar_val=explode('=', $value);
                    $BUTTONVAR[substr($btnvar_val[0],1)] = substr($btnvar_val[1], 0, -1);
                }
                
                $option0select_key = explode('OPTION0SELECT', $key);
                if($option0select_key[0] == 'L_'){
                    $OPTION0SELECT[] = substr(substr($value,1),0, -1);
                }
                
                $option0price_key = explode('OPTION0PRICE', $key);
                if($option0price_key[0] == 'L_'){
                    $OPTION0PRICE[] = substr(substr($value,1),0, -1);
                }
                
            }
            
            $dom = new DOMDocument();
            $dom->loadHTML($button_details_array['WEBSITECODE']);
            $imgs = $dom->getElementsByTagName('input');
            foreach ($imgs as $img) {            
                $temp_src = $img->getAttribute('src');
                if(!empty($temp_src)){
                    $button_img_src=$temp_src;
                }
            }            
            
            $buttonType=isset($button_details_array['BUTTONTYPE']) ? $button_details_array['BUTTONTYPE'] : '';
            $buttonCountry=isset($button_details_array['BUTTONCOUNTRY']) ? $button_details_array['BUTTONCOUNTRY'] : '';
            $buttonLanguage=isset($button_details_array['BUTTONLANGUAGE']) ? $button_details_array['BUTTONLANGUAGE'] : '';
            $buttonImageSize=isset($button_details_array['BUTTONIMAGE']) ? $button_details_array['BUTTONIMAGE'] : '';
            $buttonImageUrl=isset($button_details_array['BUTTONIMAGEURL']) ? $button_details_array['BUTTONIMAGEURL'] : '';                        
            
            $account_id= isset($BUTTONVAR['business']) ? $BUTTONVAR['business'] : '';
            $no_note= isset($BUTTONVAR['no_note']) ? $BUTTONVAR['no_note'] : '';
            $add_special_instruction= isset($BUTTONVAR['cn']) ? $BUTTONVAR['cn'] : 'Add special instructions to the seller:';
            $customersShippingAddress=isset($BUTTONVAR['no_shipping']) ? $BUTTONVAR['no_shipping'] : '';
            $cancel_return = isset($BUTTONVAR['cancel_return']) ? $BUTTONVAR['cancel_return'] : '';
            $return = isset($BUTTONVAR['return']) ? $BUTTONVAR['return'] : '';
            
            
            
            if($buttonType=='DONATE'){
                $button_option_value='donations';
                $donation_name = isset($BUTTONVAR['item_name']) ? $BUTTONVAR['item_name'] : '';
                $donation_id   = isset($BUTTONVAR['item_number']) ? $BUTTONVAR['item_number'] : '';
                $donation_amount = isset($BUTTONVAR['amount']) ? $BUTTONVAR['amount'] : '';
                $donation_currency = isset($BUTTONVAR['currency_code']) ? $BUTTONVAR['currency_code'] : '';                
            }
            
            if($buttonType=='BUYNOW'){
                $button_option_value='services';
                $product_name = isset($BUTTONVAR['item_name']) ? $BUTTONVAR['item_name'] : '';
                $product_id = isset($BUTTONVAR['item_number']) ? $BUTTONVAR['item_number'] : '';
                $item_price = isset($BUTTONVAR['amount']) ? $BUTTONVAR['amount'] : '';
                $item_price_currency = isset($BUTTONVAR['currency_code']) ? $BUTTONVAR['currency_code'] : '';
                $item_shipping_amount = isset($BUTTONVAR['shipping']) ? $BUTTONVAR['shipping'] : '';  
                $itemTaxRate = isset($BUTTONVAR['tax_rate']) ? $BUTTONVAR['tax_rate'] : '';
                $inventory_set=true;
                $DataArray=array();
                $PayPal_get_inventory=$PayPal->BMGetInventory($DataArray,$edit_hosted_button_id);
                if (isset($PayPal_get_inventory['ERRORS']) && !empty($PayPal_get_inventory['ERRORS'])) {
                    if($PayPal_get_inventory['L_ERRORCODE0']=='11991'){
                        $inventory_set=false;
                    }                    
                }
                else{
                    $track_inv         =  isset($PayPal_get_inventory['TRACKINV']) ? $PayPal_get_inventory['TRACKINV'] : '';
                    $track_pnl         =  isset($PayPal_get_inventory['TRACKPNL']) ? $PayPal_get_inventory['TRACKPNL'] : '';
                    $item_number_step2 =  isset($PayPal_get_inventory['ITEMNUMBER']) ? $PayPal_get_inventory['ITEMNUMBER'] : '';
                    $item_qty_step2    =  isset($PayPal_get_inventory['ITEMQTY']) ? $PayPal_get_inventory['ITEMQTY'] : '';
                    $item_alert_step2  =  isset($PayPal_get_inventory['ITEMALERT']) ? $PayPal_get_inventory['ITEMALERT'] : '';
                    $item_cost_step2   =  isset($PayPal_get_inventory['ITEMCOST']) ? $PayPal_get_inventory['ITEMCOST'] : '';
                    $item_soldout_url_step2 = isset($PayPal_get_inventory['SOLDOUTURL']) ? $PayPal_get_inventory['SOLDOUTURL'] : '';
                    //echo "<pre>";            
                    var_dump($PayPal_get_inventory);
                    //exit;
                }
                
             }
            
            //echo "<pre>";            
            //var_dump($button_details_array);
            //exit;
        }
                
        ?>
         <div id="wrap">
            <div id="main" class="legacyErrors">
                <div class="layout1">
                    <script type="text/javascript">var oPage = document.getElementById('main').getElementsByTagName('div')[0];var oContainer = document.createElement('div');oContainer.id = 'pageLoadMsg';oContainer.innerHTML = "Loading...";oPage.appendChild(oContainer);</script>
                    <div id="pageLoadMsg" class="accessAid">Loading...</div>
                    <div class="accessAid" id="ddLightbox">
                        <div class="header">
                            <h2>Change dropdown</h2>
                        </div>
                        <div class="">
                            <p>You can assign inventory options in only one dropdown.<br><br><span id="lightboxChoiceBody">Choose:</span></p>
                            <div class="buttons"><button class="default primary" type="submit" id="ddLightboxSubmit" name="done">Done</button>
                                <button class="closer" type="button" id="ddLightboxCancel" name="cancel">Cancel</button>
                            </div>
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
    <input type="hidden" name="button_type" value="<?php echo $button_option_value;?>">
    <?php if($string=='edit'){ ?>
        <input type="hidden" name="enable_hosted_buttons" value="enabled">
    <?php } ?>    
    <div id="accordion" class="panel-group"  role="tablist" aria-multiselectable="true">
        
            
                

                    <div id="stepOne" class="panel panel-primary">
                        <div class="header panel-heading" role="tab" id="headingOne">

                            <?php echo '<h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">'. __('Step 1: Choose a button type and enter your payment details'). '</a></h4>'; ?>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">                                                                 
                            <div class="panel-body">           <div class="container">
                                            <div class="group buttonType <?php  if($edit_button) { echo 'fadedOut'; } ?>">
                                                <div class="col-lg-4">
                                                <label for="buttonType" class="control-label">Choose a button type</label>
                                                <?php $paypal_button_options = get_paypal_button_options(); ?>
                                                <select id="buttonType" name="button_type" class="form-control">
                                                    <?php foreach ($paypal_button_options as $paypal_button_options_key => $paypal_button_options_value) { ?>
                                                        <?php 
                                                            if($paypal_button_options_key==$button_option_value)
                                                            {
                                                                $button_type_selected='selected';
                                                            }
                                                            else{
                                                                $button_type_selected='';
                                                            }
                                                        ?>
                                                        <option value="<?php echo $paypal_button_options_key;?>" <?php echo $button_type_selected; ?>><?php echo $paypal_button_options_value; ?></option>
                                                    <?php } ?>

                                                </select>                                                
                                                </div>        
                                            </div>
                                            <div class="products"><input class="hide radio subButtonType" type="radio" id="radioAddToCartButton" checked="" name="sub_button_type" value="add_to_cart"><input class="hide radio subButtonType" type="radio" id="radioBuyNowButton" name="sub_button_type" value="buy_now"></div>
                                            <div class="group details">
                                                <div class="products">
                                                    <div class="col-lg-4">
                                                        <label for="itemName" class="control-label">Item name</label>
                                                        <input class="form-control" maxlength="127" type="text" id="itemName" name="product_name" value="<?php echo $product_name; ?>">
                                                    </div>
                                                    <div class="col-lg-4"><label for="itemID">Item ID<span class="fieldNote"> (optional) </span></label><input class="form-control" maxlength="127" type="text" id="itemID" size="9" name="product_id" value="<?php echo $product_id; ?>"></div>
                                                </div>
                                                            <div class="donations accessAid fadedOut">
                                                                <div class="col-lg-4"><label for="donationName" class="control-label">Organization name/service</label><input class="form-control" maxlength="127" type="text" id="donationName" name="donation_name" value="<?php echo $donation_name; ?>" disabled=""></div>
                                                                <div class="col-lg-4"><label for="donationID" class="control-label">Donation ID<span class="fieldNote"> (optional) </span>
                                                                    </label>
                                                                    <input class="form-control" maxlength="127" type="text" id="donationID" size="27" name="donation_id" value="<?php echo $donation_id; ?>" disabled=""></div>
                                                            </div>
                                                            <div class="subscriptions accessAid fadedOut">
                                                                <div class="col-lg-4"><label for="subscriptionName" class="control-label">Item name</label><input class="form-control" maxlength="127" type="text" id="subscriptionName" name="subscription_name" value="" disabled=""></div>
                                                                <div class="col-lg-4"><label for="subscriptionID" class="control-label">Subscription ID<span class="fieldNote"> (optional) </span></label><input class="form-control" maxlength="127" type="text" id="subscriptionID" size="27" name="subscription_id" value="" disabled=""></div>
                                                            </div>
                                                <div class="gift_certs accessAid fadedOut col-lg-9"><label for="giftCertificateShopURL" class="control-label">Enter the URL where recipients can shop and redeem this gift certificate.</label><input class="form-control" type="text" id="giftCertificateShopURL" size="34" name="gift_certificate_shop_url" value="http://" disabled=""></div>
                                                    </div>
                                                    <div class="group products pricing opened">
                                                        <div class="col-lg-4"><label for="itemPrice" class="control-label">Price</label><input class="form-control" type="text" id="itemPrice" size="9" name="item_price" value="<?php echo $item_price; ?>"></div>
                                                        <div class="col-lg-4">
                                                            <label for="itemPriceCurrency" class="control-label">Currency</label>
                                                            <?php $paypal_button_currency_with_symbole = get_paypal_button_currency_with_symbole(); ?>
                                                            <select id="BillingAmountCurrency" name="item_price_currency" class="currencySelect form-control">

                                                                <?php foreach ($paypal_button_currency_with_symbole as $paypal_button_currency_with_symbole_key => $paypal_button_currency_with_symbole_value) { ?>
                                                                     <?php 
                                                                            if($paypal_button_currency_with_symbole_key==$item_price_currency)
                                                                            {
                                                                                $item_currency_selected='selected';
                                                                            }
                                                                            else{
                                                                                $item_currency_selected='';
                                                                            }
                                                                      ?>
                                                                    <option value="<?php echo $paypal_button_currency_with_symbole_key; ?>" <?php echo $item_currency_selected; ?> title="<?php echo $paypal_button_currency_with_symbole_value; ?>"><?php echo $paypal_button_currency_with_symbole_key; ?></option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="group subscriptions accessAid fadedOut col-lg-4">
                                                        <label for="subscriptionBillingAmountCurrency" class="control-label">Currency</label>
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
                                                                        <p class="heading"><strong>Customize button</strong></p>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div id="customizeSection">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p id="addDropdownPrice" class="hideShow opened">
                                                                                <label for="dropdownPrice" class="control-label">
                                                                                    <input class="checkbox form-control" type="checkbox" id="dropdownPrice" name="dropdown_price" value="createdDropdownPrice">
                                                                                    <span class="products">Add drop-down menu with price/option&nbsp;</span>
                                                                                    <span class="subscriptions accessAid fadedOut">Add a dropdown menu with prices and options</span>
                                                                                </label>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div id="dropdownPriceSection" class="hideShow accessAid hide">
                                                                        <p class="title dropdownPriceTitle col-md-9"><label for="dropdownPriceTitle" class="control-label"><span class="products">Name of drop-down menu (ex.: "Colors," "Sizes")</span><span class="subscriptions accessAid fadedOut">Description (For example, "Payment options".)</span></label><input class="text form-control" maxlength="64" type="text" id="dropdownPriceTitle" disabled="" name="dropdown_price_title" value=""></p>
                                                                        <p><label class="optionNameLbl control-label" for=""><span class="products">Menu option name</span><span class="subscriptions accessAid fadedOut">Menu Name</span></label><label class="optionPriceLbl control-label" for="optionPrice"><span class="products">Price</span><span class="subscriptions accessAid fadedOut">Amount (<span class="currencyLabel control-label">USD</span>)</span></label><label class="optionCurrencyLbl control-label" for="optionCurrency"><span class="products">Currency</span><span class="subscriptions accessAid fadedOut control-label">Frequency</span></label></p>
                                                                        <div id="optionsPriceContainer">
                                                                            <p class="optionRow col-sm-12 form-inline">
                                                                                <input maxlength="64" type="text" class="ddpOptionName text form-control" disabled="" name="ddp_option_name" value="Option 1">
                                                                                <input type="text" class="ddpOptionPrice text form-control" disabled="" name="ddp_option_price" value="">
                                                                                <?php $paypal_button_currency = get_paypal_button_currency(); ?>
                                                                                <select class="ddpOptionCurrency show form-control" name="ddp_option_currency">
                                                                                    <?php foreach ($paypal_button_currency as $paypal_button_currency_key => $paypal_button_currency_value) { ?>
                                                                                        <option value="<?php echo $paypal_button_currency_value; ?>" title="<?php echo $paypal_button_options_key; ?>"><?php echo $paypal_button_currency_value; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                                <?php $paypal_button_subscriptions = get_paypal_button_subscriptions(); ?>
                                                                                <select class="subscriptions ddpOptionFrequency form-control" name="ddp_option_frequency" disabled="">
                                                                                    <?php foreach ($paypal_button_subscriptions as $paypal_button_subscriptions_key => $paypal_button_subscriptions_value) { ?>
                                                                                        <option value="<?php echo $paypal_button_subscriptions_key; ?>"><?php echo $paypal_button_subscriptions_value; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </p>
                                                                            <p class="optionRow clearfix">
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
                                                                            </p>
                                                                        </div>
                                                                        <p class="moreOptionsLink">
                                                                            <a id="addOptionPrice" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span></a>
                                                                            <a id="removeOptionPrice" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=" class="btn btn-danger"><span class="glyphicon glyphicon-remove-sign"></span></a></p>
                                                                        <p class="saveCancel"><input class="btn btn-default" type="submit" id="saveOptionPrice" name="save_option_price" value="Done" alt="Done"><a id="cancelOptionPrice" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=" class="btn btn-danger">Cancel</a></p>
                                                                    </div>
                                                                    <div id="savedDropdownPriceSection" class="hideShow accessAid hide">
                                                                        <p><label id="savedDropdownPrice" for=""></label></p>
                                                                        <p class="editDelete"><a id="editDropdownPrice" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=" class="btn btn-info"><span class="products">Edit</span><span class="subscriptions accessAid fadedOut">Change</span></a>&nbsp;|&nbsp;<a id="deleteDropdownPrice" class="btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><span class="products glyphicon glyphicon-remove-sign"></span><span class="subscriptions accessAid fadedOut glyphicon glyphicon-remove"></span></a></p>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p id="addDropdown" class="hideShow opened">
                                                                                <label for="dropdown" class="control-label">
                                                                                    <input class="checkbox form-control" type="checkbox" id="dropdown" name="dropdown" value="createdDropdown">
                                                                                    <span class="hideShow accessAid hide" id="dropDownLabelForSubscription">Add a dropdown menu </span>
                                                                                    <span id="dropDownLabel" class="opened">Add drop-down menu&nbsp;</span>
                                                                                </label>
                                                                            </p>
                                                                        </div>
                                                                    </div>    
                                                                    
                                                                    <div class="hideShow dropdownSection accessAid hide" id="dropdownSection1">
                                                                        <p class="title col-md-9"><label for="" class="control-label">Name of drop-down menu (ex.: "Colors," "Sizes")</label><input maxlength="64" type="text" class="dropdownTitle text form-control" disabled="" name="dropdown1_title" value=""></p>
                                                                        <p class="title col-md-9"><label for="" class="control-label">Menu option name</label></p>
                                                                        <div id="optionsContainer1">
                                                                            <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd1_option_name" value="Option 1"></p>
                                                                            <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd1_option_name" value="Option 2"></p>
                                                                            <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd1_option_name" value="Option 3"></p>
                                                                        </div>
                                                                        <p class="moreOptionsLink"><a class="addOption btn btn-success" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Add option</a></p>
                                                                        <p class="saveCancel"><input class="saveOption btn btn-default" type="submit" name="save_option" value="Done" alt="Done"><a class="cancelOption btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Cancel</a></p>
                                                                    </div>
                                                                    <div class="hideShow accessAid savedDropdownSection hide" id="savedDropdownSection1">
                                                                        <p><label id="savedDropdown1" for="" class="control-label"></label></p>
                                                                        <p class="editDelete"><a class="editDropdown btn btn-info" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Edit</a>&nbsp;|&nbsp;<a class="deleteDropdown btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd="><span class="glyphicon glyphicon-remove-sign"></span></a></p>
                                                                    </div>
                                                                    <div class="hideShow dropdownSection accessAid hide" id="dropdownSection2">
                                                                        <p class="title col-md-9"><label for="" class="control-label">Name of drop-down menu (ex.: "Colors," "Sizes")</label><input maxlength="64" type="text" class="dropdownTitle text form-control" disabled="" name="dropdown2_title" value=""></p>
                                                                        <p class="title col-md-9"><label for="" class="control-label">Menu option name</label></p>
                                                                        <div id="optionsContainer2">
                                                                            <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd2_option_name" value="Option 1"></p>
                                                                            <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd2_option_name" value="Option 2"></p>
                                                                            <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd2_option_name" value="Option 3"></p>
                                                                        </div>
                                                                        <p class="moreOptionsLink"><a class="addOption btn btn-success" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Add option</a></p>
                                                                        <p class="saveCancel"><input class="saveOption btn btn-default" type="submit" name="save_option_2" value="Done" alt="Done"><a class="cancelOption btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Cancel</a></p>
                                                                    </div>
                                                                    <div class="hideShow accessAid savedDropdownSection hide" id="savedDropdownSection2">
                                                                        <p><label id="savedDropdown2" for="" class="control-label"></label></p>
                                                                        <p class="editDelete"><a class="editDropdown btn btn-info" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Edit</a>&nbsp;|&nbsp;<a class="deleteDropdown btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Delete</a></p>
                                                                    </div>
                                                                    <div class="hideShow dropdownSection accessAid hide" id="dropdownSection3">
                                                                        <p class="title col-md-9"><label for="" class="control-label">Name of drop-down menu (ex.: "Colors," "Sizes")</label><input maxlength="64" type="text" class="dropdownTitle text form-control" disabled="" name="dropdown3_title" value=""></p>
                                                                        <p class="title col-md-9"><label for="" class="control-label">Menu option name</label></p>
                                                                        <div id="optionsContainer3">
                                                                            <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd3_option_name" value="Option 1"></p>
                                                                            <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd3_option_name" value="Option 2"></p>
                                                                            <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd3_option_name" value="Option 3"></p>
                                                                        </div>
                                                                        <p class="moreOptionsLink"><a class="addOption btn btn-success" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Add option</a></p>
                                                                        <p class="saveCancel"><input class="saveOption  btn btn-default" type="submit" name="save_option_3" value="Done" alt="Done"><a class="cancelOption btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Cancel</a></p>
                                                                    </div>
                                                                    <div class="hideShow accessAid savedDropdownSection hide" id="savedDropdownSection3">
                                                                        <p><label id="savedDropdown3" for=""></label></p>
                                                                        <p class="editDelete"><a class="editDropdown btn btn-info" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Edit</a>&nbsp;|&nbsp;<a class="deleteDropdown btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Delete</a></p>
                                                                    </div>
                                                                    <div class="hideShow dropdownSection accessAid hide" id="dropdownSection4">
                                                                        <p class="title col-md-9"><label for="" class="control-label">Name of drop-down menu (ex.: "Colors," "Sizes")</label><input maxlength="64" type="text" class="dropdownTitle text form-control" disabled="" name="dropdown4_title" value=""></p>
                                                                        <p class="title col-md-9"><label for="" class="control-label">Menu option name</label></p>
                                                                        <div id="optionsContainer4">
                                                                            <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd4_option_name" value="Option 1"></p>
                                                                            <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd4_option_name" value="Option 2"></p>
                                                                            <p class="optionRow dropdown col-md-9"><input maxlength="64" type="text" class="ddOptionName text form-control" disabled="" name="dd4_option_name" value="Option 3"></p>
                                                                        </div>
                                                                        <p class="moreOptionsLink"><a class="addOption btn btn-success" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Add option</a></p>
                                                                        <p class="saveCancel"><input class="saveOption btn btn-default" type="submit" name="save_option_4" value="Done" alt="Done"><a class="cancelOption btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Cancel</a></p>
                                                                    </div>
                                                                    <div class="hideShow accessAid savedDropdownSection hide" id="savedDropdownSection4">
                                                                        <p><label id="savedDropdown4" for="" class="form-control"></label></p>
                                                                        <p class="editDelete"><a class="editDropdown btn btn-info" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Edit</a>&nbsp;|&nbsp;<a class="deleteDropdown btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Delete</a></p>
                                                                    </div>
                                                                        <p id="addNewDropdownSection" class="editDelete hideShow accessAid hide"><a id="addNewDropdown" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=" class="btn btn-success">Add another drop-down menu</a></p>                                                                                    
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <p class="hideShow opened" id="addTextfield">
                                                                                    <label for="textfield" class="control-label">
                                                                                        <input type="checkbox" value="createdTextfield" name="textfield" id="textfield" class="checkbox form-control">Add text field&nbsp;
                                                                                        <a onclick="PAYPAL.core.openWindow(event, {width: 560, height: 410})" href="https://www.paypal.com/uk/cgi-bin/webscr?cmd=_display-textfield-example" class="infoLink exampleLink" target="_blank">Example</a>
                                                                                    </label>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    <div class="hideShow accessAid textfieldSection hide" id="textfieldSection1">
                                                                        <p class="title col-md-9"><label for="textfieldTitle1" class="control-label">Enter name of text field (up to 30 characters)</label><input maxlength="30" type="text" id="textfieldTitle1" class="text form-control" disabled="" name="textfield1_title" value=""></p>
                                                                        <p class="saveCancel"><input class="saveTextfield btn btn-default" type="submit" name="save_textfield" value="Done" alt="Done"><a class="cancelTextfield btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Cancel</a></p>
                                                                    </div>
                                                                    <div class="hideShow accessAid savedTextfieldSection hide" id="savedTextfieldSection1">
                                                                        <p><label class="savedTextfield" id="savedTextfield1" for=""></label></p>
                                                                        <p class="editDelete"><a class="editTextfield btn btn-info" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Edit</a>&nbsp;|&nbsp;<a class="deleteTextfield btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Delete</a></p>
                                                                    </div>
                                                                    <div class="hideShow accessAid textfieldSection hide" id="textfieldSection2">
                                                                        <p class="title col-md-9"><label for="textfieldTitle2" class="control-label">Enter name of text field (up to 30 characters)</label><input maxlength="30" type="text" id="textfieldTitle2" class="text form-control" disabled="" name="textfield2_title" value=""></p>
                                                                        <p class="saveCancel"><input class="saveTextfield btn btn-default" type="submit" name="save_textfield" value="Done" alt="Done"><a class="cancelTextfield btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Cancel</a></p>
                                                                    </div>
                                                                    <div class="hideShow accessAid savedTextfieldSection hide" id="savedTextfieldSection2">
                                                                        <p><label class="savedTextfield control-label" id="savedTextfield2" for=""></label></p>
                                                                        <p class="editDelete"><a class="editTextfield btn btn-info" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Edit</a>&nbsp;|&nbsp;<a class="deleteTextfield btn btn-danger" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Delete</a></p>
                                                                    </div>
                                                                    <p id="addNewTextfieldSection" class="editDelete hideShow accessAid hide"><a id="addNewTextfield" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Add another text field</a></p>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <span id="buttonAppLink" class="collapsed"><a href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Customize text or appearance</a><span class="fieldNote"> (optional)</span></span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    
                                                                    <div id="buttonAppSection" class="hideShow accessAid hide">
                                                                        <?php
                                                                            if(!empty($buttonImageUrl)){
                                                                                $paypalButtonSection_class='hide';
                                                                                $customButtonSection_class='opened';
                                                                                $paypalButton_checked='';
                                                                                $customButton_checked='checked';
                                                                                $previewImageSection='hide';
                                                                                $previewCustomImageSection='opened';
                                                                            }
                                                                            else{
                                                                                $paypalButtonSection_class='opened';
                                                                                $customButtonSection_class='hide';
                                                                                $paypalButton_checked='checked';
                                                                                $customButton_checked='';
                                                                                $previewImageSection='opened';
                                                                                $previewCustomImageSection='hide';
                                                                            }
                                                                        ?>
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <p id="addPaypalButton">
                                                                                    <label for="paypalButton" class="control-label">
                                                                                    <input class="radio form-control" type="radio" id="paypalButton" <?php echo $paypalButton_checked; ?> name="paypal_button" value="true">PayPal button
                                                                                    </label>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div id="paypalButtonSection" class="hideShow <?php echo $paypalButtonSection_class; ?>">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <?php
                                                                                        if($buttonImageSize=='SML'){
                                                                                            $smallButton_checked='checked';
                                                                                        }
                                                                                        else{
                                                                                            $smallButton_checked='';
                                                                                            
                                                                                        }
                                                                                        if($buttonImageSize=='CC'){
                                                                                            $displayCcLogos_checked='checked';
                                                                                        }
                                                                                        else {
                                                                                            $displayCcLogos_checked='';        
                                                                                        }
                                                                                        if($buttonImageSize=='REG'){
                                                                                            $smallButton_checked='';
                                                                                            $displayCcLogos_checked='checked';
                                                                                        }
                                                                                    ?>
                                                                                    <p id="displaySmallButton">
                                                                                        <label for="smallButton" class="control-label">
                                                                                        <input class="checkbox form-control" type="checkbox" id="smallButton" name="small_button" value="createdSmallButton" <?php echo $smallButton_checked; ?>>Use smaller button</label>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <p id="displayCcLogos" class="hideShow hide">
                                                                                        <label for="ccLogos" class="control-label">
                                                                                            <input class="checkbox form-control" type="checkbox" <?php echo $displayCcLogos_checked; ?> id="ccLogos" name="cc_logos" value="createdButtonWithCCLogo">Display credit card logos</label></p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <p id="buttonCountryLanguage">
                                                                                        <label for="" class="control-label">Country and language for button</label>
                                                                                        <?php $paypal_button_language = get_paypal_button_languages(); ?>
                                                                                        <select id="selectCountryLanguage" name="select_country_language" class="form-control">

                                                                                            <?php foreach ($paypal_button_language as $paypal_button_language_key => $paypal_button_language_value) { ?>
                                                                                                  <?php 
                                                                                                        if($buttonLanguage.'_'.$buttonCountry === $paypal_button_language_key){
                                                                                                            $selectCountryLanguage_seleced='selected';
                                                                                                        }
                                                                                                        else{
                                                                                                            $selectCountryLanguage_seleced='';
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
                                                                                        <label for="" class="control-label">Select button text</label>
                                                                                        <span class="field">
                                                                                            <select id="buttonTextBuyNow" name="button_text" disabled="" class="form-control">
                                                                                                <option value="buy_now" selected="">Buy Now</option>
                                                                                                <option value="pay_now">Pay Now</option>
                                                                                            </select>
                                                                                        </span>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <p id="textSubscr" class="hideShow buttonText hide">
                                                                                        <label for="" class="control-label">Select button text</label>
                                                                                        <span class="field">
                                                                                            <select id="buttonTextSubscribe" name="button_text" disabled="" class="form-control">
                                                                                                <option value="subscriptions" selected="">Subscribe</option>
                                                                                                <option value="buy_now">Buy Now</option>
                                                                                            </select>
                                                                                        </span>
                                                                                    </p>
                                                                                </div>
                                                                            </div>                                                                            
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <p id="addCustomButton"><label for="customButton" class="control-label"><input class="radio form-control" type="radio" id="customButton" <?php echo $customButton_checked; ?> name="paypal_button" value="false">Use your own button image</label></p>
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
                                                                        <p class="heading"><strong>Your customer's view</strong></p>
                                                                    </div>
                                                                </div>

                                                                <div class="previewSection" style="padding-left: 10px">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p id="previewDropdownPriceSection" class="hideShow accessAid previewDropdown hide">
                                                                                <label id="previewDropdownPriceTitle" for="optionsPriceDropdown" class="control-label">Dropdown title</label>
                                                                                <select id="optionsPriceDropdown" name="options_price_dropdown" class="form-control">
                                                                                    <option value="Option 1" selected="">Option 1 - $x.xx</option>
                                                                                    <option value="Option 2">Option 2 - $x.xx</option>
                                                                                    <option value="Option 3">Option 3 - $x.xx</option>
                                                                                </select>
                                                                                <span class="hide control-label" id="frequencyTxt">Frequency</span>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                             <p class="hideShow accessAid previewDropdown hide" id="previewDropdownSection1">
                                                                                <label class="previewDropdownTitle control-label" for="optionsDropdown1">Dropdown title</label>
                                                                                <select id="optionsDropdown1" name="options_dropdown1" class="optionsDropdown form-control">
                                                                                    <option value="" selected="">Option 1</option>
                                                                                    <option value="">Option 2</option>
                                                                                    <option value="">Option 3</option>
                                                                                </select>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p class="hideShow accessAid previewDropdown hide" id="previewDropdownSection2">
                                                                                <label class="previewDropdownTitle control-label" for="">Dropdown title</label>
                                                                                <select id="optionsDropdown2" name="options_dropdown2" class="optionsDropdown form-control">
                                                                                    <option value="" selected="">Option 1</option>
                                                                                    <option value="">Option 2</option>
                                                                                    <option value="">Option 3</option>
                                                                                </select>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p class="hideShow accessAid previewDropdown hide" id="previewDropdownSection3">
                                                                                <label class="previewDropdownTitle control-label" for="">Dropdown title</label>
                                                                                <select id="optionsDropdown3" name="options_dropdown3" class="optionsDropdown form-control">
                                                                                    <option value="" selected="">Option 1</option>
                                                                                    <option value="">Option 2</option>
                                                                                    <option value="">Option 3</option>
                                                                                </select>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p class="hideShow accessAid previewDropdown hide" id="previewDropdownSection4">
                                                                                <label class="previewDropdownTitle control-label" for="">Dropdown title</label>
                                                                                <select id="optionsDropdown4" name="options_dropdown4" class="optionsDropdown form-control">
                                                                                    <option value="" selected="">Option 1</option>
                                                                                    <option value="">Option 2</option>
                                                                                    <option value="">Option 3</option>
                                                                                </select>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p class="hideShow accessAid previewDropdown hide" id="previewTextfieldSection1"><label id="previewTextfieldTitle1" for="buttonTextfield1" class="control-label">Title</label><input type="text" id="buttonTextfield1" class="text readOnlyLabel form-control" name="button_textfield1" value=""></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p class="hideShow accessAid previewDropdown hide" id="previewTextfieldSection2"><label id="previewTextfieldTitle2" for="buttonTextfield2" class="control-label">Title</label><input type="text" id="buttonTextfield2" class="text readOnlyLabel form-control" name="button_textfield2" value=""></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p class="hideShow previewImageSection <?php echo $previewImageSection; ?>"><img id="previewImage" src="<?php echo BMW_PLUGIN_URL ?>/admin/images/btn_cart_LG.gif" border="0" alt="Preview Image"></p>
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
                                                                            <h4>Shipping</h4>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="col-md-12">
                                                                                <label for="itemFlatShippingAmount" class="control-label">Use specific amount: ( <span class="currencyLabel">USD</span> )</label>
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
                                                                            <h4>Tax</h4>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                        <div class="form-group">                                                                                        
                                                                                <label for="itemTaxRate" class="control-label">Use tax rate ( % )</label>
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
                                                                        <label for="donationCurrency" class="control-label">Currency</label>
                                                                        <select id="donationCurrency" name="item_price_currency" class="currencySelect form-control" disabled="" style="width: auto !important">
                                                                            <?php foreach ($paypal_button_currency as $paypal_button_currency_key => $paypal_button_currency_value) { ?>
                                                                            <?php 
                                                                                if($paypal_button_currency_value==$donation_currency)
                                                                                {
                                                                                    $donation_currency_selected='selected';
                                                                                }
                                                                                else{
                                                                                    $donation_currency_selected='';
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
                                                                    <h4>Contribution amount</h4>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">                                                                                    
                                                                        <input class="radio donationType form-control" type="radio" id="optDonationTypeFlexible" checked="" name="donation_type" value="open" disabled="">
                                                                        <label for="optDonationTypeFlexible" class="control-label">Donors enter their own contribution amount.</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <?php
                                                                            if(!empty($donation_amount)){
                                                                                $donation_amount_check="checked";
                                                                                $donation_container_amount_class='';
                                                                            }
                                                                            else{
                                                                                $donation_amount_check="";
                                                                                $donation_container_amount_class='accessAid';
                                                                            }
                                                                        ?>
                                                                        <input class="radio donationType form-control" <?php echo $donation_amount_check; ?> type="radio" id="optDonationTypeFixed" name="donation_type" value="fixed" disabled="">
                                                                        <label for="optDonationTypeFixed" class="control-label">Donors contribute a fixed amount.</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="labelOption fixedDonationAmountContainer <?php echo $donation_container_amount_class; ?>">
                                                                <div class="row">
                                                                    <div class="col-md-9">
                                                                        <label for="fixedDonationAmount" class="control-label">Amount ( <span class="currencyLabel">USD</span> )</label>
                                                                        <input type="text" id="fixedDonationAmount" size="7" maxlength="20" class="text form-control" name="item_price" value="<?php echo $donation_amount; ?>" disabled="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row"><div class="col-md-9"><strong>Note:</strong> This button is intended for fundraising. If you are not raising money for a cause, please choose another option. Nonprofits must verify their status to withdraw donations they receive. Users that are not verified nonprofits must demonstrate how their donations will be used, once they raise more than $10,000 USD.</div></div>
                                                    </div>
                                                    <div class="group subscriptions last accessAid fadedOut">
                                                        <div class="group">
                                                            <div class="row">
                                                                <div class="col-md-9">
                                                                    <div class="form-group">
                                                                        <input type="checkbox" id="enableUsernamePasswordCreation" class="checkbox form-control" name="enable_username_password_creation" value="1" disabled="">Have PayPal create user names and passwords for customers
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="balloonCallout accessAid" id="customerControlHelp">Give customers access to "members-only" content on your site.</div>
                                                            <div class="fieldNote">
                                                                <div class="label">Notes: </div>
                                                                <div class="floatLeft">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="rbFixedAmount">
                                                            <div class="group">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="subscriptionBillingAmount" class="control-label">Billing amount each cycle ( <span class="currencyLabel">USD</span> ) </label>
                                                                            <input type="text" id="subscriptionBillingAmount" size="22" class="text form-control" name="subscription_billing_amount" value="" disabled="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="group">
                                                                <div class="row">                                                                                
                                                                        <div class="form-group">
                                                                           <div class="col-md-12"><label for="subscriptionBillingCycleNumber" class="control-label">Billing cycle</label></div>
                                                                           <div class="col-md-1">
                                                                               <?php $paypal_button_subscription_billing_cycle_number = get_paypal_button_subscription_billing_cycle_number(); ?>
                                                                                <select name="subscription_billing_cycle_number" disabled="" class="form-control" style="width: auto !important">
                                                                                    <?php foreach ($paypal_button_subscription_billing_cycle_number as $paypal_button_subscription_billing_cycle_number_key => $paypal_button_subscription_billing_cycle_number_value) { ?>
                                                                                        <option value="<?php echo $paypal_button_subscription_billing_cycle_number_value; ?>"><?php echo $paypal_button_subscription_billing_cycle_number_value; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                           </div>
                                                                           <div class="col-md-1">
                                                                               <?php $paypal_button_subscriptions_cycle = get_paypal_button_subscriptions_cycle(); ?>
                                                                                <select id="subscriptionBillingCyclePeriod" name="subscription_billing_cycle_period" disabled="" class="form-control" style="width: auto !important">
                                                                                    <?php foreach ($paypal_button_subscriptions_cycle as $paypal_button_subscriptions_cycle_key => $paypal_button_subscriptions_cycle_value) { ?>
                                                                                        <option value="<?php echo $paypal_button_subscriptions_cycle_key; ?>"><?php echo $paypal_button_subscriptions_cycle_value; ?></option>
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
                                                                        <label for="subscriptionBillingLimit" class="control-label">After how many cycles should billing stop?</label>
                                                                        <select name="subscription_billing_limit" disabled="" class="form-control" style="width: auto !important">
                                                                            <?php 
                                                                            $paypal_button_subscriptions_cycle_billing_limit = get_paypal_button_subscription_billing_limit();
                                                                            foreach ($paypal_button_subscriptions_cycle_billing_limit as $paypal_button_subscriptions_cycle_billing_limit_key => $paypal_button_subscriptions_cycle_billing_limit_value) { ?>
                                                                                <option value="<?php echo $paypal_button_subscriptions_cycle_billing_limit_value; ?>"><?php echo $paypal_button_subscriptions_cycle_billing_limit_value; ?></option>
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
                                                                        <input type="checkbox" id="offerTrial" class="checkbox form-control" name="subscriptions_offer_trial" value="1" disabled=""><label for="offerTrial" class="control-label">I want to offer a trial period</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="trialOfferOptions accessAid">

                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="subscriptionLowerRate" class="control-label">Amount to bill for the trial period ( <span class="currencyLabel">USD</span> )</label>
                                                                            <input class="hidden" type="hidden" id="subscriptionLowerRate" name="subscription_trial_billing_amount" value="1" disabled="">
                                                                            <input type="text" id="subscriptionLowerRateAmount" size="11" class="text form-control" name="subscription_trial_rate" value="" disabled="">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="form-group">
                                                                        <?php $paypal_button_subscription_trial_duration = get_paypal_button_subscription_trial_duration(); ?>                                                                                    

                                                                        <div class="col-md-12"><label class="control-label">Define the trial period</label></div>
                                                                        <div class="col-md-1">
                                                                             <select name="subscription_trial_duration" disabled="" class="form-control">
                                                                            <?php foreach ($paypal_button_subscription_trial_duration as $paypal_button_subscription_trial_duration_key => $paypal_button_subscription_trial_duration_value) { ?>
                                                                                <option value="<?php echo $paypal_button_subscription_trial_duration_key; ?>"><?php echo $paypal_button_subscription_trial_duration_value; ?></option>
                                                                            <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <select id="trialDurationType" name="subscription_trial_duration_type" disabled="" class="form-control">
                                                                            <?php foreach ($paypal_button_subscriptions_cycle as $paypal_button_subscriptions_cycle_key => $paypal_button_subscriptions_cycle_value) { ?>
                                                                                <option value="<?php echo $paypal_button_subscriptions_cycle_key; ?>"><?php echo $paypal_button_subscriptions_cycle_value; ?></option>
                                                                            <?php } ?>
                                                                            </select>
                                                                        </div>                                                                                    
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-9">
                                                                        <h5>Do you want to offer a second trial period? <span class="autoTooltip" title="" tabindex="0">What's this?<span class="accessAid">Customers will receive just one bill for each trial period.</span></span></h5>
                                                                    </div>                                                                                
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-9">
                                                                        <div class="form-group">

                                                                            <input class="radio secondTrialOfferOption form-control" type="radio" id="secondSubscriptionTrialOffer" name="subscriptions_offer_another_trial" value="1" disabled="">
                                                                            <label for="secondSubscriptionTrialOffer" class="control-label">Yes</label>

                                                                            <input class="radio secondTrialOfferOption form-control" type="radio" id="lastSubscriptionTrialOffer" checked="" name="subscriptions_offer_another_trial" value="0" disabled="">
                                                                            <label for="lastSubscriptionTrialOffer" class="control-label">No</label>

                                                                        </div>
                                                                    </div>
                                                                </div>                                                                                
                                                                    <div class="secondTrialOfferOptions accessAid">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Amount to bill for this trial period ( <span class="currencyLabel">USD</span> )</label>
                                                                                    <input type="text" id="secondSubscriptionLowerRateAmount" size="11" class="text form-control" name="subscription_trial_2_rate" value="" disabled="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">                                                                                        
                                                                            <div class="form-group">
                                                                                <div class="col-md-12">
                                                                                    <label class="control-label">How long should the trial period last?</label>
                                                                                </div>
                                                                                <div class="col-md-1">
                                                                                    <select name="subscription_trial_2_duration" disabled="" class="form-control">
                                                                                        <?php foreach ($paypal_button_subscription_trial_duration as $paypal_button_subscription_trial_duration_key => $paypal_button_subscription_trial_duration_value) { ?>
                                                                                            <option value="<?php echo $paypal_button_subscription_trial_duration_key; ?>"><?php echo $paypal_button_subscription_trial_duration_value; ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <select id="secondTrialDurationType" name="subscription_trial_2_duration_type" disabled="" class="form-control">
                                                                                        <?php foreach ($paypal_button_subscriptions_cycle as $paypal_button_subscriptions_cycle_key => $paypal_button_subscriptions_cycle_value) { ?>
                                                                                            <option value="<?php echo $paypal_button_subscriptions_cycle_key; ?>"><?php echo $paypal_button_subscriptions_cycle_value; ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </div>        
                                                                            </div>
                                                                        </div>                                                                                    
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="group gift_certs last accessAid fadedOut">
                                                        <div class="group gcCurrency">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="gcAmountCurrency" class="control-label">Currency</label>
                                                                        <select id="gcAmountCurrency" name="item_price_currency" class="currencySelect form-control" disabled="" style="width: auto !important">
                                                                            <?php foreach ($paypal_button_currency as $paypal_button_currency_key => $paypal_button_currency_value) { ?>
                                                                                <option value="<?php echo $paypal_button_currency_value; ?>" title="<?php echo $paypal_button_options_key; ?>"><?php echo $paypal_button_currency_value; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="group">
                                                             <div class="row">
                                                                <div class="col-md-9">
                                                                    <h4>Specify gift certificate amount</h4>
                                                                </div>                                                                                
                                                             </div> 
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">                                                                                    
                                                                        <input class="radio gcAmountType form-control" type="radio" id="gcAmountTypeList" checked="" name="gc_amount_type" value="custom" disabled="">
                                                                        <label for="gcAmountTypeList" class="control-label">Choose an amount from a preset list</label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">                                                                                    
                                                                        <input class="radio gcAmountType form-control" type="radio" id="gcAmountTypeFixed" name="gc_amount_type" value="fixed" disabled="">
                                                                        <label for="gcAmountTypeFixed" class="control-label">Specify an amount of your choosing</label>
                                                                </div>                                                                            
                                                            </div>

                                                            <div class="labelOption gcFixedAmountContainer accessAid">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="gcFixedAmount" class="control-label">Amount ( <span class="currencyLabel">USD</span> ) </label>
                                                                            <input type="text" id="gcFixedAmount" size="9" class="text form-control" name="gc_fixed_amount" value="" disabled="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                                        
                                                        </div>
                                                        <div class="group">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <h4 class="gcStyleHeader_new">Gift certificate style</h4>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-9">
                                                                    <div class="form-group">
                                                                        <label for="giftCertificateLogoURL" class="control-label">Add URL for logo image</label>
                                                                        <input class="text form-control" type="text" id="giftCertificateLogoURL" size="34" name="gc_logo_url" value="http://" disabled="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="group">
                                                            <div class="row">
                                                                <div class="col-md-9">
                                                                    <h4>Choose background</h4>  
                                                                </div>
                                                            </div>                                                                            
                                                            <fieldset>                                                                            
                                                                <label for="gcBackgroundColor" class="control-label">
                                                                    <input class="radio gcBackgroundType form-control" type="radio" checked="" name="gc_background_type" value="color" disabled="">Color
                                                                    <div class="labelOption">
                                                                        <?php $paypal_button_gcBackgroundColor = get_paypal_button_gcBackgroundColor(); ?>
                                                                        <select id="gcBackgroundColor" name="gc_background_color" disabled="" class="form-control">
                                                                            <?php foreach ($paypal_button_gcBackgroundColor as $paypal_button_gcBackgroundColor_key => $paypal_button_gcBackgroundColor_value) { ?>
                                                                                <option value="<?php echo $paypal_button_gcBackgroundColor_key; ?>"><?php echo $paypal_button_gcBackgroundColor_value; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </label>
                                                                <label for="gcBackgroundTheme" class="control-label">
                                                                    <input class="radio gcBackgroundType form-control" type="radio" name="gc_background_type" value="theme" disabled="">Theme
                                                                    <div class="labelOption">
                                                                        <?php $paypal_button_gcBackgroundTheme = get_paypal_button_gcBackgroundTheme(); ?>
                                                                        <select id="gcBackgroundTheme" name="gc_background_theme" disabled="" class="form-control">
                                                                            <?php foreach ($paypal_button_gcBackgroundTheme as $paypal_button_gcBackgroundTheme_key => $paypal_button_gcBackgroundTheme_value) { ?>
                                                                                <option value="<?php echo $paypal_button_gcBackgroundTheme_key; ?>"><?php echo $paypal_button_gcBackgroundTheme_value; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </label>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <div class="group notifications">
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label for="merchantIDNotificationMethod" class="control-label">Enter your PayPal Email Address or Merchant Account ID <a target="_blank" class="infoLink" href="https://www.paypal.com/businessstaticpage/BDMerchantIdInformation" onclick="PAYPAL.core.openWindow(event,{height:500, width: 450});">Learn more</a></label>
                                                                    <input type="text" class="custom_text form-control" name="business" id="business"  value="<?php echo $account_id; ?>"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                                                    
                                </div>
                        </div>    
                    </div>
                    
                    
                <div id="stepTwo" class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingTwo">
                        <?php echo '<h4 id="giftBasedHeading" class="accessAid hide panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">' . __('Step 2: Track inventory, profit & loss (optional)', 'paypal-wp-button-manager') . '</a></h4>'; ?>
                        <?php echo '<h4 id="productBasedHeading" class="opened panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">' . __('Step 2: Track inventory, profit & loss (optional)', 'paypal-wp-button-manager') . '</a></h4>'; ?>
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
                                                        if($track_inv=='1'){
                                                            $enable_inventory = 'checked';
                                                        }
                                                        else{
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
                                                        if($track_pnl=='1'){
                                                            $enable_profit_and_loss = 'checked';
                                                        }
                                                        else{
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
                                    <div id="trackByItemTable" class="fadedOut">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <input class="radio form-control" type="radio" id="trackByItem" checked="" name="track_button_by" value="trackdByItem" disabled="">
                                                    <label id="byItemLabel" for="trackByItem" class="control-label"><strong><?php echo __('By item', 'paypal-wp-button-manager'); ?></strong></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="byItemTableBody">
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
                                                            <div class="PNLRelated"><label><?php echo __('Price', 'paypal-wp-button-manager'); ?> ( <span class="currencyLabel">USD</span> )</label></div>
                                                            <div class="PNLRelated"><input class="form-control" type="text" name="item_cost" value="<?php echo $item_cost_step2; ?>" disabled=""></div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="trackByOptionTable" class="fadedOut accessAid">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <input class="radio form-control" type="radio" id="trackByOption" name="track_button_by" value="trackdByOption" disabled="">
                                                    <label for="trackByOption" class="control-label"><strong><?php echo __('By option', 'paypal-wp-button-manager'); ?></strong><?php echo __('(in drop-down menu)', 'paypal-wp-button-manager'); ?> <a id="chooseAnotherDropDown" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=#chooseAnotherDropDown" class="accessAid"><?php echo __('Choose a different drop-down', 'paypal-wp-button-manager'); ?></a></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="byOptionTableBody" class="accessAid">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-3">
                                                        <label><?php echo __('Item ID',  'paypal-wp-button-manager'); ?></label>
                                                        <div><input class="type-text form-control" type="text" name="item_id" value="" disabled=""></div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="invRelated"><label class="control-label"><?php echo __('Qty in stock', 'paypal-wp-button-manager'); ?></label></div>
                                                        <div class="invRelated"><input class="form-control" type="text" name="items_in_stock" value="" disabled=""></div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="invRelated"><label><?php echo __('Alert qty. (optional)', 'paypal-wp-button-manager'); ?> <span class="autoTooltip helpText" title="" tabindex="0"><?php echo __("What's this?", 'paypal-wp-button-manager'); ?><span class="accessAid"><?php echo __('When your inventory falls to this number, PayPal will send you an e-mail alert.', 'paypal-wp-button-manager'); ?></span></span></label></div>
                                                        <div class="invRelated"><input class="form-control" type="text" name="alert_quantity" value="" disabled=""></div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="PNLRelated"><label><?php echo __('Cost', 'paypal-wp-button-manager');?></label></div>
                                                        <div class="PNLRelated"><input class="type-text form-control" type="text" name="item_cost" value="" disabled=""></div>
                                                    </div>

                                                </div>
                                            </div>                                                                            
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
                                                        if(!empty($item_soldout_url_step2)){
                                                            $dontEnablePreOrder='checked';
                                                            $enablePreOrder='';
                                                        }
                                                        else{
                                                            $dontEnablePreOrder='';
                                                            $enablePreOrder='checked';
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
                                        <span class="littleHint">Ex: http://www.mybuynowstore.com</span>
                                        </p>
                                    </div>
                                </div>
                            </div>                       
                    </div>
                </div>
            <div id="stepThree" class="panel panel-primary">
                 <div class="panel-heading" role="tab" id="headingThree">
                    <?php echo '<h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">' . __('Step 3: Customize advanced features (optional)', 'paypal-wp-button-manager') . '</a></h4>'; ?>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">                                  
                        <div class="container">
                            <div class="row">
                                <diV class="col-md-9">
                                    <strong>Customize checkout pages</strong>
                                </diV>
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <p>If you are an advanced user, you can customize checkout pages for your customers, streamline checkout, and more in this section.</p>
                                </div>
                            </div>

                            <div id="changeOrderQuantitiesContainer" class="hide">
                                <div class="row">
                                    <div class="col-md-9">
                                        <b>Do you want to let your customer change order quantities?</b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">                                                                                
                                            <input class="radio form-control" type="radio" id="changeOrderQuantities" name="undefined_quantity" value="1">
                                            <label class="control-label" for="keepOrderQuantities">Yes</label>
                                            <input class="radio" type="radio" id="keepOrderQuantities" checked="" name="undefined_quantity" value="0">
                                            <label class="control-label" for="changeOrderQuantities">No</label>
                                        </div>
                                    </div>
                                </div>                                                                    
                            </div>
                            <div id="specialInstructionsContainer" class="opened">                                                                    
                                <div class="row">
                                    <div class="col-md-9">
                                        <p><b>Can your customer add special instructions in a message to you?</b></p>
                                        <div class="form-group">
                                            <?php
                                                if($no_note==0){
                                                    $cn_add_checked='checked';
                                                    $cn_no_checked='';
                                                    $cn_class='opened';
                                                }
                                                else{
                                                    $cn_add_checked='';
                                                    $cn_no_checked='checked';
                                                    $cn_class='hide';
                                                }
                                            ?>    
                                            <input class="radio form-control" type="radio" id="addSpecialInstructions" <?php echo $cn_add_checked; ?> name="no_note" value="0">
                                            <label class="control-label" for="addSpecialInstructions">Yes</label>
                                            &nbsp; &nbsp;
                                            <input class="radio form-control" type="radio" id="noSpecialInstructions" <?php echo $cn_no_checked; ?> name="no_note" value="1">
                                            <label class="control-label" for="noSpecialInstructions">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row <?php echo $cn_class; ?>" id="messageBoxContainer">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label  for="messageBox" class="control-label">Name of message box (40-character limit)</label>
                                            <input type="text" id="messageBox" size="40" maxlength="40" class="form-control" name="custom_note" value="<?php echo $add_special_instruction; ?>">
                                        </div>
                                    </div>
                                </div>                                                                    
                            </div>
                            <div id="shippingAddressContainer" class="opened">

                                <div class="row">
                                    <div class="col-md-9">
                                        <p><b>Do you need your customer's shipping address?</b></p>
                                        <div class="form-group">
                                                    <?php 
                                                    if($customersShippingAddress=='2'){
                                                        $shippingYes='checked';
                                                        $shippingNo='';
                                                    }
                                                    if($customersShippingAddress=='1'){
                                                        $shippingYes='';
                                                        $shippingNo='checked';
                                                    }                                                    
                                                    ?>
                                                    <input class="radio form-control" type="radio" id="needShippingAddress" <?php echo $shippingYes; ?> name="no_shipping" value="2">
                                                    <label class="control-label" for="needShippingAddress">Yes</label>

                                                    <input class="radio form-control" type="radio" id="noShippingAddress" <?php echo $shippingNo; ?> name="no_shipping" value="1">
                                                    <label class="control-label" for="noShippingAddress">No</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div id="cancellationRedirectURLContainer" class="opened">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <?php
                                              if(!empty($cancel_return)){
                                                  $cancellationCheckbox='checked';
                                                  $cancleFormcontrol='';
                                              }
                                              else{
                                                  $cancellationCheckbox='';
                                                  $cancleFormcontrol='disabled';
                                              }
                                            ?>
                                            <input class="checkbox form-control" type="checkbox" id="cancellationCheckbox" name="cancellation_return" value="1" <?php echo $cancellationCheckbox; ?> >
                                            <label for="cancellationCheckbox" class="control-label">Take customers to this URL when they cancel their checkout</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="redirectContainer">
                                    <input type="text" id="cancellationRedirectURL" size="30" class="form-control" <?php echo $cancleFormcontrol; ?> name="cancel_return" value="<?php echo $cancel_return; ?>">
                                    <div>Example: https://www.mystore.com/cancel</div>
                                </div>
                            </div>
                            <div id="successfulRedirectURLContainer" class="opened">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <?php
                                              if(!empty($return)){
                                                  $successfulCheckbox='checked';
                                                  $returnFormcontrol='';
                                              }
                                              else{
                                                  $successfulCheckbox='';
                                                  $returnFormcontrol='disabled';
                                              }
                                            ?>
                                            <input class="checkbox form-control" type="checkbox" id="successfulCheckbox" name="successful_return" value="1" <?php echo $successfulCheckbox; ?> >
                                            <label for="successfulCheckbox" class="control-label">Take customers to this URL when they finish checkout</label>
                                        </div>
                                    </div>
                                </div>                                                                    
                                <div class="redirectContainer">
                                    <input type="text" id="successfulRedirectURL" size="30" class="form-control" <?php echo $returnFormcontrol; ?> name="return" value="<?php echo $return; ?>">
                                    <div>Example: https://www.mystore.com/success</div>
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
                <script type="text/javascript">var imageUrls = {en: {BuyNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fbuynowCC\x5fLG\x2egif"},PayNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fpaynowCC\x5fLG\x2egif"},AddToCart: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fcart\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif"},Donate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fdonateCC\x5fLG\x2egif"},GiftCertificate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fgift\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fgift\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fgiftCC\x5fLG\x2egif"},Subscribe: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fsubscribeCC\x5fLG\x2egif"},PaymentPlan: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fCC\x5fLG\x2egif"},AutoBilling: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fCC\x5fLG\x2egif"}},fr: {BuyNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynowCC\x5fLG\x2egif"},PayNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynowCC\x5fLG\x2egif"},AddToCart: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif"},Donate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonateCC\x5fLG\x2egif"},GiftCertificate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgift\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgift\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgiftCC\x5fLG\x2egif"},Subscribe: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribeCC\x5fLG\x2egif"},PaymentPlan: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fCC\x5fLG\x2egif"},AutoBilling: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2ffr\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fCC\x5fLG\x2egif"}},es: {BuyNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynowCC\x5fLG\x2egif"},PayNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynowCC\x5fLG\x2egif"},AddToCart: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif"},Donate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonateCC\x5fLG\x2egif"},GiftCertificate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgift\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgift\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgiftCC\x5fLG\x2egif"},Subscribe: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribeCC\x5fLG\x2egif"},PaymentPlan: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fCC\x5fLG\x2egif"},AutoBilling: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fes\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fCC\x5fLG\x2egif"}},zh: {BuyNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fbuynowCC\x5fLG\x2egif"},PayNow: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynow\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fpaynowCC\x5fLG\x2egif"},AddToCart: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fcart\x5fLG\x2egif"},Donate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonate\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fdonateCC\x5fLG\x2egif"},GiftCertificate: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgift\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgift\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fgiftCC\x5fLG\x2egif"},Subscribe: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribe\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fsubscribeCC\x5fLG\x2egif"},PaymentPlan: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5finstallment\x5fplan\x5fCC\x5fLG\x2egif"},AutoBilling: {small: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fSM\x2egif", large: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fLG\x2egif", cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fzh\x5fXC\x2fi\x2fbtn\x2fbtn\x5fauto\x5fbilling\x5fCC\x5fLG\x2egif"}},int: {BuyNow: {cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fbuynowCC\x5fLG\x5fglobal\x2egif"},Donate: {cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fdonateCC\x5fLG\x5fglobal\x2egif"},GiftCertificate: {cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fgiftCC\x5fLG\x5fglobal\x2egif"},PayNow: {cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fpaynowCC\x5fLG\x5fglobal\x2egif"},Subscribe: {cc: "https\x3a\x2f\x2fwww\x2epaypalobjects\x2ecom\x2fen\x5fUS\x2fi\x2fbtn\x2fbtn\x5fsubscribeCC\x5fLG\x5fglobal\x2egif"}}};</script>
                <?php
                wp_enqueue_script('button-designer-js', BMW_PLUGIN_URL . 'admin/js/paypal-wp-button-manager-buttonDesigner.js', array(), '1.0', true);
            }
}

        AngellEYE_PayPal_WP_Button_Manager_button_interface::init();