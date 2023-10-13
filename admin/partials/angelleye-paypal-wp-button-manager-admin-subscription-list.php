<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Subscriptions', 'angelleye-paypal-wp-button-manager'); ?></h1>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-3"><?php
            ?><div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post"><?php
                        $this->paypal_subscriptions->prepare_items();
                        $this->paypal_subscriptions->search_box( __( 'Search Subscriptions','angelleye-paypal-wp-button-manager' ), 'table-search-input' );
                        $this->paypal_subscriptions->display();
                    ?></form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>