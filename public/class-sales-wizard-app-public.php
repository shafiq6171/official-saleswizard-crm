<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://webmonch.com
 * @since      1.0.0
 *
 * @package    Sales_Wizard_App
 * @subpackage Sales_Wizard_App/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sales_Wizard_App
 * @subpackage Sales_Wizard_App/public
 * @author     Shafiq <shafiq6171@gmail.com>
 */
class Sales_Wizard_App_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	public function swa_sent_contact_form7_data_to_crm( $contact_form){
		$title = $contact_form->title;
		$form_id = $contact_form->id();
		$submission = WPCF7_Submission::get_instance();  
		if ( $submission ) {
			$posted_data = $submission->get_posted_data();
		}
		$table_data = array(
			'form_id'=>$form_id,
			'form_name'=>$title,
		);
		$fields_mapping = get_option('fields_mapping');
		if(!empty($fields_mapping['column_ids'])){
			foreach($fields_mapping['column_ids'] as $field_key=>$field_value){
				if(isset($posted_data[$field_value])){
					$table_data[$field_value] = $posted_data[$field_value];
				}
			}
		}
		$this->form_data_insert($table_data);
		
	}
	public function swa_sent_wpform_data_to_crm($fields, $entry, $form_data, $entry_id){
		$table_data = array();
		if( $form_data[ 'id' ]){
			$table_data['form_id'] = absint($form_data[ 'id' ]);
			$table_data['form_name'] = get_the_title(absint($form_data[ 'id' ]));
		}
		$fields_mapping = get_option('fields_mapping');
		if(!empty($fields_mapping['column_ids'])){
			$wpform_ids = $fields_mapping['wpform_ids'];
			foreach($fields_mapping['column_ids'] as $field_key=>$field_value){
				if($field_value && isset($wpform_ids[$field_key])){
					$field_id_number = $wpform_ids[$field_key];
					$table_data[$field_value] = $entry['fields'][$field_id_number];
				}
			}
		}
		$this->form_data_insert($table_data);
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sales-wizard-app-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sales-wizard-app-public.js', array( 'jquery' ), $this->version, false );

	}
	public function form_data_insert($table_data = array()){
		global $wpdb;
		$table_data['lead_id'] = random_text_generator(7);
		$table_name = $wpdb->prefix."sales_wizard_log";
		$sql = $wpdb->prepare( "INSERT INTO {$table_name} (form_id,form_name,sales_wizard_data,lead_id) VALUES (%d,%s,%s,%s)", 
			$table_data['form_id'],
			$table_data['form_name'],
			json_encode($table_data),
			$table_data['lead_id']
		);
		$wpdb->query($sql);
		$id = $wpdb->insert_id;
		$response = form_data_send_to_crm($table_data);
		if($response){
			if(isset($response['code']) && $response['code'] ==200){
				$wpdb->query( $wpdb->prepare( "UPDATE {$table_name} SET log_status = '%s' WHERE id = '%d'",1,$id));
			}
		}
	}
	

}
