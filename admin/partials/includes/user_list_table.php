<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class User_List_Table extends WP_List_Table {

  public function get_columns() {
    return $columns = array(
      'name' => __( 'Name' ),
      'email' => __( 'Email' ),
      'created' => __( 'Created' ),
    );
  }

  public function get_sortable_columns() {
    return $sortable = array(
      'name' => array( 'name', true ),
      'email' => array( 'email', true ),
      'created' => array( 'created', true ),
    );
  }

  protected function column_default( $item, $column_name ) {
    if ($column_name === 'created') {
      return date("F jS, Y", strtotime($item[ $column_name ]));
    }
		return $item[ $column_name ];
	}

  public static function get_responses( $per_page = 5, $page_number = 1 ) {

    global $wpdb;

    $sql = "SELECT * FROM {$wpdb->prefix}wordpress_plugin";

    if ( ! empty( $_REQUEST['orderby'] ) ) {
      $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
      $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
    }

    $sql .= " LIMIT $per_page";

    $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


    $result = $wpdb->get_results( $sql, ARRAY_A );

    return $result;
  }

  public function prepare_items() {

    $columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

    $this->_column_headers = array( $columns, $hidden, $sortable );

    $per_page     = $this->get_items_per_page( 'responses_per_page', 5 );
    $current_page = $this->get_pagenum();
    $total_items  = self::record_count();

    $this->set_pagination_args( [
      'total_items' => $total_items,
      'per_page'    => $per_page,
      'total_pages' => ceil( $total_items / $per_page )
    ] );

    $this->items = self::get_responses( $per_page, $current_page );
  }

  public static function record_count() {
    global $wpdb;

    $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}wordpress_plugin";

    return $wpdb->get_var( $sql );
  }

  public function no_items() {
    _e( 'No records avaliable.', 'wordpress-plugin' );
  }

}

?>