<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    paypal-wp-button-manager
 * @subpackage paypal-wp-button-manager/admin
 * @author     Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_WP_Button_Manager_Admin {

	/**
     * The ID of this plugin.
     *
     * @since    0.1.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
	private $plugin_name;

	/**
     * The version of this plugin.
     *
     * @since    0.1.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
	private $version;

	/**
     * Initialize the class and set its properties.
     *
     * @since    0.1.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
	public function __construct($plugin_name, $version) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->load_dependencies();
	}

	/**
     * Register the stylesheets for the Dashboard.
     *
     * @since    0.1.0
     */
	public function enqueue_styles() {
		wp_enqueue_style('thickbox'); // call to media files in wp
		wp_enqueue_style($this->plugin_name . 'one', plugin_dir_url(__FILE__) . '/css/paypal-wp-button-manager-global.css', array(), $this->version, false);
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/paypal-wp-button-manager-master.css', array(), $this->version, false);
		wp_enqueue_style($this->plugin_name . 'two', plugin_dir_url(__FILE__) . '/css/paypal-wp-button-manager-coreLayout.css', array(), $this->version, false);
		wp_enqueue_style($this->plugin_name . 'three', plugin_dir_url(__FILE__) . '/css/paypal-wp-button-manager-me2.css', array(), $this->version, false);
		wp_enqueue_style($this->plugin_name . 'four', plugin_dir_url(__FILE__) . '/css/paypal-wp-button-manager-print.css', array(), $this->version, false);
		wp_enqueue_style($this->plugin_name . 'five', plugin_dir_url(__FILE__) . 'css/paypal-wp-button-manager-admin.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . 'seven', plugin_dir_url(__FILE__) . 'css/webkit/fontello.css', array(), $this->version, 'all');
	}

	/**
     * Register the JavaScript for the dashboard.
     *
     * @since    0.1.0
     */
	public function enqueue_scripts() {

		$screen = get_current_screen();

		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-accordion');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('jquery-ui-tooltip');

		wp_enqueue_script($this->plugin_name . 'one', plugin_dir_url(__FILE__) . 'js/paypal-wp-button-manager-global.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . 'three', plugin_dir_url(__FILE__) . 'js/paypal-wp-button-manager-pa.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . 'five', plugin_dir_url(__FILE__) . 'js/paypal-wp-button-manager-widgets.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . 'four', plugin_dir_url(__FILE__) . 'js/paypal-wp-button-manager-pp_jscode_080706.js', array('jquery'), $this->version, false);

		if($screen->post_type == 'paypal_buttons')
		{
			wp_enqueue_script($this->plugin_name . 'admin-image-uploader', plugin_dir_url(__FILE__) . 'js/paypal-wp-button-manager-admin-image-uploader.js', array('jquery'), $this->version, false);
		}

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/paypal-wp-button-manager-admin.js', array('jquery'), $this->version, false);

		if (wp_script_is($this->plugin_name)) {
			wp_localize_script($this->plugin_name, 'paypal_wp_button_manager_plugin_url', apply_filters('paypal_wp_button_manager_plugin_url_filter', array(
			'plugin_url' => plugin_dir_url(__FILE__)
			)));
		}

		if (wp_script_is($this->plugin_name)) {
			wp_localize_script($this->plugin_name, 'paypal_wp_button_manager_wpurl', apply_filters('paypal_wp_button_manager_wpurl_filter', array(
			'wp_admin_url' => admin_url() . 'admin.php?page=paypal-wp-button-manager-option&tab=company'
			)));
		}



		global $post;
		$args = array('post_type' => 'paypal_buttons', 'posts_per_page' => '100', 'post_status' => array('publish'));
		$paypal_buttons_posts = get_posts($args);
		$shortcodes = array();
		$shortcodes_values = array();
		foreach ($paypal_buttons_posts as $key_post => $paypal_buttons_posts_value) {
			$shortcodes[$paypal_buttons_posts_value->ID] = $paypal_buttons_posts_value->post_title;
		}

		if (empty($shortcodes)) {

			$shortcodes_values = array('0' => 'No shortcode Available');
		} else {
			$shortcodes_values = $shortcodes;
		}
		wp_localize_script('paypal-wp-button-manager', 'shortcodes_button_array', apply_filters('paypal_wp_button_manager_shortcode', array(
		'shortcodes_button' => $shortcodes_values
		)));
	}

	private function load_dependencies() {

		/**
         * The class responsible for defining all actions that occur in the Dashboard for Paypal buttons.
         */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/class-paypal-wp-button-manager-post-types.php';

		/**
         * The class responsible for defining all actions that occur in the Dashboard
         */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/paypal-wp-button-manager-admin-display.php';

		/**
         * The class responsible for defining function for display Html element
         */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/class-paypal-wp-button-manager-html-output.php';

		/**
         * The class responsible for defining function for display general setting tab
         */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/class-paypal-wp-button-manager-general-setting.php';

		/**
         * The class responsible for defining function for display company setting tab
         */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/class-paypal-wp-button-manager-companies.php';

		/**
         * The class responsible for defining function for add edit delete operation on company
         */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/class-paypal-wp-button-manager-companies_operation.php';
	}

	/**
     * paypal_wp_button_manager_notice_display function is use for display
     * error of paypal response.
     * @global type $post returns the post values.\
     * @since 1.0.0
     * @access public
     */
	public function paypal_wp_button_manager_notice_display() {
		global $post;
		$errors_notice = get_option('paypal_wp_button_manager_notice');
		$error_code = get_option('paypal_wp_button_manager_error_code');
		$timeout_notice = get_option('paypal_wp_button_manager_timeout_notice');
		$success_notice = get_option('paypal_wp_button_manager_success_notice');
		if ((isset($errors_notice) && !empty($errors_notice)) && (isset($error_code) && !empty($error_code)) && (empty($timeout_notice))) {
			echo _e('<div class="error"><p>Error Code:&nbsp;' . $error_code[$post->ID] . '<br/>Error Details:&nbsp;' . $errors_notice[$post->ID] . '</p><input type="hidden" name="err_btn" value="err_btn"/></div>', 'paypal-wp-button-manager');
			echo "<style>.updated{display:none;}</style>";
			unset($errors_notice[$post->ID]);
			unset($error_code[$post->ID]);
			delete_option('paypal_wp_button_manager_notice');
			delete_option('paypal_wp_button_manager_error_code');

			// update_option('paypal_wp_button_manager_notice', $errors_notice[$post->ID]);
			// update_option('paypal_wp_button_manager_error_code', $error_code[$post->ID]);
		} else if (isset($timeout_notice) && !empty($timeout_notice)) {
			echo _e('<div class="error"><p>Error Details:&nbsp;' . $timeout_notice[$post->ID] . '</p></div>', 'paypal-wp-button-manager');
			echo "<style>.updated{display:none;}</style>";
			unset($timeout_notice[$post->ID]);
			delete_option('paypal_wp_button_manager_timeout_notice');
			//    update_option('paypal_wp_button_manager_timeout_notice', $timeout_notice[$post->ID]);
		}
                
                
                if( $this->pwbm_display_plugin_update_notice() ){
                    echo "<div class='error'><p>" . sprintf(__("You have recently updated PayPal WP Button Manager. <a href=%s>%s</a> to see what's new! | <a href=%s>%s</a>", 'paypal-for-woocommerce'), esc_url(admin_url( 'index.php?page=paypal-wp-button-manager-about&tab=pbm_about' )) , __("Click here", 'paypal-for-woocommerce') , esc_url(add_query_arg("ignore_pwbm_update_notice",0)) , __("Hide this notice", 'paypal-for-woocommerce')) . "</p></div>";
                }
	}

	/**
     * paypal_wp_button_manager_success_notice_display function is use for
     * change paypal_buttons post update message.
     * @param type $messages returns the custom message.
     * @since 1.0.0
     * @access public
     */
	public function paypal_wp_button_manager_success_notice_display($messages) {

		global $post, $post_ID;
		$paypal_button_html = get_post_meta($post_ID, 'paypal_button_response', true);

		$success_message = get_post_meta($post_ID, 'paypal_wp_button_manager_success_notice', true);

		if ((isset($paypal_button_html) && !empty($success_message))) {
			$custom_message = $success_message;
		} else {
			$custom_message = 'Button Updated Successfully.';
		}



		$post_ID = $post->ID;
		$post_type = get_post_type($post_ID);

		$messages['paypal_buttons'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf(__('Button Updated Successfully')),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __($custom_message),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf(__('Button restored to revision from %s'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
		6 => sprintf(__($custom_message)),
		7 => __('Button saved.'),
		8 => sprintf(__('Button submitted. <a target="_blank" href="%s">Preview Button</a>'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
		9 => sprintf(__('Button scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Button</a>'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
		10 => sprintf(__('Button draft updated. <a target="_blank" href="%s">Preview Button</a>'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
		);
		return $messages;
	}

	/**
     *  paypal_wp_button_manager_shortcode_button_init function process for registering our button.
     *
     */
	public function paypal_wp_button_manager_shortcode_button_init() {
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
		return;

		//Add a callback to regiser our tinymce plugin
		add_filter('mce_external_plugins', array($this, 'paypal_wp_button_manager_register_tinymce_plugin'));

		// Add a callback to add our button to the TinyMCE toolbar
		add_filter('mce_buttons', array($this, 'paypal_wp_button_manager_add_tinymce_button'));

	}

	public function paypal_wp_button_manager_register_tinymce_plugin($plugin_array) {
		$plugin_array['pushortcodes'] = plugin_dir_url(__FILE__) . 'js/paypal-wp-button-manager-admin.js';
		return $plugin_array;
	}

	public function paypal_wp_button_manager_add_tinymce_button($buttons) {
		array_push($buttons, 'separator', 'pushortcodes');
		return $buttons;
	}

	public function paypal_wp_button_manager_print_mynote() {
		global $typenow, $pagenow, $wpdb, $post, $post_ID;
		$table_name = $wpdb->prefix . "posts";
		$postmeta_table = $wpdb->prefix . "postmeta";

		$viecart_post_id = get_option('paypal_wp_button_manager_viewcart_button_postid');
		if (isset($viecart_post_id) && !empty($viecart_post_id)) {
			$view_cart_postid_status = $wpdb->get_row("SELECT count(*) as cnt_viewcart_postid  from  $table_name where post_status ='publish' and post_type='paypal_buttons' and ID='$viecart_post_id'");
		}
		$viewcart_post = $wpdb->get_row("SELECT COUNT(*)as cnt_viewcart from  $table_name where post_status ='publish'  and post_type='paypal_buttons'");
		$is_shopping_button_count_obj = $wpdb->get_row("SELECT COUNT(*)as cnt_is_shopping from  $postmeta_table where meta_key='paypal_wp_button_manager_is_shopping' and meta_value='1'");



		$is_shopping_button_count = $is_shopping_button_count_obj->cnt_is_shopping;
		if (isset($view_cart_postid_status) && !empty($view_cart_postid_status)) {
			if ($view_cart_postid_status->cnt_viewcart_postid <= 0) {
				delete_option('paypal_wp_button_manager_viewcart_button');
			}
		}
		if ($viewcart_post->cnt_viewcart <= 0) {
			delete_option('paypal_wp_button_manager_view_cart_status');
			delete_option('paypal_wp_button_manager_viewcart_button');
			delete_option('paypal_wp_button_manager_viewcart_post_id');
			$wpdb->query($wpdb->prepare("DELETE from $wpdb->postmeta  WHERE meta_key  = %s", 'paypal_wp_button_manager_shopping_post'));
		}
		$shopping_cart_post = get_post_meta($post_ID, 'paypal_wp_button_manager_shopping_post');
		if (isset($shopping_cart_post) && !empty($shopping_cart_post)) {
			$shopping_cart_post_value = $shopping_cart_post;
		}
		$view_cart_button_status = get_option('paypal_wp_button_manager_view_cart_status');
		$paypal_wp_button_manager_viewcart_button = get_option('paypal_wp_button_manager_viewcart_button');
		if (isset($is_shopping_button_count) && $is_shopping_button_count <= 0) {
			$view_cart_button_status_value = '';
		} else if ((isset($view_cart_button_status) && !empty($view_cart_button_status)) && (empty($paypal_wp_button_manager_viewcart_button))) {
			$view_cart_button_status_value = "1";
		} else {
			$view_cart_button_status_value = '';
		}
	}

	public function paypal_wp_button_manager_create_viewcart_action() {
		global $post, $post_ID;
		$payapal_helper = new AngellEYE_PayPal_WP_Button_Manager_PayPal_Helper();
		$PayPalConfig = $payapal_helper->paypal_wp_button_manager_get_paypalconfig();
		$PayPal = new Angelleye_PayPal($PayPalConfig);
		$BMCreateButtonFields_viewcart = array
		(
		'buttoncode' => 'CLEARTEXT', // The kind of button code to create.  It is one of the following values:  HOSTED, ENCRYPTED, CLEARTEXT, TOKEN
		'buttontype' => 'VIEWCART', // Required.  The kind of button you want to create.  It is one of the following values:  BUYNOW, CART, GIFTCERTIFICATE, SUBSCRIBE, DONATE, UNSUBSCRIBE, VIEWCART, PAYMENTPLAN, AUTOBILLING, PAYMENT
		'buttonsubtype' => '', // The use of button you want to create.  Values are:  PRODUCTS, SERVICES
		);


		$PayPalRequestData_viewcart = array(
		'BMCreateButtonFields' => $BMCreateButtonFields_viewcart,
		'BMButtonVars' => ''
		);

		$PayPalResult_viewcart = $PayPal->BMCreateButton($PayPalRequestData_viewcart);

		if (isset($PayPalResult_viewcart['WEBSITECODE']) && !empty($PayPalResult_viewcart['WEBSITECODE'])) {
			// Create post object
			$view_cart_post = array(
			'post_title' => 'View Cart',
			'post_content' => $PayPalResult_viewcart['WEBSITECODE'],
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type' => 'paypal_buttons'
			);

			// Insert the post into the database
			$post_id = wp_insert_post($view_cart_post);
			$term = term_exists('Shopping cart', 'paypal_button_types');
			$tag[] = $term['term_id'];

			$update_term = wp_set_post_terms($post_id, $tag, 'paypal_button_types');

			update_post_meta($post_id, 'paypal_button_response', $PayPalResult_viewcart['WEBSITECODE']);
			update_post_meta($post_id, 'paypal_wp_button_manager_viewcart_button_companyid', $cid);

			// update_option('paypal_wp_button_manager_viewcart_button', '1');
			update_option('paypal_wp_button_manager_viewcart_button_postid', $post_id);
		}
	}

	public function paypal_wp_button_manager_print_emptytrash() {
		global $typenow, $pagenow, $wpdb, $post, $post_ID;

		$table_name = $wpdb->prefix . "paypal_wp_button_manager_companies";
		$postmeta_table = $wpdb->prefix . "postmeta";
		$post_tablename = $wpdb->prefix . "posts";
		$get_companycount = $wpdb->get_row("SELECT count(*) as cnt_company from $table_name");
		$get_trashpost_count = $wpdb->get_row("SELECT count(*) as cnt_count_trash from $post_tablename where post_status = 'trash' and post_type = 'paypal_buttons'");
		$get_hosted_button_count = $wpdb->get_row("SELECT count(*) as cnt_hosted from $postmeta_table where meta_key ='paypal_wp_button_manager_trash_hosted_id'");


		if (isset($get_companycount->cnt_company) && !empty($get_companycount->cnt_company)) {
			$count_companies = $get_companycount->cnt_company;
		}

		if (isset($get_trashpost_count->cnt_count_trash) && !empty($get_trashpost_count->cnt_count_trash)) {
			$count_trashpost = $get_trashpost_count->cnt_count_trash;
		}

		if (isset($get_hosted_button_count->cnt_hosted) && !empty($get_hosted_button_count->cnt_hosted)) {
			$count_hostedbuttons = $get_hosted_button_count->cnt_hosted;
		}

		$screen = get_current_screen();
		if ($screen->post_type == 'paypal_buttons') {
			if (((in_array($pagenow, array('edit.php')) && ('paypal_buttons' == 'paypal_buttons' ))) && (isset($count_companies) && $count_companies > 0) && (isset($count_hostedbuttons) && !empty($count_hostedbuttons)) && ((isset($count_trashpost) && $count_trashpost > 0)) && (isset($_GET['post_status']) && ($_GET['post_status'] == 'trash'))) {
                ?>
                <script>
                jQuery( document ).ready(function() {

                	jQuery(".wrap").find("h2").after('<div class="updated below-h2 msg_div"><p class="msg_text">Do you want to delete all PayPal hosted buttons ?</p><p class="btn_para"><span class="button button-primary button-large btn_del_allpaypal">Delete All Hosted Buttons</span></p><img src="<?php echo plugin_dir_url(__FILE__) ?>images/ajax-loader.gif" id="gifimg"/></div>');
                	jQuery( ".btn_del_allpaypal" ).click(function() {
                		jQuery('#gifimg').css('visibility','visible');
                		jQuery('#gifimg').css('display','inline');
                		var data = {
                		'action': 'del_all_hostedbutton'

                		};

                		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                		jQuery.post(ajaxurl, data, function(response) {
                			jQuery(".msg_div").remove();
                			jQuery('#gifimg').css('display','none');
                			location.reload();


                		});

                	});

                });
                </script>
                <?php
			}
		}
	}

	public static function paypal_wp_button_manager_del_all_hostedbutton() {
		global $wpdb, $post;
		$table_name = $wpdb->prefix . "posts";
		$postmeta_table = $wpdb->prefix . "postmeta";
		$get_trashpost = $wpdb->get_results("SELECT *from $table_name where post_status = 'trash' and post_type = 'paypal_buttons'");

		if (isset($get_trashpost) && !empty($get_trashpost)) {

			foreach ($get_trashpost as $get_trashpost_obj) {


				$obj_for_log = new AngellEYE_PayPal_WP_Button_Manager_button_generator();

				$button_hosted_id = get_post_meta($get_trashpost_obj->ID, 'paypal_wp_button_manager_button_id', true);
				$ddl_companyname = get_post_meta($get_trashpost_obj->ID, 'paypal_wp_button_manager_company_rel', true);
				if ((isset($ddl_companyname) && !empty($ddl_companyname)) && (isset($button_hosted_id) && !empty($button_hosted_id))) {
					// Prepare request arrays

					global $wpdb;
					$flag = '';
					$tbl_name = $wpdb->prefix . 'paypal_wp_button_manager_companies'; // do not forget about tables prefix
					$getconfig = $wpdb->get_row("SELECT * FROM `{$tbl_name}` where ID='$ddl_companyname'");
					$is_sandbox = isset($getconfig->paypal_mode) ? $getconfig->paypal_mode : '';
					if (isset($is_sandbox) && !empty($is_sandbox)) {
						if ($is_sandbox == 'Sandbox') {
							$flag = TRUE;
						} else if ($is_sandbox == 'Live') {
							$flag = FALSE;
						}
					}

					$APIUsername = isset($getconfig->paypal_api_username) ? $getconfig->paypal_api_username : '';
					$APIPassword = isset($getconfig->paypal_api_password) ? $getconfig->paypal_api_password : '';
					$APISignature = isset($getconfig->paypal_api_signature) ? $getconfig->paypal_api_signature : '';

					$payapalconfig = array('Sandbox' => $flag,
					'APIUsername' => isset($APIUsername) ? $APIUsername : '',
					'APIPassword' => isset($APIPassword) ? $APIPassword : '',
					'APISignature' => isset($APISignature) ? $APISignature : '',
					'PrintHeaders' => isset($print_headers) ? $print_headers : '',
					'LogResults' => isset($log_results) ? $log_results : '',
					'LogPath' => isset($log_path) ? $log_path : ''
					);

					$PayPal = new Angelleye_PayPal($payapalconfig);

					$BMManageButtonStatusFields = array
					(
					'hostedbuttonid' => $button_hosted_id, // Required.  The ID of the hosted button whose inventory information you want to obtain.
					'buttonstatus' => 'DELETE', // Required.  The new status of the button.  Values are:  DELETE
					);

					$PayPalRequestData = array(
					'BMManageButtonStatusFields' => $BMManageButtonStatusFields,
					);

					// Pass data into class for processing with PayPal and load the response array into $PayPalResult
					$PayPal_Delete_Button_Result = $PayPal->BMManageButtonStatus($PayPalRequestData);
					$obj_for_log->paypal_wp_button_manager_write_error_log($PayPal_Delete_Button_Result);

					global $wpdb;
					$tbl_name = $wpdb->prefix . "posts";
					$post_meta = $wpdb->prefix . "postmeta";
					$wpdb->query($wpdb->prepare("DELETE FROM $tbl_name WHERE ID = %d", $get_trashpost_obj->ID));
					$wpdb->query($wpdb->prepare("DELETE FROM $post_meta WHERE post_id = %d", $get_trashpost_obj->ID));
				}
			}
		}

		echo "1";
		exit(0);
	}

	public function paypal_wp_button_manager_wp_trash_post($post_id) {
		$post_type = get_post_type($post_id);
		$post_status = get_post_status($post_id);
		if ($post_type == 'paypal_buttons' && in_array($post_status, array('publish'))) {
			$getcompanyid = get_post_meta($post_id, 'paypal_wp_button_manager_viewcart_button_companyid', true);
			$get_hosted_id = get_post_meta($post_id, 'paypal_wp_button_manager_button_id', true);
			if (isset($getcompanyid) && !empty($getcompanyid)) {
				delete_post_meta($post_id, 'paypal_wp_button_manager_viewcart_button_companyid');
			}
			// below code is for keeep record that is there any hosted button is in trash.
			if (isset($get_hosted_id) && !empty($get_hosted_id)) {
				update_post_meta($post_id, 'paypal_wp_button_manager_trash_hosted_id', $get_hosted_id);
			}
		}
	}

	public static function paypal_wp_button_manager_checkconfig() {

		global $wpdb;
		$companies = $wpdb->prefix . 'paypal_wp_button_manager_companies';
		$result_config = $wpdb->get_row("SELECT * FROM `{$companies}` WHERE ID ='$_POST[ddl_companyname]'");
		if (isset($result_config) && !empty($result_config)) {
			$APIUsername = isset($result_config->paypal_api_username) ? $result_config->paypal_api_username : '';
			$APIPassword = isset($result_config->paypal_api_password) ? $result_config->paypal_api_password : '';
			$APISignature = isset($result_config->paypal_api_signature) ? $result_config->paypal_api_signature : '';
			if ((isset($APIUsername) && !empty($APIUsername)) && (isset($APIPassword) && !empty($APIPassword)) && (isset($APISignature) && !empty($APISignature))) {
				echo '1';
			} else {
				echo "2";
			}
		}
		exit(1);
	}

	public static function paypal_wp_button_manager_before_delete_post() {
		global $wpdb;
		$obj_for_log = new AngellEYE_PayPal_WP_Button_Manager_button_generator();

		$button_hosted_id = get_post_meta($_POST['del_post_id'], 'paypal_wp_button_manager_button_id', true);
		$ddl_companyname = get_post_meta($_POST['del_post_id'], 'paypal_wp_button_manager_company_rel', true);
		if ((isset($ddl_companyname) && !empty($ddl_companyname)) && (isset($button_hosted_id) && !empty($button_hosted_id))) {
			// Prepare request arrays

			global $wpdb;
			$flag = '';
			$tbl_name = $wpdb->prefix . 'paypal_wp_button_manager_companies'; // do not forget about tables prefix
			$getconfig = $wpdb->get_row("SELECT * FROM `{$tbl_name}` where ID='$ddl_companyname'");
			$is_sandbox = isset($getconfig->paypal_mode) ? $getconfig->paypal_mode : '';
			if (isset($is_sandbox) && !empty($is_sandbox)) {
				if ($is_sandbox == 'Sandbox') {
					$flag = TRUE;
				} else if ($is_sandbox == 'Live') {
					$flag = FALSE;
				}
			}

			$APIUsername = isset($getconfig->paypal_api_username) ? $getconfig->paypal_api_username : '';
			$APIPassword = isset($getconfig->paypal_api_password) ? $getconfig->paypal_api_password : '';
			$APISignature = isset($getconfig->paypal_api_signature) ? $getconfig->paypal_api_signature : '';

			$payapalconfig = array('Sandbox' => $flag,
			'APIUsername' => isset($APIUsername) ? $APIUsername : '',
			'APIPassword' => isset($APIPassword) ? $APIPassword : '',
			'APISignature' => isset($APISignature) ? $APISignature : '',
			'PrintHeaders' => isset($print_headers) ? $print_headers : '',
			'LogResults' => isset($log_results) ? $log_results : '',
			'LogPath' => isset($log_path) ? $log_path : ''
			);

			$PayPal = new Angelleye_PayPal($payapalconfig);

			$BMManageButtonStatusFields = array
			(
			'hostedbuttonid' => $button_hosted_id, // Required.  The ID of the hosted button whose inventory information you want to obtain.
			'buttonstatus' => 'DELETE', // Required.  The new status of the button.  Values are:  DELETE
			);

			$PayPalRequestData = array(
			'BMManageButtonStatusFields' => $BMManageButtonStatusFields,
			);

			// Pass data into class for processing with PayPal and load the response array into $PayPalResult
			$PayPal_Delete_Button_Result = $PayPal->BMManageButtonStatus($PayPalRequestData);
			$obj_for_log->paypal_wp_button_manager_write_error_log($PayPal_Delete_Button_Result);

			global $wpdb;
			$tbl_name = $wpdb->prefix . "posts";
			$post_meta = $wpdb->prefix . "postmeta";
			$wpdb->query($wpdb->prepare("DELETE FROM $tbl_name WHERE ID = %d", $_POST['del_post_id']));
			$wpdb->query($wpdb->prepare("DELETE FROM $post_meta WHERE post_id = %d", $_POST['del_post_id']));
			echo "1";
			exit(0);
		}
	}

	public static function paypal_wp_button_manager_checkhosted_button() {
		$btnid = get_post_meta($_POST['btnid'], 'paypal_wp_button_manager_button_id', true);

		if (isset($btnid) && !empty($btnid)) {
			echo "1";
		} else {
			echo "";
		}


		exit(0);
	}

	public static function paypal_wp_button_manager_delete_post_own() {
		global $wpdb;
		$tbl_name = $wpdb->prefix . "posts";
		$post_meta = $wpdb->prefix . "postmeta";
		$wpdb->query($wpdb->prepare("DELETE FROM $tbl_name WHERE ID = %d", $_POST['del_post']));
		$wpdb->query($wpdb->prepare("DELETE FROM $post_meta WHERE post_id = %d", $_POST['del_post']));
		echo "1";
		exit(0);

	}

	public static function paypal_wp_button_manager_cancel_donate() {
		update_option('paypal_wp_button_cancel','y');
		exit(1);

	}

	public static function paypal_wp_button_manager_beer_metabox() {
		add_meta_box('bmw_meta', __( 'Buy Us a Beer!', 'paypal-wp-button-manager' ), array(__CLASS__,'paypal_wp_button_manager_meta_callback'), 'paypal_buttons', 'side', 'default',10,1);
	}




	public static function paypal_wp_button_manager_meta_callback($post) {

		$is_cancel = get_option('paypal_wp_button_cancel');
       	if (isset($is_cancel) && empty($is_cancel)):?>
                
        <div class="div_buymebeer div_buymebeer_meta">
    	 <a href="https://www.angelleye.com/product/buy-beer/?utm_source=paypal_wp_button_manager&utm_medium=buy_me_a_beer&utm_campaign=beer_me" target="_blank"><img src="<?php echo BMW_PLUGIN_URL ?>/admin/images/buy-us-a-beer.png" id="img_beer"/></a>

    	  <div class="div_cancel_donate">
        		<span class="button-primary btn_can_notice">Dismiss</span>
          </div>
    	 
    	 </div>
        
      <?php  endif; 
	}

	/**
     * paypal_wp_button_manager_welcome_page function is use for
     * Welcome Page when User is Active plugin.
     * @since 1.0.0
     * @access public
     */

	public function paypal_wp_button_manager_welcome_page() {
		if ( empty( $_GET['page'] ) ) {
			return;
		}

		$welcome_page_name  = __( 'About the PayPal Button Manager', 'paypal-wp-button-manager' );
		$welcome_page_title = __( 'Welcome to the PayPal Button Manager', 'paypal-wp-button-manager' );

		switch ( $_GET['page'] ) {
			case 'paypal-wp-button-manager-about' :
				$page = add_dashboard_page( $welcome_page_title, $welcome_page_name, 'manage_options', 'paypal-wp-button-manager-about', array( $this, 'about_screen' ) );
				add_action( 'admin_print_styles-' . $page, array( $this, 'admin_css' ) );

				break;
		}
	}

	/**
	 * Output the about screen.
	 */
	public function about_screen() {
		?>
		<div class="wrap about-wrap">
			<h1><?php printf( __( 'Welcome to PayPal Button Manager %s', 'paypal-wp-button-manager' ),  $this->version ); ?></h1>
	
			<div class="about-text woocommerce-about-text">
				<?php
				$message = '';

				printf( __( '%s PayPal WP Button Manager %s is more powerful, stable and secure than ever before. We hope you enjoy using it.', 'paypal-wp-button-manager' ), $message,  $this->version );

				$tweets  = array(
                    'PayPal Button Manager for WordPress',
                    'Easily add PayPal buttons to your WordPress site!',
				);
				shuffle( $tweets );
				?>
			</div>
			
			<div class="angelleye-badge"><img src="<?php echo BMW_PLUGIN_URL ?>/admin/images/angelleye.png" id="angelleye_logo" alt="angelleye" /></div>

			<div class="woocommerce-actions woocommerce-actions-own">
				<a href="<?php echo admin_url('/options-general.php?page=paypal-wp-button-manager-option'); ?>" id="paypal-wp-button-manage-settings" class="button button-primary"><?php _e( 'Settings', 'paypal-wp-button-manager' ); ?></a>
				<a href="<?php echo esc_url( apply_filters( 'woocommerce_docs_url', 'https://www.angelleye.com/category/docs/paypal-wp-button-manager/', 'paypal-wp-button-manager' ) ); ?>" id="paypal-wp-button-manage-document" class="docs button button-primary"><?php _e( 'Docs', 'paypal-wp-button-manager' ); ?></a>
				<a id="paypal-wp-button-manage-twitter" href="https://twitter.com/share" class="twitter-share-button" data-url="https://goo.gl/2nXgSB" data-text="<?php echo esc_attr( $tweets[0] ); ?>" data-via="angelleye" data-size="large">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				
				<!-- Place this tag where you want the share button to render. -->
				<div class="g-plus" data-action="share" data-annotation="bubble" data-height="24" data-href="https://goo.gl/2nXgSB"></div>
				
				<!-- Place this tag after the last share tag. -->
				<script type="text/javascript">
				(function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/platform.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				})();
				</script>
				
				<div id="fb-root"></div>
				<script>(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
			
				<div class="fb-share-button" data-href="https://goo.gl/2nXgSB" data-layout="button_count"></div>

			</div>
			
			
			<?php
			$setting_tabs_wc = apply_filters('paypal_wp_button_manager_setting_tab', array("pbm_about" => "Overview", "pbm_credits" => "Credits", "pbm_translators" => "Translators"));
			$current_tab_wc = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';
			$aboutpage = isset($_GET['page'])
			?>
			 <h2 id="paypal-wp-button-manage-tab-wrapper" class="nav-tab-wrapper">
            <?php
            foreach ($setting_tabs_wc as $name => $label)
            echo '<a  href="' . admin_url('admin.php?page=paypal-wp-button-manager-about&tab=' . $name) . '" class="nav-tab ' . ( $current_tab_wc == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
            ?>
        </h2>
			
			 <?php
			 foreach ($setting_tabs_wc as $setting_tabkey_wc => $setting_tabvalue) {
			 	switch ($setting_tabkey_wc) {
			 		case $current_tab_wc:
                                                $this->paypal_wp_button_manager_ignore_update_notice();
			 			do_action('paypal_wp_button_manager_' . $current_tab_wc);
			 			break;

			 	}
        }?>
			
				
			<hr />
			
			<div class="return-to-dashboard">
				<a href="<?php echo admin_url('/options-general.php?page=paypal-wp-button-manager-option'); ?>"><?php _e( 'Go to Paypal Button Manager Settings', 'paypal-wp-button-manager' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * admin_css function.
	 */
	public function admin_css() {
		wp_enqueue_style($this->plugin_name . 'welcome-page', plugin_dir_url(__FILE__) . 'css/activation.css', array(), $this->version, 'all');
	}

	public function paypal_wp_button_manager_pbm_about() { ?>
		<div class="changelog">
				<h4><?php _e( 'Initial Release', 'paypal-wp-button-manager' ); ?></h4>
				<p><?php _e( 'We are excited to have recently released this Button Manager for WordPress!', 'paypal-wp-button-manager' ); ?></p>
			
				<div class="changelog about-integrations">
					<div class="wc-feature feature-section col three-col">
						<div>
							<h4><?php _e( 'Replicates PayPal.com Button Manager', 'paypal-wp-button-manager' ); ?></h4>
							<p><?php _e( 'Get the same functionality you get from your PayPal account directly inside the WordPress admin panel.', 'paypal-wp-button-manager' ); ?></p>
						</div>
						<div>
							<h4><?php _e( 'Create Secure Buttons', 'paypal-wp-button-manager' ); ?></h4>
							<p><?php _e( 'Generate PayPal buttons that are secure from adjustments to pricing and/or privacy concerns. ', 'paypal-wp-button-manager' ); ?></p>
						</div>
						<div class="last-feature">
							<h4><?php _e( 'Multiple PayPal Accounts', 'paypal-wp-button-manager' ); ?></h4>
						<p><?php printf( __( 'Create and manage buttons to use on your site from an unlimited number of PayPal accounts.', 'paypal-wp-button-manager' ), '<a href="http://docs.woothemes.com/document/webhooks/">', '</a>' ); ?></p>
						</div>
					</div>
				</div>
			</div>
			<div class="changelog">
				<div class="feature-section col three-col">
					<div>
						<h4><?php _e( 'Buy Now Button', 'paypal-wp-button-manager' ); ?></h4>
						<p><?php printf( __( 'Create buttons designed for purchasing products or services one-at-a-time.', 'paypal-wp-button-manager' ), '</a>' ); ?></p>
					</div>
					<div>
						<h4><?php _e( 'Color Customization', 'paypal-wp-button-manager' ); ?></h4>
						<p><?php printf( __( 'If you\'re looking to customise the look and feel of the frontend in 2.3, take a look at the free %sWooCommerce Colors plugin%s. This lets you change the colors with a live preview.', 'paypal-wp-button-manager' ), '<a href="https://wordpress.org/plugins/woocommerce-colors/">', '</a>' ); ?></p>
					</div>
					<div class="last-feature">
						<h4><?php _e( 'Improved Reports', 'paypal-wp-button-manager' ); ?></h4>
						<p><?php _e( 'Sales reports can now show net and gross amounts, we\'ve added a print stylesheet, and added extra data on refunds to reports.', 'paypal-wp-button-manager' ); ?></p>
					</div>
				</div>
				<div class="feature-section col three-col">
					<div>
						<h4><?php _e( 'Improved Simplify Gateway', 'paypal-wp-button-manager' ); ?></h4>
						<p><?php printf( __( 'The built in Simplify Commerce Gateway (available in the US) now supports %sHosted Payments%s - a PCI Compliant hosted payment platform.', 'paypal-wp-button-manager' ), '<a href="https://www.simplify.com/commerce/docs/tools/hosted-payments">', '</a>' ); ?></p>
					</div>
					<div>
						<h4><?php _e( 'Email Template Improvements', 'paypal-wp-button-manager' ); ?></h4>
						<p><?php printf( __( 'To make email customization simpler, we\'ve included a CSS Inliner in this release, some new template files for styling emails, and some additional hooks for developers. Read more on our %sdeveloper blog%s.', 'paypal-wp-button-manager' ), '<a href="http://develop.woothemes.com/woocommerce/2014/10/2-3-emails/">', '</a>' ); ?></p>
					</div>
					<div class="last-feature">
						<h4><?php _e( 'Simplified Coupon System', 'paypal-wp-button-manager' ); ?></h4>
						<p><?php printf( __( 'We have simplified the coupon system to ensure discounts are never applied to taxes, and we\'ve improved support for discounting products inclusive of tax. Read more on our %sdevelop blog%s.', 'paypal-wp-button-manager' ), '<a href="http://develop.woothemes.com/woocommerce/2014/12/upcoming-coupon-changes-in-woocommerce-2-3/">', '</a>' ); ?></p>
					</div>
				</div>
			</div>
		
	<?php }
	public function paypal_wp_button_manager_pbm_credits() { ?>
        <p class="about-description"><?php _e( 'PayPal WP Button Manager is developed and maintained by a core group of in-house developers. We very much appreciate contributions, though, and love to see pull requests!', 'paypal-wp-button-manager' ); ?></p>
        <p class="about-description"><?php _e( 'Want to see your name? <a href="https://github.com/angelleye/paypal-wp-button-manager/blob/master/CONTRIBUTING.md">Contribute to PayPal WP Button Manager', 'paypal-wp-button-manager' ); ?></a>.</p>
        <ul class="wp-people-group"><li class="wp-person"><a href="https://github.com/angelleye" title="View angelleye"><img src="https://avatars1.githubusercontent.com/u/629241?v=3" width="64" height="64" class="gravatar" alt="angelleye"></a><a class="web" href="https://github.com/angelleye">angelleye</a></li></ul>
        <ul class="wp-people-group"><li class="wp-person"><a href="https://github.com/nishitlangaliya" title="View nishitlangaliya"><img src="https://avatars0.githubusercontent.com/u/11435772?v=3" width="64" height="64" class="gravatar" alt="nishitlangaliya"></a><a class="web" href="https://github.com/nishitlangaliya">nishitlangaliya</a></li></ul>
		<ul class="wp-people-group"><li class="wp-person"><a href="https://github.com/kcwebmedia" title="View kcwebmedia"><img src="https://avatars0.githubusercontent.com/u/7711293?v=3&" width="64" height="64" class="gravatar" alt="kcwebmedia"></a><a class="web" href="https://github.com/kcwebmedia">kcwebmedia</a></li></ul>
        <ul class="wp-people-group"><li class="wp-person"><a href="https://github.com/kcppdevelopers" title="View kcppdevelopers"><img src="https://avatars3.githubusercontent.com/u/13145461?v=3&s=60" width="64" height="64" class="gravatar" alt="kcppdevelopers"></a><a class="web" href="https://github.com/kcppdevelopers">kcppdevelopers</a></li></ul>
    <?php
	}
	public function paypal_wp_button_manager_pbm_translators() { ?>
        <h4><?php _e( 'Seeking Translators', 'paypal-wp-button-manager' ); ?></h4>
        <p><?php _e( 'We appreciate any help we can get translating this plugin into other languages.', 'paypal-wp-button-manager' ); ?></p>
        <p><?php _e( 'If you can help, please <a target="_blank" href="https://www.angelleye.com/support">submit your translation here.</a>', 'paypal-wp-button-manager' ); ?></p>
    <?php
	}
	
	
	public function paypal_wp_button_manager_remove_wcpage_link() {
		remove_submenu_page( 'index.php', 'paypal-wp-button-manager-about' );
	}
        
        public function pwbm_display_plugin_update_notice() {
            global $current_user;
            $user_id = $current_user->ID;
            if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
                return false;
            }	
            $ignore_pwbm_update_notice = get_user_meta( $user_id, '_ignore_pwbm_update_notice', true ); 
            if( empty($ignore_pwbm_update_notice) ) {
                return true;
            } else {
                return false;
            }
        }
        
        public function paypal_wp_button_manager_ignore_update_notice() {
            global $current_user;
            $user_id = $current_user->ID;
            if ( (isset($_GET['ignore_pwbm_update_notice']) && '0' == $_GET['ignore_pwbm_update_notice']) || ( isset($_GET['tab']) && 'pbm_about' == $_GET['tab'] ) ) {
                update_user_meta($user_id, '_ignore_pwbm_update_notice', true);
            }
        }
        
        public function paypal_wp_button_manager_upgrader_process_complete($upgrader_object, $context_array) {
            global $wpdb;
            if( (isset($context_array['type']) && $context_array['type'] == 'plugin') && (isset($context_array['action']) && $context_array['action'] == 'update')) {
                if( isset($context_array['plugins']) && !empty($context_array['plugins']) ) {
                    if (in_array('paypal-wp-button-manager/paypal-wp-button-manager.php', $context_array['plugins'])) {
                        $delete = $wpdb->delete( $wpdb->prefix . 'usermeta', array( 'meta_key' => '_ignore_pwbm_update_notice' ), array( '%s' ) );
                    }
                }
            }
        }

} 
