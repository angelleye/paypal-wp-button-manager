var buttonConfig = {
    style : {
        layout : paypal_iframe_preview.layout,
        color: paypal_iframe_preview.color,
        shape: paypal_iframe_preview.shape,
        size: paypal_iframe_preview.size,
        label: paypal_iframe_preview.label,
        tagline: paypal_iframe_preview.tagline
    },
    createOrder: function() {},
    onApprove: function() {}
};

if( paypal_iframe_preview.height ){
    buttonConfig.style.height = paypal_iframe_preview.height;
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