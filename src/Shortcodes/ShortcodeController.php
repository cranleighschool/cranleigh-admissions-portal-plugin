<?php

namespace FredBradley\CranleighAdmissionsPlugin\Shortcodes;

/**
 * Class ShortcodeController
 *
 * @package FredBradley\CranleighAdmissionsPlugin\Shortcodes
 */
abstract class ShortcodeController {

	/**
	 * ShortcodeController constructor.
	 */
	public function __construct() {

		$this->init();
	}


	/**
	 * The Init
	 */
	private function init() {

		add_shortcode( $this->tag, [ $this, 'render' ] );
	}

	/**
	 * @param      $atts
	 * @param null $content
	 *
	 * @return mixed
	 */
	abstract public function render( $atts, $content = null );

}