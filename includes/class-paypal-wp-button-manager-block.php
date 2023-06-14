<?php
defined( 'ABSPATH' ) || exit;
/*
 * Class responsible for block
 */
class PayPal_WP_Button_Manager_Block{

    public function __construct(){
        add_action('init', array( $this, 'paypal_block_init') );
        add_action('enqueue_block_editor_assets', array( $this, 'block_editor_assets') );
    }

    public function paypal_block_init(){
        register_block_type(
            'angelleye-paypal-button-manager-block/block',
            array(
                'editor_script' => 'angelleye-paypal-button-manager-block-editor',
                'icon' => WBP_PLUGIN_URL . 'assets/backend/images/paypal-wp-button-manager-icon.png'
            )
        );
    }

    public function block_editor_assets(){

        $button_posts = get_posts( array( 'numberposts' => -1, 'post_type' => PayPal_WP_Button_Manager_Post::$post_type, 'post_status' => 'publish' ) );

        $buttons[] = array(
            'value' => '',
            'label' => __('Please select button','paypal-wp-button-manager')
        );
        foreach( $button_posts as $post ){
            $buttons[] = array(
                'value' => $post->ID,
                'label' => $post->post_title 
            );
        }

        wp_register_script( 'angelleye-paypal-button-manager-block-editor-assets', WBP_PLUGIN_URL . 'assets/backend/js/block-editor.js', array( 'wp-blocks', 'wp-element'), '1.0.0', true );
        wp_localize_script('angelleye-paypal-button-manager-block-editor-assets', 'wbp_block_editor', array( 'image_url' => WBP_PLUGIN_URL . 'assets/backend/images/paypal-wp-button-manager-icon.png', 'buttons' => $buttons, 'dropdown_label' => __('PayPal Button','paypal-wp-button-manager'), 'shortcode' => PayPal_WP_Button_Manager_Post::$shortcode , 'title' => __('PayPal Button', 'paypal-wp-button-manager') ) );
        wp_enqueue_script('angelleye-paypal-button-manager-block-editor-assets');
    }
}