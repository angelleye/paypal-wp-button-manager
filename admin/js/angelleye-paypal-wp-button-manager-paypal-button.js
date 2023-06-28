function angelleyeUpdateConfig(){
    var iframeSrc = jQuery('#wbp-paypal-iframe').attr('src');
    var anchor = jQuery('<a>', { href: iframeSrc })[0];
    var baseUrl = anchor.protocol + '//' + anchor.host + anchor.pathname;
    var tagline = jQuery("#wbp-button-tagline").val() == 'true' ? 'true' : 'false';
    var selectedOptions = Array.from(document.getElementById('wbp-button-hide-funding').selectedOptions);
    var selectedValues = [];
    selectedOptions.forEach(function(option) {
        selectedValues.push(option.value);
    });
    var hideFundingMethod = selectedValues.join(',');

    iframeUrl = baseUrl + '?layout=' + jQuery("#wbp-button-layout").val() + '&color=' + jQuery("#wbp-button-color").val() + '&shape=' + jQuery("#wbp-button-shape").val() + '&size=' + jQuery("#wbp-button-size").val() + '&height=' + jQuery("#wbp-button-height").val() + '&label=' + jQuery("#wbp-button-label").val() + '&tagline=' + tagline + '&hide_funding=' + hideFundingMethod;

    document.getElementById('wbp-paypal-iframe').src = iframeUrl;
}

jQuery(document).on('change', '.wbp-field, #wbp-button-hide-funding', angelleyeUpdateConfig);

jQuery(function($){
    $("#wbp-button-layout").trigger('change');
    $("#wbp-button-hide-funding").select2({
        placeholder: wbp_select2.placeholder
    });

    $(".paypal_shortcode_copy").on('click',function(){
        var temp = $("<input>");
        $("body").append(temp);
        temp.val($(".paypal_shortcode").text()).select();
        document.execCommand("copy");
        temp.remove();

        $('.paypal_shortcode_copy .tooltiptext').text(angelleye_paypal_wp_button_manager_admin_paypal_button.copied_text);

        setTimeout(function(){
            $('.paypal_shortcode_copy .tooltiptext').text(angelleye_paypal_wp_button_manager_admin_paypal_button.copy_text);
        }, 2000);
    });
});

jQuery(document).on('change','#wbp-button-layout',function(){
    if( jQuery(this).val() == 'vertical' ){
        jQuery("#wbp-button-tagline option:eq(0)").prop('selected', true );
        jQuery("#wbp-tagline-field").hide();
    } else {
        jQuery("wbp-tagline-field").show();
    }
});

jQuery(document).on('change','#item_price_currency',function(){
    jQuery('.shipping-currency').text(jQuery(this).find('option:selected').val());
});