<div id="wbp-paypal-button"></div><?php
$hide_method = !empty( $_GET['hide_funding'] ) ? '&disable-funding=' . $_GET['hide_funding'] : '';
$hidden_methods = explode(',', $_GET['hide_funding'] );
$options = array(
    'layout' => $_GET['layout'],
    'color' => $_GET['color'],
    'shape' => $_GET['shape'],
    'size' => $_GET['size'],
    'height' => $_GET['height'],
    'label' => $_GET['label'],
    'tagline' => $_GET['tagline']
);

$src = 'https://www.paypal.com/sdk/js?';
if( !in_array('card', $hidden_methods ) ){
    $src .= 'components=buttons,card-fields';
}
$src .= '&client-id=' . ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_SANDBOX_PARTNER_CLIENT_ID . '&enable-funding=venmo,paylater' . $hide_method;

?><script src="<?php echo $src; ?>"></script>
<script type="text/javascript">
    var paypal_iframe_preview = <?php echo json_encode( $options ); ?>;
    var advanced_credit_card = <?php echo !in_array('card', $hidden_methods ) ? 'true' : 'false'; ?>;</script>
<script src="<?php echo ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL; ?>public/js/angelleye-paypal-wp-button-manager-preview.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL; ?>public/css/angelleye-paypal-wp-button-manager-preview.css"><?php
if( !in_array('card', $hidden_methods ) ){
    ?><div class="angelleye-or-message"><hr><?php _e('OR','angelleye-paypal-wp-button-manager'); ?><hr></div>
    <div id="checkout-form">
        <div id="card-name-field-container"></div>
        <div id="card-number-field-container"></div>
        <div id="card-expiry-field-container"></div>
        <div id="card-cvv-field-container"></div>
        <button id="card-field-submit-button" type="button" style="<?php echo (isset( $_GET['background_color'] ) && !empty( $_GET['background_color'] ) ) ? 'background: #'. $_GET['background_color'] : '' ?>; <?php echo ( isset( $_GET['foreground_color'] ) && !empty( $_GET['foreground_color'] ) ) ? 'color: #'. $_GET['foreground_color'] : ''; ?>"><?php _e('Place Order','angelleye-paypal-wp-button-manager'); ?></button>
    </div><?php
}