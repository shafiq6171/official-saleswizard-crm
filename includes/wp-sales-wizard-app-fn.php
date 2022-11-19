<?php
/**
 * Exit if accessed directly
 *
 * @since      1.0.0
 * @package    Sales_Wizard_App
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(!function_exists('random_text_generator')){
	/**
	 * This function is used to get random text.
	 *
	 */
	function random_text_generator($n = 7) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $n; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$randomString .= $characters[$index];
		}
		return strtolower($randomString);
	}
}
if(!function_exists('get_sales_wizard_client_ip')){
	/*
	 *
	 * This function is used to get client ip.
	 */
	function get_sales_wizard_client_ip() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			//check ip from share internet
			$ipaddress = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
		} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			//to check ip is pass from proxy
			$ipaddress = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);
		} else {
			$ipaddress = sanitize_text_field($_SERVER['REMOTE_ADDR']);
		}
		if(filter_var($ipaddress, FILTER_VALIDATE_IP)){
		  return $ipaddress;
		}
		return  'Not Valid IP';
	}
}
if(!function_exists('form_data_send_to_crm')){
	/*
	 *  contact form 7 or wpform data by array as funtion parameters 
	 * call remote api.
	 * send contact form 7 or wpform field to sales wizard app crm.
	 */
	function form_data_send_to_crm($user_column_data = array()){
		$lead_id = $user_column_data['lead_id'];
		$form_id = $user_column_data['form_id'];
		unset($user_column_data['lead_id']);
		unset($user_column_data['form_name']);
		unset($user_column_data['form_id']);
		
		$sales_wizard_app_enable = ( 'on' === esc_attr(get_option( 'sales-wizard-app-enable', '' )) ? true : false );
		$sales_wizard_api_url = esc_url(get_option( 'sales-wizard-api-url', '' ));
		$sales_wizard_api_key = esc_attr(get_option( 'sales-wizard-api-key', '' ));
		$sales_wizard_api_version = esc_attr(get_option( 'sales-wizard-api-version', '' ));
		$sales_wizard_api_mode = ( 'on' === esc_attr(get_option( 'sales-wizard-app-test-mode', '' )) ? true : false);
		$user_columns = array();
		foreach($user_column_data as $column_key=>$column_value){
			$user_columns[] = array('string_value'=>$column_value,'column_id'=>$column_key);	
		}
		$colum_data = array(
			'client_ip'=> get_sales_wizard_client_ip(),
			'lead_id'=>$lead_id,
			'user_column_data'=>$user_columns,
			'api_version'=>$sales_wizard_api_version,
			'form_id'=> $form_id,
			'campaign_id'=> $form_id,
			'wordpress_key'=> $sales_wizard_api_key,
			'is_test'=>$sales_wizard_api_mode,
		);
		if($sales_wizard_app_enable && $sales_wizard_api_url && $sales_wizard_api_key ){
			$data = wp_remote_post($sales_wizard_api_url, array(
					'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
					'body'        => json_encode($colum_data),
					'method'      => 'POST',
					'data_format' => 'body',
				));
			return $data['response'];
		}
	}
}
if(!function_exists('export_contacts_csv')){
	/**
	* export csv contact form 7 or wpform  stored data
	*/
	function export_contacts_csv($contacts = array(),$headers_column = array()){
			$file_name = 'contacts-'.time().'.csv';
			header('Content-Type: text/csv; charset=utf-8');
			header("Content-Disposition: attachment; filename=$file_name");
			$output = fopen("php://output", "wb");
			fputcsv($output, $headers_column);
			foreach ($contacts as $row){
				fputcsv($output, $row);
			}
			fclose($output);
	}
}