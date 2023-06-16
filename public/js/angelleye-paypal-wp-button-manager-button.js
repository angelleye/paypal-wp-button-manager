jQuery(function($){
    $('.wbp-button').each(function(){
        var button_id = $(this).data('button_id');
        var btn_obj = eval( 'btn_obj_' + button_id );
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
                    return data.orderID;
                });
            },
            onApprove: function( data, actions ) {
               actions.redirect(btn_obj.capture_url + '?paypal_order_id=' + data.orderID + '&button_id=' + button_id);
            }
        };
        if( btn_obj.height ){
            buttonConfig.style.height = parseInt( btn_obj.height );
        }
        paypal.Buttons(buttonConfig).render("#wbp-button-" + button_id );
    });
});