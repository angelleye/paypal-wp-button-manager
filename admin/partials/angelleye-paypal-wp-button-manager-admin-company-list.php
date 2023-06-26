<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Accounts', 'angelleye-paypal-wp-button-manager'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=' . self::$paypal_button_company_slug . '&type=new'); ?>" class="page-title-action"><?php _e('Add Account','angelleye-paypal-wp-button-manager'); ?></a>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-3">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post"><?php
                        $this->paypal_companies->prepare_items();
                        $this->paypal_companies->search_box( __( 'Search Accounts','angelleye-paypal-wp-button-manager' ), 'table-search-input' );
                        $this->paypal_companies->display();
                    ?></form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>