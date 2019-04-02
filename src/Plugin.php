<?php

namespace FredBradley\CranleighAdmissionsPlugin;

/**
 * Class Plugin
 *
 */
class Plugin extends BaseController {

	protected $plugin_name = 'cranleigh-admissions-portal-plugin';
	/**
	 * @var array
	 */
	private $shortcodes = [
		Shortcodes\AdmissionsDoc::class
	];

	/**
	 * Plugin constructor.
	 */
	public function __construct() {

		parent::__construct();
		$this->runUpdateChecker( $this->plugin_name );
	}


	/**
	 *
	 */
	public function setupPlugin() {

		new Settings();
		$this->loadShortcodes();

	}

	/**
	 * Quick method that loads all the shortcodes listed in the $shortcodes var above.
	 */
	public function loadShortcodes() {

		foreach ( $this->shortcodes as $shortcode ) {
			new $shortcode;
		}
	}

}

