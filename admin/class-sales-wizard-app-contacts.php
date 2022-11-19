<?php
if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class sales_wizard_app_contacts extends WP_List_Table {

    /**
     * This is variable which is used for the store all the data.
     *
     * @var array $example_data variable for store data.
     */
    public $table_data;

    /**
     * This is variable which is used for the total count.
     *
     * @var array $table_row_total_count variable for total count.
     */
    public $table_row_total_count;

    /**
     * This construct colomns in contacts report table.
     *
     * @name get_columns.
     * @since      1.0.0
     */
    public function get_columns() {

        $columns = array(
            'cb' => '<input type="checkbox" />',
            'form_name' => __('Form name', 'sales-wizard-app'),
            'sales_wizard_data' => __('Contacts', 'sales-wizard-app'),
            'log_status' => __('status', 'sales-wizard-app'),
            'date' => __('date', 'sales-wizard-app'),
        );
        return apply_filters('sales_wizard_app_column_table', $columns);
    }

    /**
     * This show susbcriptions table list.
     *
     * @name column_default.
     * @since      1.0.0
     * @param array  $item  array of the items.
     * @param string $column_name name of the colmn.
     */
    public function column_default($item, $column_name) {

        switch ($column_name) {
            case 'form_name':
                return $item[$column_name];
            case 'sales_wizard_data':
				$contact_string = "";
				$contacts = json_decode($item['sales_wizard_data'],JSON_OBJECT_AS_ARRAY);
				unset($contacts['form_id']);
				unset($contacts['form_name']);
				foreach($contacts as $contact_key=>$contact_value){
					$contact_string .= '<p><strong>'.str_replace('_',' ',ucwords(strtolower($contact_key))).':</strong> '.$contact_value.'</p>';
				}
                return $contact_string ;
			case 'date':
				return $item[$column_name];
            case 'log_status':
			return $this->column_status($item);
            default:
                return apply_filters('sales_wizard_app_add_case_column', false, $column_name, $item);
        }
    }

    public function column_status($item) {
        $actions = [];
        if ($item['log_status'] == 0) {
            $log_stauts = sprintf('<span style="color: #b32d2e">%s</span>', __('Not sent', 'sales-wizard-app'));
            $actions['send'] = sprintf('<a href="%s" title="%s">%s</a>', wp_nonce_url(admin_url('admin.php?page=sales-wizard-app&sales_wizard_status_admin=send&contact_id=' . $item['id']), $item['id'] . $item['log_status']), $item['id'], __('Send again', 'webd'));
        } elseif ($item['log_status'] == 1) {
			   $log_stauts =  sprintf('<span style="color: #2271b1">%s</span>', __('Sent', 'sales-wizard-app'));
        }
		return $log_stauts . $this->row_actions($actions);
    }

    /**
     * Perform admin bulk action setting for contacts table.
     *
     * @name process_bulk_action.
     */
    public function process_bulk_action() {
		global $wpdb; 	
		$table_name = $wpdb->prefix . 'sales_wizard_log';
        if ('bulk-delete' === $this->current_action()) {
            if (isset($_POST['contacts_list_table'])) {
                $contacts_list_table = sanitize_text_field(wp_unslash($_POST['contacts_list_table']));
                if (wp_verify_nonce($contacts_list_table, 'contacts_list_table')) {
                    if (isset($_POST['contact_ids']) && !empty($_POST['contact_ids'])) {
                        $all_id = $_POST['contact_ids'];
						if(is_array($all_id)){
							foreach ($all_id as $key => $id) {
								$contact_id = sanitize_text_field( $id );
								$wpdb->delete($table_name, ['id' => $contact_id]);
							}
						}
                    }
                }
            }
            ?>
            <div class="notice notice-success is-dismissible"> 
                <p><strong><?php esc_html_e('contacts Deleted Successfully', 'sales-wizard-app'); ?></strong></p>
            </div>
            <?php
        }
		if ('bulk-send' === $this->current_action()) {
            if (isset($_POST['contacts_list_table'])) {
                $contacts_list_table = sanitize_text_field(wp_unslash($_POST['contacts_list_table']));
                if (wp_verify_nonce($contacts_list_table, 'contacts_list_table')) {
                    if (isset($_POST['contact_ids']) && !empty($_POST['contact_ids'])) {
                        $all_id = $_POST['contact_ids'];
						if(is_array($all_id)){
							foreach ($all_id as $key => $id) {
								$contact_id = $id;
								$results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE id = %d", $contact_id ),ARRAY_A );
								$response = form_data_send_to_crm($results);
								if($response){
									$wpdb->query( $wpdb->prepare( "UPDATE {$table_name} SET log_status = '%s' WHERE id = '%d'",1,$id));
								}
							}
						}
						
                    }
                }
            }
            ?>
            <div class="notice notice-success is-dismissible"> 
                <p><strong><?php esc_html_e('contacts sents Successfully', 'sales-wizard-app'); ?></strong></p>
            </div>
            <?php
        }
        do_action('sales_wizard_app_process_bulk_reset_option', $this->current_action(), $_POST);
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @name process_bulk_action.
     * @since      1.0.0
     * @return array
     */
    public function get_bulk_actions() {
        $actions = array(
            'bulk-delete' => __('Delete', 'sales-wizard-app'),
            'bulk-send' => __('send to crm', 'sales-wizard-app'),
        );
        return apply_filters('sales_wizard_app_bulk_option', $actions);
    }

    /**
     * Returns an associative array containing the bulk action for sorting.
     *
     * @name get_sortable_columns.
     * @since      1.0.0
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'date' => array('date', false),
            'log_status' => array('log_status', false),
        );
        return $sortable_columns;
    }

    /**
     * Prepare items for sorting.
     *
     * @name prepare_items.
     * @since      1.0.0
     */
    public function prepare_items() {
        $per_page = 3;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        $current_page = $this->get_pagenum();
        $this->table_data = $this->get_sales_wizard_contacts_list();
        $data = $this->table_data;
        usort($data, array($this, 'sales_wizard_contacts_usort_reorder'));
        $data = array_slice($data, ( ( $current_page - 1 ) * $per_page), $per_page);
        $total_items = $this->table_row_total_count;
        $this->items = $data;
        $this->set_pagination_args(
                array(
                    'total_items' => $total_items,
                    'per_page' => $per_page,
                    'total_pages' => ceil($total_items / $per_page),
                )
        );
    }

    /**
     * Return sorted associative array.
     *
     * @name sales_wizard_contacts_usort_reorder.
     * @since      1.0.0
     * @return array
     * @param array $cloumna column of the contacts report.
     * @param array $cloumnb column of the contacts report.
     */
    public function sales_wizard_contacts_usort_reorder($cloumna, $cloumnb) {

        $orderby = (!empty($_REQUEST['orderby']) ) ? sanitize_text_field(wp_unslash($_REQUEST['orderby'])) : 'date';
        $order = (!empty($_REQUEST['order']) ) ? sanitize_text_field(wp_unslash($_REQUEST['order'])) : 'desc';

        if (is_numeric($cloumna[$orderby]) && is_numeric($cloumnb[$orderby])) {
            if ($cloumna[$orderby] == $cloumnb[$orderby]) {
                return 0;
            } elseif ($cloumna[$orderby] < $cloumnb[$orderby]) {
                $result = -1;
                return ( 'asc' === $order ) ? $result : -$result;
            } elseif ($cloumna[$orderby] > $cloumnb[$orderby]) {
                $result = 1;
                return ( 'asc' === $order ) ? $result : -$result;
            }
        } else {
            $result = strcmp($cloumna[$orderby], $cloumnb[$orderby]);
            return ( 'asc' === $order ) ? $result : -$result;
        }
    }

    /**
     * THis function is used for the add the checkbox.
     *
     * @name column_cb.
     * @since      1.0.0
     * @return array
     * @param array $item array of the items.
     */
    public function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="contact_ids[]" value="%s" />',
                $item['id']
        );
    }

    /**
     * This function used to get all contacts reports list.
     *
     * @name get_sales_wizard_contacts_list.
     * @since      1.0.0
     * @return array
     */
    public function get_sales_wizard_contacts_list() {
		global $wpdb;
		$table_name = $wpdb->prefix.'sales_wizard_log';
		 $search_text = "%%";
		if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
			$search_request_text = sanitize_text_field($_REQUEST['s']);
			$search_text = "%".$search_request_text."%";
        }
        $results = $wpdb->get_results( 
                $wpdb->prepare( "SELECT * FROM {$table_name} WHERE sales_wizard_data LIKE %s",$search_text),
				ARRAY_A
        );
		$total_count = count( $results);	
        $this->table_row_total_count = $total_count;
        return $results;
    }

    /**
     * Create the extra table option.
     *
     * @name extra_tablenav.
     * @since      1.0.0
     * @param string $which which.
     */
    public function extra_tablenav($which) {
        // Add list option. 
		?>
		<div class="alignleft actions">
			<button class="button action" type="submit" name="export_contacts"><?php _e('Export','sales-wizard-app');?></button>
		</div>
		<?php
        do_action('sales_wizard_app_contacts_extra_tablenav_html', $which);
    }

}
