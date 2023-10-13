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
jQuery(document).on('change', '#item-name, #item-price, #item_price_currency, .shipping-tax.row .shipping-amount, .text-rate, #hide-data-fields', previewData);

jQuery(function($){
    $("#wbp-button-layout").trigger('change');
    $("#wbp-button-hide-funding").select2({
        placeholder: wbp_select2.placeholder
    });

    previewData();

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

    $('.row-actions .trash > a').on('click', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var post_id = $(this).parents('tr').attr('id').replace('post-','');
        var html = '<div id="button_delete_popup"></div>';
        $('body').append(html);

        var interval, i;
        $("#button_delete_popup").dialog({
            minWidth: 500,
            buttons: {
                Delete: function(){
                    location.href = url;
                },
                Cancel: function(){
                    $(this).dialog('close');
                }
            },
            title: angelleye_paypal_wp_button_manager_admin_paypal_button.delete_caution,
            open: function( event, ui ){
                $("#button_delete_popup").html('<p>' + angelleye_paypal_wp_button_manager_admin_paypal_button.delete_caution_wait_message + '<span class="append"></span></p>');
                $(".ui-dialog-buttonset > button:eq(0)").hide();
                i=0;
                interval = setInterval( function(){
                    i++;
                    if( i%3 == 0 ){
                        i=0;
                        $("#button_delete_popup p > .append").text('');
                    }
                    $("#button_delete_popup p > .append").append('.');
                }, 1000);

                $.ajax({
                    url: angelleye_paypal_wp_button_manager_admin_paypal_button.ajaxurl,
                    method: 'POST',
                    data: {
                        'action': 'angelleye_paypal_wp_button_manager_admin_paypal_button_check_shortcode_used',
                        'post_id' : post_id
                    },
                    success: function( response ){
                        if( response.success ){
                            if( response.posts.length > 0 ){
                                clearInterval( interval );
                                var _html = '<p>' + angelleye_paypal_wp_button_manager_admin_paypal_button.delete_caution_2 + '</p><ol>';
                                for( var j=0; j<response.posts.length; j++ ){
                                    _html += '<li><a target="_blank" href="' + response.posts[j].url + '">' + response.posts[j].title + '</a></li>';
                                }
                                _html += '</ol><p>' + angelleye_paypal_wp_button_manager_admin_paypal_button.delete_caution_3 + '</p><p>' + angelleye_paypal_wp_button_manager_admin_paypal_button.delete_caution_4 + '<p><p>' + angelleye_paypal_wp_button_manager_admin_paypal_button.delete_caution_5 + '</p>';
                                $("#button_delete_popup").html(_html);
                                $(".ui-dialog-buttonset > button:eq(0)").show();
                            } else {
                                location.href = url;
                            }
                        }
                    }
                });
            },
            close: function( event, ui ){
                i=0;
                clearInterval( interval );
            }
        });
    });

    $("input#hide-data-fields").on("click", function() {
        if($(this).prop("checked") == true) {
            $(".data-fields-additional-settings-row").hide();
        } else {
            $(".data-fields-additional-settings-row").show();
        }
    });

    $('.angelleye-color-picker').wpColorPicker({
        change: previewData
    });
});

jQuery(document).on('change','#wbp-button-layout',function(){
    if( jQuery(this).val() == 'vertical' ){
        jQuery("#wbp-button-tagline option:eq(0)").prop('selected', true );
        jQuery("#wbp-tagline-field").hide();
    } else {
        jQuery("#wbp-tagline-field").show();
    }
});

jQuery(document).on('change','#item_price_currency',function(){
    jQuery('.shipping-currency').text(jQuery(this).find('option:selected').val());
});

function priceHTML(amount){
    return '<span class="wbp-price"><span class="currency">' + jQuery('#item_price_currency').find(":selected").data('title') + '</span><span class="amount">' + parseFloat(amount).toFixed(2) + '</span></span>';
}

function previewData(){
    jQuery(".wbp-form").hide();

    if( jQuery("#item-name").val() ){
        jQuery(".item-name").text(jQuery("#item-name").val());
        jQuery(".item-name-details").show();
    } else {
        jQuery(".item-name-details").hide();
    }

    var price = 0;
    if( jQuery("#item-price").val() ){
        jQuery(".item-price").html( priceHTML( jQuery("#item-price").val() ) );
        price += parseFloat( jQuery("#item-price").val() );
        jQuery(".price-currency").show();
    } else {
        jQuery(".price-currency").hide();
    }

    if( jQuery(".shipping-amount").val() ){
        jQuery(".shipping-rate .shipping").html( priceHTML( jQuery(".shipping-amount").val() ) );
        price += parseFloat( jQuery(".shipping-amount").val() );
        jQuery(".shipping-rate").show();
    } else {
        jQuery(".shipping-rate").hide();
    }

    var tax = 0;
    if( jQuery(".text-rate").val() ){
        jQuery(".tax-rate-label").text( angelleye_paypal_wp_button_manager_admin_paypal_button.preview_tax_label.replace('%s', jQuery(".text-rate").val() ) );
        tax = parseFloat( jQuery(".text-rate").val() );
        var tax_price = ( price * ( tax / 100 ) );
        jQuery(".tax-amount").html( priceHTML( tax_price ) );
        jQuery(".tax-rate").show();
    } else {
        jQuery(".tax-rate").hide();
    }

    if( price != 0 ){
        if( tax != 0 ){
            var total_price = price + ( price * ( tax / 100 ) );
        } else {
            var total_price = price;
        }
        jQuery(".total-amount").html( priceHTML( total_price ) );
        jQuery(".total-amount-label").parent().show();
    } else {
        jQuery(".total-amount-label").parent().hide();
    }

    if( jQuery('input[name="left_background_color"]').val() ){
        var left_background_color = jQuery('input[name="left_background_color"]').val();
        jQuery('.item-name-label, .item-price-label, .shipping-rate-label, .tax-rate-label, .total-amount-label').css('background-color', left_background_color);
    }

    if( jQuery('input[name="left_foreground_color"]').val() ){
        var left_foreground_color = jQuery('input[name="left_foreground_color"]').val();
        jQuery('.item-name-label, .item-price-label, .shipping-rate-label, .tax-rate-label, .total-amount-label').css('color', left_foreground_color);
    }

    if( jQuery('input[name="right_background_color"]').val() ){
        var right_background_color = jQuery('input[name="right_background_color"]').val();
        jQuery('.item-name, .item-price, .shipping-rate .shipping, .tax-amount, .total-amount').css('background-color', right_background_color);
    }

    if( jQuery('input[name="right_foreground_color"]').val() ){
        var right_foreground_color = jQuery('input[name="right_foreground_color"]').val();
        jQuery('.item-name, .item-price, .shipping-rate .shipping, .tax-amount, .total-amount').css('color', right_foreground_color);
    }

    if( !jQuery("#hide-data-fields").is(':checked') ){
        jQuery(".wbp-form").show();
    }
}