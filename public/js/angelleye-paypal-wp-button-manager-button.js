jQuery(function($){
    $('.wbp-button').each(function(){
        var button_id = $(this).data('button_id');
        var btn_obj = eval( 'btn_obj_' + button_id );
        if( btn_obj.type == 'services' || btn_obj.type == 'subscription' ){
            var buttonConfig = {
                style : {
                    layout: btn_obj.layout,
                    color: btn_obj.color,
                    shape: btn_obj.shape,
                    size: btn_obj.size,
                    label: btn_obj.label,
                    tagline: btn_obj.tagline == 'true' ? true : false
                },
                createOrder: function( data, actions ) {
                    return fetch(btn_obj.api_url, {
                        method: 'POST',
                        headers: {
                            'content-type': 'application/json'
                        },
                        body: JSON.stringify({
                            'button_id' : button_id
                        })
                    }).then(function(response) {
                        return response.json();
                    }).then(function(data) {
                        if( data.orderID ){
                            return data.orderID;
                        } else {
                            if( data.message ){
                                throw new Error(data.message);
                            } else {
                                throw new Error(btn_obj.general_error)
                            }
                        }
                    });
                },
                onApprove: function( data, actions ) {
                   actions.redirect(btn_obj.capture_url + '?paypal_order_id=' + data.orderID + '&button_id=' + button_id);
                },
                onError: function( err, actions ){
                    jQuery('.angelleye-paypal-wp-button-manager-error').remove();
                    jQuery("#form-" + button_id).before('<div class="angelleye-paypal-wp-button-manager-error">' + err + '</div>');
                }
            };
            if( btn_obj.height ){
                buttonConfig.style.height = parseInt( btn_obj.height );
            }
            paypal.Buttons(buttonConfig).render("#wbp-button-" + button_id );
        }
    });
});