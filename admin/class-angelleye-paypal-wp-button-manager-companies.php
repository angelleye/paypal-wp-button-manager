<?php
defined( 'ABSPATH' ) || exit;
/*
 * Class responsible for paypal company listing management with list table.
 */
if (!class_exists('Angelleye_Paypal_Wp_Button_Manager_List_Table')) {
    require_once(ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH . '/admin/helpers/classes/wp-list-table.php');
}
class Angelleye_Paypal_Wp_Button_Manager_Companies extends Angelleye_Paypal_Wp_Button_Manager_List_Table {

    public function __construct() {

        parent::__construct( [
            'singular' => __( 'PayPal Accounts', 'angelleye-paypal-wp-button-manager' ),
            'plural' => __( 'PayPal Account', 'angelleye-paypal-wp-button-manager' ),
            'ajax' => false

        ] );

    }

    /**
     * Returns the companies filtered by query
     *
     * @param int $per_page Records to show on every page
     * @param int $page_number Page number from the pagination
     * 
     * @return array
     * */
    public static function get_companies( $per_page = 20, $page_number = 1 ) {

        global $wpdb;

        $sql = "SELECT ID, company_name name, paypal_person_name, paypal_mode, paypal_merchant_id FROM {$wpdb->prefix}angelleye_paypal_button_manager_companies";

        $search = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';

        if( !empty( $search ) ){
            $search = esc_sql( $search );
            $sql .= ' WHERE company_name LIKE "%' . $search . '%" OR paypal_person_name LIKE "%' . $search . '%" OR paypal_merchant_id LIKE "%' . $search . '%"';
        }

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }

    /**
     * Returns the total record count
     * 
     * @return int
     * */
    public static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}angelleye_paypal_button_manager_companies";

        return $wpdb->get_var( $sql );
    }

    /**
     * Returns the no company string
     * 
     * @return string
     * */
    public function no_items() {
        _e( 'No accounts avaliable.', 'angelleye-paypal-wp-button-manager' );
    }

    /**
     * Sets the default column
     * 
     * @param array $item array of single company entry
     * @param string $column_name name of the column
     * 
     * @return string
     * */
    public function column_default($item, $column_name){
          switch ($column_name) {
                case 'ID':
                case 'name':
                case 'paypal_person_name':
                case 'paypal_mode':
                case 'paypal_merchant_id':
                default:
                    return $item[$column_name];
          }
    }

    /**
     * Returns the required tag checkbox column
     * 
     * @param array $item array of single company entry
     * 
     * @return string
     * */
    public function column_cb($item){
        return '';
    }

    /**
     * Returns the column name
     * 
     * @param array $item array of single company entry
     * 
     * @return string
     * */
    function column_name( $item ) {
        $actions = array(
            'delete' => sprintf('<a href="%s?page=%s&action=%s&company_id=%s">%s</a>', admin_url('admin.php'),Angelleye_Paypal_Wp_Button_Manager_Company::$paypal_button_company_slug, 'delete', $item['ID'], __('Delete', 'angelleye-paypal-wp-button-manager') )
        );
        $view_page = '<a href="' . admin_url('admin.php?page=' . Angelleye_Paypal_Wp_Button_Manager_Company::$paypal_button_company_slug . '&company_id=' . $item['ID'] ) . '">'. $item['name'] . '</a>';

        return sprintf('%1$s %2$s', $view_page, $this->row_actions($actions));
    }

    /**
     * Returns the columns
     * 
     * @return array
     * */
    function get_columns() {
        $columns = array(
            'name'          => __('Account Name', 'angelleye-paypal-wp-button-manager'),
            'paypal_person_name'         => __('Contact Name', 'angelleye-paypal-wp-button-manager'),
            'paypal_mode'   => __('PayPal Mode', 'angelleye-paypal-wp-button-manager'),
            'paypal_merchant_id'        => __('PayPal Merchant ID', 'angelleye-paypal-wp-button-manager')
        );
        return $columns;
    }

    /**
     * Returns the sortable columns
     * 
     * @return array
     * */
    protected function get_sortable_columns(){
        $sortable_columns = array(
            'name'  => array('name', true),
            'paypal_person_name' => array('paypal_person_name', true),
            'paypal_merchant_id'   => array('paypal_merchant_id', true)
        );
        return $sortable_columns;
    }

    /**
     * Prepares the items
     * */
    function prepare_items(){
        $this->_column_headers = $this->get_column_info();

        $per_page     = $this->get_items_per_page( 'paypal_companies_per_page', 20 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page
        ] );

        $this->items = self::get_companies( $per_page, $current_page );
    }
}