<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://webmonch.com
 * @since      1.0.0
 *
 * @package    Sales_Wizard_App
 * @subpackage Sales_Wizard_App/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sales_Wizard_App
 * @subpackage Sales_Wizard_App/admin
 * @author     Shafiq <shafiq6171@gmail.com>
 */
class Sales_Wizard_App_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sales_Wizard_App_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sales_Wizard_App_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sales-wizard-app-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sales_Wizard_App_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sales_Wizard_App_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sales-wizard-app-admin.js', array( 'jquery' ), $this->version, false );

	}
	public function sales_wizard_app_admin_menu(){
		$capability = 'manage_options';
        $parent_slug = 'sales-wizard-app';
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['sales-wizard-app'] ) ) {
			add_menu_page( 	__( 'SalesWizard CRM', 'sales-wizard-app' ), __('SalesWizard CRM plugin', 'sales-wizard-app' ), $capability,  $parent_slug, array( $this, 'wp_swa_options_menu_html' ), SALES_WIZARD_APP_DIR_URL . 'admin/images/sales-wizard-app-logo.png', 31 );
		}
	}
	public function wp_swa_options_menu_html() {
		include_once SALES_WIZARD_APP_DIR_PATH . 'admin/partials/sales-wizard-app-admin-menu.php';
	}
	/**
	 * sales wizard app settings tab save.
	 *
	 * @name swa_admin_save_tab_settings.
	 * @since 1.0.0
	 */
	public function swa_admin_save_tab_settings() {
		global $sale_wizard_app_obj;
		global $sale_wizard_app_notices;
		if ( isset( $_POST['swa_save_general_settings'] ) && isset( $_POST['swa-general-nonce-field'] ) ) {
			$wps_sfw_geberal_nonce = sanitize_text_field( wp_unslash( $_POST['swa-general-nonce-field'] ) );
			if ( wp_verify_nonce( $wps_sfw_geberal_nonce, 'swa-general-nonce' ) ) {
				$wps_sfw_gen_flag = false;
				// General settings.
				$sfw_genaral_settings = apply_filters( 'swa_general_settings_array', array() );
				$sfw_button_index = array_search( 'submit', array_column( $sfw_genaral_settings, 'type' ) );
				if ( isset( $sfw_button_index ) && ( null == $sfw_button_index || '' == $sfw_button_index ) ) {
					$sfw_button_index = array_search( 'button', array_column( $sfw_genaral_settings, 'type' ) );
				}
				if ( isset( $sfw_button_index ) && '' !== $sfw_button_index ) {

					unset( $sfw_genaral_settings[ $sfw_button_index ] );
					if ( is_array( $sfw_genaral_settings ) && ! empty( $sfw_genaral_settings ) ) {
						foreach ( $sfw_genaral_settings as $sfw_genaral_setting ) {
							if ( isset( $sfw_genaral_setting['id'] ) && '' !== $sfw_genaral_setting['id'] ) {

								if ( isset( $_POST[ $sfw_genaral_setting['id'] ] ) && ! empty( $_POST[ $sfw_genaral_setting['id'] ] ) ) {
									$posted_value = sanitize_text_field( wp_unslash( $_POST[ $sfw_genaral_setting['id'] ] ) );
									update_option( $sfw_genaral_setting['id'], $posted_value );
								} else {
									update_option( $sfw_genaral_setting['id'], '' );
								}
							} else {
								$wps_sfw_gen_flag = true;
							}
						}
					}
					if ( $wps_sfw_gen_flag ) {
						$wps_sfw_error_text = esc_html__( 'Id of some field is missing', 'sales-wizard-app' );
						$sale_wizard_app_obj->wps_sfw_plug_admin_notice( $wps_sfw_error_text, 'error' );
					} else {
						$sale_wizard_app_notices = true;
					}
				}
			}
		}
		if ( isset( $_POST['swa_save_fields_map'] ) && isset( $_POST['swa-field-map-nonce-field'] ) ) {
			$wps_sfw_geberal_nonce = sanitize_text_field($_POST['swa-field-map-nonce-field']);
			if ( wp_verify_nonce( $wps_sfw_geberal_nonce, 'swa-field-map-nonce' ) ) {
				if ( isset( $_POST['column_id']) && ! empty($_POST['column_id'])) {
					$column_id = map_deep($_POST['column_id'], 'sanitize_text_field');
					$wpform_id = map_deep($_POST['wpform_id'], 'sanitize_text_field');
					$field_map = array('column_ids'=>$column_id,'wpform_ids'=>$wpform_id);
					update_option('fields_mapping', $field_map );
				} else {
					update_option('fields_mapping', '' );
				}
			}
		}
	}
	/**
	 * generate settings fields array .
	 *
	 * @name swa_admin_general_settings_page.
	 * @since 1.0.0
	 */
	public function swa_admin_general_settings_page( $sfw_settings_general ) {

		$sfw_settings_general = array(
			array(
				'title' => __( 'Enable/Disable', 'sales-wizard-app' ),
				'type'  => 'checkbox',
				'description'  => __( 'Check this box to send data to CRM.', 'sales-wizard-app' ),
				'id'    => 'sales-wizard-app-enable',
				'class' => 'sfw-checkbox-class',
				'value' => 'on',
				'checked' => ( 'on' === esc_attr(get_option( 'sales-wizard-app-enable', '' )) ? 'on' : 'off' ),
			),
			array(
				'title' => __( 'API Url', 'sales-wizard-app' ),
				'type'  => 'text',
				'description'  => '',
				'id'    => 'sales-wizard-api-url',
				'value' => esc_url(get_option( 'sales-wizard-api-url', '' )),
				'class' => 'sfw-text-class regular-text',
				'placeholder' => __( 'api url', 'sales-wizard-app' ),
			),
			array(
				'title' => __( 'API Key', 'sales-wizard-app' ),
				'type'  => 'password',
				'description'  => '',
				'id'    => 'sales-wizard-api-key',
				'value' => esc_attr(get_option( 'sales-wizard-api-key', '' )),
				'class' => 'sfw-text-class regular-text',
				'placeholder' => '',
			),
			array(
				'title' => __( 'API Version', 'sales-wizard-app' ),
				'type'  => 'text',
				'description'  => '',
				'id'    => 'sales-wizard-api-version',
				'value' => ($version = esc_attr(get_option( 'sales-wizard-api-version', '' ))) ? $version: '1.0',
				'class' => 'sfw-text-class',
				'placeholder' => '',
			),
			array(
				'title' => __( 'Test mode', 'sales-wizard-app' ),
				'type'  => 'checkbox',
				'description'  => "",
				'id'    => 'sales-wizard-app-test-mode',
				'class' => 'sfw-checkbox-class',
				'value' => 'on',
				'checked' => ( 'on' === esc_attr(get_option( 'sales-wizard-app-test-mode', '' )) ? 'on' : 'off' ),
			),
			array(
				'type'  => 'button',
				'id'    => 'swa_save_general_settings',
				'button_text' => __( 'Save Settings', 'sales-wizard-app' ),
				'class' => 'sfw-button-class ',
			),
		);
		// Add general settings.
		return apply_filters( 'wps_sfw_add_general_settings_fields', $sfw_settings_general );

	}
	/*
	* send contact data to crm from admin dashboard which are not sent initialy
	*/
	public function sfw_admin_send_not_sent_contacts(){
		global $wpdb; 	
		$contacts = array();
		$table_name = $wpdb->prefix . 'sales_wizard_log';
		 if (isset($_GET['sales_wizard_status_admin']) && $_GET['sales_wizard_status_admin'] = 'send' && isset($_GET['contact_id']) && isset($_GET['_wpnonce']) && !empty($_GET['_wpnonce'])) {
           $redirect_url = admin_url() . "admin.php?page=sales-wizard-app";
		   $contact_id = sanitize_text_field(wp_unslash($_GET['contact_id']));
			$results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE id = %d", $contact_id ),ARRAY_A );
			if(!empty($results)){
				$contacts = json_decode($results['sales_wizard_data'],JSON_OBJECT_AS_ARRAY);
				$response = form_data_send_to_crm($contacts);
				if($response){
					if(isset($response['code']) && $response['code'] ==200){
						$wpdb->query( $wpdb->prepare( "UPDATE {$table_name} SET log_status = '%s' WHERE id = '%d'",1,$contact_id));
						$redirect_url = admin_url() . "admin.php?page=sales-wizard-app&contact_sent=true";
					}
					update_option('sales_wizard_return',$response);
				}
			}
			wp_safe_redirect($redirect_url);
			exit;
        } 
		/**
		* csv export request
		*/
		if (isset($_REQUEST['export_contacts'])) {
		     if (isset($_POST['contact_ids']) && !empty($_POST['contact_ids'])) {
				$all_id = map_deep($_POST['contact_ids'], 'sanitize_text_field');
				if(is_array($all_id)){
					foreach ($all_id as $key => $id) {
						$contact_id = sanitize_text_field($id);
						$results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE id = %d", $contact_id ),ARRAY_A );
						$contacts[] = json_decode($results['sales_wizard_data'],JSON_OBJECT_AS_ARRAY); 
					}
				}
			 }else{
				$results = $wpdb->get_results("SELECT * FROM {$table_name}",ARRAY_A );
				if(!empty($results)){
					foreach($results as $result_value){
						$contacts[] = json_decode($result_value['sales_wizard_data'],JSON_OBJECT_AS_ARRAY);
					}
				}
			 }
			$headers_column = array();
			$row_number = 0;
			if(!empty($contacts)){
				foreach ($contacts as $contact_value){
					$row_number++;
					if($row_number ==1){
						foreach($contact_value as $key=>$value){
							$headers_column[] = str_replace('_',' ',ucwords(strtolower($key))); 
						 }
						break;
					}
				}
			}
			export_contacts_csv($contacts,$headers_column);
			exit;
        }
	}
}