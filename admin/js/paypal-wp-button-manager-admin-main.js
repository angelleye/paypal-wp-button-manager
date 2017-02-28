jQuery(function ($) {
    
    jQuery('#buttonType').change(function() {
        var img_type = jQuery(this).val();
        if (img_type == 'donations') {
            jQuery('#previewImage').attr('src','https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_donate_cc_147x47.png');
        } else if (img_type == 'gift_certs') {
            jQuery('#previewImage').attr('src','https://www.paypalobjects.com/en_US/i/btn/btn_gift_LG.gif');	
        } else if (img_type == 'products') {
            jQuery('#previewImage').attr('src','https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_addtocart_120x26.png');	
        } else if (img_type == 'services') {
            jQuery('#previewImage').attr('src','https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_cc_171x47.png');	
        } else if (img_type == 'subscriptions') {
            jQuery('#previewImage').attr('src','https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_subscribe_cc_147x47.png');	
        } 
        
    });
    
    jQuery('#BillingAmountCurrency').change(function() {
        var currencySelected=jQuery(this).val();
        jQuery('.currencyLabel').html('').html(currencySelected);
    });
    
    jQuery('#dropdownPrice').click(function(){		
        if(jQuery('#dropdownPriceSection').hasClass('hide') && jQuery('#previewDropdownPriceSection').hasClass('hide')){
            jQuery('#dropdownPriceSection').removeClass('hide').addClass('opened');
            jQuery('#previewDropdownPriceSection').removeClass('hide').addClass('opened');
        }
        else{
            jQuery('#dropdownPriceSection').removeClass('opened').addClass('hide');
            jQuery('#previewDropdownPriceSection').removeClass('opened').addClass('hide');
        }
    });
    
    $(document).on('keyup','#dropdownPriceTitle',function(){        
        if(''===jQuery.trim(jQuery(this).val())){
            jQuery('#previewDropdownPriceTitle').html('').html('Dropdown Title');
        }
        else{
            $('#previewDropdownPriceTitle').html('').html($(this).val());
        } 
    }); 
    
    $('#cancelOptionPrice').click(function(){
        jQuery('#previewDropdownPriceSection').removeClass('opened').addClass('hide');
    });
    
});