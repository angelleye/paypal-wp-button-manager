var buttonConfig = {
    style : {
        layout : 'horizontal',
        color: 'gold',
        shape: 'rect',
        size: 'large',
        label: 'pay',
        tagline: false
    },
    createOrder: function() {},
    onApprove: function() {}
};

var paypalButton = null;
function angelleyeRenderButton() {
    if (paypalButton) {
        paypalButton.close();
    }

    paypalButton = paypal.Buttons(buttonConfig);
    paypalButton.render('#wbp-paypal-button');
}

function angelleyeUpdateConfig(){
    buttonConfig.style.layout = jQuery("#wbp-button-layout").val();
    buttonConfig.style.color = jQuery("#wbp-button-color").val();
    buttonConfig.style.shape = jQuery("#wbp-button-shape").val();
    buttonConfig.style.size = jQuery("#wbp-button-size").val();
    if( jQuery("#wbp-button-height").val() ){
        buttonConfig.style.height = parseInt( jQuery("#wbp-button-height").val() );
    } else {
        if( 'height' in buttonConfig.style ){
            delete buttonConfig.style.height;
        }
    }
    buttonConfig.style.label = jQuery("#wbp-button-label").val();
    buttonConfig.style.tagline = jQuery("#wbp-button-tagline").val() == 'true' ? true : false;

    angelleyeRenderButton();
}

jQuery(document).on('change', '.wbp-field', angelleyeUpdateConfig);

jQuery(function($){
    $('.paypal_shortcode').tooltip();
    angelleyeUpdateConfig();
    $("#wbp-button-layout").trigger('change');
    $("#wbp-button-hide-funding").select2({
        placeholder: wbp_select2.placeholder
    });

    $('.paypal_shortcode').on('click',function(){
        var temp = $("<input>");
        $("body").append(temp);
        temp.val($(this).text()).select();
        document.execCommand("copy");
        temp.remove();
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