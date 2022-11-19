<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @package    Sales_Wizard_App
 * @subpackage Sales_Wizard_App/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$fields = get_option('fields_mapping');

//global $sale_wizard_app_obj;
//$wpform_fields_map = apply_filters( 'swa_wpform_fields_map_array', array() );
wp_enqueue_style('sales-wizard-app');
wp_enqueue_script('sales-wizard-app');
?>

<div class="wrap">
  <p>
  <?php _e('In column ID use codes listed below. Use codes with uppercase letters and with underscore.
Column ID is used to recognize data sent to SalesWizard CRM. You must use Column ID as a field name in Contact Form 7 and WP Form. Field ID use only for WP Form which refers to WP Forms field ID.','sales-wizard-app');?>
 </p>
<p><?php _e('See column id example','sales-wizard-app');?> <a href="" id="column-list"><?php _e('here','sales-wizard-app');?></a> </p>
 <form  method="post" class="simply-form">
      <?php wp_nonce_field( 'swa-field-map-nonce', 'swa-field-map-nonce-field' ); ?>
      <table class="formulas-table">
	 
		<thead>
			<tr>
				<td><strong> </strong></td>
				<td><strong><?php _e('Field ID(wpform field id)','sales-wizard-app');?></strong></td>
			</tr>
		</thead>
		<tbody>
			<?php
			if(!empty($fields['column_ids'])){
				$wpform_ids = $fields['wpform_ids'];
				foreach($fields['column_ids'] as $field_key=>$field_value){
					echo '<tr><th><input type="text" name="column_id[]" value="'.$field_value.'" /></th><th><input type="text"  name="wpform_id[]" value="'.$wpform_ids[$field_key].'" /></th><th><button type="button" class="remove">'.__('Remove','sales-wizard-app').'</button></th></tr>';
				}
			}else{ ?>
			<tr>
				<th><input type="text" name="column_id[]" placeholder="COMPANY_NAME" ></th>
				<th><input type="text"  name="wpform_id[]" placeholder="2"></th>
				<th></th>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3" style="text-align: right;"><button type="button" id="add"> <?php _e('Add','sales-wizard-app');?></button></td>
			</tr>
		</tfoot>
      </table>
	  <button type="submit"  class="button-primary sfw-button-class" name="swa_save_fields_map"> <?php _e('Save','sales-wizard-app');?></button>
   </form>
</div>

<div id="column-id-list" class="modal">
  <div class="modal-content">
    <span class="close" id="close-modal">&times;</span>
    <table>
		<tr>
			<th colspan="2" class="table-th-heading"><h4>To add, Click on formula</h4></th>
		</tr> 
		<tr>
			<td class="add-model-field">FULL_NAME</td>
			<td class="add-model-field">FIRST_NAME</td>
		</tr>   
		<tr>    
			<td class="add-model-field">LAST_NAME</td>
			<td class="add-model-field">EMAIL</td>
		</tr>   
		<tr>    
			<td class="add-model-field">PHONE_NUMBER</td>
			<td class="add-model-field">POSTAL_CODE</td>
		</tr>   
		<tr>    
			<td class="add-model-field">COMPANY_NAME</td>
			<td class="add-model-field">JOB_TITLE</td>
		</tr>   
		<tr>    
			<td class="add-model-field">WORK_EMAIL</td>
			<td class="add-model-field">WORK_PHONE</td>
		</tr>   
		<tr>    
			<td class="add-model-field">STREET_ADDRESS</td>
			<td class="add-model-field">CITY</td>
		</tr>   
		<tr>    
			<td class="add-model-field">REGION</td>
			<td class="add-model-field">COUNTRY</td>
		</tr>   
		<tr>    
			<td class="add-model-field">VEHICLE_MODEL</td>
			<td class="add-model-field">VEHICLE_TYPE</td>
		</tr>   
		<tr>    
			<td class="add-model-field">PREFERRED_DEALERSHIP</td>
			<td class="add-model-field">VEHICLE_PURCHASE_TIMELINE</td>
		</tr>   
		<tr>    
			<td class="add-model-field">VEHICLE_CONDITION</td>
			<td class="add-model-field">VEHICLE_OWNERSHIP</td>
		</tr>   
		<tr>    
			<td class="add-model-field">VEHICLE_PAYMENT_TYPE</td>
			<td class="add-model-field">COMPANY_SIZE</td>
		</tr>   
		<tr>    
			<td class="add-model-field">ANNUAL_SALES</td>
			<td class="add-model-field">YEARS_IN_BUSINESS</td>
		</tr>   
		<tr>    
			<td class="add-model-field">JOB_DEPARTMENT</td>
			<td class="add-model-field">JOB_ROLE</td>
		</tr>   
		<tr>    
			<td class="add-model-field">EDUCATION_PROGRAM</td>
			<td class="add-model-field">EDUCATION_COURSE</td>
		</tr>   
		<tr>    
			<td class="add-model-field">PRODUCT</td>
			<td class="add-model-field">SERVICE</td>
		</tr>   
		<tr>    
			<td class="add-model-field">OFFER</td>
			<td  class="add-model-field">CATEGORY</td>
		</tr>   
		<tr>    
			<td class="add-model-field">PREFERRED_CONTACT_METHOD</td>
			<td class="add-model-field">PREFERRED_LOCATION</td>
		</tr>   
		<tr>    
			<td class="add-model-field">PREFERRED_CONTACT_TIME</td>
			<td class="add-model-field">PURCHASE_TIMELINE</td>
		</tr>   
		<tr>    
			<td class="add-model-field">YEARS_OF_EXPERIENCE</td>
			<td class="add-model-field">JOB_INDUSTRY</td>
		</tr>   
		<tr>    
			<td class="add-model-field">LEVEL_OF_EDUCATION</td>
			<td class="add-model-field">PROPERTY_TYPE</td>
		</tr>   
		<tr>    
			<td class="add-model-field">REALTOR_HELP_GOAL</td>
			<td class="add-model-field">PROPERTY_COMMUNITY</td>
		</tr>   
		<tr>    
			<td class="add-model-field">PRICE_RANGE</td>
			<td class="add-model-field">NUMBER_OF_BEDROOMS</td>
		</tr>   
		<tr>    
			<td class="add-model-field">FURNISHED_PROPERTY</td>
			<td class="add-model-field">PETS_ALLOWED_PROPERTY</td>
		</tr>   
		<tr>    
			<td class="add-model-field">NEXT_PLANNED_PURCHASE</td>
			<td class="add-model-field">EVENT_SIGNUP_INTEREST</td>
		</tr>   
		<tr>    
			<td class="add-model-field">PREFERRED_SHOPPING_PLACES</td>
			<td class="add-model-field">FAVORITE_BRAND</td>
		</tr>   
		<tr>    
			<td class="add-model-field">TRANSPORTATION_COMMERCIAL_LICENSE_TYPE</td>
			<td class="add-model-field">EVENT_BOOKING_INTEREST</td>
		</tr>   
		<tr>    
			<td class="add-model-field">DESTINATION_COUNTRY</td>
			<td class="add-model-field">DESTINATION_CITY</td>
		</tr>   
		<tr>    
			<td class="add-model-field">DEPARTURE_COUNTRY</td>
			<td class="add-model-field">DEPARTURE_CITY</td>
		</tr>   
		<tr>    
			<td class="add-model-field">DEPARTURE_DATE</td>
			<td class="add-model-field">RETURN_DATE</td>
		</tr>   
		<tr>    
			<td class="add-model-field">NUMBER_OF_TRAVELERS</td>
			<td class="add-model-field">TRAVEL_BUDGET</td>
		</tr>   
		<tr>    
			<td class="add-model-field">TRAVEL_ACCOMMODATION</td>
			<td class="add-model-field"></td>
		</tr>
	</table>
  </div>
</div>