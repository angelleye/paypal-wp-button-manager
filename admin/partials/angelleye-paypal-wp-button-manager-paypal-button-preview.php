<div id="wbp-paypal-button"></div><?php
$hide_method = !empty( $_GET['hide_funding'] ) ? '&disable-funding=' . $_GET['hide_funding'] : '';
$options = array(
    'layout' => $_GET['layout'],
    'color' => $_GET['color'],
    'shape' => $_GET['shape'],
    'size' => $_GET['size'],
    'height' => $_GET['height'],
    'label' => $_GET['label'],
    'tagline' => $_GET['tagline']
);
?><script src="https://www.paypal.com/sdk/js?&client-id=<?php echo ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_SANDBOX_PARTNER_CLIENT_ID; ?>&enable-funding=venmo,paylater<?php echo $hide_method; ?>"></script>
<script type="text/javascript">var paypal_iframe_preview = <?php echo json_encode( $options ); ?>;</script>
<script src="<?php echo ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL; ?>public/js/angelleye-paypal-wp-button-manager-preview.js"></script>