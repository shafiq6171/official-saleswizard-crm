<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Sales_Wizard_App
 * @subpackage Sales_Wizard_App/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}

global $sale_wizard_app_obj;
global $sale_wizard_app_notices;
$swa_active_tab   = isset( $_GET['swa_tab'] ) ? sanitize_key( $_GET['swa_tab'] ) : 'sales-wizard-app';
$swa_default_tabs = $sale_wizard_app_obj->swa_plug_default_tabs();

if ( $sale_wizard_app_notices ) {
	$wps_sfw_error_text = esc_html__( 'Settings saved !', 'subscriptions-for-woocommerce' );
	$sale_wizard_app_obj->swa_plug_admin_notice( $wps_sfw_error_text, 'success' );
}
do_action( 'wps_swa_notice_message' );
?>

<header>
	<div class="wps-header-container wps-bg-white wps-r-8">
		<h1 class="wps-header-title"><?php _e( 'Official SalesWiazard CRM Plugin', 'sales-wizard-app' ); ?></h1>
	</div>
</header>

<div class="wrap">
		<nav class="nav-tab-wrapper">
			<?php
			if ( is_array( $swa_default_tabs ) && ! empty( $swa_default_tabs ) ) {

				foreach ( $swa_default_tabs as $sfw_tab_key => $sfw_default_tab ) {

					$sfw_tab_classes = 'wps-link ';

					if ( ! empty( $swa_active_tab ) && $swa_active_tab === $sfw_tab_key ) {
						$sfw_tab_classes .= 'nav-tab-active';
					}
					?>
				
						<a id="<?php echo esc_attr( $sfw_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=sales-wizard-app' ) . '&swa_tab=' . esc_attr( $sfw_tab_key ) ); ?>" class="nav-tab <?php if($swa_active_tab === esc_attr( $sfw_tab_key )):?>nav-tab-active<?php endif; ?>"><?php echo esc_html( $sfw_default_tab['title'] ); ?></a>
				
					<?php
				}
			}
			?>
	</nav>

	<div class="tab-content">
		<?php
		do_action( 'wps_swa_before_general_settings_form' );
		// if submenu is directly clicked on woocommerce.
		if ($swa_active_tab =='' ||  $swa_active_tab =='sales-wizard-app' ) {
			$swa_active_tab = 'sales-wizard-reports';
		}

			// look for the path based on the tab id in the admin templates.
		if ( ! isset( $swa_default_tabs[ $swa_active_tab ]['file_path'] ) ) {
			$file_path = SALES_WIZARD_APP_DIR_PATH;
		} else {
			$file_path = $swa_default_tabs[ $swa_active_tab ]['file_path'];
		}
			$sfw_tab_content_path = $file_path . 'admin/partials/' . $swa_active_tab . '.php';
			$sale_wizard_app_obj->swa_plug_load_template( $sfw_tab_content_path );
			do_action( 'wps_swa_after_general_settings_form' );
		?>
	</div>
</div>