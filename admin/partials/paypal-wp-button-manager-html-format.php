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
                        <option value="<?php echo $result_records_value['ID']; ?>"><?php echo $result_records_value['title']; ?></option>
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
    public static function paypal_wp_button_manager_for_wordpress_button_interface_html() {
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
                        <input type="hidden" id="CONTEXT_CGI_VAR" name="CONTEXT" value="X3-7SZn2ExXucINxlliZ_05NdFsrIIpaV9TcRYNLL_GiOwm9XgEZzWKQeV0"><input type="hidden" id="cmd" name="cmd" value="_flow"><input type="hidden" id="onboarding_cmd" name="onboarding_cmd" value=""><input type="hidden" id="fakeSubmit" name="fakeSubmit" value=""><input type="hidden" id="secureServerName" name="secureServerName" value="www.paypal.com/us"><input type="hidden" id="selectedDropDown" name="selectedDropDown" value="">
                        <div id="main" class="panel-group">
                            <div class="layout1">
                                <div class="accordionContainer">
                                    <div class="accordion dynamic">

                                        <div id="stepOne" class="box defaultOpen open panel panel-primary">
                                            <div class="header panel-heading">
                                                
                                                <?php echo '<h4 class="panel-title">'. __('Step 1: Choose a button type and enter your payment details'). '</h4>'; ?>
                                            </div>

                                            <div class="body" style="height: auto; opacity: 1;">
                                                <div class="content">
                                                    <div class="container">
                                                        <div class="group buttonType">
                                                            <div class="col-lg-4">
                                                            <label for="buttonType" class="control-label">Choose a button type</label>
                                                            <?php $paypal_button_options = get_paypal_button_options(); ?>
                                                            <select id="buttonType" name="button_type" class="form-control">
                                                                <?php foreach ($paypal_button_options as $paypal_button_options_key => $paypal_button_options_value) { ?>
                                                                    <option value="<?php echo $paypal_button_options_key; ?>"><?php echo $paypal_button_options_value; ?></option>
                                                                <?php } ?>

                                                            </select>
                                                            </div>        
                                                        </div>
                                                        <div class="products"><input class="hide radio subButtonType" type="radio" id="radioAddToCartButton" checked="" name="sub_button_type" value="add_to_cart"><input class="hide radio subButtonType" type="radio" id="radioBuyNowButton" name="sub_button_type" value="buy_now"></div>
                                                        <div class="group details">
                                                            <div class="products">
                                                                <div class="col-lg-4"><label for="itemName" class="control-label">Item name</label><input class="form-control" maxlength="127" type="text" id="itemName" name="product_name" value=""></div>
                                                                <div class="col-lg-4"><label for="itemID">Item ID<span class="fieldNote"> (optional) </span></label><input class="form-control" maxlength="127" type="text" id="itemID" size="9" name="product_id" value=""></div>
                                                            </div>
                                                                        <div class="donations accessAid fadedOut">
                                                                            <div class="col-lg-4"><label for="donationName" class="control-label">Organization name/service</label><input class="form-control" maxlength="127" type="text" id="donationName" name="donation_name" value="" disabled=""></div>
                                                                            <div class="col-lg-4"><label for="donationID" class="control-label">Donation ID<span class="fieldNote"> (optional) </span>
                                                                                </label>
                                                                                <input class="form-control" maxlength="127" type="text" id="donationID" size="27" name="donation_id" value="" disabled=""></div>
                                                                        </div>
                                                                        <div class="subscriptions accessAid fadedOut">
                                                                            <div class="col-lg-4"><label for="subscriptionName" class="control-label">Item name</label><input class="form-control" maxlength="127" type="text" id="subscriptionName" name="subscription_name" value="" disabled=""></div>
                                                                            <div class="col-lg-4"><label for="subscriptionID" class="control-label">Subscription ID<span class="fieldNote"> (optional) </span></label><input class="form-control" maxlength="127" type="text" id="subscriptionID" size="27" name="subscription_id" value="" disabled=""></div>
                                                                        </div>
                                                            <div class="gift_certs accessAid fadedOut col-lg-9"><label for="giftCertificateShopURL" class="control-label">Enter the URL where recipients can shop and redeem this gift certificate.</label><input class="form-control" type="text" id="giftCertificateShopURL" size="34" name="gift_certificate_shop_url" value="http://" disabled=""></div>
                                                                </div>
                                                                <div class="group products pricing opened">
                                                                    <div class="col-lg-4"><label for="itemPrice" class="control-label">Price</label><input class="form-control" type="text" id="itemPrice" size="9" name="item_price" value=""></div>
                                                                    <div class="col-lg-4">
                                                                        <label for="itemPriceCurrency" class="control-label">Currency</label>
                                                                        <?php $paypal_button_currency_with_symbole = get_paypal_button_currency_with_symbole(); ?>
                                                                        <select id="BillingAmountCurrency" name="item_price_currency" class="currencySelect form-control">

                                                                            <?php foreach ($paypal_button_currency_with_symbole as $paypal_button_currency_with_symbole_key => $paypal_button_currency_with_symbole_value) { ?>
                                                                                <option value="<?php echo $paypal_button_currency_with_symbole_key; ?>" title="<?php echo $paypal_button_currency_with_symbole_value; ?>"><?php echo $paypal_button_currency_with_symbole_key; ?></option>
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
                                                                            <p class="heading"><strong>Customize button</strong></p>
                                                                            <div id="customizeSection">
                                                                                <p id="addDropdownPrice" class="hideShow opened"><label for="dropdownPrice" class="control-label"><input class="checkbox form-control" type="checkbox" id="dropdownPrice" name="dropdown_price" value="createdDropdownPrice"><span class="products">Add drop-down menu with price/option&nbsp;</span><span class="subscriptions accessAid fadedOut">Add a dropdown menu with prices and options</span>
                                                                                    </label></p>
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
                                                                                    <p id="addDropdown" class="hideShow opened"><label for="dropdown" class="control-label"><input class="checkbox form-control" type="checkbox" id="dropdown" name="dropdown" value="createdDropdown"><span class="hideShow accessAid hide" id="dropDownLabelForSubscription">Add a dropdown menu </span><span id="dropDownLabel" class="opened">Add drop-down menu&nbsp;</span>

                                                                                    </label></p>
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
                                                                                <p class="hideShow opened" id="addTextfield"><label for="textfield" class="control-label"><input type="checkbox" value="createdTextfield" name="textfield" id="textfield" class="checkbox form-control">Add text field&nbsp;<a onclick="PAYPAL.core.openWindow(event, {width: 560, height: 410})" href="https://www.paypal.com/uk/cgi-bin/webscr?cmd=_display-textfield-example" class="infoLink exampleLink" target="_blank">Example</a></label></p>
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
                                                                                <span id="buttonAppLink" class="collapsed"><a href="https://www.paypal.com/us/cgi-bin/webscr?cmd=">Customize text or appearance</a><span class="fieldNote"> (optional)</span></span>
                                                                                <div id="buttonAppSection" class="hideShow accessAid hide">
                                                                                    <p id="addPaypalButton"><label for="paypalButton" class="control-label"><input class="radio form-control" type="radio" id="paypalButton" checked="" name="paypal_button" value="true">PayPal button</label></p>
                                                                                    <div id="paypalButtonSection" class="hideShow opened">
                                                                                        <p id="displaySmallButton"><label for="smallButton" class="control-label"><input class="checkbox form-control" type="checkbox" id="smallButton" name="small_button" value="createdSmallButton">Use smaller button</label></p>
                                                                                        <p id="displayCcLogos" class="hideShow hide"><label for="ccLogos" class="control-label"><input class="checkbox form-control" type="checkbox" id="ccLogos" checked="" name="cc_logos" value="createdButtonWithCCLogo">Display credit card logos</label></p>
                                                                                        <p id="buttonCountryLanguage">
                                                                                            <label for="" class="control-label">Country and language for button</label>
                                                                                            <?php $paypal_button_language = get_paypal_button_languages(); ?>
                                                                                            <select id="selectCountryLanguage" name="select_country_language" class="form-control">

                                                                                                <?php foreach ($paypal_button_language as $paypal_button_language_key => $paypal_button_language_value) { ?>
                                                                                                    <option value="<?php echo $paypal_button_language_key; ?>"><?php echo $paypal_button_language_value; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                            <input type="hidden" id="countryCode" name="country_code" value="US"><input type="hidden" id="langCode" name="lang_code" value="en"><input type="hidden" id="buttonUrl" name="button_url" value="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif"><input type="hidden" id="popupButtonUrl" name="popup_button_url" value=""><input type="hidden" id="flagInternational" name="flag_international" value="true" disabled=""><input type="hidden" id="titleStr" name="title_str" value="Title"><input type="hidden" id="optionStr" name="option_str" value="Option"><input type="hidden" id="addOptionStr" name="add_option_str" value="Add another option">
                                                                                        </p>
                                                                                        <p id="textBuyNow" class="hideShow buttonText hide">
                                                                                            <label for="" class="control-label">Select button text</label>
                                                                                            <span class="field">
                                                                                                <select id="buttonTextBuyNow" name="button_text" disabled="" class="form-control">
                                                                                                    <option value="buy_now" selected="">Buy Now</option>
                                                                                                    <option value="pay_now">Pay Now</option>
                                                                                                </select>
                                                                                            </span>
                                                                                        </p>
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
                                                                                    <p id="addCustomButton"><label for="customButton" class="control-label"><input class="radio form-control" type="radio" id="customButton" name="paypal_button" value="false">Use your own button image</label>
                                                                                    </p>
                                                                                    <div id="customButtonSection" class="hideShow accessAid hide"><input type="text" id="customImageUrl" class="text form-control" name="custom_image_url"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="buyerViewSection">
                                                                            <p class="heading"><strong>Your customer's view</strong></p>

                                                                            <div class="previewSection">
                                                                                <p id="previewDropdownPriceSection" class="hideShow accessAid previewDropdown hide">
                                                                                    <label id="previewDropdownPriceTitle" for="optionsPriceDropdown" class="control-label">Dropdown title</label>
                                                                                    <select id="optionsPriceDropdown" name="options_price_dropdown" class="form-control">
                                                                                        <option value="Option 1" selected="">Option 1 - $x.xx</option>
                                                                                        <option value="Option 2">Option 2 - $x.xx</option>
                                                                                        <option value="Option 3">Option 3 - $x.xx</option>
                                                                                    </select>
                                                                                    <span class="hide control-label" id="frequencyTxt">Frequency</span>
                                                                                </p>
                                                                                <p class="hideShow accessAid previewDropdown hide" id="previewDropdownSection1">
                                                                                    <label class="previewDropdownTitle control-label" for="optionsDropdown1">Dropdown title</label>
                                                                                    <select id="optionsDropdown1" name="options_dropdown1" class="optionsDropdown form-control">
                                                                                        <option value="" selected="">Option 1</option>
                                                                                        <option value="">Option 2</option>
                                                                                        <option value="">Option 3</option>
                                                                                    </select>
                                                                                </p>
                                                                                <p class="hideShow accessAid previewDropdown hide" id="previewDropdownSection2">
                                                                                    <label class="previewDropdownTitle control-label" for="">Dropdown title</label>
                                                                                    <select id="optionsDropdown2" name="options_dropdown2" class="optionsDropdown form-control">
                                                                                        <option value="" selected="">Option 1</option>
                                                                                        <option value="">Option 2</option>
                                                                                        <option value="">Option 3</option>
                                                                                    </select>
                                                                                </p>
                                                                                <p class="hideShow accessAid previewDropdown hide" id="previewDropdownSection3">
                                                                                    <label class="previewDropdownTitle control-label" for="">Dropdown title</label>
                                                                                    <select id="optionsDropdown3" name="options_dropdown3" class="optionsDropdown form-control">
                                                                                        <option value="" selected="">Option 1</option>
                                                                                        <option value="">Option 2</option>
                                                                                        <option value="">Option 3</option>
                                                                                    </select>
                                                                                </p>
                                                                                <p class="hideShow accessAid previewDropdown hide" id="previewDropdownSection4">
                                                                                    <label class="previewDropdownTitle control-label" for="">Dropdown title</label>
                                                                                    <select id="optionsDropdown4" name="options_dropdown4" class="optionsDropdown form-control">
                                                                                        <option value="" selected="">Option 1</option>
                                                                                        <option value="">Option 2</option>
                                                                                        <option value="">Option 3</option>
                                                                                    </select>
                                                                                </p>
                                                                                <p class="hideShow accessAid previewDropdown hide" id="previewTextfieldSection1"><label id="previewTextfieldTitle1" for="buttonTextfield1" class="control-label">Title</label><input type="text" id="buttonTextfield1" class="text readOnlyLabel form-control" name="button_textfield1" value=""></p>
                                                                                <p class="hideShow accessAid previewDropdown hide" id="previewTextfieldSection2"><label id="previewTextfieldTitle2" for="buttonTextfield2" class="control-label">Title</label><input type="text" id="buttonTextfield2" class="text readOnlyLabel form-control" name="button_textfield2" value=""></p>
                                                                                <p class="hideShow opened previewImageSection"><img id="previewImage" src="<?php echo BMW_PLUGIN_URL ?>/admin/images/btn_cart_LG.gif" border="0" alt="Preview Image"></p>
                                                                                <p class="hideShow accessAid previewCustomImageSection hide"><img id="previewCustomImage" src="<?php echo BMW_PLUGIN_URL ?>/admin/images/info_nobuttonpreview_121wx26h.gif" border="0" alt="Use your own button image"></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="group products">
                                                                    <div class="shipping">
                                                                        <fieldset>
                                                                            <legend><strong>Shipping</strong></legend>
                                                                            <label for="itemFlatShippingAmount" class="control-label">Use specific amount: <input class="text form-control" type="text" id="itemFlatShippingAmount" size="9" name="item_shipping_amount" value=""><span class="currencyLabel">USD</span>

                                                                            </label>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="tax">
                                                                        <fieldset>
                                                                            <legend><strong>Tax</strong></legend>
                                                                            <label for="itemTaxRate" class="control-label">Use tax rate <input class="text xsmall form-control" type="text" id="itemTaxRate" name="item_tax_rate" value="">%</label>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                                <div class="group donations last accessAid fadedOut">
                                                                    <div class="group donationCurrency">
                                                                        <label for="donationCurrency" class="control-label">Currency</label>
                                                                        <select id="donationCurrency" name="item_price_currency" class="currencySelect form-control" disabled="">
                                                                            <?php foreach ($paypal_button_currency as $paypal_button_currency_key => $paypal_button_currency_value) { ?>
                                                                                <option value="<?php echo $paypal_button_currency_value; ?>" title="<?php echo $paypal_button_options_key; ?>"><?php echo $paypal_button_currency_value; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="group contributionAmount">
                                                                        <fieldset>
                                                                            <legend>Contribution amount</legend>
                                                                            <label for="optDonationTypeFlexible" class="control-label"><input class="radio donationType form-control" type="radio" id="optDonationTypeFlexible" checked="" name="donation_type" value="open" disabled="">Donors enter their own contribution amount.</label>&nbsp;&nbsp;<label for="optDonationTypeFixed" class="control-label"><input class="radio donationType form-control" type="radio" id="optDonationTypeFixed" name="donation_type" value="fixed" disabled="">Donors contribute a fixed amount.</label>
                                                                            <div class="labelOption fixedDonationAmountContainer accessAid"><label for="fixedDonationAmount" class="control-label">Amount&nbsp;&nbsp;</label><input type="text" id="fixedDonationAmount" size="7" maxlength="20" class="text form-control" name="item_price" value="" disabled=""><span class="currencyLabel">USD</span></div>
                                                                        </fieldset>
                                                                    </div>
                                                                    <p><strong>Note:</strong> This button is intended for fundraising. If you are not raising money for a cause, please choose another option. Nonprofits must verify their status to withdraw donations they receive. Users that are not verified nonprofits must demonstrate how their donations will be used, once they raise more than $10,000 USD.</p>
                                                                </div>
                                                                <div class="group subscriptions last accessAid fadedOut">
                                                                    <div class="group">
                                                                        <input type="checkbox" id="enableUsernamePasswordCreation" class="checkbox form-control" name="enable_username_password_creation" value="1" disabled="">Have PayPal create user names and passwords for customers&nbsp;
                                                                        <div class="balloonCallout accessAid" id="customerControlHelp">Give customers access to "members-only" content on your site.</div>
                                                                        <div class="fieldNote">
                                                                            <div class="label">Notes: </div>
                                                                            <div class="floatLeft">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="rbFixedAmount">
                                                                        <div class="group"><label for="subscriptionBillingAmount" class="control-label">Billing amount each cycle</label><input type="text" id="subscriptionBillingAmount" size="22" class="text form-control" name="subscription_billing_amount" value="" disabled=""><span class="currencyLabel">USD</span></div>
                                                                        <div class="group">
                                                                            <label for="subscriptionBillingCycleNumber" class="control-label">Billing cycle</label>
                                                                            <?php $paypal_button_subscription_billing_cycle_number = get_paypal_button_subscription_billing_cycle_number(); ?>
                                                                            <select name="subscription_billing_cycle_number" disabled="" class="form-control">
                                                                                <?php foreach ($paypal_button_subscription_billing_cycle_number as $paypal_button_subscription_billing_cycle_number_key => $paypal_button_subscription_billing_cycle_number_value) { ?>
                                                                                    <option value="<?php echo $paypal_button_subscription_billing_cycle_number_value; ?>"><?php echo $paypal_button_subscription_billing_cycle_number_value; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                            <?php $paypal_button_subscriptions_cycle = get_paypal_button_subscriptions_cycle(); ?>
                                                                            <select id="subscriptionBillingCyclePeriod" name="subscription_billing_cycle_period" disabled="" class="form-control">
                                                                                <?php foreach ($paypal_button_subscriptions_cycle as $paypal_button_subscriptions_cycle_key => $paypal_button_subscriptions_cycle_value) { ?>
                                                                                    <option value="<?php echo $paypal_button_subscriptions_cycle_key; ?>"><?php echo $paypal_button_subscriptions_cycle_value; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="group">
                                                                        <label for="subscriptionBillingLimit" class="control-label">After how many cycles should billing stop?</label>
                                                                        <select name="subscription_billing_limit" disabled="" class="form-control">
                                                                            <?php 
                                                                            $paypal_button_subscriptions_cycle_billing_limit = get_paypal_button_subscription_billing_limit();
                                                                            foreach ($paypal_button_subscriptions_cycle_billing_limit as $paypal_button_subscriptions_cycle_billing_limit_key => $paypal_button_subscriptions_cycle_billing_limit_value) { ?>
                                                                                <option value="<?php echo $paypal_button_subscriptions_cycle_billing_limit_value; ?>"><?php echo $paypal_button_subscriptions_cycle_billing_limit_value; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="group">
                                                                        <label for="offerTrial" class="control-label"><input type="checkbox" id="offerTrial" class="checkbox form-control" name="subscriptions_offer_trial" value="1" disabled="">I want to offer a trial period</label>
                                                                        <div class="trialOfferOptions accessAid">
                                                                            <fieldset>
                                                                                <legend>Amount to bill for the trial period
                                                                                </legend>
                                                                                <label for="subscriptionLowerRate" class="control-label">
                                                                                    <input class="hidden" type="hidden" id="subscriptionLowerRate" name="subscription_trial_billing_amount" value="1" disabled="">
                                                                                    <div><input type="text" id="subscriptionLowerRateAmount" size="11" class="text form-control" name="subscription_trial_rate" value="" disabled=""><span class="currencyLabel">USD</span></div>
                                                                                </label>
                                                                            </fieldset>
                                                                            <fieldset>
                                                                                <?php $paypal_button_subscription_trial_duration = get_paypal_button_subscription_trial_duration(); ?>
                                                                                <legend>Define the trial period</legend>
                                                                                <select name="subscription_trial_duration" disabled="" class="form-control">
                                                                                    <?php foreach ($paypal_button_subscription_trial_duration as $paypal_button_subscription_trial_duration_key => $paypal_button_subscription_trial_duration_value) { ?>
                                                                                        <option value="<?php echo $paypal_button_subscription_trial_duration_key; ?>"><?php echo $paypal_button_subscription_trial_duration_value; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                                <select id="trialDurationType" name="subscription_trial_duration_type" disabled="" class="form-control">
                                                                                    <?php foreach ($paypal_button_subscriptions_cycle as $paypal_button_subscriptions_cycle_key => $paypal_button_subscriptions_cycle_value) { ?>
                                                                                        <option value="<?php echo $paypal_button_subscriptions_cycle_key; ?>"><?php echo $paypal_button_subscriptions_cycle_value; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </fieldset>
                                                                            <fieldset>
                                                                                <legend>Do you want to offer a second trial period? <span class="autoTooltip" title="" tabindex="0">What's this?<span class="accessAid">Customers will receive just one bill for each trial period.</span></span></legend>
                                                                                <label for="secondSubscriptionTrialOffer" class="control-label"><input class="radio secondTrialOfferOption form-control" type="radio" id="secondSubscriptionTrialOffer" name="subscriptions_offer_another_trial" value="1" disabled="">Yes</label>
                                                                                <div class="secondTrialOfferOptions accessAid">
                                                                                    <fieldset>
                                                                                        <legend>Amount to bill for this trial period
                                                                                        </legend>
                                                                                        <div><input type="text" id="secondSubscriptionLowerRateAmount" size="11" class="text form-control" name="subscription_trial_2_rate" value="" disabled=""><span class="currencyLabel">USD</span></div>
                                                                                    </fieldset>
                                                                                    <fieldset>
                                                                                        <legend>How long should the trial period last?</legend>
                                                                                        <select name="subscription_trial_2_duration" disabled="" class="form-control">
                                                                                            <?php foreach ($paypal_button_subscription_trial_duration as $paypal_button_subscription_trial_duration_key => $paypal_button_subscription_trial_duration_value) { ?>
                                                                                                <option value="<?php echo $paypal_button_subscription_trial_duration_key; ?>"><?php echo $paypal_button_subscription_trial_duration_value; ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                        <select id="secondTrialDurationType" name="subscription_trial_2_duration_type" disabled="" class="form-control">
                                                                                            <?php foreach ($paypal_button_subscriptions_cycle as $paypal_button_subscriptions_cycle_key => $paypal_button_subscriptions_cycle_value) { ?>
                                                                                                <option value="<?php echo $paypal_button_subscriptions_cycle_key; ?>"><?php echo $paypal_button_subscriptions_cycle_value; ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </fieldset>
                                                                                </div>
                                                                                <label for="lastSubscriptionTrialOffer" class="control-label"><input class="radio secondTrialOfferOption form-control" type="radio" id="lastSubscriptionTrialOffer" checked="" name="subscriptions_offer_another_trial" value="0" disabled="">No</label>
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="group gift_certs last accessAid fadedOut">
                                                                    <div class="group gcCurrency">
                                                                        <label for="gcAmountCurrency" class="control-label">Currency</label>
                                                                        <select id="gcAmountCurrency" name="item_price_currency" class="currencySelect form-control" disabled="">
                                                                            <?php foreach ($paypal_button_currency as $paypal_button_currency_key => $paypal_button_currency_value) { ?>
                                                                                <option value="<?php echo $paypal_button_currency_value; ?>" title="<?php echo $paypal_button_options_key; ?>"><?php echo $paypal_button_currency_value; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="group">
                                                                        <fieldset>
                                                                            <legend>Specify gift certificate amount</legend>
                                                                            <label for="gcAmountTypeList" class="control-label"><input class="radio gcAmountType form-control" type="radio" id="gcAmountTypeList" checked="" name="gc_amount_type" value="custom" disabled="">Choose an amount from a preset list</label><label for="gcAmountTypeFixed" class="control-label"><input class="radio gcAmountType form-control" type="radio" id="gcAmountTypeFixed" name="gc_amount_type" value="fixed" disabled="">Specify an amount of your choosing</label>
                                                                            <div class="labelOption gcFixedAmountContainer accessAid"><label for="gcFixedAmount" class="control-label">Amount</label><input type="text" id="gcFixedAmount" size="9" class="text form-control" name="gc_fixed_amount" value="" disabled=""><span class="currencyLabel">USD</span></div>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="group">
                                                                        <p class="gcStyleHeader_new"><strong>Gift certificate style</strong></p>
                                                                        <label for="giftCertificateLogoURL" class="control-label">Add URL for logo image</label><input class="text form-control" type="text" id="giftCertificateLogoURL" size="34" name="gc_logo_url" value="http://" disabled="">
                                                                    </div>
                                                                    <div class="group">
                                                                        <fieldset>
                                                                            <legend>Choose background</legend>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="stepTwo" class="box">
                                                    <div class="header">
                                                        <?php echo '<h3 id="giftBasedHeading" class="accessAid hide">' . __('Step 2: Track inventory, profit & loss (optional)', 'paypal-wp-button-manager') . '</h3>'; ?>
                                                        <?php echo '<h3 id="productBasedHeading" class="opened">' . __('Step 2: Track inventory, profit & loss (optional)', 'paypal-wp-button-manager') . '</h3>'; ?>
                                                    </div>
                                                    <div class="body">
                                                        <div class="content">
                                                            <div class="container clearfix">
                                                                <div class="step2-left-active">
                                                                    <input class="checkbox form-control" type="checkbox" id="enableHostedButtons" checked="" name="enable_hosted_buttons" value="enabled"><label for="enableHostedButtons" class="control-label"><?php echo __('Save button at PayPal', 'paypal-wp-button-manager'); ?></label>
                                                                    <div class="info-list-wrapper">
                                                                        <ul>
                                                                            <li><?php echo __('Protect your buttons from fraudulent changes', 'paypal-wp-button-manager'); ?></li>
                                                                            <li><?php echo __('Automatically add buttons to "My Saved Buttons" in your PayPal profile', 'paypal-wp-button-manager'); ?></li>
                                                                            <li><?php echo __('Easily create similar buttons', 'paypal-wp-button-manager'); ?></li>
                                                                            <li><?php echo __("Edit your buttons with PayPal's tools", 'paypal-wp-button-manager'); ?> </li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="step2-inventory" id="inventoryOptions">
                                                                        <input class="checkbox form-control" type="checkbox" id="enableInventory" name="enable_inventory" value="enabledInventory"><label for="enableInventory" class="control-label"><?php echo __('Track inventory', 'paypal-wp-button-manager'); ?></label>
                                                                        <p class="hint"><?php echo __("Don't oversell items not in stock -- Get an email alert when inventory is low.", 'paypal-wp-button-manager'); ?></p>
                                                                        <input class="checkbox form-control" type="checkbox" id="enableProfitAndLoss" name="enable_profit_and_loss" value="enabledProfitAndLoss"><label for="enableProfitAndLoss" class="control-label"><?php echo __('Track profit and losses', 'paypal-wp-button-manager'); ?></label>
                                                                        <p class="hint"><?php echo __("View profit and loss report by product/service.", 'paypal-wp-button-manager'); ?></p>
                                                                    </div>
                                                                </div>
                                                                <div class="step2-extra-fields opened" id="inventoryTable">
                                                                    <div id="trackByItemTable" class="fadedOut">
                                                                        <input class="radio form-control" type="radio" id="trackByItem" checked="" name="track_button_by" value="trackdByItem" disabled=""><label id="byItemLabel" for="trackByItem" class="control-label"><strong><?php echo __('By item', 'paypal-wp-button-manager'); ?></strong></label>
                                                                        <div id="byItemTableBody">
                                                                            <div class="inventory-table-row">
                                                                                <div class="left-edge">&nbsp;</div>
                                                                                <div><?php echo __('Item ID', 'paypal-wp-button-manager'); ?></div>
                                                                                <div class="invRelated"><?php echo __('Qty. in stock', 'paypal-wp-button-manager'); ?></div>
                                                                                <div class="invRelated"><?php echo __('Alert qty. (optional)', 'paypal-wp-button-manager'); ?> <span class="autoTooltip helpText" title="" tabindex="0"><?php echo __("What's this?", 'paypal-wp-button-manager'); ?><span class="accessAid"><?php echo __('When your inventory falls to this number, PayPal will send you an e-mail alert.', 'paypal-wp-button-manager'); ?></span></span></div>
                                                                                <div class="PNLRelated"><?php echo __('Price', 'paypal-wp-button-manager'); ?></div>
                                                                                <div class="right-edge">&nbsp;</div>
                                                                            </div>
                                                                            <div class="inventory-table-row">
                                                                                <div class="left-edge">&nbsp;</div>
                                                                                <div><input class="type-text form-control" type="text" name="item_id" value="" disabled=""></div>
                                                                                <div class="invRelated"><input class="type-text form-control" type="text" name="items_in_stock" value="" disabled=""></div>
                                                                                <div class="invRelated"><input class="type-text form-control" type="text" name="alert_quantity" value="" disabled=""></div>
                                                                                <div class="PNLRelated"><input class="type-text form-control" type="text" name="item_cost" value="" disabled=""></div>
                                                                                <div class="right-edge"><?php echo __('USD', 'paypal-wp-button-manager'); ?></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="trackByOptionTable" class="fadedOut accessAid">
                                                                        <input class="radio form-control" type="radio" id="trackByOption" name="track_button_by" value="trackdByOption" disabled=""><label for="trackByOption"><strong><?php echo __('By option', 'paypal-wp-button-manager'); ?></strong><?php echo __('(in drop-down menu)', 'paypal-wp-button-manager'); ?> <a id="chooseAnotherDropDown" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=#chooseAnotherDropDown" class="accessAid"><?php echo __('Choose a different drop-down', 'paypal-wp-button-manager'); ?></a></label>
                                                                        <div id="byOptionTableBody" class="accessAid">
                                                                            <div class="inventory-table-row">
                                                                                <div class="left-edge">&nbsp;</div>
                                                                                <div><?php echo __('Item ID',  'paypal-wp-button-manager'); ?></div>
                                                                                <div class="invRelated"><?php echo __('Qty in stock', 'paypal-wp-button-manager'); ?></div>
                                                                                <div class="invRelated"><?php echo __('Alert qty. (optional)', 'paypal-wp-button-manager'); ?> <span class="autoTooltip helpText" title="" tabindex="0"><?php echo __("What's this?", 'paypal-wp-button-manager'); ?><span class="accessAid"><?php echo __('When your inventory falls to this number, PayPal will send you an e-mail alert.', 'paypal-wp-button-manager'); ?></span></span></div>
                                                                                <div class="PNLRelated"><?php echo __('Cost', 'paypal-wp-button-manager');?> </div>
                                                                                <div class="right-edge">&nbsp;</div>
                                                                            </div>
                                                                            <div class="inventory-table-row">
                                                                                <div class="left-edge">&nbsp;</div>
                                                                                <div><input class="type-text form-control" type="text" name="item_id" value="" disabled=""></div>
                                                                                <div class="invRelated"><input class="type-text form-control" type="text" name="items_in_stock" value="" disabled=""></div>
                                                                                <div class="invRelated"><input class="type-text form-control" type="text" name="alert_quantity" value="" disabled=""></div>
                                                                                <div class="PNLRelated"><input class="type-text form-control" type="text" name="item_cost" value="" disabled=""></div>
                                                                                <div class="right-edge">&nbsp;</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="step2-bottom-fields fadedOut opened" id="soldOutOption">
                                                                    <h5 id="shoppingHead" class="opened"><?php echo __('Can customers buy an item when it is sold out?', 'paypal-wp-button-manager'); ?></h5>
                                                                    <div class="pre-order opened" id="shoppingPreOrder"><input class="radio form-control" type="radio" id="enablePreOrder" name="enable_pre_order" value="enabledPreOrder" disabled=""><label for="enablePreOrder" class="control-label"><?php echo __('Yes, customers can buy the item as usual.', 'paypal-wp-button-manager'); ?></label></div>
                                                                    <div class="no-pre-order">
                                                                        <input class="radio opened form-control" type="radio" id="dontEnablePreOrder" checked="" name="enable_pre_order" value="dontEnablePreOrder" disabled=""><label id="shoppingNoPreOrderLabel" for="dontEnablePreOrder" class="opened control-label"><?php echo __("No, don't let customers buy the item.", 'paypal-wp-button-manager'); ?> <a target="_blank" class="infoLink" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=xpt/Merchant/popup/BDSoldOutExample" onclick="PAYPAL.core.openWindow(event, {width: 560, height: 410})">Preview</a></label>
                                                                        <p class="hint opened fadedOut" id="shoppingURL"><span class="littleHint"><?php echo __('Take customers to specific page when they click', 'paypal-wp-button-manager'); ?> <strong><?php echo __('Continue Shopping', 'paypal-wp-button-manager'); ?></strong><?php echo __('button on "item sold out" page', 'paypal-wp-button-manager'); ?></span><input class="type-text form-control" type="text" id="soldOutURL" name="sold_out_url" value="" disabled=""><span class="littleHint">Ex: http://www.mybuynowstore.com</span></p>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="stepThree" class="box last">
                                                    <div class="header">
                                                        <?php echo '<h3>' . __('Step 3: Customize advanced features (optional)', 'paypal-wp-button-manager') . '</h3>'; ?>
                                                    </div>
                                                    <div class="body">
                                                        <div class="content">
                                                            <div class="container">
                                                                <p class="header">Customize checkout pages</p>
                                                                <p>If you are an advanced user, you can customize checkout pages for your customers, streamline checkout, and more in this section.</p>
                                                                <div id="changeOrderQuantitiesContainer" class="hide">
                                                                    <div>Do you want to let your customer change order quantities?</div>
                                                                    <label class="topSpacer" for="changeOrderQuantities"><input class="radio form-control" type="radio" id="changeOrderQuantities" name="undefined_quantity" value="1">Yes</label><label class="bottomSpacer" for="keepOrderQuantities"><input class="radio" type="radio" id="keepOrderQuantities" checked="" name="undefined_quantity" value="0">No</label>
                                                                </div>
                                                                <div id="specialInstructionsContainer" class="opened">
                                                                    <div>Can your customer add special instructions in a message to you?</div>
                                                                    <label class="topSpacer control-label" for="addSpecialInstructions"><input class="radio form-control" type="radio" id="addSpecialInstructions" checked="" name="no_note" value="0">Yes</label><label id="messageBoxContainer" for="messageBox" class="control-label">Name of message box (40-character limit)<input type="text" id="messageBox" size="40" maxlength="40" class="text form-control" name="custom_note" value="Add special instructions to the seller:"></label><label class="bottomSpacer control-label" for="noSpecialInstructions"><input class="radio form-control" type="radio" id="noSpecialInstructions" name="no_note" value="1">No</label>
                                                                </div>
                                                                <div id="shippingAddressContainer" class="opened">
                                                                    <div>Do you need your customer's shipping address?</div>
                                                                    <label class="topSpacer control-label" for="needShippingAddress"><input class="radio form-control" type="radio" id="needShippingAddress" checked="" name="no_shipping" value="2">Yes</label><label class="bottomSpacer control-label" for="noShippingAddress"><input class="radio form-control" type="radio" id="noShippingAddress" name="no_shipping" value="1">No</label>
                                                                </div>
                                                                <div id="cancellationRedirectURLContainer" class="opened">
                                                                    <label for="cancellationCheckbox" class="control-label"><input class="checkbox form-control" type="checkbox" id="cancellationCheckbox" name="cancellation_return" value="1">Take customers to this URL when they cancel their checkout</label>
                                                                    <div class="redirectContainer">
                                                                        <input type="text" id="cancellationRedirectURL" size="30" class="text form-control" disabled="" name="cancel_return" value="">
                                                                        <div>Example: https://www.mystore.com/cancel</div>
                                                                    </div>
                                                                </div>
                                                                <div id="successfulRedirectURLContainer" class="opened">
                                                                    <label for="successfulCheckbox" class="control-label"><input class="checkbox form-control" type="checkbox" id="successfulCheckbox" name="successful_return" value="1">Take customers to this URL when they finish checkout</label>
                                                                    <div class="redirectContainer">
                                                                        <input type="text" id="successfulRedirectURL" size="30" class="text form-control" disabled="" name="return" value="">
                                                                        <div>Example: https://www.mystore.com/success</div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--  <input type="submit" value="Create Button" class="button-primary create_button" name="publish">-->
                                        </div>
                                    </div>
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