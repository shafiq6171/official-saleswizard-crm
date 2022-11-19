<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link             	 	https://saleswizard.pl/
 * @since             		1.0.0
 * @package           		Sales_Wizard_App
 *
 * @wordpress-plugin
 * Plugin Name:       		Official SalesWizard CRM
 * Plugin URI:       		https://saleswizard.pl/plugin/
 * Description:       		Official SalesWizard CRM plugin which helps you to send all your enquires to your SalesWizard CRM
 * Version:           		1.0.0
 * Author:            		4B Systems sp. z o.o
 * Author URI:        		https://saleswizard.pl/profile/
 * Text Domain:       		sales-wizard-app
 * Domain Path:       		/languages
 * Requires at least:       5.2
 * Tested up to:            6.1.0
 * Requires PHP: 			5.6
 * Contact Form 7 at least: 5.6
 * Contact Form 7 tested up to:  5.6.3
 * wpforms-lite at least: 1.7.2
 * wpforms-lite tested up to: 1.7.7
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


function define_sales_wizard_app() {
		sales_wizard_app_constants( 'SALES_WIZARD_APP_VERSION', '1.0.0' );
		sales_wizard_app_constants( 'SALES_WIZARD_APP_FILE', __FILE__ );
		sales_wizard_app_constants( 'SALES_WIZARD_APP_BASE', plugin_basename( SALES_WIZARD_APP_FILE));
		sales_wizard_app_constants( 'SALES_WIZARD_APP_DIR_PATH', plugin_dir_path( SALES_WIZARD_APP_FILE) );
		sales_wizard_app_constants( 'SALES_WIZARD_APP_DIR_URL', plugin_dir_url( SALES_WIZARD_APP_FILE ) );
}
function sales_wizard_app_constants( $key, $value ) {
		if ( ! defined( $key ) ) {
			define( $key, $value );
		}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sales-wizard-app-activator.php
 */
function activate_sales_wizard_app() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sales-wizard-app-activator.php';
	Sales_Wizard_App_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sales-wizard-app-deactivator.php
 */
function deactivate_sales_wizard_app() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sales-wizard-app-deactivator.php';
	Sales_Wizard_App_Deactivator::deactivate();
}
/*
* plugin settings page url
*/
function sales_wizard_app_plugin_settings($links) {
	$links['settings'] = '<a href="admin.php?page=sales-wizard-app&swa_tab=sales-wizard-settings">Settings</a>';
	$links['supports'] = '<a href="https://saleswizard.pl/kontakt/" target="_blank">Supports</a>';
	return $links;
}
register_activation_hook( __FILE__, 'activate_sales_wizard_app' );
register_deactivation_hook( __FILE__, 'deactivate_sales_wizard_app' );
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_" . $plugin, 'sales_wizard_app_plugin_settings');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sales-wizard-app.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sales_wizard_app() {
	define_sales_wizard_app();
	$sale_wizard_app_plugin = new Sales_Wizard_App();
	$sale_wizard_app_plugin->run();
	$GLOBALS['sale_wizard_app_obj'] = $sale_wizard_app_plugin;
	$GLOBALS['sale_wizard_app_notices'] = false;
}
run_sales_wizard_app();
