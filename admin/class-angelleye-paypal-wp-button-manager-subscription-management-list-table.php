<?php
defined( 'ABSPATH' ) || exit;
/*
 * Class responsible for paypal subscription management with list table.
 */
if (!class_exists('Angelleye_Paypal_Wp_Button_Manager_List_Table')) {
    require_once(ANGELLEYE_PAYPAL_WP_BUTTON_MANAGER_PLUGIN_PATH . '/admin/helpers/classes/wp-list-table.php');
}
class Angelleye_Paypal_Wp_Button_Manager_Subscription_Management_List_Table extends Angelleye_Paypal_Wp_Button_Manager_List_Table {

    public function __construct() {

        parent::__construct( [
            'singular' => __( 'Subscription', 'angelleye-paypal-wp-button-manager' ),
            'plural' => __( 'Subscriptions', 'angelleye-paypal-wp-button-manager' ),
            'ajax' => false

        ] );

    }

    /**
     * Returns the subscriptions filtered by query
     *
     * @param int $per_page Records to show on every page
     * @param int $page_number Page number from the pagination
     * 
     * @return array
     * */
    public static function get_subscriptions( $per_page = 20, $page_number = 1 ) {

        global $wpdb;

        $sql = "SELECT ID, user_id, email_address, first_name, last_name, next_payment_due_date, status FROM {$wpdb->prefix}angelleye_paypal_button_manager_subscriptions";

        $search = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';

        if( !empty( $search ) ){
            $search = esc_sql( $search );
            $sql .= ' WHERE user_id LIKE "%' . $search . '%" OR email_address LIKE "%' . $search . '%" OR first_name LIKE "%' . $search . '%" OR last_name LIKE "%' . $search . '%" OR status LIKE "%' . $search . '%"';
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

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}angelleye_paypal_button_manager_subscriptions";

        return $wpdb->get_var( $sql );
    }

    /**
     * Returns the no subscriptions string
     * 
     * @return string
     * */
    public function no_items() {
        _e( 'No subscriptions avaliable.', 'angelleye-paypal-wp-button-manager' );
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
                case 'email_address':
                    return '<a href="mailto:' . $item[$column_name] . '">' . $item[$column_name] . '</a>';
                case 'next_payment_due_date':
                    return date('F j, Y', strtotime( $item[$column_name] ) );
                case 'status':
                    return ucfirst( $item[$column_name] );
                case 'ID':
                case 'first_name':
                case 'last_name':
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
        return $item['ID'];
    }

    /**
     * Returns the column name
     * 
     * @param array $item array of single subscription entry
     * 
     * @return string
     * */
    function column_name( $item ) {
        return $item['email_address'];
    }

    /**
     * Returns the columns
     * 
     * @return array
     * */
    function get_columns() {
        $columns = array(
            'email_address' => __('Email', 'angelleye-paypal-wp-button-manager'),
            'first_name' => __('First Name', 'angelleye-paypal-wp-button-manager'),
            'last_name' => __('Last Name', 'angelleye-paypal-wp-button-manager'),
            'next_payment_due_date' => __('Next Payment Due On', 'angelleye-paypal-wp-button-manager'),
            'status' => __('Status', 'angelleye-paypal-wp-button-manager')
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
            'email_address'  => array('email_address', true),
            'first_name' => array('first_name', true),
            'last_name'   => array('last_name', true),
            'next_payment_due_date' => array('next_payment_due_date', true )
        );
        return $sortable_columns;
    }

    /**
     * Prepares the items
     * */
    function prepare_items(){
        $this->_column_headers = $this->get_column_info();

        $per_page     = $this->get_items_per_page( 'paypal_subscriptions_per_page', 20 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page
        ] );

        $this->items = self::get_subscriptions( $per_page, $current_page );
    }
}