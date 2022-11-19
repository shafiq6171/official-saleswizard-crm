<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://webmonch.com
 * @since      1.0.0
 *
 * @package    Sales_Wizard_App
 * @subpackage Sales_Wizard_App/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sales_Wizard_App
 * @subpackage Sales_Wizard_App/includes
 * @author     Shafiq <shafiq6171@gmail.com>
 */
class Sales_Wizard_App {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Sales_Wizard_App_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SALES_WIZARD_APP_VERSION' ) ) {
			$this->version = SALES_WIZARD_APP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'sales-wizard-app';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Sales_Wizard_App_Loader. Orchestrates the hooks of the plugin.
	 * - Sales_Wizard_App_i18n. Defines internationalization functionality.
	 * - Sales_Wizard_App_Admin. Defines all hooks for the admin area.
	 * - Sales_Wizard_App_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sales-wizard-app-loader.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sales-wizard-app-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wp-sales-wizard-app-fn.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sales-wizard-app-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sales-wizard-app-public.php';

		$this->loader = new Sales_Wizard_App_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Sales_Wizard_App_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Sales_Wizard_App_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Sales_Wizard_App_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'sales_wizard_app_admin_menu' );
		// All admin actions and filters after License Validation goes here.
		$this->loader->add_filter( 'swa_add_plugins_menus_array', $plugin_admin, 'wp_swa_admin_submenu_page', 15 );
		$this->loader->add_filter( 'swa_general_settings_array', $plugin_admin, 'swa_admin_general_settings_page', 10 );
		//$this->loader->add_filter( 'swa_wpform_fields_map_array', $plugin_admin, 'swa_admin_wpform_filed_map', 10 );
		// Saving tab settings.
		$this->loader->add_action( 'admin_init', $plugin_admin, 'swa_admin_save_tab_settings' );
		$this->loader->add_action('admin_init', $plugin_admin, 'sfw_admin_send_not_sent_contacts');
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Sales_Wizard_App_Public( $this->get_plugin_name(), $this->get_version() );
		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		//$this->loader->add_action( 'wpcf7_mail_sent', $plugin_public, 'swa_sent_contact_form7_data_to_crm' );
		$this->loader->add_action( 'wpcf7_before_send_mail', $plugin_public, 'swa_sent_contact_form7_data_to_crm' );
		$this->loader->add_action( 'wpforms_process_complete', $plugin_public, 'swa_sent_wpform_data_to_crm',10, 4  );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Sales_Wizard_App_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	/**
	 * Predefined default saleswizard settings tabs.
	 *
	 * @return  Array       An key=>value pair of saleswizard settings tabs.
	 */
	public function swa_plug_default_tabs() {

		$swa_default_tabs = array();
		$swa_default_tabs = apply_filters( 'swa_plugin_standard_admin_settings_tabs_before', $swa_default_tabs );
			$swa_default_tabs['sales-wizard-app'] = array(
			'title'       => esc_html__( 'Report', 'sales-wizard-app' ),
			'name'        => 'sales-wizard-report',
			'file_path'        => SALES_WIZARD_APP_DIR_PATH,
		);
		$swa_default_tabs['sales-wizard-field-maps'] = array(
			'title'       => esc_html__( 'Field Map', 'sales-wizard-app' ),
			'name'        => 'field-maps',
			'file_path'        => SALES_WIZARD_APP_DIR_PATH,
		);
		$swa_default_tabs['sales-wizard-settings'] = array(
			'title'       => esc_html__( 'Settings', 'sales-wizard-app' ),
			'name'        => 'sales-wizard-settings',
			'file_path'        => SALES_WIZARD_APP_DIR_PATH,
		);
		
		$swa_default_tabs = apply_filters( 'swa_plugin_standard_admin_settings_tabs_end', $swa_default_tabs );

		return $swa_default_tabs;
	}
		/**
	 * Locate and load appropriate tempate.
	 *
	 * @since   1.0.0
	 */
	public function swa_plug_load_template( $content_path ) {

		if ( file_exists( $content_path ) ) {
			include $content_path;
		} else {

			/* translators: %s: file path */
			$sfw_notice = sprintf( esc_html__( 'Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'subscriptions-for-woocommerce' ), $content_path );
			$this->swa_plug_admin_notice( $sfw_notice, 'error' );
		}
	}
	/**
	 * Show admin notices.
	 *
	 * @param  string $swa_message    Message to display.
	 * @param  string $type       notice type, accepted values - error/update/update-nag.
	 * @since  1.0.0
	 */
	public static function swa_plug_admin_notice( $sfw_message, $type = 'error' ) {

		$sfw_classes = 'notice ';

		switch ( $type ) {

			case 'update':
				$sfw_classes .= 'updated is-dismissible';
				break;

			case 'update-nag':
				$sfw_classes .= 'update-nag is-dismissible';
				break;

			case 'success':
				$sfw_classes .= 'notice-success is-dismissible';
				break;

			default:
				$sfw_classes .= 'notice-error is-dismissible';
		}

		$sfw_notice  = '<div class="' . esc_attr( $sfw_classes ) . ' wps-errorr-8">';
		$sfw_notice .= '<p>' . esc_html( $sfw_message ) . '</p>';
		$sfw_notice .= '</div>';

		echo wp_kses_post( $sfw_notice );
	}
	/**
	 * Generate html components.
	 *
	 * @param  string $swa_components    html to display.
	 * @since  1.0.0
	 */
	public function swa_plug_generate_html( $sfw_components = array() ) {
		if ( is_array( $sfw_components ) && ! empty( $sfw_components ) ) {
			foreach ( $sfw_components as $sfw_component ) {
				$wps_sfw_name = array_key_exists( 'name', $sfw_component ) ? $sfw_component['name'] : $sfw_component['id'];
				switch ( $sfw_component['type'] ) {

					case 'hidden':
					case 'number':
					case 'email':
					case 'text':
						?>
					<div class="wps-form-group wps-sfw-<?php echo esc_attr( $sfw_component['type'] ); ?>">
						<div class="wps-form-group__label">
							<label for="<?php echo esc_attr( $sfw_component['id'] ); ?>" class="wps-form-label"><?php echo esc_html( $sfw_component['title'] ); // WPCS: XSS ok. ?></label>
						</div>
						<div class="wps-form-group__control">
							<label class="mdc-text-field mdc-text-field--outlined">
						
								<input 
								class="mdc-text-field__input <?php echo esc_attr( $sfw_component['class'] ); ?>" 
								name="<?php echo esc_attr( $wps_sfw_name ); ?>"
								id="<?php echo esc_attr( $sfw_component['id'] ); ?>"
								type="<?php echo esc_attr( $sfw_component['type'] ); ?>"
								value="<?php echo esc_attr( $sfw_component['value'] ); ?>"
								placeholder="<?php echo esc_attr( $sfw_component['placeholder'] ); ?>"
								>
							</label>
							<div class="mdc-text-field-helper-line">
								<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo esc_attr( $sfw_component['description'] ); ?></div>
							</div>
						</div>
					</div>
						<?php
						break;

					case 'password':
						?>
					<div class="wps-form-group">
						<div class="wps-form-group__label">
							<label for="<?php echo esc_attr( $sfw_component['id'] ); ?>" class="wps-form-label"><?php echo esc_html( $sfw_component['title'] ); // WPCS: XSS ok. ?></label>
						</div>
						<div class="wps-form-group__control">
							<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<input 
								class="mdc-text-field__input <?php echo esc_attr( $sfw_component['class'] ); ?> wps-form__password" 
								name="<?php echo esc_attr( $wps_sfw_name ); ?>"
								id="<?php echo esc_attr( $sfw_component['id'] ); ?>"
								type="<?php echo esc_attr( $sfw_component['type'] ); ?>"
								value="<?php echo esc_attr( $sfw_component['value'] ); ?>"
								placeholder="<?php echo esc_attr( $sfw_component['placeholder'] ); ?>"
								>
								<i class="material-icons mdc-text-field__icon mdc-text-field__icon--trailing wps-password-hidden" tabindex="0" role="button">visibility</i>
							</label>
							<div class="mdc-text-field-helper-line">
								<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo esc_attr( $sfw_component['description'] ); ?></div>
							</div>
						</div>
					</div>
						<?php
						break;

					case 'textarea':
						?>
					<div class="wps-form-group">
						<div class="wps-form-group__label">
							<label class="wps-form-label" for="<?php echo esc_attr( $sfw_component['id'] ); ?>"><?php echo esc_attr( $sfw_component['title'] ); ?></label>
						</div>
						<div class="wps-form-group__control">
							<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea"  	for="text-field-hero-input">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
										<span class="mdc-floating-label"><?php echo esc_attr( $sfw_component['placeholder'] ); ?></span>
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<span class="mdc-text-field__resizer">
									<textarea class="mdc-text-field__input <?php echo esc_attr( $sfw_component['class'] ); ?>" rows="2" cols="25" aria-label="Label" name="<?php echo esc_attr( $wps_sfw_name ); ?>" id="<?php echo esc_attr( $sfw_component['id'] ); ?>" placeholder="<?php echo esc_attr( $sfw_component['placeholder'] ); ?>"><?php echo esc_textarea( $sfw_component['value'] ); // WPCS: XSS ok. ?></textarea>
								</span>
							</label>

						</div>
					</div>

						<?php
						break;

					case 'select':
					case 'multiselect':
						?>
					<div class="wps-form-group">
						<div class="wps-form-group__label">
							<label class="wps-form-label" for="<?php echo esc_attr( $sfw_component['id'] ); ?>"><?php echo esc_html( $sfw_component['title'] ); ?></label>
						</div>
						<div class="wps-form-group__control">
							<div class="wps-form-select">
								<select name="<?php echo esc_attr( $wps_sfw_name ); ?><?php echo ( 'multiselect' === $sfw_component['type'] ) ? '[]' : ''; ?>" id="<?php echo esc_attr( $sfw_component['id'] ); ?>" class="mdl-textfield__input <?php echo esc_attr( $sfw_component['class'] ); ?>" <?php echo 'multiselect' === $sfw_component['type'] ? 'multiple="multiple"' : ''; ?> >
									<?php
									foreach ( $sfw_component['options'] as $sfw_key => $sfw_val ) {
										?>
										<option value="<?php echo esc_attr( $sfw_key ); ?>"
											<?php
											if ( is_array( $sfw_component['value'] ) ) {
												selected( in_array( (string) $sfw_key, $sfw_component['value'], true ), true );
											} else {
												selected( $sfw_component['value'], (string) $sfw_key );
											}
											?>
											/>
											<?php echo esc_html( $sfw_val ); ?>
										</option>
										<?php
									}
									?>
								</select>
								<label class="mdl-textfield__label" for="octane"><?php echo esc_html( $sfw_component['description'] ); ?></label>
							</div>
						</div>
					</div>

						<?php
						break;

					case 'checkbox':
						?>
					<div class="wps-form-group">
						<div class="wps-form-group__label">
							<label for="<?php echo esc_attr( $sfw_component['id'] ); ?>" class="wps-form-label"><?php echo esc_html( $sfw_component['title'] ); ?></label>
						</div>
						<div class="wps-form-group__control wps-pl-4">
							<div class="mdc-form-field">
								<div class="mdc-checkbox">
									<input 
									name="<?php echo esc_attr( $wps_sfw_name ); ?>"
									id="<?php echo esc_attr( $sfw_component['id'] ); ?>"
									type="checkbox"
									class="mdc-checkbox__native-control <?php echo esc_attr( isset( $sfw_component['class'] ) ? $sfw_component['class'] : '' ); ?>"
									value="<?php echo esc_attr( $sfw_component['value'] ); ?>"
									<?php
									if ( 'on' === $sfw_component['checked'] ) {
										checked( $sfw_component['checked'], 'on' );
									}
									?>
									/>
								</div>
								<label for="<?php echo esc_attr( $sfw_component['id'] ); ?>"><?php echo esc_html( $sfw_component['description'] ); // WPCS: XSS ok. ?></label>
							</div>
						</div>
					</div>
						<?php
						break;

					case 'radio':
						?>
					<div class="wps-form-group">
						<div class="wps-form-group__label">
							<label for="<?php echo esc_attr( $sfw_component['id'] ); ?>" class="wps-form-label"><?php echo esc_html( $sfw_component['title'] ); ?></label>
						</div>
						<div class="wps-form-group__control wps-pl-4">
							<div class="wps-flex-col">
								<?php
								foreach ( $sfw_component['options'] as $sfw_radio_key => $sfw_radio_val ) {
									?>
									<div class="mdc-form-field">
										<div class="mdc-radio">
											<input
											name="<?php echo esc_attr( $wps_sfw_name ); ?>"
											value="<?php echo esc_attr( $sfw_radio_key ); ?>"
											type="radio"
											class="mdc-radio__native-control <?php echo esc_attr( $sfw_component['class'] ); ?>"
											<?php checked( $sfw_radio_key, $sfw_component['value'] ); ?>
											>
											<div class="mdc-radio__background">
												<div class="mdc-radio__outer-circle"></div>
												<div class="mdc-radio__inner-circle"></div>
											</div>
											<div class="mdc-radio__ripple"></div>
										</div>
										<label for="radio-1"><?php echo esc_html( $sfw_radio_val ); ?></label>
									</div>	
									<?php
								}
								?>
							</div>
						</div>
					</div>
						<?php
						break;

					case 'radio-switch':
						?>

					<div class="wps-form-group">
						<div class="wps-form-group__label">
							<label for="" class="wps-form-label"><?php echo esc_html( $sfw_component['title'] ); ?></label>
						</div>
						<div class="wps-form-group__control">
							<div>
								<div class="mdc-switch">
									<div class="mdc-switch__track"></div>
									<div class="mdc-switch__thumb-underlay">
										<div class="mdc-switch__thumb"></div>
										<input name="<?php echo esc_attr( $wps_sfw_name ); ?>" type="checkbox" id="basic-switch" value="on" class="mdc-switch__native-control" role="switch" aria-checked="
																<?php
																if ( 'on' == $sfw_component['value'] ) {
																	echo 'true';
																} else {
																	echo 'false';
																}
																?>
										"
										<?php checked( $sfw_component['value'], 'on' ); ?>
										>
									</div>
								</div>
							</div>
						</div>
					</div>
						<?php
						break;

					case 'button':
						?>
					<div class="wps-form-group">
						<div class="wps-form-group__label"></div>
						<div class="wps-form-group__control">
							<button class="mdc-button mdc-button--raised <?php echo esc_attr( $sfw_component['class'] ); ?>" name="<?php echo esc_attr( $wps_sfw_name ); ?>"
								id="<?php echo esc_attr( $sfw_component['id'] ); ?>"> <span class="mdc-button__ripple"></span>
								<span class="mdc-button__label"><?php echo esc_attr( $sfw_component['button_text'] ); ?></span>
							</button>
						</div>
					</div>

						<?php
						break;

					case 'submit':
						?>
					<tr valign="top">
						<td scope="row">
							<input type="submit" class="button button-primary" 
							name="<?php echo esc_attr( $wps_sfw_name ); ?>"
							id="<?php echo esc_attr( $sfw_component['id'] ); ?>"
							value="<?php echo esc_attr( $sfw_component['button_text'] ); ?>"
							/>
						</td>
					</tr>
						<?php
						break;

					default:
						break;
				}
			}
		}
	}

}
