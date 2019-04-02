<?php

namespace FredBradley\CranleighAdmissionsPlugin;

use Puc_v4_Factory;

/**
 * Class BaseController
 *
 */
abstract class BaseController {

	/**
	 * @return mixed
	 */
	abstract public function setupPlugin();

	/**
	 * BaseController constructor.
	 */
	public function __construct() {

		$this->setupPlugin();
	}


	/**
	 * @param string $plugin_name
	 * @param string $githubUserName
	 */
	public function runUpdateChecker( string $plugin_name, string $githubUserName = 'cranleighschool' ) {

		return $this->update_check( $plugin_name, $githubUserName );
	}

	/**
	 * @param string $plugin_name
	 * @param string $user
	 */
	private function update_check( string $plugin_name, string $user ) {

		$updateChecker = Puc_v4_Factory::buildUpdateChecker(
			'https://github.com/' . $user . '/' . $plugin_name . '/',
			dirname( dirname( __FILE__ ) ) . '/' . $plugin_name . '.php',
			$plugin_name
		);

		/* Add in option form for setting auth token*/
		//$updateChecker->setAuthentication(GITHUB_AUTH_TOKEN);

		$updateChecker->setBranch( 'master' );
	}

}
