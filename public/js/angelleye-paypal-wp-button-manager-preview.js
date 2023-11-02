var buttonConfig = {
    style : {
        layout : paypal_iframe_preview.layout,
        color: paypal_iframe_preview.color,
        shape: paypal_iframe_preview.shape,
        size: paypal_iframe_preview.size,
        label: paypal_iframe_preview.label,
    },
    createOrder: function() {},
    onApprove: function() {}
};

if( paypal_iframe_preview.height ){
    buttonConfig.style.height = parseInt( paypal_iframe_preview.height );
}

if( paypal_iframe_preview.layout != 'vertical' && paypal_iframe_preview.tagline ){
    buttonConfig.style.tagline = paypal_iframe_preview.tagline
}

var paypalButton = null;
function angelleyeRenderButton() {
    // options

    if (paypalButton) {
        paypalButton.close();
    }
    
    paypalButton = paypal.Buttons(buttonConfig);
    paypalButton.render('#wbp-paypal-button');

    if(advanced_credit_card){
        // Create the Card Fields Component and define callbacks
        const cardField = paypal.CardFields({
            createOrder: function () {},
            onApprove: function () {}
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

document.addEventListener("DOMContentLoaded", function(event) { 
    angelleyeRenderButton();
});