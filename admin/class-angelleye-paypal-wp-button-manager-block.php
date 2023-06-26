<?php
defined( 'ABSPATH' ) || exit;
/*
 * Class responsible for block
 */
class Angelleye_Paypal_Wp_Button_Manager_Block{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    public function __construct( $plugin_name, $version ){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action('init', array( $this, 'paypal_block_init') );
        add_action('enqueue_block_editor_assets', array( $this, 'block_editor_assets') );
    }

    public function paypal_block_init(){
        register_block_type(
            'angelleye-paypal-wp-button-manager-block/block',
            array(
                'editor_script' => 'angelleye-paypal-wp-button-manager-block-editor',
                'icon' => ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_IMAGE_PATH . 'angelleye-paypal-wp-button-manager-icon.png'
            )
        );
    }

    public function block_editor_assets(){

        $button_posts = get_posts( array( 'numberposts' => -1, 'post_type' => Angelleye_Paypal_Wp_Button_Manager_Post::$post_type, 'post_status' => 'publish' ) );

        $buttons[] = array(
            'value' => '',
            'label' => __('Please select button','angelleye-paypal-wp-button-manager')
        );
        foreach( $button_posts as $post ){
            $buttons[] = array(
                'value' => $post->ID,
                'label' => $post->post_title 
            );
        }

        wp_register_script( $this->plugin_name . '-block-editor-assets', ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_URL . 'admin/js/angelleye-paypal-wp-button-manager-block-editor.js', array( 'wp-blocks', 'wp-element'), $this->version, true );
        wp_localize_script( $this->plugin_name . '-block-editor-assets', 'wbp_block_editor', array( 'image_url' => ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_IMAGE_PATH . 'angelleye-paypal-wp-button-manager-icon.png', 'buttons' => $buttons, 'dropdown_label' => __('PayPal Button','angelleye-paypal-wp-button-manager'), 'shortcode' => Angelleye_Paypal_Wp_Button_Manager_Post::$shortcode , 'title' => __('PayPal Button', 'angelleye-paypal-wp-button-manager') ) );
        wp_enqueue_script( $this->plugin_name . '-block-editor-assets');
    }
}