<?php
/**
 * Created by PhpStorm.
 * User: fredbradley
 * Date: 2019-04-02
 * Time: 08:52
 */

namespace FredBradley\CranleighAdmissionsPlugin;

/**
 * Class Settings
 *
 * @package FredBradley\CranleighAdmissionsPlugin
 */
class Settings {

	public static $menu_page      = 'cranleigh_admissions_plugin';
	public static $transient_name = 'admis_portal_entry_points';

	/**
	 * Settings constructor.
	 */
	public function __construct() {

		add_action( 'admin_menu', [ $this, 'cranleigh_admissions_add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'cranleigh_admissions_settings_init' ] );
		add_action( 'update_option_cranleigh_admissions_settings', [ $this, 'cranleigh_on_update' ], 10, 2 );

	}
	public static function options_page_uri() {
		return admin_url( 'options-general.php?page=' . self::$menu_page );
	}

	/**
	 * Static function to readily return the values requested.
	 *
	 * @param string $option_name
	 *
	 * @return mixed
	 */
	public static function get( string $option_name ) {

		$options = get_option( 'cranleigh_admissions_settings' );

		return $options[ $option_name ];
	}


	/**
	 *
	 */
	function cranleigh_admissions_add_admin_menu() {

		add_options_page(
			'cranleigh_admissions_plugin',
			'Admissions Portal',
			'manage_options',
			self::$menu_page,
			[ $this, 'cranleigh_admissions_options_page' ]
		);

	}

	/**
	 * This function is fired when the settings page is saved.
	 * Specifically we want to delete the transient that is saved as it may no longer be relevant.
	 *
	 * @param $oldvalue
	 * @param $newvalue
	 */
	function cranleigh_on_update( $oldvalue, $newvalue ) {
		delete_transient( self::$transient_name );

		// To refactor later... could set the transient here as well, instead of in the EntryPoints class. Just an idea.
	}

	/**
	 *
	 */
	function cranleigh_admissions_settings_init() {

		register_setting( 'pluginPage', 'cranleigh_admissions_settings', [ $this, 'plugin_options_validate' ] );

		add_settings_section(
			'cranleigh_admissions_pluginPage_section',
			__( 'Admissions Portal Settings', 'cranleigh_admissions' ),
			[ $this, 'cranleigh_admissions_settings_section_callback' ],
			'pluginPage'
		);

		add_settings_field(
			'portal_uri',
			__( 'Portal URI', 'cranleigh_admissions' ),
			[ $this, 'portal_uri_render' ],
			'pluginPage',
			'cranleigh_admissions_pluginPage_section'
		);
		add_settings_field(
			'api_token',
			__( 'Api Token', 'cranleigh_admission' ),
			[ $this, 'api_token_render' ],
			'pluginPage',
			'cranleigh_admissions_pluginPage_section'
		);

	}

	/**
	 * Plugin Options Validate
	 *
	 * Validates user data for some/all of your input fields.
	 *
	 * @param array $input
	 *
	 * @return array $input
	 */
	function plugin_options_validate( array $input ): array {

		// Check our textbox option field contains no HTML tags - if so strip them out
		$input['portal_uri'] = wp_filter_nohtml_kses( $input['portal_uri'] );

		// Ensure that there is a trailing slash...
		$input['portal_uri'] = trailingslashit( $input['portal_uri'] );

		return $input; // return validated input
	}

	/**
	 *
	 */
	function portal_uri_render() {

		?>
		<input type='url' name='cranleigh_admissions_settings[portal_uri]' value='<?php echo self::get( 'portal_uri' ); ?>'>
		<?php

	}
	function api_token_render() {

		?>
		<input type='text' name='cranleigh_admissions_settings[api_token]' value='<?php echo self::get( 'api_token' ); ?>'>
		<?php

	}


	/**
	 *
	 */
	function cranleigh_admissions_settings_section_callback() {

		echo __( 'Things needed for Admissions Portal integration to work...', 'cranleigh_admissions' );

	}


	/**
	 *
	 */
	function cranleigh_admissions_options_page() {

		?>
		<div class="wrap">
			<form action='options.php' method='post'>

				<h2>Cranleigh Admissions Portal</h2>
				<p>This plugin adds a couple of features which link the website with the Cranleigh Schools' Admissions Portal.</p>
				<p>Some setup is required below for the features to work.</p>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

			</form>
		</div>
		<?php

	}


}
