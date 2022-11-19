<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wp_enqueue_style('sales-wizard-app');
wp_enqueue_script('sales-wizard-app');
global $sale_wizard_app_obj;
$sfw_genaral_settings = apply_filters( 'swa_general_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="wps-sfw-gen-section-form">
	<div class="sfw-secion-wrap">
		<?php
		$sfw_general_html = $sale_wizard_app_obj->swa_plug_generate_html( $sfw_genaral_settings );
		echo esc_html( $sfw_general_html );
		wp_nonce_field( 'swa-general-nonce', 'swa-general-nonce-field' );
		?>
	</div>
</form>
