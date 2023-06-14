<?php
defined( 'ABSPATH' ) || exit;
/*
 * Auto load all class files required for plugin.
 */
class WBP_Autoloader {

	/**
	 * loads all class files
	 * */
	public function init() {

		$arr_files = glob(dirname(__FILE__) . "/class-*.php");
		$arr_files = array_merge( $arr_files, glob(dirname(__FILE__) . "/helpers/*.php" ) );

		foreach ($arr_files as $key => $value) {
			
			if(file_exists($value) && is_readable($value)) {

				require_once $value;
			} else {
				return false;
			}
		}
		return true;
	}

	/**
	 * Creates the object of necessary classes to run on load
	 * */
	public function load(){
		new PayPal_WP_Button_Manager_Activator();
		new PayPal_WP_Button_Manager_Company();
		new PayPal_WP_Button_Manager_Post();
		new PayPal_WP_Button_Manager_Shortcode();
		new PayPal_WP_Button_Manager_Order();
		new PayPal_WP_Button_Manager_Block();
	}
}