jQuery(function($){
    $('.wbp-button').each(function(){
        var button_id = $(this).data('button_id');
        var btn_obj = eval( 'btn_obj_' + button_id );
        if( btn_obj.type == 'services'){
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
            
            if(btn_obj.advanced_credit_card){
                // Create the Card Fields Component and define callbacks
                const cardField = paypal.CardFields({
                    createOrder: function (data) {
                        return fetch(btn_obj.api_url, {
                            method: "post",
                            headers: {
                                'content-type': 'application/json'
                            },
                            body: JSON.stringify({
                                'button_id' : button_id,
                                'paymentSource': data.paymentSource
                            })
                        })
                        .then((res) => {
                            return res.json();
                        })
                        .then((orderData) => {
                            if( orderData.orderID ){
                                return orderData.orderID;
                            } else {
                                if( orderData.message ){
                                    throw new Error(orderData.message);
                                } else {
                                    throw new Error(btn_obj.general_error)
                                }
                            }
                        });
                    },
                    onApprove: function (data) {
                        location.href = btn_obj.capture_url + '?paypal_order_id=' + data.orderID + '&button_id=' + button_id;
                    },
                    onError: function (error) {
                        jQuery('.angelleye-paypal-wp-button-manager-error').remove();
                        jQuery("#form-" + button_id).before('<div class="angelleye-paypal-wp-button-manager-error">' + error + '</div>');
                    }
                });

                // Render each field after checking for eligibility
                if (cardField.isEligible()) {
                    const nameField = cardField.NameField();
                    nameField.render('#card-name-field-container');

                    const numberField = cardField.NumberField();
                    numberField.render('#card-number-field-container');

                    const cvvField = cardField.CVVField();
                    cvvField.render('#card-cvv-field-container');

                    const expiryField = cardField.ExpiryField();
                    expiryField.render('#card-expiry-field-container');

                    // Add click listener to submit button and call the submit function on the CardField component
                    document.getElementById("card-field-submit-button").addEventListener("click", () => {
                        cardField
                        .submit()
                        .then(() => {
                            // submit successful
                        });
                    });
                };
            }
            
        }
    });
});