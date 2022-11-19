<?php

/**
 * Fired during plugin activation
 *
 * @link       https://webmonch.com
 * @since      1.0.0
 *
 * @package    Sales_Wizard_App
 * @subpackage Sales_Wizard_App/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sales_Wizard_App
 * @subpackage Sales_Wizard_App/includes
 * @author     Shafiq <shafiq6171@gmail.com>
 */
class Sales_Wizard_App_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
        global $wp_version;

        $flag = false;
        $wp = '4.0';    // min WordPress version
        $php = '5.5';   // min PHP version

        if ( version_compare( PHP_VERSION, $php, '<' ) ) {
            $flag = 'PHP';
        } elseif ( version_compare( $wp_version, $wp, '<' ) ) {
            $flag = 'WordPress';
        } 

        if($flag){    
          $version = $php;
          if ('WordPress'==$flag) {
              $version = $wp;
          }
			deactivate_plugins( basename( __FILE__ ) );
        }  

        $data_table1 = $wpdb->prefix."sales_wizard_log";
        $sql = "CREATE TABLE IF NOT EXISTS $data_table1 (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`form_id` varchar(20) NOT NULL,
					`form_name` varchar(200) NOT NULL,
					`sales_wizard_data` text NOT NULL,
					`lead_id` varchar(50) NOT NULL,
					`date` datetime NOT NULL DEFAULT current_timestamp(),
					`log_status` enum('0','1','2','3') NOT NULL DEFAULT '0',
					PRIMARY KEY (`id`)
                );";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
	}

}
