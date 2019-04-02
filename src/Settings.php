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


	/**
	 * Settings constructor.
	 */
	public function __construct() {

		add_action( 'admin_menu', [ $this, 'cranleigh_admissions_add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'cranleigh_admissions_settings_init' ] );

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
			'cranleigh_admissions_plugin',
			[ $this, 'cranleigh_admissions_options_page' ]
		);

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
