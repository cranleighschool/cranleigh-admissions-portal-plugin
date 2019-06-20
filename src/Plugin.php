<?php

namespace FredBradley\CranleighAdmissionsPlugin;

use YeEasyAdminNotices\V1\AdminNotice;

/**
 * Class Plugin
 */
class Plugin extends BaseController {


	/**
	 * @var string
	 */
	protected $plugin_name = 'cranleigh-admissions-portal-plugin';
	/**
	 * @var array
	 */
	private $shortcodes = [
		Shortcodes\AdmissionsDoc::class,
		Shortcodes\EntryPoints::class,
	];

	/**
	 * Plugin constructor.
	 */
	public function __construct() {

		parent::__construct();
		$this->run_update_checker( $this->plugin_name );
		if ( is_admin() ) {
			$this->check_admissions_portal_connection();
		}
	}


	/**
	 *
	 */
	public function setup_plugin() {

		new Settings();
		$this->load_shortcodes();

	}

	/**
	 * Returns false if there is an error of some kind, otherwise returns an array of Curl headers.
	 *
	 * @return array|bool
	 */
	public function check_admissions_portal_connection() {

		if ( null === Settings::get( 'portal_uri' ) ) {
			AdminNotice::create()->error( 'For Admissions Portal Plugin to work you need to set the Portal URI on the settings page!' )->show();

			return false;
		}
		$head = wp_remote_head( Settings::get( 'portal_uri' ) );
		if ( is_wp_error( $head ) ) {
			AdminNotice::create()->error( "Unable to connect to your <strong>Admissions Portal</strong>. Perhaps <a href='" . Settings::options_page_uri() . "'>double check your settings?</a><br /><br /><code>" . $head->get_error_message() . '</code>' )->show();

			return false;
		}

		return $head;

	}


	/**
	 * Quick method that loads all the shortcodes listed in the $shortcodes var above.
	 */
	public function load_shortcodes() {

		foreach ( $this->shortcodes as $shortcode ) {
			new $shortcode();
		}
	}

}

