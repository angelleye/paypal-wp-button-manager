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
}

document.addEventListener("DOMContentLoaded", function(event) { 
    angelleyeRenderButton();
});